@extends('errors.layout')

@section('title', 'Forbidden')
@section('code', '403 — Forbidden')
@section('heading', 'Access denied.')
@section('icon_bg', 'bg-rose-600')
@section('icon')
    <path stroke-linecap="round" stroke-linejoin="round"
        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
@endsection
@section('message')
    You don't have permission to view this page or resource. If you think this is a mistake, contact your administrator.
@endsection
@section('footer')
    Reference: 403 Forbidden — your account may lack the required role or access level.
@endsection