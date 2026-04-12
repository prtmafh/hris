@extends('karyawan.layouts.app')

@section('title', 'Pengajuan Izin')

@push('styles')
<style>
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
        border: none;
    }
    .table-history th { font-size: .8rem; text-transform: uppercase; color: #6b7280; font-weight: 600; }
    .table-history td { font-size: .9rem; vertical-align: middle; }
    .form-section { background: #f9fafb; border-radius: 14px; padding: 1.5rem; }
</style>
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl px-3 px-md-4">

            {{-- PAGE HEADER --}}
            <div class="d-flex align-items-center mt-4 mb-4">
                <div>
                    <h2 class="fw-bolder text-dark mb-1" style="font-size: clamp(1.1rem,4vw,1.4rem);">Pengajuan Izin</h2>
                    <span class="text-muted fw-semibold" style="font-size:.9rem;">Ajukan izin, sakit, atau cuti Anda di sini</span>
                </div>
            </div>

            <div class="row g-4 mb-5">

                {{-- FORM PENGAJUAN --}}
                <div class="col-12 col-xl-5">
                    <div class="card main-card h-100">
                        <div class="card-header border-0 pt-4 px-4">
                            <h3 class="card-title fw-bolder text-dark mb-0" style="font-size:1.1rem;">Form Pengajuan</h3>
                        </div>
                        <div class="card-body px-4 pt-3">
                            <form method="POST" action="{{ route('karyawan.izin.store') }}">
                                @csrf
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal Izin <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal"
                                            class="form-control @error('tanggal') is-invalid @enderror"
                                            value="{{ old('tanggal') }}"
                                            min="{{ now()->format('Y-m-d') }}">
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis Izin <span class="text-danger">*</span></label>
                                        <select name="jenis_izin" class="form-select @error('jenis_izin') is-invalid @enderror">
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="sakit" {{ old('jenis_izin') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="izin"  {{ old('jenis_izin') == 'izin'  ? 'selected' : '' }}>Izin</option>
                                            <option value="cuti"  {{ old('jenis_izin') == 'cuti'  ? 'selected' : '' }}>Cuti</option>
                                        </select>
                                        @error('jenis_izin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                                        <textarea name="keterangan" rows="4"
                                            class="form-control @error('keterangan') is-invalid @enderror"
                                            placeholder="Jelaskan alasan izin Anda...">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- RIWAYAT --}}
                <div class="col-12 col-xl-7">
                    <div class="card main-card h-100">
                        <div class="card-header border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                            <h3 class="card-title fw-bolder text-dark mb-0" style="font-size:1.1rem;">Riwayat Pengajuan</h3>
                            <span class="badge badge-light-primary">{{ $izin->total() }} data</span>
                        </div>
                        <div class="card-body px-4 pt-2">
                            <div class="table-responsive">
                                <table class="table table-hover table-history">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($izin as $iz)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($iz->tanggal)->translatedFormat('d M Y') }}</td>
                                            <td class="text-capitalize">{{ $iz->jenis_izin }}</td>
                                            <td>
                                                <span class="d-inline-block text-truncate" style="max-width:150px;" title="{{ $iz->keterangan }}">
                                                    {{ $iz->keterangan }}
                                                </span>
                                            </td>
                                            <td>
                                                @switch($iz->status_approval)
                                                    @case('pending')
                                                        <span class="badge badge-light-warning">Menunggu</span>
                                                        @break
                                                    @case('disetujui')
                                                        <span class="badge badge-light-success">Disetujui</span>
                                                        @break
                                                    @case('ditolak')
                                                        <span class="badge badge-light-danger">Ditolak</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-light-secondary text-capitalize">{{ $iz->status_approval }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="fas fa-file-alt fa-2x mb-2 d-block text-gray-400"></i>
                                                Belum ada pengajuan izin.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                {{ $izin->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
