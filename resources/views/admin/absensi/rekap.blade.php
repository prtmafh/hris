@extends('admin.layouts.app')

@section('title','Rekap Absensi')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="bar-chart-2"></i>
                            </div>
                            Rekap Absensi
                        </h1>

                        {{-- <div class="page-header-subtitle">
                            Rekapitulasi kehadiran karyawan per periode
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </header>



    <div class="container-xl px-4">


        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-header">
                Filter Rekap Kehadiran
            </div>

            <div class="card-body">

                <form method="GET" action="{{ route('rekap.tahunan') }}" class="row gx-3 gy-3 align-items-end">

                    <div class="col-md-4">
                        <label class="small mb-1">
                            Tahun
                        </label>

                        <select name="tahun" class="form-select form-select-sm">

                            @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun==$t?'selected':'' }}>
                                {{ $t }}
                            </option>
                            @endforeach

                        </select>

                    </div>



                    <div class="col-md-4">
                        <label class="small mb-1">
                            Bulan
                        </label>

                        <select name="bulan" class="form-select form-select-sm">

                            <option value="">
                                Semua Bulan
                            </option>

                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']
                            as $i=>$namaBulan)

                            <option value="{{ $i+1 }}" {{ $bulan==($i+1)?'selected':'' }}>
                                {{ $namaBulan }}
                            </option>

                            @endforeach

                        </select>
                    </div>



                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-sm">
                            <i data-feather="search"></i>
                            Tampilkan
                        </button>
                    </div>

                </form>

            </div>
        </div>




        {{-- SUMMARY CARDS --}}
        <div class="row mb-4">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-lg border-start-success h-100">
                    <div class="card-body">
                        <div class="small text-muted">
                            Total Hadir
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ collect($rekap)->sum('hadir') }}
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-lg border-start-warning h-100">
                    <div class="card-body">
                        <div class="small text-muted">
                            Total Terlambat
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ collect($rekap)->sum('terlambat') }}
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-lg border-start-info h-100">
                    <div class="card-body">
                        <div class="small text-muted">
                            Total Izin
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ collect($rekap)->sum('izin') }}
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-lg border-start-danger h-100">
                    <div class="card-body">
                        <div class="small text-muted">
                            Total Alpha
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ collect($rekap)->sum('alpha') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>




        {{-- TABLE --}}
        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>
                    Rekap Tahun {{ $tahun }}

                    @if($bulan)
                    —
                    {{
                    ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan-1]
                    }}
                    @endif
                </div>

                <div class="small text-muted">
                    {{ count($rekap) }} karyawan
                </div>

            </div>



            <div class="card-body">

                <div class="table-responsive">

                    <table id="datatablesSimple" class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Profil Karyawan</th>
                                <th>Jabatan</th>
                                <th class="text-center">
                                    Hadir
                                </th>
                                <th class="text-center">
                                    Terlambat
                                </th>
                                <th class="text-center">
                                    Izin
                                </th>
                                <th class="text-center">
                                    Alpha
                                </th>
                                <th class="text-center">
                                    Total Hari
                                </th>
                            </tr>
                        </thead>


                        <tbody>

                            @forelse($rekap as $index=>$r)

                            <tr>

                                <td class="text-muted fw-semibold">
                                    {{ $index+1 }}
                                </td>



                                <td>

                                    <div class="d-flex align-items-center">

                                        <img src="{{ $r['karyawan']->foto
? asset('storage/'.$r['karyawan']->foto)
: 'https://ui-avatars.com/api/?name='.urlencode($r['karyawan']->nama) }}" width="40" height="40"
                                            class="rounded-circle me-3">


                                        <div>

                                            <div class="fw-semibold text-capitalize">
                                                {{ $r['karyawan']->nama }}
                                            </div>

                                            <div class="small text-muted">
                                                NIK:
                                                {{ $r['karyawan']->nik }}
                                            </div>

                                        </div>

                                    </div>

                                </td>




                                <td class="text-capitalize">
                                    {{ optional($r['karyawan']->jabatan)->nama_jabatan ?? '-' }}
                                </td>




                                <td class="text-center">
                                    <span class="badge bg-green-soft text-green">
                                        {{ $r['hadir'] }}
                                    </span>
                                </td>


                                <td class="text-center">
                                    <span class="badge bg-yellow-soft text-yellow">
                                        {{ $r['terlambat'] }}
                                    </span>
                                </td>


                                <td class="text-center">
                                    <span class="badge bg-blue-soft text-blue">
                                        {{ $r['izin'] }}
                                    </span>
                                </td>


                                <td class="text-center">
                                    <span class="badge bg-red-soft text-red">
                                        {{ $r['alpha'] }}
                                    </span>
                                </td>



                                <td class="text-center fw-bold">
                                    {{ $r['total'] }}
                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    Tidak ada data untuk periode ini
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>
            </div>

        </div>


    </div>

</main>
@endsection