@extends('errors.layout')

@section('title', 'Page Expired')
@section('code', '419 — Page Expired')
@section('heading', 'Session expired.')
@section('icon_bg', 'bg-amber-500')
@section('icon')
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
@endsection
@section('message')
    Your session has timed out or the form token has expired. Go back, refresh the page, and try again.
@endsection
@section('footer')
    This usually happens after sitting idle for a while. Your data may not have been saved.
@endsection