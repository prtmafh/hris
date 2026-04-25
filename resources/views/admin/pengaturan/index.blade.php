@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@push('styles')
{{-- <style>
    .kategori-badge {
        font-size: 0.72rem;
        letter-spacing: 0.04em;
    }

    .info-field {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.35rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: #495057;
        min-height: 2.25rem;
        display: flex;
        align-items: center;
    }

    html[data-sb-theme="dark"] .info-field {
        background-color: var(--sb-dark-surface-soft);
        border-color: var(--sb-dark-border);
        color: var(--sb-dark-text);
    }

    html[data-sb-theme="dark"] .modal-info-section {
        background-color: var(--sb-dark-surface) !important;
        border-color: var(--sb-dark-border) !important;
    }

    .modal-info-section {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
    }

    .nilai-cell {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style> --}}
@endpush

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="settings"></i></div>
                            Pengaturan Aplikasi
                        </h1>
                        <div class="page-header-subtitle">
                            Kelola konfigurasi dan parameter operasional aplikasi.
                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <div class="badge bg-white text-primary px-3 py-2 fs-6">
                            <i class="fas fa-sliders-h me-1"></i>
                            {{ $pengaturan->count() }} Pengaturan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-list-ul text-primary"></i>
                    <span class="fw-semibold">Daftar Pengaturan</span>
                </div>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#modalTambahPengaturan">
                    <i class="fas fa-plus me-1"></i> Tambah Pengaturan
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Nama Pengaturan</th>
                                <th>Kategori</th>
                                <th>Nilai</th>
                                <th>Keterangan</th>
                                <th class="text-center pe-4" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengaturan as $index => $item)
                            <tr>
                                <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $item->label ?: $item->key }}</td>
                                <td>
                                    @php
                                    $grupColor = match(strtolower($item->grup ?? '')) {
                                    'system', 'sistem' => 'dark',
                                    'operasional' => 'primary',
                                    'absensi' => 'info',
                                    'gaji', 'penggajian' => 'success',
                                    'notifikasi' => 'warning',
                                    default => 'secondary',
                                    };
                                    $grupLabel = match(strtolower($item->grup ?? '')) {
                                    'system', 'sistem' => 'Sistem',
                                    'operasional' => 'Operasional',
                                    'absensi' => 'Absensi',
                                    'gaji', 'penggajian' => 'Penggajian',
                                    'notifikasi' => 'Notifikasi',
                                    default => ucfirst($item->grup ?: 'Umum'),
                                    };
                                    @endphp
                                    <span class="badge bg-{{ $grupColor }}-soft text-{{ $grupColor }} kategori-badge">
                                        {{ $grupLabel }}
                                    </span>
                                </td>
                                <td class="nilai-cell" title="{{ $item->value }}">
                                    @if($item->tipe === 'boolean')
                                    @if(in_array(strtolower($item->value ?? ''), ['1', 'true', 'ya', 'yes']))
                                    <span class="badge bg-success-soft text-success">
                                        <i class="fas fa-check me-1"></i>Aktif
                                    </span>
                                    @else
                                    <span class="badge bg-danger-soft text-danger">
                                        <i class="fas fa-times me-1"></i>Nonaktif
                                    </span>
                                    @endif
                                    @else
                                    <span class="small">{{ $item->value ?: '-' }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $item->keterangan ?: '-' }}</td>
                                <td class="text-center pe-4">
                                    <button type="button" class="btn btn-sm btn-warning rounded-pill px-3"
                                        title="Ubah nilai pengaturan" onclick="openEditPengaturan(
                                            {{ $item->id }},
                                            @js($item->label ?: $item->key),
                                            @js($item->key),
                                            @js($item->tipe),
                                            @js($item->value),
                                            @js($item->keterangan)
                                        )">
                                        <i class="fas fa-pen fa-xs me-1"></i> Ubah
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                    Belum ada data pengaturan.
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

