@extends('karyawan.layouts.app')

@section('title', 'Pengajuan Lembur')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="clock"></i></div>
                            Lembur Saya
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        <div class="row g-4">
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="fw-bold">Form Pengajuan Lembur</div>
                        <div class="small text-muted">Isi detail jam lembur agar dapat diproses oleh admin.</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('karyawan.lembur.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small mb-1">Tanggal Lembur</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal') }}">
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small mb-1">Jam Mulai</label>
                                    <input type="time" name="jam_mulai"
                                        class="form-control @error('jam_mulai') is-invalid @enderror"
                                        value="{{ old('jam_mulai') }}">
                                    @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small mb-1">Jam Selesai</label>
                                    <input type="time" name="jam_selesai"
                                        class="form-control @error('jam_selesai') is-invalid @enderror"
                                        value="{{ old('jam_selesai') }}">
                                    @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3 mb-4">
                                <label class="form-label small mb-1">Keterangan</label>
                                <textarea name="keterangan" rows="4"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    placeholder="Tulis pekerjaan yang dikerjakan saat lembur">{{ old('keterangan') }}</textarea>
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

            <div class="col-xl-8">
                <div class="card">
                    <div
                        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <div class="fw-bold">Riwayat Pengajuan Lembur</div>
                            <div class="small text-muted">Daftar pengajuan lembur yang pernah Anda buat.</div>
                        </div>
                        <span class="badge bg-primary">{{ $lembur->count() }} data</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatablesSimple" data-simple-datatable
                                class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Total Jam</th>
                                        <th>Upah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lembur as $lb)
                                    @php
                                    $badge = match($lb->status) {
                                    'pending' => 'yellow',
                                    'disetujui' => 'green',
                                    'ditolak' => 'red',
                                    default => 'secondary',
                                    };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($lb->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        <td>{{ $lb->jam_mulai ? \Carbon\Carbon::parse($lb->jam_mulai)->format('H:i') :
                                            '-' }}</td>
                                        <td>{{ $lb->jam_selesai ? \Carbon\Carbon::parse($lb->jam_selesai)->format('H:i')
                                            : '-' }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $lb->total_jam }} jam
                                            </span>
                                        </td>
                                        <td class="fw-semibold">
                                            Rp {{ number_format($lb->total_upah, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">{{
                                                $lb->status }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada pengajuan lembur.
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