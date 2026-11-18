<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Block L Electricity Report</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:6px;text-align:left}</style>
</head>
<body>
<h1>BLOCK L — Electricity Report</h1>
<p>Generated {{ $generatedAt->format('M j, Y g:i A') }}</p>
<table>
    <thead><tr><th>Room</th><th>Payments</th><th>Total TZS</th></tr></thead>
    <tbody>
        @foreach($byRoom as $row)
            <tr><td>Room {{ $row->room_number }}</td><td>{{ $row->count }}</td><td>{{ number_format($row->total, 2) }}</td></tr>
        @endforeach
    </tbody>
</table>
</body>
</html>