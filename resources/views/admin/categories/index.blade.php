@extends('layouts.admin')

@section('title', 'Categories')
@section('heading', 'Taxonomy')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-400">Define merchandising lanes surfaced on the homepage.</p>
        <a href="{{ route('admin.categories.create') }}" class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-900 shadow-lg shadow-black/30">New category</a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <table class="min-w-full divide-y divide-white/5 text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-200">
                @foreach ($categories as $category)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-xs text-slate-400">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-right text-xs font-semibold">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-white hover:underline">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post" class="inline pl-3" onsubmit="return confirm('Delete category?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-300 hover:text-rose-100" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}
@endsection
