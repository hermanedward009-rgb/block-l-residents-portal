@php $current = app()->getLocale(); @endphp
<div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-0.5 text-xs font-semibold shadow-sm" role="group" aria-label="{{ __('portal.language') }}">
    <a href="{{ route('language.switch', 'en') }}" class="rounded-lg px-2.5 py-1.5 {{ $current === 'en' ? 'bg-navy-800 text-white' : 'text-slate-600 hover:bg-slate-50' }}">EN</a>
    <a href="{{ route('language.switch', 'sw') }}" class="rounded-lg px-2.5 py-1.5 {{ $current === 'sw' ? 'bg-navy-800 text-white' : 'text-slate-600 hover:bg-slate-50' }}">SW</a>
</div>