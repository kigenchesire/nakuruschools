@extends('errors.layout')

@section('title', 'Access Denied')
@section('code')
    4<span>0</span>3
@endsection
@section('heading', 'Access denied')
@section('message', $exception->getMessage() ?: 'You don’t have permission to view this page.')
