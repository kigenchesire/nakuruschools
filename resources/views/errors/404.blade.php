@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code')
    4<span>0</span>4
@endsection
@section('heading', 'We couldn’t find that page')
@section('message', 'The page you are looking for may have been moved, renamed or is no longer available. Try one of the links below.')
@section('actions')
    <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-lg">Latest News</a>
@endsection
