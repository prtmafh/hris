@php
$selectedJabatan = old('jabatan_id', optional($lowongan)->jabatan_id);
$selectedStatus = old('status', optional($lowongan)->status ?? 'draft');
$tanggalBuka = old('tanggal_buka', optional(optional($lowongan)->tanggal_buka)->format('Y-m-d'));
$tanggalTutup = old('tanggal_tutup', optional(optional($lowongan)->tanggal_tutup)->format('Y-m-d'));
@endphp

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label fw-bold">Judul Lowongan <span class="text-danger">*</span></label>
        <input type="text" name="judul" class="form-control" value="{{ old('judul', optional($lowongan)->judul) }}"
            placeholder="Contoh: Staff Administrasi" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold">Jabatan <span class="text-danger">*</span></label>
        <select name="jabatan_id" class="form-select" required>
            <option value="">Pilih Jabatan</option>
            @foreach($jabatan as $item)
            <option value="{{ $item->id }}" {{ (string) $selectedJabatan === (string) $item->id ? 'selected' : '' }}>
                {{ $item->nama_jabatan }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold">Kuota <span class="text-danger">*</span></label>
        <input type="number" name="kuota" min="1" class="form-control"
            value="{{ old('kuota', optional($lowongan)->kuota ?? 1) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold">Tanggal Buka <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_buka" class="form-control" value="{{ $tanggalBuka }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold">Tanggal Tutup <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_tutup" class="form-control" value="{{ $tanggalTutup }}" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select" required>
        <option value="draft" {{ $selectedStatus === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="aktif" {{ $selectedStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="ditutup" {{ $selectedStatus === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
    <textarea name="deskripsi" class="form-control" rows="4"
        placeholder="Tuliskan ringkasan posisi dan kebutuhan pekerjaan" required>{{ old('deskripsi', optional($lowongan)->deskripsi) }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Kualifikasi <span class="text-danger">*</span></label>
    <textarea name="kualifikasi" class="form-control" rows="4"
        placeholder="Tuliskan pendidikan, pengalaman, skill, atau syarat lain" required>{{ old('kualifikasi', optional($lowongan)->kualifikasi) }}</textarea>
</div>

<div class="mb-0">
    <label class="form-label fw-bold">Tanggung Jawab</label>
    <textarea name="tanggung_jawab" class="form-control" rows="4"
        placeholder="Tuliskan tanggung jawab utama posisi ini">{{ old('tanggung_jawab', optional($lowongan)->tanggung_jawab) }}</textarea>
</div>
