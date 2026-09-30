<?php

namespace Tests\Feature;

use App\Models\AboutSection;
use App\Models\ContactEnquiry;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\Resource;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
        $this->seed();

        $this->admin = User::first();
        $this->actingAs($this->admin);
    }

    public function test_every_admin_screen_renders(): void
    {
        $news = News::first();
        $pages = [
            'admin.dashboard', 'admin.sliders.index', 'admin.sliders.create', 'admin.about.index', 'admin.about.create',
            'admin.features.index', 'admin.features.create', 'admin.news.index', 'admin.news.create',
            'admin.gallery.index', 'admin.gallery.create', 'admin.gallery.albums.index', 'admin.gallery.albums.create',
            'admin.faqs.index', 'admin.faqs.create', 'admin.resources.index', 'admin.resources.create',
            'admin.contact.edit', 'admin.social.edit', 'admin.enquiries.index', 'admin.users.index',
            'admin.users.create', 'admin.settings.edit', 'admin.profile.edit',
        ];

        foreach ($pages as $name) {
            $this->get(route($name))->assertOk();
        }

        $this->get(route('admin.sliders.edit', Slider::first()))->assertOk();
        $this->get(route('admin.about.edit', AboutSection::first()))->assertOk();
        $this->get(route('admin.features.edit', Feature::first()))->assertOk();
        $this->get(route('admin.news.edit', $news))->assertOk();
        $this->get(route('admin.news.show', $news))->assertOk();
        $this->get(route('admin.gallery.edit', GalleryImage::first()))->assertOk();
        $this->get(route('admin.gallery.albums.edit', GalleryAlbum::first()))->assertOk();
        $this->get(route('admin.faqs.edit', Faq::first()))->assertOk();
        $this->get(route('admin.resources.edit', Resource::first()))->assertOk();
        $this->get(route('admin.users.edit', $this->admin))->assertOk();
    }

    public function test_slider_crud_with_image_upload(): void
    {
        $this->post(route('admin.sliders.store'), [
            'title' => 'Welcome Back to School',
            'image' => UploadedFile::fake()->image('slide.jpg', 2400, 1350),
            'button_text' => 'Learn More',
            'button_url' => '/about',
            'is_active' => '1',
        ])->assertRedirect(route('admin.sliders.index'))->assertSessionHas('success');

        $slider = Slider::where('title', 'Welcome Back to School')->firstOrFail();
        Storage::disk('public')->assertExists($slider->image);
        $this->assertSame(1920, getimagesizefromstring(Storage::disk('public')->get($slider->image))[0], 'Large uploads are downscaled');

        $this->patch(route('admin.sliders.toggle', $slider))->assertRedirect();
        $this->assertFalse($slider->fresh()->is_active);

        $this->post(route('admin.sliders.store'), ['title' => 'No image'])->assertSessionHasErrors('image');
        $this->post(route('admin.sliders.store'), [
            'title' => 'Bad link', 'image' => UploadedFile::fake()->image('s.jpg', 800, 600),
            'button_text' => 'Go', 'button_url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('button_url');

        $path = $slider->image;
        $this->delete(route('admin.sliders.destroy', $slider))->assertRedirect();
        $this->assertModelMissing($slider);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_sortable_lists_can_be_reordered(): void
    {
        $ids = Faq::ordered()->pluck('id')->reverse()->values()->all();

        $this->postJson(route('admin.faqs.reorder'), ['ids' => $ids])->assertOk();

        $this->assertSame($ids, Faq::ordered()->pluck('id')->all());
    }

    public function test_news_is_sanitised_published_and_featured(): void
    {
        $this->post(route('admin.news.store'), [
            'title' => 'Sports Day Results',
            'content' => '<p onclick="steal()">Great day!</p><script>alert(1)</script><a href="javascript:evil()">x</a>',
            'status' => 'draft',
        ])->assertRedirect(route('admin.news.index'));

        $news = News::where('title', 'Sports Day Results')->firstOrFail();
        $this->assertSame('sports-day-results', $news->slug);
        $this->assertSame($this->admin->id, $news->author_id);
        $this->assertStringNotContainsString('script', $news->content);
        $this->assertStringNotContainsString('onclick', $news->content);
        $this->assertStringNotContainsString('javascript', $news->content);
        $this->assertStringContainsString('Great day!', $news->content);
        $this->assertNotEmpty($news->excerpt);

        $this->patch(route('admin.news.publish', $news))->assertRedirect();
        $news->refresh();
        $this->assertTrue($news->isPublished());
        $this->assertNotNull($news->published_at);
        $this->get(route('news.show', $news))->assertOk();

        $this->patch(route('admin.news.feature', $news));
        $this->assertTrue($news->fresh()->is_featured);

        // A second article with the same title gets a unique slug.
        $this->post(route('admin.news.store'), ['title' => 'Sports Day Results', 'content' => '<p>Again</p>', 'status' => 'draft']);
        $this->assertTrue(News::where('slug', 'sports-day-results-2')->exists());

        $this->delete(route('admin.news.destroy', $news))->assertRedirect();
        $this->assertSoftDeleted($news);
    }

    public function test_gallery_accepts_multiple_uploads_with_thumbnails(): void
    {
        $album = GalleryAlbum::first();

        $this->post(route('admin.gallery.store'), [
            'images' => [
                UploadedFile::fake()->image('one.jpg', 2000, 1500),
                UploadedFile::fake()->image('two.png', 1200, 1600),
            ],
            'gallery_album_id' => $album->id,
            'caption' => 'Science week',
            'is_active' => '1',
        ])->assertRedirect(route('admin.gallery.index'))->assertSessionHas('success', '2 images uploaded successfully.');

        $images = GalleryImage::where('caption', 'Science week')->get();
        $this->assertCount(2, $images);
        foreach ($images as $image) {
            Storage::disk('public')->assertExists([$image->image, $image->thumbnail]);
        }

        $this->post(route('admin.gallery.store'), [
            'images' => [UploadedFile::fake()->create('virus.php', 10, 'application/x-php')],
        ])->assertSessionHasErrors('images.0');
    }

    public function test_resources_are_stored_privately_under_random_names(): void
    {
        $this->post(route('admin.resources.store'), [
            'title' => 'Term 3 Newsletter',
            'category' => 'Other',
            'file' => UploadedFile::fake()->create('../../evil name.pdf', 120, 'application/pdf'),
            'is_active' => '1',
        ])->assertRedirect(route('admin.resources.index'));

        $resource = Resource::where('title', 'Term 3 Newsletter')->firstOrFail();
        Storage::disk('local')->assertExists($resource->file);
        Storage::disk('public')->assertMissing($resource->file);
        $this->assertStringStartsWith('resources/', $resource->file);
        $this->assertStringNotContainsString('evil', $resource->file);
        $this->assertSame('pdf', $resource->file_type);

        $this->get(route('admin.resources.download', $resource))->assertOk();

        $this->post(route('admin.resources.store'), [
            'title' => 'Script', 'file' => UploadedFile::fake()->create('x.exe', 10, 'application/x-msdownload'),
        ])->assertSessionHasErrors('file');

        $old = $resource->file;
        $this->put(route('admin.resources.update', $resource), [
            'title' => 'Term 3 Newsletter (updated)',
            'file' => UploadedFile::fake()->create('new.pdf', 50, 'application/pdf'),
            'is_active' => '1',
        ])->assertRedirect();
        Storage::disk('local')->assertMissing($old);
    }

    public function test_user_management_and_self_protection(): void
    {
        $this->post(route('admin.users.store'), [
            'first_name' => 'Grace', 'last_name' => 'Achieng', 'email' => 'grace@example.com',
            'status' => 'active', 'password' => 'Password123', 'password_confirmation' => 'Password123',
        ])->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'grace@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('Password123', $user->password));

        // Editing without a password keeps it; with one resets it.
        $this->put(route('admin.users.update', $user), ['first_name' => 'Grace', 'last_name' => 'A.', 'email' => $user->email, 'status' => 'active'])->assertRedirect();
        $this->assertTrue(Hash::check('Password123', $user->fresh()->password));

        $this->patch(route('admin.users.toggle', $user))->assertRedirect();
        $this->assertFalse($user->fresh()->isActive());

        $this->patch(route('admin.users.toggle', $this->admin))->assertForbidden();
        $this->delete(route('admin.users.destroy', $this->admin))->assertForbidden();

        $this->delete(route('admin.users.destroy', $user))->assertRedirect();
        $this->assertSoftDeleted($user);
    }

    public function test_settings_contact_and_social_update_the_website(): void
    {
        $this->put(route('admin.settings.update'), [
            'tagline' => 'Mountains of Opportunity',
            'enquire_button_text' => 'Apply Today',
            'logo' => UploadedFile::fake()->image('logo.png', 400, 400),
        ])->assertRedirect()->assertSessionHas('success');

        Storage::disk('public')->assertExists(Setting::where('key', 'logo')->value('value'));

        $this->put(route('admin.contact.update'), [
            'school_name' => 'Nakuru Schools',
            'phone' => '+254 722 123 456',
            'email' => 'office@example.com',
            'google_maps_url' => '<iframe src="https://www.google.com/maps/embed?pb=abc" width="600"></iframe>',
        ])->assertRedirect()->assertSessionHas('success');

        $this->put(route('admin.social.update'), [
            'links' => [
                'facebook' => ['url' => 'https://facebook.com/nakuruschools', 'is_active' => '1', 'sort_order' => 1],
                'tiktok' => ['url' => '', 'is_active' => '0', 'sort_order' => 2],
                'instagram' => ['url' => '', 'is_active' => '0'],
                'youtube' => ['url' => '', 'is_active' => '0'],
                'whatsapp' => ['url' => '', 'is_active' => '0'],
            ],
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertFalse(SocialLink::where('platform', 'instagram')->value('is_active'));

        $this->put(route('admin.social.update'), ['links' => ['x' => ['url' => '', 'is_active' => '1']]])
            ->assertSessionHasErrors('links.x.url');

        // Cached site data was refreshed: the public site shows the new values.
        $this->get('/')
            ->assertSee('Mountains of Opportunity')
            ->assertSee('Apply Today')
            ->assertSee('+254 722 123 456')
            ->assertSee('https://facebook.com/nakuruschools')
            ->assertDontSee('https://www.instagram.com/');

        $this->get('/contact')->assertSee('https://www.google.com/maps/embed?pb=abc', false);
    }

    public function test_enquiries_are_marked_read_and_status_updated(): void
    {
        $enquiry = ContactEnquiry::create([
            'name' => 'Parent', 'email' => 'p@example.com', 'subject' => 'Fees', 'message' => 'What are the fees?',
        ]);

        $this->get(route('admin.enquiries.show', $enquiry))->assertOk()->assertSee('What are the fees?');
        $this->assertSame('read', $enquiry->fresh()->status);

        $this->patch(route('admin.enquiries.status', $enquiry), ['status' => 'responded'])->assertRedirect();
        $this->assertSame('responded', $enquiry->fresh()->status);

        $this->patch(route('admin.enquiries.status', $enquiry), ['status' => 'bogus'])->assertSessionHasErrors('status');
    }

    public function test_core_about_sections_cannot_be_deleted(): void
    {
        $mission = AboutSection::where('key', 'mission')->first();

        $this->delete(route('admin.about.destroy', $mission))->assertSessionHas('error');
        $this->assertModelExists($mission);

        $this->put(route('admin.about.update', $mission), ['title' => 'Our Mission', 'summary' => 'Updated mission statement.', 'is_active' => '1'])
            ->assertRedirect(route('admin.about.index'));
        $this->get('/about')->assertSee('Updated mission statement.');
    }

    public function test_profile_password_change_requires_current_password(): void
    {
        $this->put(route('admin.profile.password'), [
            'current_password' => 'wrong', 'password' => 'NewPass123', 'password_confirmation' => 'NewPass123',
        ])->assertSessionHasErrorsIn('password', 'current_password');

        $this->admin->update(['password' => 'OldPass123']);
        $this->put(route('admin.profile.password'), [
            'current_password' => 'OldPass123', 'password' => 'NewPass123', 'password_confirmation' => 'NewPass123',
        ])->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewPass123', $this->admin->fresh()->password));
    }
}
