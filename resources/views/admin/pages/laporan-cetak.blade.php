<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Borrowing Report {{ ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][$bulan - 1] }} {{ $tahun }}</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            font-size: 13px; 
            color: #333; 
            line-height: 1.5;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 30px; 
            background: #fff;
        }
        th, td { 
            border: 1px solid #e5e7eb; 
            padding: 12px 15px; 
            text-align: left; 
        }
        th { 
            background-color: #f8fafc; 
            font-weight: bold; 
            color: #475569;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .header { 
            text-align: center; 
            margin-bottom: 40px; 
            padding-bottom: 20px;
            border-bottom: 2px solid #90272c;
        }
        .header h1 { 
            margin: 0 0 10px 0; 
            font-size: 24px; 
            color: #90272c;
            text-transform: uppercase;
        }
        .header p { 
            margin: 0; 
            color: #64748b; 
            font-size: 14px;
            font-weight: 500;
        }
        .status-badge { 
            padding: 4px 8px; 
            border-radius: 6px; 
            font-size: 11px; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .status-dipinjam { background-color: #fee2e2; color: #dc2626; }
        .status-dikembalikan { background-color: #dcfce7; color: #16a34a; }
        
        .no-print {
            margin-bottom: 30px;
            padding: 20px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .btn {
            padding: 10px 20px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-primary {
            background: #90272c; 
            color: #fff; 
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
        }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .header { border-bottom-color: #000; }
            .header h1 { color: #000; }
            th { background-color: #f1f1f1 !important; color: #000; -webkit-print-color-adjust: exact; }
            .status-dipinjam { background-color: #fee2e2 !important; -webkit-print-color-adjust: exact; }
            .status-dikembalikan { background-color: #dcfce7 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-primary">🖨️ Print / Save as PDF</button>
            <a href="{{ route('admin.profile') }}" class="btn btn-secondary">Back</a>
            <p style="margin-left:auto; margin-bottom:0; color:#64748b; font-weight:bold;">👉 To save as PDF: Select "Save as PDF" in your print dialog's "Destination" dropdown.</p>
        </div>

        <div class="header">
            <h1>Digital Library Borrowing Report</h1>
            <p>Period: 
                {{ ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][$bulan - 1] }} 
                {{ $tahun }}
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Borrower</th>
                    <th style="width: 25%">Book Title</th>
                    <th style="width: 15%">Borrow Date</th>
                    <th style="width: 15%">Return Date</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Fines</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $p->user?->name ?? $p->user?->full_name ?? 'Unknown' }}</strong>
                        <div style="font-size: 11px; color:#64748b; margin-top:2px;">{{ $p->user?->email }}</div>
                    </td>
                    <td>
                        <strong>{{ $p->buku?->judul ?? 'Unknown (Deleted)' }}</strong>
                        <div style="font-size: 11px; color:#64748b; margin-top:2px;">ISBN: {{ $p->buku?->isbn ?? '-' }}</div>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                        <div style="font-size: 11px; color:#ef4444; margin-top:2px;">Due: {{ \Carbon\Carbon::parse($p->batas_kembali)->format('d M Y') }}</div>
                    </td>
                    <td>
                        {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}
                    </td>
                    <td>
                        <span class="status-badge status-{{ strtolower($p->status) }}">{{ $p->status === 'dipinjam' ? 'Borrowed' : 'Returned' }}</span>
                    </td>
                    <td>
                        @if($p->denda > 0)
                            <strong style="color: #dc2626;">Rp {{ number_format($p->denda, 0, ',', '.') }}</strong>
                            <div style="font-size: 10px; color: {{ $p->status_denda == 'lunas' ? '#16a34a' : '#dc2626' }}; margin-top:2px; font-weight:bold; text-transform:uppercase;">
                                {{ $p->status_denda === 'lunas' ? 'PAID' : 'UNPAID' }}
                            </div>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">No borrowing data for this period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <script>
        // Opsional: Langsung munculkan dialog print saat halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
