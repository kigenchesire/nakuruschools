@extends('layouts.admin')

@section('title', 'Contact Information')

@section('content')
    <x-admin.breadcrumbs title="Contact Information" description="Shown in the website header, footer, homepage and Contact page." />

    <form action="{{ route('admin.contact.update') }}" method="post" novalidate>
        @csrf @method('PUT')
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="form-section-title">School & contacts</h2>
                        <x-admin.input name="school_name" label="School name" :value="$contact->school_name" required maxlength="150" help="Used across the website, page titles and emails." />
                        <div class="row">
                            <div class="col-md-6"><x-admin.input name="phone" type="tel" label="Phone" :value="$contact->phone" maxlength="30" prepend="bi-telephone" /></div>
                            <div class="col-md-6"><x-admin.input name="alt_phone" type="tel" label="Alternative phone" :value="$contact->alt_phone" maxlength="30" prepend="bi-telephone" /></div>
                            <div class="col-md-6"><x-admin.input name="email" type="email" label="Email" :value="$contact->email" maxlength="150" prepend="bi-envelope" /></div>
                            <div class="col-md-6"><x-admin.input name="alt_email" type="email" label="Alternative email" :value="$contact->alt_email" maxlength="150" prepend="bi-envelope" /></div>
                        </div>
                        <x-admin.input name="physical_address" label="Physical address" :value="$contact->physical_address" maxlength="255" prepend="bi-geo-alt" />
                        <x-admin.input name="postal_address" label="Postal address" :value="$contact->postal_address" maxlength="255" prepend="bi-mailbox" placeholder="P.O. Box …" />
                        <x-admin.input name="office_hours" label="Office hours" :value="$contact->office_hours" maxlength="255" prepend="bi-clock" placeholder="Mon – Fri: 8:00 am – 5:00 pm" />
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="form-section-title">Map location</h2>
                        <x-admin.textarea name="google_maps_url" label="Google Maps embed URL" :value="$contact->google_maps_url" rows="3"
                            help="In Google Maps choose Share → Embed a map → Copy HTML and paste it here (the link is extracted automatically). Or leave blank and use coordinates below." />
                        <div class="row">
                            <div class="col-6"><x-admin.input name="latitude" label="Latitude" :value="$contact->latitude" inputmode="decimal" placeholder="-0.303099" /></div>
                            <div class="col-6"><x-admin.input name="longitude" label="Longitude" :value="$contact->longitude" inputmode="decimal" placeholder="36.080026" /></div>
                        </div>
                        @if ($contact->map_embed_url)
                            <div class="ratio ratio-4x3 rounded overflow-hidden border">
                                <iframe src="{{ $contact->map_embed_url }}" title="Map preview" loading="lazy"></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-actions mt-4 card">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> Save Contact Information</button>
        </div>
    </form>
@endsection
