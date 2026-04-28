@extends('karyawan.layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Izin Saya
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        <div class="row g-4">

            {{-- FORM PENGAJUAN --}}
            <div class="col-12 col-xl-5">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="fw-bold">Form Pengajuan Izin</div>
                        <div class="small text-muted">Lengkapi data pengajuan untuk diproses oleh admin.</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('karyawan.izin.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small mb-1">Tanggal Izin</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal') }}" min="{{ now()->format('Y-m-d') }}">
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small mb-1">Jenis Izin</label>
                                <select name="jenis_izin" class="form-select @error('jenis_izin') is-invalid @enderror">
                                    <option value="">Pilih jenis izin</option>
                                    <option value="sakit" {{ old('jenis_izin')=='sakit' ? 'selected' : '' }}>Sakit
                                    </option>
                                    <option value="izin" {{ old('jenis_izin')=='izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="cuti" {{ old('jenis_izin')=='cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                                @error('jenis_izin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small mb-1">Keterangan</label>
                                <textarea name="keterangan" rows="4"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    placeholder="Tulis alasan pengajuan izin Anda">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather="send"></i> Kirim Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KUOTA + RIWAYAT --}}
            <div class="col-12 col-xl-7">

                {{-- KUOTA --}}
                <div class="card border-left-primary shadow-sm mb-4">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1 fw-bold">Kuota Izin Tahunan</h5>
                                <small class="text-muted">Maksimal 12 kali izin per tahun</small>
                            </div>
                            <span class="badge bg-primary fs-6 ms-2">{{ $sisaIzin }} Sisa</span>
                        </div>

                        <div class="row text-center mb-3 g-2">
                            <div class="col-4">
                                <h3 class="fw-bold text-primary mb-0">{{ $kuotaIzin }}</h3>
                                <small class="text-muted">Total Kuota</small>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold text-warning mb-0">{{ $izinTerpakai }}</h3>
                                <small class="text-muted">Terpakai</small>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold text-success mb-0">{{ $sisaIzin }}</h3>
                                <small class="text-muted">Sisa</small>
                            </div>
                        </div>

                        @php $persen = ($izinTerpakai / $kuotaIzin) * 100; @endphp

                        <div class="progress" style="height: 18px">
                            <div class="progress-bar
                                {{ $persen > 80 ? 'bg-danger' : ($persen > 50 ? 'bg-warning' : 'bg-success') }}"
                                style="width: {{ $persen }}%">
                                <span class="d-none d-sm-inline">{{ round($persen) }}%</span>
                            </div>
                        </div>

                        @if($sisaIzin <= 0) <div class="alert alert-danger mt-3 mb-0">
                            Kuota izin Anda habis. Tidak dapat mengajukan izin lagi.
                    </div>
                    @endif

                </div>
            </div>

            {{-- RIWAYAT --}}
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <div class="fw-bold">Riwayat Pengajuan Izin</div>
                        <div class="small text-muted">Daftar seluruh pengajuan izin Anda.</div>
                    </div>
                    <span class="badge bg-primary">{{ $izin->count() }} data</span>
                </div>
                <div class="card-body p-0 p-md-3">
                    <div class="table-responsive">
                        <table id="datatablesSimple" data-simple-datatable class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th class="d-none d-md-table-cell">Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($izin as $iz)
                                @php
                                $badge = match($iz->status_approval) {
                                'pending' => 'yellow',
                                'disetujui' => 'green',
                                'ditolak' => 'red',
                                default => 'secondary',
                                };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="white-space: nowrap">
                                            {{ \Carbon\Carbon::parse($iz->tanggal)->translatedFormat('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-capitalize">
                                            {{ $iz->jenis_izin }}
                                        </span>
                                    </td>
                                    <td class="text-muted d-none d-md-table-cell">{{ $iz->keterangan }}</td>
                                    <td>
                                        <span class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">
                                            {{ $iz->status_approval }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Belum ada pengajuan izin.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
</main>
@endsection