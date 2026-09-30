<?php

namespace Tests\Feature;

use App\Models\ContactEnquiry;
use App\Models\News;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
        $this->seed();
    }

    public function test_every_public_page_renders(): void
    {
        foreach (['/', '/about', '/news', '/gallery', '/resources', '/faqs', '/contact', '/login'] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get('/news?category=Sports')->assertOk();
        $this->get('/gallery?album=sports')->assertOk()->assertSee('Relay race on Sports Day');
        $this->get('/resources?category=Policies')->assertOk()->assertSee('Parents Handbook');
    }

    public function test_homepage_shows_cms_content_and_seo_tags(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Inspiring Excellence. Building Futures.')
            ->assertSee('Why parents choose Nakuru Schools')
            ->assertSee('Admissions Open for the 2027 Academic Year')
            ->assertSee('<meta property="og:title"', false)
            ->assertSee('<meta name="description"', false);
    }

    public function test_published_article_is_visible_and_counts_views(): void
    {
        $article = News::published()->first();

        $this->get(route('news.show', $article))
            ->assertOk()
            ->assertSee($article->title)
            ->assertSee('<meta property="og:type" content="article">', false);

        $this->assertSame(1, $article->fresh()->views);
    }

    public function test_draft_and_scheduled_articles_are_hidden(): void
    {
        $draft = News::where('status', 'draft')->first();
        $this->get(route('news.show', $draft))->assertNotFound();

        $scheduled = News::published()->first();
        $scheduled->update(['published_at' => now()->addWeek()]);
        $this->get(route('news.show', $scheduled))->assertNotFound();
        $this->get('/news')->assertDontSee($scheduled->title);
    }

    public function test_contact_form_stores_an_enquiry(): void
    {
        $this->post('/contact', [
            'name' => 'Jane Wanjiru',
            'email' => 'jane@example.com',
            'phone' => '0712 345 678',
            'subject' => 'Admission for Grade 3',
            'message' => 'Hello, are there vacancies in Grade 3 for next term?',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('contact_enquiries', [
            'email' => 'jane@example.com',
            'status' => 'unread',
        ]);
    }

    public function test_contact_form_validates_and_blocks_honeypot_spam(): void
    {
        $this->post('/contact', ['name' => '', 'email' => 'not-an-email', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->post('/contact', [
            'name' => 'Bot', 'email' => 'bot@example.com', 'subject' => 'Buy now',
            'message' => 'Spam spam spam spam', 'website' => 'http://spam.test',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, ContactEnquiry::count());
    }

    public function test_resource_download_streams_private_file_and_counts(): void
    {
        $resource = Resource::first();

        $response = $this->get(route('resources.download', $resource));
        $response->assertOk();
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertSame(1, $resource->fresh()->download_count);

        $resource->update(['is_active' => false]);
        $this->get(route('resources.download', $resource))->assertNotFound();
    }

    public function test_sitemap_lists_pages_and_articles(): void
    {
        $article = News::published()->first();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('news.show', $article), false);
    }

    public function test_unknown_page_uses_branded_404(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('We couldn’t find that page');
    }
}
