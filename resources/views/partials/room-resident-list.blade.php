@php
    $adminLinks = $adminLinks ?? false;
    $highlightUser = $highlightUser ?? null;
    $compact = $compact ?? false;
    $prominent = $prominent ?? false;
    $emptyText = $emptyText ?? 'No residents in this room';
    $listClass = $listClass ?? 'text-sm text-navy-900';
    $residents = $room?->relationLoaded('activeResidents')
        ? ($room->activeResidents ?? collect())
        : ($room?->activeResidents()->get() ?? collect());
@endphp
@if($residents->isNotEmpty())
    <ul class="{{ $listClass }} {{ $compact ? 'space-y-1' : 'space-y-2' }}" role="list">
        @foreach($residents as $resident)
            <li @class([
                'flex items-center gap-2',
                'justify-between' => $adminLinks,
                'rounded-lg px-2 py-1.5' => ! $compact,
                'bg-emerald-100/80 ring-1 ring-emerald-300' => ! $compact && $highlightUser && $highlightUser->id === $resident->id,
                'bg-slate-50' => ! $compact && (! $highlightUser || $highlightUser->id !== $resident->id),
            ])>
                <div class="flex min-w-0 items-center gap-2">
                    @unless($compact)
                        @include('partials.avatar', ['user' => $resident, 'size' => 'xs'])
                    @endunless
                    <span @class(['font-semibold', 'text-base text-navy-900' => $prominent])>{{ $resident->name }}</span>
                    @if($highlightUser && $highlightUser->id === $resident->id)
                        <span class="text-[10px] font-bold text-emerald-700">({{ __('portal.you') }})</span>
                    @endif
                </div>
                @if($adminLinks)
                    <a href="{{ route('admin.users.show', $resident) }}" class="btn-inline-neutral shrink-0 text-[10px]">{{ __('portal.manage_account') }}</a>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p class="text-sm text-slate-400">{{ $emptyText }}</p>
@endif