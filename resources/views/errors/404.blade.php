@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404 — Not Found')
@section('heading', 'Nothing here.')
@section('icon')
    <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
@endsection
@section('message')
    The page or file you're looking for doesn't exist, was moved, or is no longer available.
    Check the URL or head back to safety.
@endsection
@section('footer')
    If you followed a link from inside the portal and landed here, please report it so we can fix it.
@endsection