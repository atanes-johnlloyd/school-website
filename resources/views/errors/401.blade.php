@extends('errors.layout')

@section('title', 'Unauthorized')
@section('code', '401 — Unauthorized')
@section('heading', 'Sign in first.')
@section('icon_bg', 'bg-amber-500')
@section('icon')
    <path stroke-linecap="round" stroke-linejoin="round"
        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
@endsection
@section('message')
    You need to be signed in to view this page. Log in with your account and try again.
@endsection
@section('actions')
    <a href="/" class="inline-flex items-center gap-2 bg-[#005506] hover:bg-[#004105] text-white font-black text-xs sm:text-sm px-6 py-3 rounded-full shadow-md transition-all active:scale-95">
        Back to Home
    </a>
    <a href="/login" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 font-bold text-xs sm:text-sm px-6 py-3 rounded-full shadow-sm transition-all active:scale-95">
        Sign In
    </a>
@endsection
@section('footer')
    If you already have an account but still see this, clear your cookies and try again.
@endsection