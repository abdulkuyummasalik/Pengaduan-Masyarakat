@extends('layouts.app')
@section('content')
    <div class=" flex justify-center items-center min-h-screen">
        <div class="bg-white/10 text-white shadow-md rounded-lg p-6 w-2/3">
            <h1 class="text-2xl font-bold text-center mb-6">Jumlah Pengaduan dan Tanggapan terhadap Pengaduan<br>
            </h1>
            <canvas id="pengaduanChart"></canvas>
        </div>
    </div>
    <script>
        const data = {
            labels: ["Pengaduan", "Tanggapan"],
            datasets: [{
                label: "Total Data",
                data: [{{ $reports }}, {{ $responses }}],
                backgroundColor: ["#22c55e", "#f97316"],
                borderWidth: 1,
            }, ],
        };

        const config = {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `Total Data: ${tooltipItem.raw}`;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        };

        const pengaduanChart = new Chart(
            document.getElementById("pengaduanChart"),
            config
        );
    </script>
@endsection
