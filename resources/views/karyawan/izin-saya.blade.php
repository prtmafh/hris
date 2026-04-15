@extends('karyawan.layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div
                class="page-header-content pt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                        Izin Saya
                    </h1>
                    <div class="page-header-subtitle">Ajukan izin, sakit, atau cuti dan pantau status persetujuannya.
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row g-4">
            <div class="col-xl-5">
                <div class="card shadow-sm h-100">
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

            <div class="col-xl-7">
                <div class="card shadow-sm ">
                    <div
                        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <div class="fw-bold">Riwayat Pengajuan Izin</div>
                            <div class="small text-muted">Daftar seluruh pengajuan izin Anda.</div>
                        </div>
                        <span class="badge bg-primary">{{ $izin->total() }} data</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-muted small text-uppercase">Tanggal</th>
                                        <th class="text-muted small text-uppercase">Jenis</th>
                                        <th class="text-muted small text-uppercase">Keterangan</th>
                                        <th class="text-muted small text-uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($izin as $iz)
                                    @php
                                    $badge = match($iz->status_approval) {
                                    'pending' => 'warning',
                                    'disetujui' => 'success',
                                    'ditolak' => 'danger',
                                    default => 'secondary',
                                    };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($iz->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border text-capitalize">{{
                                                $iz->jenis_izin }}</span>
                                        </td>
                                        <td class="text-muted">{{ $iz->keterangan }}</td>
                                        <td>
                                            <span class="badge bg-{{ $badge }} text-capitalize">{{ $iz->status_approval
                                                }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada pengajuan izin.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $izin->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection