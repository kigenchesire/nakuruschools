@extends('layouts.app')

@php $contact = $site->contact(); @endphp

@section('title', 'Contact Us')
@section('meta_description', 'Contact ' . $site->schoolName() . ': phone, email, location, office hours and an enquiry form for admissions and general questions.')

@section('content')
    <x-frontend.page-header title="Contact Us" subtitle="We’d love to hear from you. Reach out with any question about admissions, fees or school life." />

    <section class="section">
        <div class="container">
            <div class="contact-grid mb-5">
                @include('frontend.partials.contact-cards', ['contact' => $contact])
            </div>

            <div class="row g-5 align-items-stretch">
                <div class="col-lg-7" id="contact-form">
                    <div class="form-card">
                        <span class="eyebrow">Send an Enquiry</span>
                        <h2 class="section-title h3">How can we help?</h2>
                        <p class="text-body-secondary mb-4">Fill in the form and our team will get back to you, usually within one working day.</p>

                        @if (session('success'))
                            <div class="alert alert-success d-flex gap-2 align-items-start" role="status">
                                <i class="bi bi-check-circle-fill fs-5" aria-hidden="true"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <strong>Please check the form.</strong> Some fields need your attention.
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="post" novalidate>
                            @csrf
                            <div class="hp-field" aria-hidden="true">
                                <label for="website">Leave this field empty</label>
                                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full name <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autocomplete="name" maxlength="120" @error('name') aria-describedby="name-error" @enderror>
                                    @error('name')<div class="invalid-feedback" id="name-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email address <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="email" maxlength="150" @error('email') aria-describedby="email-error" @enderror>
                                    @error('email')<div class="invalid-feedback" id="email-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone number</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" autocomplete="tel" maxlength="30" placeholder="e.g. 0712 345 678" @error('phone') aria-describedby="phone-error" @enderror>
                                    @error('phone')<div class="invalid-feedback" id="phone-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject <span class="text-danger" aria-hidden="true">*</span></label>
                                    <input type="text" id="subject" name="subject" value="{{ old('subject', request('subject')) }}" class="form-control @error('subject') is-invalid @enderror" required maxlength="150" placeholder="e.g. Admission enquiry" @error('subject') aria-describedby="subject-error" @enderror>
                                    @error('subject')<div class="invalid-feedback" id="subject-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message <span class="text-danger" aria-hidden="true">*</span></label>
                                    <textarea id="message" name="message" rows="6" class="form-control @error('message') is-invalid @enderror" required maxlength="5000" @error('message') aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback" id="message-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                    <small class="text-body-secondary"><span class="text-danger" aria-hidden="true">*</span> Required fields</small>
                                    <button type="submit" class="btn btn-red btn-lg btn-icon-end">Send Message <i class="bi bi-send-fill ms-1" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5 d-flex flex-column">
                    <span class="eyebrow">Find Us</span>
                    <h2 class="section-title h3">Visit our campus</h2>
                    <div>@include('frontend.partials.map', ['contact' => $contact])</div>
                    @if ($site->socialLinks()->isNotEmpty())
                        <div class="mt-4">
                            <h3 class="h6 fw-bold mb-3" style="font-family: inherit">Follow us</h3>
                            <x-frontend.social-icons variant="dark" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
