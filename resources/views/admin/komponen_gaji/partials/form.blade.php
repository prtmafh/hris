<div class="mb-3">
    <label class="small mb-1">Nama Komponen</label>
    <input type="text" name="nama" class="form-control" required
        value="{{ old('nama', $komponenItem?->nama) }}"
        placeholder="Contoh: Tunjangan Transportasi">
</div>
<div class="mb-3">
    <label class="small mb-1">Tipe</label>
    <select name="tipe" class="form-select" required>
        <option value="pemasukan" @selected(old('tipe', $komponenItem?->tipe) === 'pemasukan')>Pemasukan / Tunjangan</option>
        <option value="potongan" @selected(old('tipe', $komponenItem?->tipe) === 'potongan')>Potongan</option>
    </select>
</div>
<div class="mb-3">
    <label class="small mb-1">Status</label>
    <select name="status" class="form-select" required>
        <option value="aktif" @selected(old('status', $komponenItem?->status ?? 'aktif') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(old('status', $komponenItem?->status) === 'nonaktif')>Nonaktif</option>
    </select>
</div>
<div>
    <label class="small mb-1">Keterangan</label>
    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $komponenItem?->keterangan) }}</textarea>
</div>
