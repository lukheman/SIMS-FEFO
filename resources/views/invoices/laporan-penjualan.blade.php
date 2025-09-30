<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan Barang</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <style>
        hr {
            height: 2px;
            background-color: black;
            border: none;
        }

        .container {
            width: 70%;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        #keterangan tr td:first-child {
            width: 150px;
        }

        .pesanan,
        #rata-rata {
            border-collapse: collapse;
            margin-top: 50px;
            margin-bottom: 50px;
            width: 90%;
        }


        .pesanan td,
        .pesanan th,
        #rata-rata td,
        #rata-rata th {
            border: 1px solid black;
            padding: 8px;
        }

        .row {
            display: flex;
        }

        .col {
            flex: 1;
            padding: 10px;
        }

        .text-right {
            text-align: end;
        }

        /* Chart-specific styling for better print layout */
        .chart-container {
            position: relative;
            width: 100%;
            height: 400px;
            margin-top: 20px;
            margin-bottom: 50px;
        }

        @media print {
            .chart-container {
                height: 500px !important;
                width: 100% !important;
            }

            canvas {
                font-size: 10pt;
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

        <table class="pesanan">

            <thead>

                <tr>
                    <th>Tanggal</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                    <th>Rata-rata Harian</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga (Rp.)</th>
                </tr>

            </thead>

            <tbody>
                @foreach ($groupedPenjualan as $group)
                @foreach ($group['items'] as $index => $item)
                <tr>
                    <td class="text-center">{{ $item->tanggal }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-right"> {{ $item->label_jumlah_unit_terjual}} </td>
                    @if ($index === 0)
                    <td rowspan="{{ $group['rowspan'] }}" style="text-align: center;">
                        {{ $group['rata_rata_harian'] }}
                    </td>
                    @endif
                    <td class="text-right"> {{ $item->label_harga_jual }} </td>
                    <td class="text-right"> {{ $item->label_total_harga_jual }} </td>
                </tr>
                @endforeach
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                    <td style="text-align: right;"><strong>Rp. {{ number_format($total, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>

        </table>

        <h5 class="text-center">Grafik Rata-rata Penjualan Harian per Produk</h5>
        <div class="chart-container">
            <canvas id="averageSalesChart"></canvas>
        </div>

        <h5 class="text-center">5 Produk Terlaris</h5>
        <table class="pesanan">

            <thead>

                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                </tr>

            </thead>

            <tbody>
                @foreach ($top5 as $group)
                @foreach ($group['items'] as $index => $item)

                <tr>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-right"> {{ $item->label_jumlah_unit_terjual}} </td>


                </tr>
                @endforeach
                @endforeach
            </tbody>

        </table>

        <div class="row">
            <div class="col">
            </div>
            <div class="col" style="text-align: center;">
                <p style="margin-bottom: 100px;"><b>UD Toko Diva Mowewe</b></p>
                <p><b><u>{{ $ttd }}</u></b></p>
            </div>
        </div>
    </div>

</body>

<script>
    // Register the datalabels plugin
    Chart.register(ChartDataLabels);

    // Prepare data for the Average Sales Chart
    const productNames = [];
    const averageDailySales = [];

    @foreach ($groupedPenjualan as $group)
        productNames.push('{{ $group['items'][0]->produk->nama_produk }}');
        averageDailySales.push({{ round($group['rrh']) }});
    @endforeach

    const ctxAverageSales = document.getElementById('averageSalesChart').getContext('2d');
    const averageSalesChart = new Chart(ctxAverageSales, {
        type: 'bar', // Bar chart is suitable for comparing averages
        data: {
            labels: productNames,
            datasets: [{
                label: 'Rata-rata Penjualan Harian',
                data: averageDailySales,
                backgroundColor: 'rgba(255, 159, 64, 0.7)', // Orange color
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1,
                borderRadius: 8,
                barPercentage: 0.8,
                categoryPercentage: 0.8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Unit Terjual',
                        font: {
                            size: 14
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Produk',
                        font: {
                            size: 14
                        }
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12
                        },
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: 'Grafik Rata-rata Penjualan Harian per Produk ({{ $periode }})',
                    font: {
                        size: 16
                    },
                    padding: 20
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    font: {
                        size: 10,
                        weight: 'bold'
                    },
                    color: '#333',
                    formatter: (value) => value
                }
            },
            layout: {
                padding: {
                    top: 30,
                    bottom: 10
                }
            }
        }
    });

    // Adjust chart height for print
    window.addEventListener('beforeprint', () => {
        const chartContainers = document.querySelectorAll('.chart-container');
        chartContainers.forEach(container => {
            container.style.height = '500px';
            container.style.width = '100%';
        });
        // Re-render charts for print
        for (const id in Chart.instances) {
            Chart.instances[id].resize();
        }
    });

    window.addEventListener('afterprint', () => {
        const chartContainers = document.querySelectorAll('.chart-container');
        chartContainers.forEach(container => {
            container.style.height = '400px';
            container.style.width = '100%';
        });
        // Re-render charts back to screen size
        for (const id in Chart.instances) {
            Chart.instances[id].resize();
        }
    });
</script>

</html>
