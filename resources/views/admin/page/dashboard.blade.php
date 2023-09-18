@extends('admin.index')
@section('title-head', 'Dashboard')
@section('title-content', 'Dashboard')

@prepend('styles')
    <style>
        .chart {
            display: flex;
            justify-content: center;
            overflow: hidden;
            position: relative;
            margin: 0 auto;
            width: 100%;
        }

        #barChart {
            height: 600px;
        }


        #pieChart {
            height: 275px !important;
        }

        @media (max-width: 576px) {
            .chart {
                max-height: 150px;
            }
        }

        @media (max-width: 768px) {
            .chart {
                max-height: 200px;
            }
        }

        @media (max-width: 992px) {
            .chart {
                max-height: 300px;
            }
        }

        @media (max-width: 1200px) {
            .chart {
                max-height: 400px;
            }
        }

        *,
        *::after,
        *::before {
            box-sizing: border-box;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
@endsection

@section('content')
    <div class="card" style="border-top: 4px solid #42B549;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Pengguna Dengan Penjualan Terbanyak</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Buku Dengan Penjualan Terbanyak</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data untuk chart
            var barChartData = {
                labels: ['Yusuf', 'Azzam', 'Ginanjar', 'Rizki'],
                datasets: [{
                    label: 'Penjualan',
                    data: [20, 35, 30, 15],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };

            var pieChartData = {
                labels: ['Bincang Akhlak', 'Jujutsu Kaisen', 'Learn n Play', 'Sejarah Tuhan'],
                datasets: [{
                    data: [20, 35, 30, 15],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Inisialisasi chart
            var barChart = new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: barChartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true
                }
            });

            var pieChart = new Chart(document.getElementById('pieChart'), {
                type: 'pie',
                data: pieChartData,
                options: {
                    responsive: true
                }
            });
        });
    </script>
@endprepend
