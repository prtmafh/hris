<div class="modal-header"><h5 class="modal-title">{{ $item ? 'Edit' : 'Tambah' }} Perusahaan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
    <div class="col-md-8"><label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label><input name="nama_perusahaan" class="form-control" required value="{{ old('nama_perusahaan', $item?->nama_perusahaan) }}"></div>
    <div class="col-md-4"><label class="form-label">Nama Singkat</label><input name="nama_singkat" class="form-control" value="{{ old('nama_singkat', $item?->nama_singkat) }}"></div>
    <div class="col-md-6"><label class="form-label">NIB <span class="text-danger">*</span></label><input name="nib" class="form-control" required value="{{ old('nib', $item?->nib) }}"></div>
    <div class="col-md-6"><label class="form-label">NPWP</label><input name="npwp" class="form-control" value="{{ old('npwp', $item?->npwp) }}"></div>
    <div class="col-md-6"><label class="form-label">Logo</label><input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/webp" data-logo-input="preview{{ $modalId }}"><img id="preview{{ $modalId }}" src="{{ $item?->logo ? asset('storage/'.$item->logo) : '' }}" class="{{ $item?->logo ? '' : 'd-none' }} rounded border mt-2 p-1" style="width:70px;height:70px;object-fit:contain;"></div>
    <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="aktif" @selected(old('status', $item?->status ?? 'aktif') === 'aktif')>Aktif</option><option value="nonaktif" @selected(old('status', $item?->status) === 'nonaktif')>Nonaktif</option></select></div>
    <div class="col-md-6"><label class="form-label">Nomor Telepon</label><input name="no_telepon" class="form-control" value="{{ old('no_telepon', $item?->no_telepon) }}"></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $item?->email) }}"></div>
    <div class="col-md-12"><label class="form-label">Website</label><input type="url" name="website" class="form-control" value="{{ old('website', $item?->website) }}"></div>
    <div class="col-md-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $item?->alamat) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $item?->deskripsi) }}</textarea></div>
</div></div>
<div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
