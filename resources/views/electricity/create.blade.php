@extends('layouts.app')
@section('heading', 'Submit electricity payment')
@section('content')
<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-6">
    <p class="text-sm text-slate-600">Resident: <strong>{{ $user->name }}</strong> · Room <strong>{{ $user->room_number }}</strong></p>
    @if($activePeriod)
        <p class="mt-2 text-sm">Suggested contribution: TZS {{ number_format($activePeriod->amount_tzs, 2) }} ({{ $activePeriod->title }})</p>
    @endif
    <form method="POST" action="{{ route('electricity.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
        @csrf
        <div><label class="text-sm font-medium">Amount paid (TZS)</label><input type="number" step="0.01" name="amount_tzs" required class="form-input mt-1"></div>
        <div><label class="text-sm font-medium">Payment date</label><input type="date" name="payment_date" value="{{ now()->format('Y-m-d') }}" required class="form-input mt-1"></div>
        <div><label class="text-sm font-medium">Payment method</label>
            <select name="payment_method" class="form-input mt-1" required>
                @foreach($methods as $method)<option value="{{ $method }}">{{ $method }}</option>@endforeach
            </select>
        </div>
        <div><label class="text-sm font-medium">Transaction reference</label><input name="transaction_reference" class="form-input mt-1"></div>
        <div><label class="text-sm font-medium">Receipt (optional)</label><input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" class="form-input mt-1"></div>
        <button class="btn-primary">Submit for verification</button>
    </form>
</div>
@endsection