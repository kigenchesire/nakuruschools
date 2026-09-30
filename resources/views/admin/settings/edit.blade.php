@extends('layouts.admin')

@section('title', 'Settings')

@php $s = $settings; @endphp

@section('content')
    <x-admin.breadcrumbs title="Settings" description="Branding, homepage text and search-engine defaults. Contact details are managed under Contact Information." />

    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')

        <ul class="nav nav-tabs mb-0 border-bottom-0" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-branding" type="button" role="tab" aria-controls="tab-branding" aria-selected="true"><i class="bi bi-palette me-1" aria-hidden="true"></i>Branding</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-home" type="button" role="tab" aria-controls="tab-home" aria-selected="false"><i class="bi bi-house me-1" aria-hidden="true"></i>Homepage</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-seo" type="button" role="tab" aria-controls="tab-seo" aria-selected="false"><i class="bi bi-search me-1" aria-hidden="true"></i>SEO</button></li>
        </ul>

        <div class="card rounded-top-0">
            <div class="card-body tab-content">
                <div class="tab-pane fade show active" id="tab-branding" role="tabpanel" tabindex="0">
                    <div class="row g-4">
                        <div class="col-md-6 col-xl-4">
                            <x-admin.image-field name="logo" label="School logo" :current="\App\Support\Media::url($s['logo'])" removable accept="image/png,image/jpeg,image/webp"
                                help="PNG with transparent background works best. Up to 2 MB. Stored exactly as uploaded." />
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <x-admin.image-field name="favicon" label="Favicon" :current="\App\Support\Media::url($s['favicon'])" removable accept="image/png,image/x-icon,.ico"
                                help="Square PNG (e.g. 512×512) or .ico, up to 512 KB." />
                        </div>
                        <div class="col-xl-4">
                            <x-admin.input name="tagline" label="Tagline / motto" :value="$s['tagline']" maxlength="150" />
                            <x-admin.input name="enquire_button_text" label="Header button text" :value="$s['enquire_button_text']" maxlength="30" help="The call-to-action in the main navigation, e.g. “Enquire Now”." />
                            <x-admin.textarea name="footer_about" label="Footer description" :value="$s['footer_about']" rows="3" maxlength="400" />
                            <x-admin.input name="footer_text" label="Copyright line" :value="$s['footer_text']" maxlength="200" help="Leave blank for “© {{ date('Y') }} School name. All Rights Reserved.”" />
                            <x-admin.input name="powered_by" label="Powered by (optional)" :value="$s['powered_by']" maxlength="100" />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-home" role="tabpanel" tabindex="0">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <h2 class="form-section-title">Welcome section</h2>
                            <x-admin.input name="home_welcome_eyebrow" label="Small heading" :value="$s['home_welcome_eyebrow']" maxlength="80" />
                            <x-admin.input name="home_welcome_title" label="Title" :value="$s['home_welcome_title']" maxlength="150" />
                            <x-admin.textarea name="home_welcome_text" label="Text" :value="$s['home_welcome_text']" rows="7" maxlength="2000" help="Leave an empty line between paragraphs." />
                        </div>
                        <div class="col-lg-6">
                            <h2 class="form-section-title">Call-to-action banner</h2>
                            <x-admin.input name="cta_title" label="Title" :value="$s['cta_title']" maxlength="120" />
                            <x-admin.textarea name="cta_text" label="Text" :value="$s['cta_text']" rows="3" maxlength="400" />
                            <div class="row">
                                <div class="col-md-6"><x-admin.input name="cta_button_text" label="Button text" :value="$s['cta_button_text']" maxlength="40" /></div>
                                <div class="col-md-6"><x-admin.input name="cta_button_url" label="Button link" :value="$s['cta_button_url']" maxlength="255" placeholder="/contact" /></div>
                            </div>
                            <div class="alert alert-light border small"><i class="bi bi-info-circle me-1" aria-hidden="true"></i> Slides, “Why Choose Us” cards and key figures are managed from their own pages in the sidebar.</div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-seo" role="tabpanel" tabindex="0">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <x-admin.textarea name="meta_description" label="Default meta description" :value="$s['meta_description']" rows="3" maxlength="300" help="Shown in search results for pages without their own description. Aim for 150–160 characters." />
                            <x-admin.input name="meta_keywords" label="Meta keywords" :value="$s['meta_keywords']" maxlength="300" help="Comma-separated." />
                        </div>
                        <div class="col-lg-5">
                            <x-admin.image-field name="og_image" label="Social sharing image" :current="\App\Support\Media::url($s['og_image'])" removable help="Shown when pages are shared on WhatsApp, Facebook, etc. 1200×630 recommended." />
                        </div>
                    </div>
                </div>
            </div>
            <div class="sticky-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> Save Settings</button>
            </div>
        </div>
    </form>
@endsection
