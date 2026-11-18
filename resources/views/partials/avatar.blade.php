@props(['user', 'size' => 'sm', 'class' => ''])
@php
    $sizeClass = match ($size) {
        'xs' => 'portal-avatar-xs',
        'sm' => 'portal-avatar-sm',
        'md' => 'portal-avatar-md',
        'lg' => 'portal-avatar-lg',
        default => 'portal-avatar-sm',
    };
    $px = match ($size) {
        'xs' => 36,
        'sm' => 40,
        'md' => 96,
        'lg' => 112,
        default => 40,
    };
@endphp
<img
    src="{{ $user->avatarUrl() }}"
    alt=""
    width="{{ $px }}"
    height="{{ $px }}"
    class="portal-avatar {{ $sizeClass }} {{ $class }}"
    loading="lazy"
    decoding="async"
>