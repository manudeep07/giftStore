@extends('layouts.admin')

@section('title', 'Create category')
@section('heading', 'New taxonomy')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data" class="max-w-3xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
        @csrf
        <label class="block text-sm font-semibold text-white">Name
            <input name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>
        <label class="block text-sm font-semibold text-white">Slug (optional)
            <input name="slug" value="{{ old('slug') }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>
        <label class="block text-sm font-semibold text-white">Description
            <textarea name="description" rows="3" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white">{{ old('description') }}</textarea>
        </label>
        <label class="block text-sm font-semibold text-white">Sort order
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white" />
        </label>
        <label class="block text-sm font-semibold text-white">Hero image
            <input type="file" name="image" class="mt-2 w-full text-sm text-slate-300 file:mr-4 file:rounded-xl file:border-0 file:bg-white file:px-4 file:py-2 file:text-xs file:font-semibold file:text-slate-900" />
        </label>
        <button class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900">Save</button>
    </form>
@endsection
