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

        .text-danger {
            color: red;
        }

        #keterangan tr td:first-child {
            width: 150px;
        }

        #pesanan,
        #rata-rata {
            border-collapse: collapse;
            margin-top: 50px;
            margin-bottom: 50px;
            width: 90%;
        }

        #pesanan td,
        #pesanan th,
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

        /* Chart-specific styling for better print layout */
        .chart-container {
            position: relative;
            width: 100%;
            height: 400px; /* Adjust height as needed for individual charts */
            margin-top: 20px;
            margin-bottom: 50px;
        }

        /* Media query for print styles */
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

        <h5 class="text-center"><u>Laporan Economic Order Quantity</u></h5>

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
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Safety Stock</th>
                    <th>ROP</th>
                    <th>Persediaan Saat Ini</th>
                    <th>Jumlah yang harus dipesan (EOQ)</th>
                    <th>Frekuensi Pemesanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_eoq as $item)
                <tr>
                    <td class="text-center">{{ $item['produk']->kode_produk }}</td>
                    <td>{{ $item['produk']->nama_produk }}</td>
                    <td class="text-center">{{ $item['produk']->safety_stock }}</td>
                    <td class="text-center">{{ $item['produk']->reorder_point }}</td>
                    <td class="text-center">{{ $item['produk']->label_persediaan }}</td>
                    <td class="text-center">{{ $item['produk']->economic_order_quantity }}</td>
                    <td class="text-center">{{ $item['produk']->frekuensi_pemesanan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h5 class="text-center">Grafik EOQ, Safety Stock, dan ROP per Produk</h5>
        <div id="charts-wrapper">
            @foreach ($data_eoq as $item)
            <div class="chart-container">
                <canvas id="chart-{{ $item['produk']->kode_produk }}"></canvas>
            </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col"></div>
            <div class="col" style="text-align: center;">
                <p style="margin-bottom: 100px;"><b>UD Toko Diva Mowewe</b></p>
                <p><b><u>{{ $ttd }}</u></b></p>
            </div>
        </div>
    </div>

    <script>
        // Register the datalabels plugin
        Chart.register(ChartDataLabels);

        // Loop through each product to create a separate chart
        @foreach ($data_eoq as $item)
            const ctx_{{ $item['produk']->kode_produk }} = document.getElementById('chart-{{ $item['produk']->kode_produk }}').getContext('2d');
            new Chart(ctx_{{ $item['produk']->kode_produk }}, {
                type: 'bar',
                data: {
                    labels: ['{{ $item['produk']->nama_produk }}'], // Label for this single product
                    datasets: [
                        {
                            label: 'EOQ (Jumlah Dipesan)',
                            data: [{{ round($item['eoq']) }}],
                            backgroundColor: 'rgba(54, 162, 235, 0.7)', // Blue
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Safety Stock',
                            data: [{{ round($item['safety_stock']) }}],
                            backgroundColor: 'rgba(255, 99, 132, 0.7)', // Red
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Reorder Point (ROP)',
                            data: [{{ round($item['reorder_point']) }}],
                            backgroundColor: 'rgba(75, 192, 192, 0.7)', // Teal
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Persediaan Saat Ini',
                            data: [{{ round($item['produk']->persediaan->jumlah) }}],
                            backgroundColor: 'rgba(255, 206, 86, 0.7)', // Yellow
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Frekuensi Pemesanan',
                            data: [{{ round($item['produk']->frekuensi_pemesanan) }}],
                            backgroundColor: 'rgba(153, 102, 255, 0.7)', // Purple
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah (Unit / Frekuensi)',
                                font: { size: 14 }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                font: { size: 12 }
                            }
                        },
                        x: {
                            title: {
                                display: false, // Hide x-axis title as product name is in chart title
                                text: 'Nama Produk',
                                font: { size: 14 }
                            },
                            ticks: {
                                display: false // Hide x-axis ticks as there's only one bar
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: { size: 12 },
                                padding: 20
                            }
                        },
                        title: {
                            display: true,
                            text: 'Grafik EOQ, Safety Stock, ROP, Persediaan Saat Ini, dan Frekuensi Pemesanan untuk {{ $item['produk']->nama_produk }}',
                            font: { size: 16 },
                            padding: 20
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            font: { size: 10, weight: 'bold' },
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
        @endforeach

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
</body>

</html>
