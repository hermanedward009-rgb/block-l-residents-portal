<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\CleaningSchedule;
use App\Models\ElectricityPayment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function index()
    {
        $activePeriodId = request('period_id');

        $byRoom = collect(range(1, 10))->map(function (int $room) use ($activePeriodId) {
            $query = ElectricityPayment::query()
                ->where('room_number', $room)
                ->where('status', PaymentStatus::Verified);

            if ($activePeriodId) {
                $query->where('electricity_period_id', $activePeriodId);
            }

            return [
                'room' => $room,
                'total' => $query->sum('amount_tzs'),
                'count' => $query->count(),
            ];
        });

        return view('admin.reports.index', [
            'byRoom' => $byRoom,
            'registrations' => User::query()->whereNotNull('room_number')->latest()->get(),
            'cleaningCompletions' => CleaningSchedule::query()->where('is_completed', true)->with('room')->latest('completed_at')->get(),
            'monthlyPayments' => ElectricityPayment::query()
                ->where('status', PaymentStatus::Verified)
                ->get()
                ->groupBy(fn ($payment) => $payment->payment_date->format('Y-m'))
                ->map(fn ($group, $month) => (object) ['month' => $month, 'total' => $group->sum('amount_tzs')])
                ->sortByDesc('month')
                ->values(),
        ]);
    }

    public function exportPdf()
    {
        $data = [
            'byRoom' => ElectricityPayment::query()
                ->where('status', PaymentStatus::Verified)
                ->selectRaw('room_number, SUM(amount_tzs) as total, COUNT(*) as count')
                ->groupBy('room_number')
                ->orderBy('room_number')
                ->get(),
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $data);

        return $pdf->download('block-l-electricity-report.pdf');
    }

    public function exportExcel(): StreamedResponse
    {
        $filename = 'block-l-electricity-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Resident', 'Room', 'Amount (TZS)', 'Date', 'Method', 'Reference', 'Status']);

            ElectricityPayment::query()->with('user')->orderByDesc('payment_date')->chunk(100, function ($rows) use ($handle) {
                foreach ($rows as $payment) {
                    fputcsv($handle, [
                        $payment->user->name,
                        $payment->room_number,
                        $payment->amount_tzs,
                        $payment->payment_date->format('Y-m-d'),
                        $payment->payment_method,
                        $payment->transaction_reference,
                        $payment->status->value,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}