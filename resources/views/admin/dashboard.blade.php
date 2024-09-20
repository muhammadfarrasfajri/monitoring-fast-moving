<x-app-layout>
    <x-slot name="title">
        Monitoring Material Fast Moving Sparepart & Pelumas
    </x-slot>
    <section class="row">
        <x-card-sum size="4" text="TOTAL ITEM FAST MOVING" value="{{ $totalItem ?? 0 }}" icon="box"
            color="info" />
        <x-card-sum size="4" text="QOH TERISI" value="{{ $filledQoh ?? 0 }}" icon="box" color="success" />
        <x-card-sum size="4" text="CUKUP PR" value="{{ $cukupPR ?? 0 }}" icon="box" color="warning" />
        <x-card-sum size="4" text="CUKUP PO" value="{{ $cukupPO ?? 0 }}" icon="box" color="primary" />
        <x-card-sum size="4" text="CUKUP PR & PO" value="{{ $cukupprpo ?? 0 }}" icon="box"
            color="secondary" />
        <x-card-sum size="4" text="CREATE PR" value="{{ $createPR ?? 0 }}" icon="box" color="danger" />
    </section>
    <section class="row">
        <div class="col-md-4">
            <!-- Area Charts -->
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-navbar text-white">
                    <h6 class="m-0 font-weight-bold">PERSENTASE KETERSEDIAAN MATERIAL</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie mb-4">
                        <canvas id="qohChart"></canvas>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-danger">Qoh Kosong</span>
                            <span class="badge badge-danger badge-pill">{{ $emptyQoh }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-primary">Qoh Terisi</span>
                            <span class="badge badge-primary badge-pill">{{ $filledQoh }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- log activity section --}}
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header py-4 d-flex flex-row align-items-center justify-content-between bg-navbar text-white">
                    <h6 class="m-0 font-weight-bold">MRP Contrl / Record Count</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-Light">
                                <tr>
                                    <th>STATUS RUNNING MRP</th>
                                    <th>P02</th>
                                    <th>P03</th>
                                    <th>P04</th>
                                    <th>P05</th>
                                    <th>P06</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>CUKUP PR</th>
                                    <td>{{ $dataByMRP['P02']['cukup_pr'] }}</td>
                                    <td>{{ $dataByMRP['P03']['cukup_pr'] }}</td>
                                    <td>{{ $dataByMRP['P04']['cukup_pr'] }}</td>
                                    <td>{{ $dataByMRP['P05']['cukup_pr'] }}</td>
                                    <td>{{ $dataByMRP['P06']['cukup_pr'] }}</td>
                                </tr>
                                <tr>
                                    <th>QOH ROP</th>
                                    <td>{{ $dataByMRP['P02']['qoh_rop'] }}</td>
                                    <td>{{ $dataByMRP['P03']['qoh_rop'] }}</td>
                                    <td>{{ $dataByMRP['P04']['qoh_rop'] }}</td>
                                    <td>{{ $dataByMRP['P05']['qoh_rop'] }}</td>
                                    <td>{{ $dataByMRP['P06']['qoh_rop'] }}</td>
                                </tr>
                                <tr>
                                    <th>CUKUP PR & PO</th>
                                    <td>{{ $dataByMRP['P02']['cukup_pr_po'] }}</td>
                                    <td>{{ $dataByMRP['P03']['cukup_pr_po'] }}</td>
                                    <td>{{ $dataByMRP['P04']['cukup_pr_po'] }}</td>
                                    <td>{{ $dataByMRP['P05']['cukup_pr_po'] }}</td>
                                    <td>{{ $dataByMRP['P06']['cukup_pr_po'] }}</td>
                                </tr>
                                <tr>
                                    <th>CREATE PR</th>
                                    <td>{{ $dataByMRP['P02']['create_pr'] }}</td>
                                    <td>{{ $dataByMRP['P03']['create_pr'] }}</td>
                                    <td>{{ $dataByMRP['P04']['create_pr'] }}</td>
                                    <td>{{ $dataByMRP['P05']['create_pr'] }}</td>
                                    <td>{{ $dataByMRP['P06']['create_pr'] }}</td>
                                </tr>
                                <tr>
                                    <th>CUKUP PO</th>
                                    <td>{{ $dataByMRP['P02']['cukup_po'] }}</td>
                                    <td>{{ $dataByMRP['P03']['cukup_po'] }}</td>
                                    <td>{{ $dataByMRP['P04']['cukup_po'] }}</td>
                                    <td>{{ $dataByMRP['P05']['cukup_po'] }}</td>
                                    <td>{{ $dataByMRP['P06']['cukup_po'] }}</td>
                                </tr>
                                <tr>
                                    <th>Total keseluruhan</th>
                                    <td>{{ $totalTable['P02'] }}</td>
                                    <td>{{ $totalTable['P03'] }}</td>
                                    <td>{{ $totalTable['P04'] }}</td>
                                    <td>{{ $totalTable['P05'] }}</td>
                                    <td>{{ $totalTable['P06'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .table th,
            .table td {
                font-size: 0.8rem;
                padding: 1.45em;
            }
        </style>
        <div class="col-md-12">
            <x-card>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card text-white text-center p-2 shadow-sm" style="background-color: #4BC0C0;">
                            <h5 class="card-title mb-3 fs-6 fs-md-5 fs-lg-4">Total PR Terbit:</h5>
                            <p class="card-text fs-6 fs-md-5 fs-lg-4">{{ $totalPRYear }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card text-white text-center p-2 shadow-sm" style="background-color: #9966FF;">
                            <h5 class="card-title mb-3 fs-6 fs-md-5 fs-lg-4">Total PO Terbit:</h5>
                            <p class="card-text fs-6 fs-md-5 fs-lg-4">{{ $totalPOYear }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.dashboard') }}" method="GET">
                        <label for="year">Pilih Tahun:</label>
                        <select name="year" id="year" onchange="this.form.submit()">
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </form>
                    <canvas id="prPoChart"></canvas>
                </div>
            </x-card>
        </div>
        <div class="col-md-12">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-navbar text-white">
                <h6 class="m-0 font-weight-bold">TREND KETERSEDIAAN KAT-A</h6>
            </div>
            <x-card>
                <div class="card-body">
                    <form method="GET" action="">
                        <label for="yearFilter">Pilih Tahun:</label>
                        <select id="yearFilter" name="year" onchange="this.form.submit()">
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <canvas id="trendChart"></canvas>
                </div>
            </x-card>
        </div>
        <style>
            #trendChart {
                width: 100%;
                max-width: 900px;
                /* Sesuaikan max-width sesuai kebutuhan */
                max-height: 400px;
                /* Sesuaikan height sesuai kebutuhan */
            }
        </style>
        <x-slot name="script">
            <script>
                Chart.defaults.global.defaultFontFamily = 'Nunito',
                    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                Chart.defaults.global.defaultFontColor = '#858796';

                document.addEventListener("DOMContentLoaded", function() {
                    var ctxQohChart = document.getElementById("qohChart");
                    if (ctxQohChart) {
                        ctxQohChart.style.width = '200px';
                        ctxQohChart.style.height = '100px';
                        new Chart(ctxQohChart, {
                            type: 'pie',
                            data: {
                                labels: ['Filled QOH', 'Empty QOH'],
                                datasets: [{
                                    data: [{{ $filledPercentage ?? 0 }}, {{ $emptyPercentage ?? 0 }}],
                                    backgroundColor: ['#0000ff', '#ff1a1a'],
                                    hoverBorderColor: "rgba(200, 236, 244, 1)",
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                responsive: true,
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    caretPadding: 10,
                                },
                                legend: {
                                    display: false
                                }
                            },
                        });
                    }
                });
                // Ambil data dari controller (PHP to JavaScript)
                const months = @json(array_column($result, 'month'));
                const totalPR = @json(array_column($result, 'totalPR'));
                const totalPO = @json(array_column($result, 'totalPO'));

                const ctx = document.getElementById('prPoChart').getContext('2d');

                const prPoChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months, // Label sumbu X (bulan)
                        datasets: [{
                                label: 'Total PR Terbit',
                                data: totalPR, // Data total PR
                                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna batang
                                borderColor: 'rgba(75, 192, 192, 1)', // Warna garis batang
                                borderWidth: 1
                            },
                            {
                                label: 'Total PO Terbit',
                                data: totalPO, // Data total PO
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true // Mulai dari 0 pada sumbu Y
                            }
                        }
                    }
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const labels = @json($trendsByYear->pluck('Bulan'));
                    const jumlahKatA = @json($trendsByYear->pluck('JUMLAH KAT-A'));
                    const qohTerisi = @json($trendsByYear->pluck('QOH TERISI'));
                    const cukupPR = @json($trendsByYear->pluck('CUKUP PR'));
                    const cukupPO = @json($trendsByYear->pluck('CUKUP PO'));
                    const cukupPRPO = @json($trendsByYear->pluck('CUKUP PR & PO'));
                    const createPR = @json($trendsByYear->pluck('CREATE PR'));

                    // Inisialisasi chart seperti biasa
                    const ctx = document.getElementById('trendChart').getContext('2d');
                    const trendChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'JUMLAH KAT-A',
                                    data: jumlahKatA,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'CREATE PR',
                                    data: createPR,
                                    borderColor: 'rgba(255, 159, 64, 1)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'QOH TERISI',
                                    data: qohTerisi,
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'CUKUP PR',
                                    data: cukupPR,
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'CUKUP PO',
                                    data: cukupPO,
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'CUKUP PR & PO',
                                    data: cukupPRPO,
                                    borderColor: 'rgba(255, 206, 86, 1)',
                                    fill: false,
                                    tension: 0.1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            aspectRatio: 2,
                            scales: {
                                x: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: 'Bulan'
                                    }
                                },
                                y: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah'
                                    }
                                }
                            }
                        }
                    });
                });

                $('.add-csv').click(function() {
                    $('#add-csv').modal('show')
                })
                $(document).ready(function() {
                    $('table').DataTable();
                });
            </script>
        </x-slot>
</x-app-layout>
