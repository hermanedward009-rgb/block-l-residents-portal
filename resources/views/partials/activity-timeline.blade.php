@props(['logs', 'empty' => __('portal.no_activity'), 'showActor' => true])

<ul class="activity-timeline mt-4 space-y-0" role="list">
    @forelse ($logs as $log)
        <li class="activity-timeline-item {{ $log->accentClass() }}">
            <span class="activity-timeline-icon" aria-hidden="true">{{ $log->icon() }}</span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-slate-800">{{ $log->description }}</p>
                <p class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-slate-500">
                    @if ($showActor && $log->user)
                        <span class="font-semibold text-navy-800">{{ $log->user->name }}</span>
                        <span aria-hidden="true">·</span>
                    @endif
                    <time datetime="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->diffForHumans() }}</time>
                </p>
            </div>
        </li>
    @empty
        <li class="activity-empty">{{ $empty }}</li>
    @endforelse
</ul>