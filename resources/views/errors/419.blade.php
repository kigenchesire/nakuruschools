@extends('errors.layout')

@section('title', 'Page Expired')
@section('code')
    4<span>1</span>9
@endsection
@section('heading', 'This page has expired')
@section('message', 'For your security, forms expire after a period of inactivity. Please go back, refresh the page and try again.')
@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-red btn-lg"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i> Go Back &amp; Retry</a>
@endsection
