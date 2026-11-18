@extends('layouts.app')
@section('heading', 'Reports & records')
@section('content')
<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('admin.reports.pdf') }}" class="btn-primary">Export PDF</a>
    <a href="{{ route('admin.reports.excel') }}" class="btn-secondary">Export Excel (CSV)</a>
</div>
<h3 class="font-semibold">Electricity by room (verified)</h3>
<table class="mt-3 w-full overflow-hidden rounded-xl border border-slate-200 bg-white text-sm">
    <thead class="bg-slate-50"><tr><th class="px-4 py-2 text-left">Room</th><th class="px-4 py-2 text-left">Payments</th><th class="px-4 py-2 text-left">Total TZS</th></tr></thead>
    <tbody>
        @foreach($byRoom as $row)
            <tr class="border-t border-slate-100"><td class="px-4 py-2">Room {{ $row['room'] }}</td><td class="px-4 py-2">{{ $row['count'] }}</td><td class="px-4 py-2">{{ number_format($row['total'], 2) }}</td></tr>
        @endforeach
    </tbody>
</table>
<h3 class="mt-8 font-semibold">Monthly verified payments</h3>
<ul class="mt-2 text-sm text-slate-600">
    @foreach($monthlyPayments as $row)
        <li>{{ $row->month }} — TZS {{ number_format($row->total, 2) }}</li>
    @endforeach
</ul>
@endsection