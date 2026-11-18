@php
    $isAdmin = auth()->user()->isAdmin();
    $link = fn ($name, $label, $icon) => [
        'href' => route($name),
        'active' => request()->routeIs($name) || request()->routeIs(str_replace('.index', '.*', $name)),
        'label' => $label,
        'icon' => $icon,
    ];
    $items = $isAdmin ? [
        $link('admin.dashboard', __('portal.admin_dashboard'), '▣'),
        $link('admin.users.index', __('portal.residents_rooms'), '👥'),
        $link('electricity.index', __('portal.electricity'), '⚡'),
        $link('admin.electricity.periods', __('portal.contribution_settings'), '⚙'),
        $link('admin.cleaning.index', __('portal.toilet_rota'), '🚽'),
        $link('admin.announcements.index', __('portal.announcements'), '📢'),
        $link('advice.index', __('portal.advice'), '💬'),
        $link('admin.reports.index', __('portal.reports'), '📊'),
    ] : [
        $link('dashboard', __('portal.dashboard'), '▣'),
        $link('profile.show', __('portal.my_profile'), '👤'),
        $link('electricity.index', __('portal.electricity'), '⚡'),
        $link('cleaning.index', __('portal.toilet_rota'), '🚽'),
        $link('advice.index', __('portal.advice'), '💬'),
        $link('announcements.index', __('portal.announcements'), '📢'),
    ];
@endphp
@foreach ($items as $item)
    <a href="{{ $item['href'] }}" class="nav-link {{ $item['active'] ? 'nav-link-active' : '' }}">
        <span aria-hidden="true">{{ $item['icon'] }}</span>
        <span>{{ $item['label'] }}</span>
    </a>
@endforeach