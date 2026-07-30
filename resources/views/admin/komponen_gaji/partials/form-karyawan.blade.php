<input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">
<div class="mb-3">
    <label class="small mb-1">Komponen</label>
    <select name="komponen_gaji_id" class="form-select" required>
        @foreach($pilihanKomponen as $pilihan)
        <option value="{{ $pilihan->id }}" @selected(old('komponen_gaji_id', $pengaturanItem?->komponen_gaji_id) === $pilihan->id)>
            {{ $pilihan->nama }} ({{ ucfirst($pilihan->tipe) }})
        </option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-5 mb-3">
        <label class="small mb-1">Metode</label>
        <select name="metode" class="form-select" required>
            <option value="nominal" @selected(old('metode', $pengaturanItem?->metode ?? 'nominal') === 'nominal')>Nominal Tetap</option>
            <option value="persentase" @selected(old('metode', $pengaturanItem?->metode) === 'persentase')>Persentase Gaji Dasar</option>
        </select>
    </div>
    <div class="col-md-7 mb-3">
        <label class="small mb-1">Nilai</label>
        <input type="number" name="nilai" class="form-control" min="0" step="0.01" required
            value="{{ old('nilai', $pengaturanItem?->nilai) }}" placeholder="Nominal rupiah atau persen">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="small mb-1">Mulai Berlaku</label>
        <input type="date" name="tanggal_mulai" class="form-control"
            value="{{ old('tanggal_mulai', $pengaturanItem?->tanggal_mulai?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="small mb-1">Selesai Berlaku</label>
        <input type="date" name="tanggal_selesai" class="form-control"
            value="{{ old('tanggal_selesai', $pengaturanItem?->tanggal_selesai?->format('Y-m-d')) }}">
    </div>
</div>
<div class="mb-3">
    <label class="small mb-1">Status</label>
    <select name="status" class="form-select" required>
        <option value="aktif" @selected(old('status', $pengaturanItem?->status ?? 'aktif') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(old('status', $pengaturanItem?->status) === 'nonaktif')>Nonaktif</option>
    </select>
</div>
<div>
    <label class="small mb-1">Keterangan</label>
    <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $pengaturanItem?->keterangan) }}</textarea>
</div>
