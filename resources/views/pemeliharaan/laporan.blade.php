<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemeliharaan Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        
        .header h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-box {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            flex: 1;
            margin: 0 5px;
        }
        
        .info-box h4 {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-box p {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .statistik {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        
        .stat-box .label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-danger { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-success { background: #28a745; color: white; }
        .badge-secondary { background: #6c757d; color: white; }
        
        .total-section {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .total-section h3 {
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            @page {
                margin: 1cm;
            }
        }
        
        .print-button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .back-button {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
            text-decoration: none;
            display: inline-block;
        }
        
        .back-button:hover {
            background: #545b62;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-button">🖨️ Cetak Laporan</button>
        <a href="{{ route('pemeliharaan.index') }}" class="back-button">← Kembali</a>
    </div>

    <div class="header">
        <h2>LAPORAN PEMELIHARAAN BARANG</h2>
        <p>Sistem Informasi Manajemen Inventaris</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="statistik">
        <div class="stat-box">
            <div class="label">Total Data</div>
            <div class="value">{{ $statistik['total'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Rusak</div>
            <div class="value" style="color: #dc3545;">{{ $statistik['rusak'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Dalam Perbaikan</div>
            <div class="value" style="color: #ffc107;">{{ $statistik['dalam_perbaikan'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Selesai</div>
            <div class="value" style="color: #28a745;">{{ $statistik['selesai'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Tidak Bisa Diperbaiki</div>
            <div class="value" style="color: #6c757d;">{{ $statistik['tidak_bisa_diperbaiki'] }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nama Barang</th>
                <th width="10%">Kode Barang</th>
                <th width="10%">Tgl Rusak</th>
                <th width="20%">Kerusakan</th>
                <th width="12%">Penanggung Jawab</th>
                <th width="13%">Biaya Perbaikan</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemeliharaan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                <td>{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                <td>{{ $item->kerusakan }}</td>
                <td>{{ $item->penanggung_jawab }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->biaya_perbaikan, 0, ',', '.') }}</td>
                <td>
                    <span class="badge badge-{{ $item->statusBadge }}">
                        {{ $item->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data pemeliharaan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        <h3>Total Biaya Perbaikan Keseluruhan:</h3>
        <div class="total-amount">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
    </div>

    <div class="footer">
        <div class="signature">
            <div class="signature-box">
                <div>Dibuat Oleh,</div>
                <div class="signature-line">Admin</div>
            </div>
            <div class="signature-box">
                <div>Mengetahui,</div>
                <div class="signature-line">Kepala Bagian</div>
            </div>
        </div>
    </div>
</body>
</html>