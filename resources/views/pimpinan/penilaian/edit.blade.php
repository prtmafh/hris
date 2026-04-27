@extends('pimpinan.layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit-3"></i></div>
                            Edit Penilaian
                        </h1>
                        <div class="page-header-subtitle">{{ $penilaian->karyawan->nama ?? '-' }} —
                            {{ $penilaian->nama_bulan }} {{ $penilaian->periode_tahun }}</div>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('pimpinan.penilaian.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width:16px;height:16px;"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <i data-feather="star" class="me-2 text-warning"></i>
                        Edit Penilaian — {{ $penilaian->karyawan->nama ?? '-' }}
                    </div>
                    <div class="card-body">
                        <!-- Info karyawan (readonly) -->
                        <div class="alert alert-info d-flex gap-3 mb-4">
                            <i data-feather="info" style="width:20px;height:20px;flex-shrink:0;"></i>
                            <div>
                                <strong>{{ $penilaian->karyawan->nama ?? '-' }}</strong>
                                ({{ $penilaian->karyawan->jabatan->nama_jabatan ?? '-' }}) —
                                Periode {{ $penilaian->nama_bulan }} {{ $penilaian->periode_tahun }}
                            </div>
                        </div>

                        <form action="{{ route('pimpinan.penilaian.update', $penilaian->id) }}" method="POST">
                            @csrf @method('PUT')

                            <p class="text-muted small mb-3">
                                <i data-feather="info" style="width:14px;height:14px;"></i>
                                Nilai 0–100. Total = Kehadiran×40% + Kedisiplinan×30% + Kinerja×30%
                            </p>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nilai Kehadiran <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="nilai_kehadiran" step="0.01" min="0" max="100"
                                            class="form-control @error('nilai_kehadiran') is-invalid @enderror"
                                            value="{{ old('nilai_kehadiran', $penilaian->nilai_kehadiran) }}" required
                                            oninput="hitungTotal()">
                                        <span class="input-group-text">/ 100</span>
                                        @error('nilai_kehadiran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-text">Bobot 40%</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nilai Kedisiplinan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="nilai_kedisiplinan" step="0.01" min="0" max="100"
                                            class="form-control @error('nilai_kedisiplinan') is-invalid @enderror"
                                            value="{{ old('nilai_kedisiplinan', $penilaian->nilai_kedisiplinan) }}" required
                                            oninput="hitungTotal()">
                                        <span class="input-group-text">/ 100</span>
                                        @error('nilai_kedisiplinan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-text">Bobot 30%</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nilai Kinerja <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="nilai_kinerja" step="0.01" min="0" max="100"
                                            class="form-control @error('nilai_kinerja') is-invalid @enderror"
                                            value="{{ old('nilai_kinerja', $penilaian->nilai_kinerja) }}" required
                                            oninput="hitungTotal()">
                                        <span class="input-group-text">/ 100</span>
                                        @error('nilai_kinerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-text">Bobot 30%</div>
                                </div>
                            </div>

                            <!-- Preview Total -->
                            <div class="card bg-light mt-4 mb-3">
                                <div class="card-body py-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="small text-muted">Nilai Total (Preview)</div>
                                            <div class="h3 mb-0 fw-bold" id="previewTotal">0.00</div>
                                            <div class="progress mt-2" style="height:8px;">
                                                <div class="progress-bar" id="progressTotal" style="width:0%"></div>
                                            </div>
                                        </div>
                                        <div class="col-auto text-center">
                                            <div class="small text-muted mb-1">Grade</div>
                                            <span class="badge fs-4 px-3 py-2" id="previewGrade" style="min-width:50px;">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                                    rows="3" placeholder="Catatan tambahan (opsional)...">{{ old('catatan', $penilaian->catatan) }}</textarea>
                                @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pimpinan.penilaian.index') }}" class="btn btn-light">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" style="width:16px;height:16px;"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function hitungTotal() {
        const kehadiran    = parseFloat(document.querySelector('[name=nilai_kehadiran]').value) || 0;
        const kedisiplinan = parseFloat(document.querySelector('[name=nilai_kedisiplinan]').value) || 0;
        const kinerja      = parseFloat(document.querySelector('[name=nilai_kinerja]').value) || 0;

        const total = (kehadiran * 0.4) + (kedisiplinan * 0.3) + (kinerja * 0.3);
        document.getElementById('previewTotal').textContent = total.toFixed(2);
        document.getElementById('progressTotal').style.width = total + '%';

        let grade = 'D', color = 'danger';
        if (total >= 90) { grade = 'A'; color = 'success'; }
        else if (total >= 75) { grade = 'B'; color = 'primary'; }
        else if (total >= 60) { grade = 'C'; color = 'warning'; }

        const badge = document.getElementById('previewGrade');
        badge.textContent = grade;
        badge.className = `badge fs-4 px-3 py-2 bg-${color}`;
        document.getElementById('progressTotal').className = `progress-bar bg-${color}`;
    }
    hitungTotal();
</script>
@endpush
