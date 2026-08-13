@php($prefix = $hasil ? 'edit-'.$hasil->id : 'tambah')
<div class="row g-3">
    <div class="col-md-12">
        <label class="form-label">Pelamar <span class="text-danger">*</span></label>
        <select name="pelamar_id" class="form-select" required>
            <option value="">Pilih pelamar</option>
            @foreach($pelamar as $pelamarItem)
                <option value="{{ $pelamarItem->id }}" @selected((string) old('pelamar_id', $hasil?->pelamar_id ?? request('pelamar_id')) === (string) $pelamarItem->id)>
                    {{ $pelamarItem->nama }} — {{ $pelamarItem->lowongan->judul ?? 'Lowongan tidak tersedia' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis Tes <span class="text-danger">*</span></label>
        <input name="jenis_tes" class="form-control" list="jenis-tes-{{ $prefix }}"
            value="{{ old('jenis_tes', $hasil?->jenis_tes) }}" placeholder="Contoh: Wawancara" required maxlength="100">
        <datalist id="jenis-tes-{{ $prefix }}">
            <option value="Wawancara"><option value="Psikotes"><option value="Tes Kesehatan">
            <option value="Tes Kompetensi"><option value="Tes Administrasi"><option value="Tes Lainnya">
        </datalist>
        <div class="form-text">Bisa mengetik jenis tes lain sesuai kebutuhan.</div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tanggal Tes <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_tes" class="form-control"
            value="{{ old('tanggal_tes', $hasil?->tanggal_tes?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Nilai</label>
        <input type="number" name="nilai" class="form-control" min="0" max="999999.99" step="0.01"
            value="{{ old('nilai', $hasil?->nilai) }}" placeholder="Opsional">
    </div>
    <div class="col-md-6">
        <label class="form-label">Hasil <span class="text-danger">*</span></label>
        <select name="hasil" class="form-select" required>
            <option value="lulus" @selected(old('hasil', $hasil?->hasil) === 'lulus')>Lulus</option>
            <option value="dipertimbangkan" @selected(old('hasil', $hasil?->hasil) === 'dipertimbangkan')>Dipertimbangkan</option>
            <option value="tidak_lulus" @selected(old('hasil', $hasil?->hasil) === 'tidak_lulus')>Tidak Lulus</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Penguji</label>
        <input name="penguji" class="form-control" value="{{ old('penguji', $hasil?->penguji) }}" maxlength="150"
            placeholder="Nama pewawancara/dokter/penguji">
    </div>
    <div class="col-12">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" class="form-control" rows="3" maxlength="3000"
            placeholder="Ringkasan hasil, kelebihan, kekurangan, atau rekomendasi">{{ old('catatan', $hasil?->catatan) }}</textarea>
    </div>
</div>
