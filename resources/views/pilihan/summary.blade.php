@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">

      {{-- Diagram Persentase Suara --}}
      <div class="card section-card mb-4">
        <div class="card-header section-header">
          Vote Percentage Chart
        </div>
        <div class="card-body section-body d-flex justify-content-center">
          <div style="width: 100%; max-width: 300px;">
            <canvas id="chartSuara" width="300" height="300"></canvas>
          </div>
        </div>
      </div>

      {{-- Perolehan Suara --}}
      <div class="card section-card">
        <div class="card-header section-header">
          Vote Result
        </div>
        <div class="card-body section-body p-0">
          <table class="table table-hover table-bordered mb-0">
            <thead>
              <tr>
                <th>Nomor Urut</th>
                <th>Foto Pasangan Calon</th>
                <th>Nama Pasangan</th>
                <th>Jumlah Suara</th>
                <th>Persentase</th>
              </tr>
            </thead>
            {{-- tambahkan kelas text-center dan align-middle di sini --}}
            <tbody class="text-center align-middle">
              @foreach ($candidates as $candidate)
              <tr>
                <td>{{ $candidate->id }}</td>
                <td>
                  @if ($candidate->photo_paslon)
                    <img src="{{ asset('storage/'.$candidate->photo_paslon) }}"
                         width="100" alt="photo">
                  @endif
                </td>
                <td>{{ $candidate->nama_ketua }} &amp; {{ $candidate->nama_wakil }}</td>
                <td>{{ $candidate->users->count() }} Suara</td>
                <td>{{ round($candidate->users->count() / $jumlah * 100, 2) }} %</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="5">
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
  $data   = [];
  $colors = ['#FF6384','#36A2EB','#FFCE56','#8BC34A','#9C27B0','#FF9800'];

  foreach($candidates as $c) {
    $labels[] = $c->nama_ketua.' & '.$c->nama_wakil;
    $data[]   = $c->users->count();
  }
@endphp

<script>
  const ctx = document.getElementById('chartSuara').getContext('2d');
  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: {!! json_encode($labels) !!},
      datasets: [{ data: {!! json_encode($data) !!},
                   backgroundColor: {!! json_encode(array_slice($colors,0,count($data))) !!},
                   borderColor: "#fff", borderWidth: 1 }]
    },
    options: {
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: ctx => {
              const total = ctx.chart._metasets[ctx.datasetIndex].total;
              const val   = ctx.raw;
              const pct   = ((val/total)*100).toFixed(1);
              return `${ctx.label}: ${val} Suara (${pct}%)`;
            }
          }
        }
      }
    }
  });
</script>
@endsection
