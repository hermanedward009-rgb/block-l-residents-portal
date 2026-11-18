@extends('layouts.app')
@section('heading', 'Reports & records')
@section('content')
<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('admin.reports.pdf') }}" class="btn-primary">Export PDF</a>
    <a href="{{ route('admin.reports.excel') }}" class="btn-secondary">Export Excel (CSV)</a>
</div>
<section class="dashboard-panel">
    <h3 class="dashboard-panel-title"><span aria-hidden="true">⚡</span> Electricity by room (verified)</h3>
    <div class="portal-table-wrap mt-4 border-0 p-0 shadow-none ring-0 hover:shadow-none">
        <table class="portal-table">
            <thead><tr><th>Room</th><th>Payments</th><th>Total TZS</th></tr></thead>
            <tbody>
                @foreach($byRoom as $row)
                    <tr><td>Room {{ $row['room'] }}</td><td>{{ $row['count'] }}</td><td>{{ number_format($row['total'], 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<section class="dashboard-panel mt-6">
    <h3 class="dashboard-panel-title"><span aria-hidden="true">📅</span> Monthly verified payments</h3>
    <ul class="mt-4 space-y-1 text-sm text-slate-600">
        @foreach($monthlyPayments as $row)
            <li class="portal-list-row">{{ $row->month }} — TZS {{ number_format($row->total, 2) }}</li>
        @endforeach
    </ul>
</section>
@endsection