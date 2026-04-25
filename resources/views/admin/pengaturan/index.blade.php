@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="settings"></i>
                            </div>
                            Pengaturan Aplikasi
                        </h1>
                    </div>
                    <div class="col-auto mb-3">
                        <button type="button" class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahPengaturan">
                            <i data-feather="plus"></i>
                            Tambah Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">

        <div class="card mb-4">
            <div class="card-header">Daftar Pengaturan</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Key</th>
                                <th>Nama Pengaturan</th>
                                <th>Grup</th>
                                <th>Nilai</th>
                                <th width="100" class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($pengaturan as $index => $item)

                            @php
                            $grupColor = match(strtolower($item->grup ?? '')) {
                            'system', 'sistem' => 'dark',
                            'operasional' => 'blue',
                            'absensi' => 'yellow',
                            'gaji', 'penggajian' => 'green',
                            'notifikasi' => 'orange',
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

                            <tr>

                                {{-- No --}}
                                <td class="fw-semibold text-muted">{{ $index + 1 }}</td>

                                {{-- Key --}}
                                <td>
                                    <code class="small">{{ $item->key }}</code>
                                </td>

                                {{-- Nama --}}
                                <td>
                                    <div class="fw-semibold">{{ $item->label ?: $item->key }}</div>
                                    @if($item->keterangan)
                                    <div class="small text-muted">{{ \Str::limit($item->keterangan, 60) }}</div>
                                    @endif
                                </td>

                                {{-- Grup --}}
                                <td>
                                    <span class="badge bg-{{ $grupColor }}-soft text-{{ $grupColor }}">
                                        {{ $grupLabel }}
                                    </span>
                                </td>

                                {{-- Nilai --}}
                                <td>
                                    @if($item->tipe === 'boolean')
                                    @if(in_array(strtolower($item->value ?? ''), ['1','true','ya','yes']))
                                    <span class="badge bg-green-soft text-green">Aktif</span>
                                    @else
                                    <span class="badge bg-red-soft text-red">Nonaktif</span>
                                    @endif
                                    @else
                                    <span class="small text-truncate d-inline-block" style="max-width:160px"
                                        title="{{ $item->value }}">
                                        {{ $item->value ?: '-' }}
                                    </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">

                                    {{-- Detail --}}
                                    <button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}"
                                        title="Lihat Detail">
                                        <i data-feather="eye"></i>
                                    </button>

                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-datatable btn-icon btn-transparent-dark"
                                        title="Ubah Pengaturan" onclick="openEditPengaturan(
                                            {{ $item->id }},
                                            @js($item->label ?: $item->key),
                                            @js($item->key),
                                            @js($item->label ?: $item->key),
                                            @js($item->tipe),
                                            @js($item->value),
                                            @js($item->grup),
                                            @js($item->keterangan)
                                        )">
                                        <i data-feather="edit"></i>
                                    </button>

                                </td>
                            </tr>

    @empty
    <tr>
        <td colspan="6" class="text-center text-muted py-5">
            Belum ada data pengaturan.
        </td>
    </tr>
    @endforelse

    </tbody>
    </table>
    </div>
    </div>
    </div>

    @foreach($pengaturan as $item)
    @php
    $grupColor = match(strtolower($item->grup ?? '')) {
    'system', 'sistem' => 'dark',
    'operasional' => 'blue',
    'absensi' => 'yellow',
    'gaji', 'penggajian' => 'green',
    'notifikasi' => 'orange',
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
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom bg-white">
                    <h5 class="modal-title d-flex align-items-center">
                        <i data-feather="settings" class="me-2"></i>
                        Detail Pengaturan
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card border mb-4">
                                <div class="card-body text-center">
                                    <div class="avatar avatar-xl mb-3">
                                        <div
                                            class="avatar-img rounded-circle bg-primary-soft d-flex align-items-center justify-content-center">
                                            <i data-feather="sliders"></i>
                                        </div>
                                    </div>
                                    <div class="fw-bold fs-5">{{ $item->label ?: $item->key }}</div>
                                    <div class="small text-muted mb-3">{{ $item->key }}</div>
                                    <span class="badge bg-{{ $grupColor }}-soft text-{{ $grupColor }}">{{ $grupLabel
                                        }}</span>
                                </div>
                            </div>
                            <div class="card border">
                                <div class="card-header">Configuration Summary</div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span class="small text-muted">Data Type</span>
                                        <strong>{{ ucfirst($item->tipe ?: '-') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between py-2">
                                        <span class="small text-muted">Group</span>
                                        <strong>{{ $grupLabel }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card border mb-4">
                                <div class="card-header">Setting Value</div>
                                <div class="card-body">
                                    @if($item->tipe==='boolean')
                                    @if(in_array(strtolower($item->value ?? ''),['1','true','ya','yes']))
                                    <span class="badge bg-green-soft text-green">Aktif</span>
                                    @else
                                    <span class="badge bg-red-soft text-red">Nonaktif</span>
                                    @endif
                                    @elseif($item->tipe==='json')
                                    <pre class="small bg-light p-4 rounded mb-0">{{ $item->value ?: '-' }}</pre>
                                    @else
                                    <div class="p-4 bg-light rounded fw-semibold">{{ $item->value ?: '-' }}</div>
                                    @endif
                                </div>
                            </div>
                            @if($item->keterangan)
                            <div class="card border">
                                <div class="card-header">Description</div>
                                <div class="card-body">
                                    <div class="p-3 bg-light rounded">{{ $item->keterangan }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" data-bs-dismiss="modal" onclick="openEditPengaturan(
                            {{ $item->id }},
                            @js($item->label ?: $item->key),
                            @js($item->key),
                            @js($item->label ?: $item->key),
                            @js($item->tipe),
                            @js($item->value),
                            @js($item->grup),
                            @js($item->keterangan)
                        )">
                        <i data-feather="edit" class="me-1"></i> Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    </div>
</main>


{{-- MODAL UBAH PENGATURAN --}}
<div class="modal fade" id="modalEditPengaturan" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <form id="formEditPengaturan" method="POST">
            @csrf
            @method('PUT')

                <div class="modal-header border-bottom bg-white">
                    <h5 id="modal_judul_pengaturan" class="modal-title d-flex align-items-center">
                        <i data-feather="edit-2" class="me-2"></i>
                        Ubah Pengaturan
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>


                <div class="modal-body p-0">

                    <div class="row g-0">

                        <div class="col-lg-4 border-end bg-light">

                            <div class="p-4">

                                <h6 class="mb-3">
                                    Configuration Info
                                </h6>

                                <div id="modal_keterangan_wrapper" class="alert alert-info small d-none mb-0">
                                    <span id="modal_keterangan_text"></span>
                                </div>

                            </div>

                        </div>



                        <div class="col-lg-8">

                            <div class="p-4">

                                <div class="row g-3 mb-3">

                                    <div class="col-md-6">
                                        <label class="small mb-1">
                                            Key Pengaturan
                                        </label>

                                        <input id="edit_pengaturan_key" name="key" class="form-control">
                                    </div>


                                    <div class="col-md-6">
                                        <label class="small mb-1">
                                            Nama Pengaturan
                                        </label>

                                        <input id="edit_pengaturan_label" name="label" class="form-control">
                                    </div>

                                </div>



                                <div class="row g-3 mb-3">

                                    <div class="col-md-6">
                                        <label class="small mb-1">
                                            Tipe Data
                                        </label>

                                        <select id="edit_pengaturan_tipe" name="tipe" class="form-select">

                                            <option value="string">String</option>
                                            <option value="integer">Integer</option>
                                            <option value="decimal">Decimal</option>
                                            <option value="boolean">Boolean</option>
                                            <option value="json">JSON</option>
                                            <option value="time">Time</option>
                                            <option value="date">Date</option>

                                        </select>
                                    </div>


                                    <div class="col-md-6">
                                        <label class="small mb-1">
                                            Grup
                                        </label>

                                        <input id="edit_pengaturan_grup" name="grup" class="form-control">
                                    </div>

                                </div>



                                <div class="mb-3">
                                    <label class="small mb-1">
                                        Keterangan
                                    </label>

                                    <textarea id="edit_pengaturan_keterangan" name="keterangan" rows="2"
                                        class="form-control"></textarea>
                                </div>



                                <div>
                                    <label class="small mb-1">
                                        Nilai
                                    </label>

                                    <input id="edit_pengaturan_value_text" name="value" class="form-control">

                                    <textarea id="edit_pengaturan_value_textarea" name="value" rows="5"
                                        class="form-control d-none"></textarea>

                                    <select id="edit_pengaturan_value_boolean" name="value" class="form-select d-none">
                                        <option value="1">Ya / Aktif</option>
                                        <option value="0">Tidak / Nonaktif</option>
                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>


{{-- MODAL TAMBAH PENGATURAN --}}
<div class="modal fade" id="modalTambahPengaturan" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <form id="formTambahPengaturan" method="POST" action="{{ route('admin.pengaturan.store') }}">
            @csrf

                <div class="modal-header border-bottom bg-white">
                    <h5 class="modal-title d-flex align-items-center">
                        <i data-feather="plus-circle" class="me-2"></i>
                        Tambah Pengaturan
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>



                <div class="modal-body p-4">

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="small mb-1">
                                Key Pengaturan
                            </label>

                            <input type="text" name="key" id="tambah_key" class="form-control">
                        </div>


                        <div class="col-md-6">
                            <label class="small mb-1">
                                Nama Pengaturan
                            </label>

                            <input type="text" name="label" id="tambah_label" class="form-control">
                        </div>

                    </div>



                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="small mb-1">
                                Tipe Data
                            </label>

                            <select id="tambah_tipe" name="tipe" class="form-select">

                                <option value="">Pilih tipe</option>
                                <option value="string">String</option>
                                <option value="integer">Integer</option>
                                <option value="decimal">Decimal</option>
                                <option value="boolean">Boolean</option>
                                <option value="json">JSON</option>
                                <option value="time">Time</option>
                                <option value="date">Date</option>

                            </select>
                        </div>



                        <div class="col-md-6">
                            <label class="small mb-1">
                                Grup
                            </label>

                            <input type="text" name="grup" id="tambah_grup" class="form-control">
                        </div>

                    </div>



                    <div class="mb-3">
                        <label class="small mb-1">
                            Keterangan
                        </label>

                        <textarea name="keterangan" id="tambah_keterangan" rows="2" class="form-control"></textarea>
                    </div>



                    <div>
                        <label class="small mb-1">
                            Nilai Default
                        </label>

                        <input type="text" id="tambah_value" name="value" class="form-control">
                    </div>

                </div>



                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Simpan Pengaturan
                    </button>

                </div>

            </form>
        </div>
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

    function openEditPengaturan(id, label, key, labelRaw, tipe, value, grup, keterangan) {
        document.getElementById('formEditPengaturan').action = `/admin/pengaturan/${id}`;
        document.getElementById('modal_judul_pengaturan').textContent = label;

        document.getElementById('edit_pengaturan_key').value         = key ?? '';
        document.getElementById('edit_pengaturan_label').value       = labelRaw ?? '';
        document.getElementById('edit_pengaturan_tipe').value        = tipe ?? '';
        document.getElementById('edit_pengaturan_grup').value        = grup ?? '';
        document.getElementById('edit_pengaturan_keterangan').value  = keterangan ?? '';

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
            inputBoolean.value = ['1','true','ya','yes'].includes(String(value).toLowerCase()) ? '1' : '0';
        } else {
            inputText.classList.remove('d-none');
            inputText.disabled = false;
            inputText.value = value ?? '';
            if (tipeKey === 'integer')      { inputText.type = 'number'; inputText.step = '1'; }
            else if (tipeKey === 'decimal') { inputText.type = 'number'; inputText.step = '0.01'; }
            else if (tipeKey === 'date')    { inputText.type = 'date';   inputText.removeAttribute('step'); }
            else if (tipeKey === 'time')    { inputText.type = 'time';   inputText.removeAttribute('step'); }
            else                            { inputText.type = 'text';   inputText.removeAttribute('step'); }
        }

        new bootstrap.Modal(document.getElementById('modalEditPengaturan')).show();
    }

    // Tipe change handler — modal Edit
    document.getElementById('edit_pengaturan_tipe')?.addEventListener('change', function () {
        const tipe         = this.value;
        const valueText    = document.getElementById('edit_pengaturan_value_text');
        const valueTextarea = document.getElementById('edit_pengaturan_value_textarea');
        const valueBoolean = document.getElementById('edit_pengaturan_value_boolean');
        const currentValue = valueBoolean.value || valueTextarea.value || valueText.value;

        hideAllValueInputs();

        if (tipe === 'json') {
            valueTextarea.classList.remove('d-none'); valueTextarea.disabled = false;
            valueTextarea.value = currentValue;
        } else if (tipe === 'boolean') {
            valueBoolean.classList.remove('d-none'); valueBoolean.disabled = false;
            valueBoolean.value = ['1','true','ya','yes'].includes(String(currentValue).toLowerCase()) ? '1' : '0';
        } else {
            valueText.classList.remove('d-none'); valueText.disabled = false;
            valueText.value = currentValue;
            if (tipe === 'integer')      { valueText.type = 'number'; valueText.step = '1'; }
            else if (tipe === 'decimal') { valueText.type = 'number'; valueText.step = '0.01'; }
            else if (tipe === 'date')    { valueText.type = 'date';   valueText.removeAttribute('step'); }
            else if (tipe === 'time')    { valueText.type = 'time';   valueText.removeAttribute('step'); }
            else                         { valueText.type = 'text';   valueText.removeAttribute('step'); }
        }
    });

    // Tipe change handler — modal Tambah
    document.getElementById('tambah_tipe')?.addEventListener('change', function () {
        const valueInput = document.getElementById('tambah_value');
        const tipe = this.value;
        if (tipe === 'integer')      { valueInput.type = 'number'; valueInput.step = '1'; }
        else if (tipe === 'decimal') { valueInput.type = 'number'; valueInput.step = '0.01'; }
        else if (tipe === 'date')    { valueInput.type = 'date';   valueInput.removeAttribute('step'); }
        else if (tipe === 'time')    { valueInput.type = 'time';   valueInput.removeAttribute('step'); }
        else                         { valueInput.type = 'text';   valueInput.removeAttribute('step'); }
    });

    // Reset modal Tambah saat ditutup
    document.getElementById('modalTambahPengaturan')?.addEventListener('hidden.bs.modal', function () {
        document.getElementById('formTambahPengaturan').reset();
        const v = document.getElementById('tambah_value');
        v.type = 'text';
        v.removeAttribute('step');
    });

    // Buka ulang modal Edit jika ada validation error
    @if($errors->any() && session('edit_pengaturan_id'))
        @php $cur = $pengaturan->firstWhere('id', session('edit_pengaturan_id')); @endphp
        @if($cur)
        openEditPengaturan(
            {{ $cur->id }},
            @js($cur->label ?: $cur->key),
            @js(old('key', $cur->key)),
            @js(old('label', $cur->label ?: $cur->key)),
            @js(old('tipe', $cur->tipe)),
            @js(old('value', $cur->value)),
            @js(old('grup', $cur->grup)),
            @js(old('keterangan', $cur->keterangan))
        );
        @endif
    @endif
</script>
@endpush