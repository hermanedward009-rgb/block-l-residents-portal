@extends('layouts.app')
@section('heading', 'Publish announcements')
@section('content')
<form method="POST" action="{{ route('admin.announcements.store') }}" class="mb-8 max-w-2xl space-y-3 rounded-2xl border border-slate-200 bg-white p-5">
    @csrf
    <input name="title" placeholder="Title" class="form-input" required>
    <textarea name="body" rows="5" class="form-input" placeholder="Announcement details" required></textarea>
    <div>
        <label class="text-sm font-medium text-slate-700">Send to</label>
        <select name="target_room_number" class="form-input mt-1">
            <option value="">All rooms (every approved resident)</option>
            @foreach($rooms as $room)
                <option value="{{ $room->number }}">{{ $room->displayName() }}@if($names = $room->residentNames()) — {{ implode(', ', $names) }}@endif</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-500">Only members of the selected room see room-specific announcements.</p>
    </div>
    <button class="btn-primary">Publish announcement</button>
</form>
<div class="space-y-3">
    @foreach($announcements as $item)
        <article class="flex items-start justify-between rounded-xl border border-slate-200 bg-white p-4">
            <div>
                <h4 class="font-semibold">{{ $item->title }}</h4>
                <p class="text-xs text-navy-700">Audience: {{ $item->audienceLabel() }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ Str::limit($item->body, 160) }}</p>
            </div>
            <form method="POST" action="{{ route('admin.announcements.destroy', $item) }}">@csrf @method('DELETE')<button class="text-xs text-red-600">Delete</button></form>
        </article>
    @endforeach
</div>
{{ $announcements->links() }}
@endsection