@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500 — Server Error')
@section('heading', 'Something broke.')
@section('icon_bg', 'bg-rose-600')
@section('icon')
    <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
@endsection
@section('message')
    Something went wrong on our end. The issue has been logged and we're looking into it. Please try again in a moment.
@endsection
@section('actions')
    <a href="/" class="inline-flex items-center gap-2 bg-[#005506] hover:bg-[#004105] text-white font-black text-xs sm:text-sm px-6 py-3 rounded-full shadow-md transition-all active:scale-95">
        Back to Home
    </a>
    <button onclick="location.reload()" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 font-bold text-xs sm:text-sm px-6 py-3 rounded-full shadow-sm transition-all active:scale-95">
        Try Again
    </button>
@endsection
@section('footer')
    If this keeps happening, please report it so we can fix it.
@endsection