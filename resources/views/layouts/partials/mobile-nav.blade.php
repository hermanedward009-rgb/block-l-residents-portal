@php
    $isAdmin = auth()->user()->isAdmin();
    $active = fn (string $pattern) => request()->routeIs($pattern) ? 'text-navy-800' : 'text-slate-500';
    $items = [
        ['route' => $isAdmin ? 'admin.dashboard' : 'dashboard', 'label' => __('portal.home'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z'],
        ['route' => 'electricity.index', 'label' => __('portal.power'), 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
        ['route' => 'cleaning.index', 'label' => __('portal.toilets'), 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['route' => 'advice.index', 'label' => __('portal.advice'), 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['route' => 'profile.show', 'label' => __('portal.profile'), 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
@endphp
@foreach ($items as $item)
    <a href="{{ route($item['route']) }}" class="flex min-w-0 flex-1 flex-col items-center gap-0.5 px-1 py-1 {{ $active($item['route']) }}">
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
        </svg>
        <span class="truncate text-[10px] font-medium">{{ $item['label'] }}</span>
    </a>
@endforeach
<form method="POST" action="{{ route('logout') }}" class="flex min-w-0 flex-1 flex-col items-center justify-center">
    @csrf
    <button type="submit" class="flex flex-col items-center gap-0.5 px-1 py-1 text-red-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        <span class="text-[10px] font-semibold">{{ __('portal.logout') }}</span>
    </button>
</form>