@extends('layouts.admin')

@section('title', 'Social Media')

@section('content')
    <x-admin.breadcrumbs title="Social Media Links" description="Enabled platforms appear in the top bar, footer and Contact page. Leave a platform disabled to hide it." />

    <form action="{{ route('admin.social.update') }}" method="post" novalidate>
        @csrf @method('PUT')
        <div class="card">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Platform</th>
                            <th scope="col" style="min-width: 280px">Profile URL</th>
                            <th scope="col" style="width: 110px">Order</th>
                            <th scope="col" style="width: 110px">Enabled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($links as $link)
                            @php $p = $link->platform; @endphp
                            <tr>
                                <th scope="row" class="fw-semibold text-nowrap">
                                    <i class="bi {{ $link->icon }} fs-5 me-2 text-primary" aria-hidden="true"></i>{{ $link->label }}
                                </th>
                                <td>
                                    <label for="url-{{ $p }}" class="visually-hidden">{{ $link->label }} URL</label>
                                    <input type="url" id="url-{{ $p }}" name="links[{{ $p }}][url]" value="{{ old("links.$p.url", $link->url) }}" maxlength="255"
                                           class="form-control @error("links.$p.url") is-invalid @enderror"
                                           placeholder="{{ $p === 'whatsapp' ? 'https://wa.me/2547XXXXXXXX' : 'https://' . ($p === 'x' ? 'x' : $p) . '.com/…' }}">
                                    @error("links.$p.url")<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </td>
                                <td>
                                    <label for="order-{{ $p }}" class="visually-hidden">{{ $link->label }} order</label>
                                    <input type="number" id="order-{{ $p }}" name="links[{{ $p }}][sort_order]" value="{{ old("links.$p.sort_order", $link->sort_order) }}" min="0" max="99" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="links[{{ $p }}][is_active]" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="active-{{ $p }}" name="links[{{ $p }}][is_active]" value="1" @checked(old("links.$p.is_active", $link->is_active))>
                                        <label class="form-check-label visually-hidden" for="active-{{ $p }}">Enable {{ $link->label }}</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="sticky-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1" aria-hidden="true"></i> Save Social Links</button>
            </div>
        </div>
    </form>
@endsection
