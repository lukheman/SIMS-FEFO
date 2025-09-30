<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pesanan Barang</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    {{-- Pastikan Chart.js dan plugin datalabels sudah diimpor --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        hr {
            height: 2px;
            background-color: black;
            border: none;
            margin: 20px 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: end;
        }

        /* Header styling */
        .report-header {
            margin-bottom: 30px;
        }

        .report-header h2 {
            margin: 0;
            font-size: 24px;
            color: #222;
        }

        .report-header h5 {
            margin: 5px 0 15px 0;
            font-size: 18px;
            color: #555;
        }


        #keterangan {
            margin-bottom: 20px;
        }

        #keterangan tr td:first-child {
            width: 150px;
            font-weight: bold;
        }

        #pesanan,
        #rata-rata {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            margin-bottom: 30px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */ /* Dihilangkan */
        }

        #pesanan th,
        #rata-rata th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 12px 8px;
            border: 1px solid #ddd;
        }

        #pesanan td,
        #rata-rata td {
            border: 1px solid #ddd;
            padding: 10px 8px;
        }

        #pesanan tbody tr:nth-child(even) {
            /* background-color: #f9f9f9; */ /* Dihilangkan */
            background-color: transparent; /* Pastikan tidak ada background */
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 50px;
        }

        .col {
            flex: 1;
            padding: 10px;
        }

        .col.signature {
            flex: 0.5;
            text-align: center;
        }

        /* Chart-specific styling for better print layout */
        .chart-container {
            position: relative;
            width: 100%;
            height: 400px;
            margin-top: 20px;
            margin-bottom: 50px;
            background-color: #fff;
            padding: 15px;
            box-sizing: border-box;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                width: 90%;
            }

            .chart-container {
                height: 500px !important;
                width: 100% !important;
                page-break-inside: avoid;
            }

            canvas {
                font-size: 10pt;
            }

            /* Adjust badge styles for print if needed, ensuring readability */
            .badge {
                border: 1px solid #ccc;
                padding: 3px 6px;
                border-radius: 3px;
                background-color: #e0e0e0 !important;
                color: #333 !important;
                font-size: 0.85em;
            }

            /* Hide payment button in print */
            .btn-status-pembayaran-lunas {
                display: none;
            }
        }
    </style>

</head>

<body onload="window.print()">
    <div class="container">

        <x-kop-laporan />

        <h5 class="text-center"><u>Laporan Penjualan</u></h5>

        <table id="keterangan">
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $periode }}</td>
            </tr>
        </table>

        <table id="pesanan">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pemesan</th>
                    <th>Status Transaksi</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>
                        {{-- Assuming x-status-transaksi renders a badge or similar --}}
                        <x-status-transaksi :status="$item->status" />
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $item->metode_pembayaran }}</span>
                    </td>
                    <td>
                        {{-- This button should ideally not be part of a printed report --}}
                        <button class="btn btn-sm btn-success btn-status-pembayaran-lunas"
                            data-id-transaksi="{{ $item->id }}"
                            {{ $item->metode_pembayaran === \App\Constants\MetodePembayaran::COD ? 'disabled' : '' }}>
                            <i class="fas fa-money-check"></i> Lunas
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        <h5 class="text-center"><u>Statistik Status Transaksi</u></h5>
        <div class="chart-container">
            <canvas id="transactionStatusChart"></canvas>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                {{-- Left column can be used for notes or additional information if needed --}}
            </div>
            <div class="col signature">
                <p style="margin-bottom: 80px;"><b>UD Toko Diva Mowewe</b></p>
                <p><b><u>{{ $ttd }}</u></b></p>
            </div>
        </div>
    </div>

    <script>
        // Pastikan variabel $counts diteruskan dari controller ke Blade view
        // Contoh: public function generateReport(Request $request) { ... return view('report', ['counts' => $counts]); }
        const transactionCounts = @json($counts ?? []); // Pastikan $counts tersedia, fallback ke array kosong jika tidak

        const labels = Object.keys(transactionCounts);
        const data = Object.values(transactionCounts);

        // Fungsi untuk menghasilkan warna acak yang konsisten untuk setiap label
        function getBackgroundColor(label) {
            const colors = {
                'pending': 'rgba(255, 159, 64, 0.7)',    // Orange
                'diproses': 'rgba(54, 162, 235, 0.7)',   // Blue
                'dikirim': 'rgba(75, 192, 192, 0.7)',    // Green-ish
                'ditolak': 'rgba(255, 99, 132, 0.7)',    // Red
                'diterima': 'rgba(153, 102, 255, 0.7)',  // Purple
                'selesai': 'rgba(60, 179, 113, 0.7)',    // Medium Sea Green (more solid green)
                'batal': 'rgba(100, 100, 100, 0.7)',     // Grey
            };
            // Default color if status not found
            return colors[label.toLowerCase()] || 'rgba(200, 200, 200, 0.7)';
        }

        const backgroundColors = labels.map(label => getBackgroundColor(label));
        const borderColors = labels.map(label => getBackgroundColor(label).replace('0.7', '1')); // Opaque border

        const ctx = document.getElementById('transactionStatusChart').getContext('2d');
        const transactionStatusChart = new Chart(ctx, {
            type: 'bar', // Menggunakan bar chart
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Tidak perlu legend jika hanya ada satu dataset
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi Berdasarkan Status',
                        font: {
                            size: 16
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: (value) => value, // Menampilkan nilai aktual
                        color: '#333',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Status Transaksi'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        },
                        ticks: {
                            // Memastikan label sumbu y adalah bilangan bulat
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Mengaktifkan plugin datalabels
        });
    </script>

</body>

</html>
