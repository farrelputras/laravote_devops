@extends('layouts.app')

@section('content')

@if(session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<div class="container">
    <div class="row">
        <div class="col-md-12">

            {{-- Chart Area --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Diagram Persentase Suara</h3>
                </div>
                <div class="d-flex justify-content-center my-4">
                    <div style="width: 100%; max-width: 300px;">
                        <canvas id="chartSuara" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tabel Suara --}}
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">Perolehan Suara</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Nomor Urut</th>
                                <th>Foto Pasangan Calon</th>
                                <th>Nama Pasangan</th>
                                <th>Jumlah Suara</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidates as $candidate)
                            <tr>
                                <td>{{ $candidate->id }}</td>
                                <td>
                                    @if ($candidate->photo_paslon)
                                    <img src="{{ asset('storage/'.$candidate->photo_paslon) }}" width="100px" />
                                    @endif
                                </td>
                                <td>{{ $candidate->nama_ketua . ' dan ' . $candidate->nama_wakil }}</td>
                                <td>{{ $candidate->users->count() }} Suara</td>
                                <td>
                                    @if($jumlah > 0)
                                    {{ number_format(($candidate->users->count() / $jumlah) * 100, 2) }} %
                                    @else
                                    0%
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10">
                                    {{ $candidates->appends(Request::all())->links() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
$labels = [];
$data = [];
$colors = ['#FFCE56', '#8BC34A', '#9C27B0', '#FF9800', '#FF6384', '#36A2EB'];

foreach($candidates as $candidate) {
$labels[] = $candidate->nama_ketua . ' & ' . $candidate->nama_wakil;
$data[] = $candidate->users->count();
}
@endphp

<script>
    const canvas = document.getElementById('chartSuara');
    if (canvas instanceof HTMLCanvasElement) {
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    data: {!! json_encode($data) !!},
                    backgroundColor: {!! json_encode(array_slice($colors, 0, count($data))) !!},
                    borderColor: "#fff",
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} Suara (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('Canvas element #chartSuara not found or not a <canvas>');
    }
</script>

@endsection