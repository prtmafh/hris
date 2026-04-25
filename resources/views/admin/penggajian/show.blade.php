@extends('admin.layouts.app')

@section('title','Detail Penggajian')

@section('content')
@php
$namaBulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
'Juli','Agustus','September','Oktober','November','Desember'];

$pemasukan = $penggajian->details->where('tipe','pemasukan');
$potongan = $penggajian->details->where('tipe','potongan');
$totalPemasukan = $pemasukan->sum('jumlah');
$totalPotongan = $potongan->sum('jumlah');
@endphp

<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="file-text"></i>
                            </div>
                            Detail Penggajian
                        </h1>
                    </div>

                    <div class="col-auto mb-3 d-flex gap-2">

                        <a href="{{ route('admin.penggajian') }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left"></i>
                            Kembali
                        </a>

                        @if($penggajian->status==='proses')
                        <form method="POST" action="{{ route('admin.penggajian.bayar',$penggajian->id) }}"
                            id="formTandaiBayar">
                            @csrf
                            <button type="button" class="btn btn-sm btn-success" onclick="confirmTandaiBayar(event)">
                                <i data-feather="check-circle"></i>
                                Tandai Dibayar
                            </button>
                        </form>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </header>


    <div class="container-xl px-4">

        <nav class="nav nav-borders">
            <a class="nav-link active ms-0">
                Payroll Detail
            </a>
        </nav>

        <hr class="mt-0 mb-4">


        <div class="row">

            {{-- LEFT SIDEBAR --}}
            <div class="col-xl-4">

                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">
                        Employee Profile
                    </div>

                    <div class="card-body text-center">

                        <img class="img-account-profile rounded-circle mb-2" src="{{ $penggajian->karyawan->foto
? asset('storage/'.$penggajian->karyawan->foto)
: 'https://ui-avatars.com/api/?name='.urlencode($penggajian->karyawan->nama) }}">

                        <div class="fw-bold fs-5 text-capitalize">
                            {{ $penggajian->karyawan->nama }}
                        </div>

                        <div class="small text-muted mb-3 text-capitalize">
                            {{ $penggajian->karyawan->jabatan->nama_jabatan ?? '-' }}
                        </div>

                        @if($penggajian->status=='dibayar')
                        <span class="badge bg-green-soft text-green">
                            Dibayar
                        </span>
                        @else
                        <span class="badge bg-yellow-soft text-yellow">
                            Proses
                        </span>
                        @endif

                    </div>
                </div>


                <div class="card mt-4">
                    <div class="card-header">
                        Payroll Info
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Periode
                            </span>

                            <strong>
                                {{ $namaBulan[$penggajian->periode_bulan] }}
                                {{ $penggajian->periode_tahun }}
                            </strong>
                        </div>


                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Hari Hadir
                            </span>

                            <strong>
                                {{ $penggajian->total_hadir }}
                            </strong>
                        </div>


                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Lembur
                            </span>

                            <strong>
                                Rp {{ number_format($penggajian->total_lembur,0,',','.') }}
                            </strong>
                        </div>


                        <div class="d-flex justify-content-between py-2">
                            <span class="small text-muted">
                                Potongan
                            </span>

                            <strong class="text-danger">
                                Rp {{ number_format($penggajian->potongan,0,',','.') }}
                            </strong>
                        </div>

                    </div>
                </div>


                <div class="card mt-4">
                    <div class="card-header">
                        Net Salary
                    </div>

                    <div class="card-body text-center">

                        <div class="text-muted small mb-2">
                            Take Home Pay
                        </div>

                        <div class="display-6 fw-bold text-primary">
                            Rp {{ number_format($penggajian->total_gaji,0,',','.') }}
                        </div>

                    </div>
                </div>

            </div>


            {{-- RIGHT --}}
            <div class="col-xl-8">


                <div class="card mb-4">
                    <div class="card-header">
                        Salary Components
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-hover mb-0 align-middle">

                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th class="text-end">
                                        Jumlah
                                    </th>
                                    <th class="text-center">
                                        Tipe
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @if($pemasukan->isNotEmpty())

                                <tr>
                                    <td colspan="3" class="bg-light text-success fw-bold">
                                        Pemasukan
                                    </td>
                                </tr>

                                @foreach($pemasukan as $d)
                                <tr>

                                    <td>
                                        {{ $d->keterangan }}
                                    </td>

                                    <td class="text-end fw-semibold text-success">
                                        Rp {{ number_format($d->jumlah,0,',','.') }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-green-soft text-green">
                                            Pemasukan
                                        </span>
                                    </td>

                                </tr>
                                @endforeach

                                <tr class="border-top">
                                    <td class="text-muted">
                                        Subtotal
                                    </td>

                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($totalPemasukan,0,',','.') }}
                                    </td>

                                    <td></td>
                                </tr>

                                @endif



                                @if($potongan->isNotEmpty())

                                <tr>
                                    <td colspan="3" class="bg-light text-danger fw-bold">
                                        Potongan
                                    </td>
                                </tr>

                                @foreach($potongan as $d)

                                <tr>

                                    <td>
                                        {{ $d->keterangan }}
                                    </td>

                                    <td class="text-end fw-semibold text-danger">
                                        Rp {{ number_format($d->jumlah,0,',','.') }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-red-soft text-red">
                                            Potongan
                                        </span>
                                    </td>

                                </tr>

                                @endforeach


                                <tr class="border-top">
                                    <td class="text-muted">
                                        Subtotal
                                    </td>

                                    <td class="text-end fw-bold text-danger">
                                        Rp {{ number_format($totalPotongan,0,',','.') }}
                                    </td>

                                    <td></td>
                                </tr>

                                @endif


                                @if($pemasukan->isEmpty() && $potongan->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Belum ada rincian komponen
                                    </td>
                                </tr>
                                @endif

                            </tbody>

                            <tfoot>
                                <tr class="table-light">
                                    <th>
                                        Total Gaji Bersih
                                    </th>

                                    <th class="text-end text-primary fw-bold">
                                        Rp {{ number_format($penggajian->total_gaji,0,',','.') }}
                                    </th>

                                    <th></th>
                                </tr>
                            </tfoot>

                        </table>

                    </div>
                </div>


                <div class="row">

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="small text-muted">
                                    Total Pemasukan
                                </div>

                                <div class="h4 fw-bold text-success">
                                    Rp {{ number_format($totalPemasukan,0,',','.') }}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="small text-muted">
                                    Total Potongan
                                </div>

                                <div class="h4 fw-bold text-danger">
                                    Rp {{ number_format($totalPotongan,0,',','.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>

    </div>

</main>
@endsection


@push('scripts')
<script>
    function confirmTandaiBayar(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        text: 'Tandai gaji ini sebagai sudah dibayar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tandai Dibayar',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if(result.isConfirmed){
            document.getElementById('formTandaiBayar').submit();
        }
    });

    return false;
}
</script>
@endpush