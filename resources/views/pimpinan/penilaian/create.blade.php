@extends('pimpinan.layouts.app')

@section('title', 'Beri Penilaian')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="edit-3"></i>
                            </div>
                            Beri Penilaian Karyawan
                        </h1>
                    </div>

                    <div class="col-12 col-xl-auto mb-3">
                        <a href="{{ route('pimpinan.penilaian.index') }}" class="btn btn-sm btn-light text-primary">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <i data-feather="star" class="me-2 text-warning"></i> Form Penilaian
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pimpinan.penilaian.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Karyawan <span
                                            class="text-danger">*</span></label>
                                    <select name="karyawan_id"
                                        class="form-select @error('karyawan_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Karyawan --</option>
                                        @foreach($karyawan as $k)
                                        <option value="{{ $k->id }}" {{ old('karyawan_id')==$k->id ? 'selected' : '' }}
                                            {{ in_array($k->id, $sudahDinilai) ? 'disabled' : '' }}>
                                            {{ $k->nama }}
                                            ({{ $k->jabatan->nama_jabatan ?? '-' }})
                                            {{ in_array($k->id, $sudahDinilai) ? '— sudah dinilai' : '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('karyawan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Bulan Periode <span
                                            class="text-danger">*</span></label>
                                    <select name="periode_bulan"
                                        class="form-select @error('periode_bulan') is-invalid @enderror" required>
                                        @foreach(range(1,12) as $b)
                                        <option value="{{ $b }}" {{ (old('periode_bulan', $bulan)==$b) ? 'selected' : ''
                                            }}>
                                            {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMMM') }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('periode_bulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tahun Periode <span
                                            class="text-danger">*</span></label>
                                    <select name="periode_tahun"
                                        class="form-select @error('periode_tahun') is-invalid @enderror" required>
                                        @foreach($tahunList as $t)
                                        <option value="{{ $t }}" {{ (old('periode_tahun', $tahun)==$t) ? 'selected' : ''
                                            }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                    @error('periode_tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <hr class="my-4">
                            <p class="text-muted small mb-3">
                                <i data-feather="info" style="width:14px;height:14px;"></i>
                                Nilai 0–100. Total = Kehadiran×40% + Kedisiplinan×30% + Kinerja×30%
                            </p>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nilai Kehadiran <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="nilai_kehadiran" step="0.01" min="0" max="100"
                                            class="form-control @error('nilai_kehadiran') is-invalid @enderror"
                                            value="{{ old('nilai_kehadiran', 0) }}" readonly>
                                        <span class="input-group-text">/ 100</span>
                                        @error('nilai_kehadiran') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Otomatis dari data absensi. Bobot 40%</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nilai Kedisiplinan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="nilai_kedisiplinan" step="0.01" min="0" max="100"
                                            class="form-control @error('nilai_kedisiplinan') is-invalid @enderror"
                                            value="{{ old('nilai_kedisiplinan', 0) }}" required oninput="hitungTotal()">
                                        <span class="input-group-text">/ 100</span>
                                        @error('nilai_kedisiplinan') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Bobot 30%</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nilai Kinerja <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="nilai_kinerja" step="0.01" min="0" max="100"
                                            class="form-control @error('nilai_kinerja') is-invalid @enderror"
                                            value="{{ old('nilai_kinerja', 0) }}" required oninput="hitungTotal()">
                                        <span class="input-group-text">/ 100</span>
                                        @error('nilai_kinerja') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                            <span class="badge fs-4 px-3 py-2" id="previewGrade"
                                                style="min-width:50px;">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Catatan tambahan (opsional)...">{{ old('catatan') }}</textarea>
                                @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pimpinan.penilaian.index') }}" class="btn btn-light">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" style="width:16px;height:16px;"></i> Simpan Penilaian
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
    const nilaiKehadiranMap = @json($nilaiKehadiranMap);

    function updateNilaiKehadiran() {
        const karyawanId = document.querySelector('[name=karyawan_id]').value;
        const tahun      = document.querySelector('[name=periode_tahun]').value;
        const bulan      = document.querySelector('[name=periode_bulan]').value;
        const nilai      = nilaiKehadiranMap?.[karyawanId]?.[tahun]?.[bulan] ?? 0;

        document.querySelector('[name=nilai_kehadiran]').value = parseFloat(nilai).toFixed(2);
        hitungTotal();
    }

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

    document.querySelector('[name=karyawan_id]').addEventListener('change', updateNilaiKehadiran);
    document.querySelector('[name=periode_bulan]').addEventListener('change', updateNilaiKehadiran);
    document.querySelector('[name=periode_tahun]').addEventListener('change', updateNilaiKehadiran);
    updateNilaiKehadiran();
</script>
@endpush
