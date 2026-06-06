<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <title>Activity Logs Report</title>
    <style>
        body {
            color: #111827;
            font-family: Arial, "Battambang", sans-serif;
            font-size: 12px;
            margin: 24px;
        }

        h1 {
            font-size: 22px;
            margin: 0 0 6px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 7px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Print / Save PDF</button>
    <h1>Activity Logs Report</h1>
    <p>Generated at {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Action</th>
                <th>Module</th>
                <th>Description</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $log->user->username ?? 'System' }}</td>
                    <td>{{ str($log->action)->replace('_', ' ')->title() }}</td>
                    <td>{{ $log->module }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No activity logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
