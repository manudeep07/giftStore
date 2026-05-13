@extends('layouts.admin')

@section('title', 'Reviews')
@section('heading', 'Moderation')

@section('content')
    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
        <table class="min-w-full divide-y divide-white/5 text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-200">
                @foreach ($reviews as $review)
                    <tr>
                        <td class="px-6 py-4 font-semibold">{{ $review->product?->name }}</td>
                        <td class="px-6 py-4 text-xs text-slate-400">{{ $review->user?->email }}</td>
                        <td class="px-6 py-4 text-amber-300">{{ str_repeat('★', $review->rating) }}</td>
                        <td class="px-6 py-4 text-xs">{{ $review->is_approved ? 'Live' : 'Queued' }}</td>
                        <td class="px-6 py-4 text-right text-xs font-semibold space-x-3">
                            @unless ($review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="post" class="inline">
                                    @csrf
                                    <button class="text-emerald-300 hover:text-emerald-100" type="submit">Approve</button>
                                </form>
                            @endunless
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="post" class="inline" onsubmit="return confirm('Discard review?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-300" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $reviews->links() }}
@endsection
