@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('code', '429 — Too Many Requests')
@section('heading', 'Slow down.')
@section('icon_bg', 'bg-orange-500')
@section('icon')
    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
@endsection
@section('message')
    You've made too many requests in a short period. Please wait a moment before trying again.
@endsection
@section('footer')
    If this keeps happening, contact the system administrator.
@endsection