{{-- Modal Ubah Pengaturan --}}
<div class="modal fade" id="modalEditPengaturan" tabindex="-1" aria-labelledby="modalEditPengaturanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditPengaturan" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0 rounded-top">
                    <h5 class="modal-title fw-semibold" id="modalEditPengaturanLabel">
                        <i class="fas fa-pen me-2"></i>
                        Ubah Pengaturan: <span id="modal_judul_pengaturan" class="fst-italic fw-normal"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Keterangan sebagai petunjuk --}}
                    <div id="modal_keterangan_wrapper"
                        class="alert alert-info d-flex gap-2 align-items-start py-2 px-3 mb-4 d-none">
                        <i class="fas fa-info-circle mt-1 shrink-0"></i>
                        <small id="modal_keterangan_text" class="mb-0"></small>
                    </div>

                    {{-- Input Nilai --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold mb-2">
                            Nilai Pengaturan <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="edit_pengaturan_value_text" name="value"
                            class="form-control @error('value') is-invalid @enderror" placeholder="Masukkan nilai...">
                        <textarea id="edit_pengaturan_value_textarea" name="value" rows="5"
                            class="form-control @error('value') is-invalid @enderror d-none"
                            placeholder="Masukkan nilai..."></textarea>
                        <select id="edit_pengaturan_value_boolean" name="value"
                            class="form-select @error('value') is-invalid @enderror d-none">
                            <option value="1">Ya / Aktif</option>
                            <option value="0">Tidak / Nonaktif</option>
                        </select>
                        @error('value')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer border-top bg-light rounded-bottom d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function hideAllValueInputs() {
        ['edit_pengaturan_value_text', 'edit_pengaturan_value_textarea', 'edit_pengaturan_value_boolean'].forEach(id => {
            const el = document.getElementById(id);
            el.classList.add('d-none');
            el.disabled = true;
        });
    }

    function openEditPengaturan(id, label, key, tipe, value, keterangan) {
        document.getElementById('formEditPengaturan').action = `/admin/pengaturan/${id}`;
        document.getElementById('modal_judul_pengaturan').textContent = label;

        const keteranganWrapper = document.getElementById('modal_keterangan_wrapper');
        const keteranganText    = document.getElementById('modal_keterangan_text');
        if (keterangan) {
            keteranganText.textContent = keterangan;
            keteranganWrapper.classList.remove('d-none');
        } else {
            keteranganWrapper.classList.add('d-none');
        }

        hideAllValueInputs();

        const inputText     = document.getElementById('edit_pengaturan_value_text');
        const inputTextarea = document.getElementById('edit_pengaturan_value_textarea');
        const inputBoolean  = document.getElementById('edit_pengaturan_value_boolean');
        const tipeKey       = (tipe || '').toLowerCase();

        if (tipeKey === 'json') {
            inputTextarea.classList.remove('d-none');
            inputTextarea.disabled = false;
            inputTextarea.value = value ?? '';
        } else if (tipeKey === 'boolean') {
            inputBoolean.classList.remove('d-none');
            inputBoolean.disabled = false;
            inputBoolean.value = ['1', 'true', 'ya', 'yes'].includes(String(value).toLowerCase()) ? '1' : '0';
        } else {
            inputText.classList.remove('d-none');
            inputText.disabled = false;
            inputText.value = value ?? '';

            if (tipeKey === 'integer') {
                inputText.type = 'number'; inputText.step = '1';
            } else if (tipeKey === 'decimal') {
                inputText.type = 'number'; inputText.step = '0.01';
            } else if (tipeKey === 'date') {
                inputText.type = 'date'; inputText.removeAttribute('step');
            } else if (tipeKey === 'time') {
                inputText.type = 'time'; inputText.removeAttribute('step');
            } else {
                inputText.type = 'text'; inputText.removeAttribute('step');
            }
        }

        new bootstrap.Modal(document.getElementById('modalEditPengaturan')).show();
    }

    @if($errors->has('value') && session('edit_pengaturan_id'))
        @php
            $currentPengaturan = $pengaturan->firstWhere('id', session('edit_pengaturan_id'));
        @endphp
        @if($currentPengaturan)
            openEditPengaturan(
                {{ $currentPengaturan->id }},
                @js($currentPengaturan->label ?: $currentPengaturan->key),
                @js($currentPengaturan->key),
                @js($currentPengaturan->tipe),
                @js(old('value', $currentPengaturan->value)),
                @js($currentPengaturan->keterangan)
            );
        @endif
    @endif

    // Handle tipe change for tambah modal
    document.getElementById('tambah_tipe').addEventListener('change', function() {
        const tipe = this.value;
        const valueInput = document.getElementById('tambah_value');
        const placeholder = getPlaceholderForType(tipe);
        valueInput.placeholder = placeholder;

        // Set input type based on tipe
        if (tipe === 'integer') {
            valueInput.type = 'number';
            valueInput.step = '1';
        } else if (tipe === 'decimal') {
            valueInput.type = 'number';
            valueInput.step = '0.01';
        } else if (tipe === 'date') {
            valueInput.type = 'date';
            valueInput.removeAttribute('step');
        } else if (tipe === 'time') {
            valueInput.type = 'time';
            valueInput.removeAttribute('step');
        } else {
            valueInput.type = 'text';
            valueInput.removeAttribute('step');
        }
    });

    function getPlaceholderForType(tipe) {
        switch(tipe) {
            case 'string': return 'Masukkan teks...';
            case 'integer': return 'Masukkan bilangan bulat (contoh: 100)';
            case 'decimal': return 'Masukkan bilangan desimal (contoh: 15.50)';
            case 'boolean': return 'Masukkan 1 (ya) atau 0 (tidak)';
            case 'json': return 'Masukkan JSON valid (contoh: {"key": "value"})';
            case 'time': return 'Masukkan waktu (contoh: 08:00)';
            case 'date': return 'Masukkan tanggal (contoh: 2024-01-01)';
            default: return 'Masukkan nilai default';
        }
    }

    // Reset form when modal is hidden
    document.getElementById('modalTambahPengaturan').addEventListener('hidden.bs.modal', function() {
        document.getElementById('formTambahPengaturan').reset();
        document.getElementById('tambah_value').type = 'text';
        document.getElementById('tambah_value').removeAttribute('step');
    });
</script>

{{-- Modal Tambah Pengaturan --}}
<div class="modal fade" id="modalTambahPengaturan" tabindex="-1" aria-labelledby="modalTambahPengaturanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="formTambahPengaturan" method="POST" action="{{ route('admin.pengaturan.store') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0 rounded-top">
                    <h5 class="modal-title fw-semibold" id="modalTambahPengaturanLabel">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Pengaturan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tambah_key" class="form-label fw-semibold">
                                Key Pengaturan <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('key') is-invalid @enderror" id="tambah_key"
                                name="key" placeholder="Masukkan key pengaturan (contoh: jam_masuk)"
                                value="{{ old('key') }}" required>
                            <div class="form-text">Key unik untuk identifikasi pengaturan</div>
                            @error('key')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tambah_label" class="form-label fw-semibold">
                                Label Pengaturan <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('label') is-invalid @enderror"
                                id="tambah_label" name="label" placeholder="Masukkan label pengaturan"
                                value="{{ old('label') }}" required>
                            <div class="form-text">Nama tampilan yang mudah dipahami</div>
                            @error('label')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tambah_tipe" class="form-label fw-semibold">
                                Tipe Data <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('tipe') is-invalid @enderror" id="tambah_tipe" name="tipe"
                                required>
                                <option value="">Pilih tipe data</option>
                                <option value="string" {{ old('tipe')=='string' ? 'selected' : '' }}>String (Teks)
                                </option>
                                <option value="integer" {{ old('tipe')=='integer' ? 'selected' : '' }}>Integer (Bilangan
                                    Bulat)</option>
                                <option value="decimal" {{ old('tipe')=='decimal' ? 'selected' : '' }}>Decimal (Bilangan
                                    Desimal)</option>
                                <option value="boolean" {{ old('tipe')=='boolean' ? 'selected' : '' }}>Boolean
                                    (Ya/Tidak)</option>
                                <option value="json" {{ old('tipe')=='json' ? 'selected' : '' }}>JSON</option>
                                <option value="time" {{ old('tipe')=='time' ? 'selected' : '' }}>Time (HH:MM)</option>
                                <option value="date" {{ old('tipe')=='date' ? 'selected' : '' }}>Date (YYYY-MM-DD)
                                </option>
                            </select>
                            <div class="form-text">Tipe data yang akan disimpan</div>
                            @error('tipe')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tambah_grup" class="form-label fw-semibold">Grup Pengaturan</label>
                            <input type="text" class="form-control @error('grup') is-invalid @enderror" id="tambah_grup"
                                name="grup" placeholder="Masukkan grup (contoh: sistem, absensi)"
                                value="{{ old('grup') }}">
                            <div class="form-text">Kategori pengaturan (opsional)</div>
                            @error('grup')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tambah_keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="tambah_keterangan"
                            name="keterangan" rows="2"
                            placeholder="Penjelasan singkat tentang pengaturan ini">{{ old('keterangan') }}</textarea>
                        <div class="form-text">Penjelasan fungsi pengaturan (opsional)</div>
                        @error('keterangan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="tambah_value" class="form-label fw-semibold">Nilai Default</label>
                        <input type="text" class="form-control @error('value') is-invalid @enderror" id="tambah_value"
                            name="value" placeholder="Masukkan nilai default" value="{{ old('value') }}">
                        <div class="form-text">Nilai awal pengaturan (opsional)</div>
                        @error('value')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer border-top bg-light rounded-bottom d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="fas fa-save me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endpush