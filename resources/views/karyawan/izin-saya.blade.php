@extends('karyawan.layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="file-text"></i></div>
                                Pengajuan Izin & Cuti
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4">
            <div class="row g-4">
                <div class="col-12 col-xl-5">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="fw-bold">Form Pengajuan</div>
                            <div class="small text-muted">Pilih jenis pengajuan yang sesuai.</div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('karyawan.izin.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label small mb-1">Jenis Pengajuan</label>
                                    <div class="d-flex gap-3 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_pengajuan"
                                                id="jenis_cuti" value="cuti"
                                                {{ old('jenis_pengajuan', 'cuti') === 'cuti' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jenis_cuti">Cuti</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_pengajuan"
                                                id="jenis_izin_khusus" value="izin_khusus"
                                                {{ old('jenis_pengajuan') === 'izin_khusus' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jenis_izin_khusus">Izin Khusus</label>
                                        </div>
                                    </div>
                                    @error('jenis_pengajuan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="cuti-fields" class="form-section">
                                    <div class="mb-3">
                                        <label class="form-label small mb-1">Jenis Cuti</label>
                                        <input type="text" name="jenis_izin"
                                            class="form-control @error('jenis_izin') is-invalid @enderror"
                                            value="{{ old('jenis_izin', 'Cuti Tahunan') }}"
                                            placeholder="Contoh: Cuti Tahunan">
                                        @error('jenis_izin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="izin-khusus-fields" class="form-section d-none">
                                    <div class="mb-3">
                                        <label class="form-label small mb-1">Jenis Izin Khusus</label>
                                        <select name="jenis_izin_khusus_id"
                                            class="form-select @error('jenis_izin_khusus_id') is-invalid @enderror">
                                            <option value="">Pilih jenis izin khusus</option>
                                            @foreach ($jenisIzinKhusus as $jenis)
                                                <option value="{{ $jenis->id }}"
                                                    {{ old('jenis_izin_khusus_id') == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('jenis_izin_khusus_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small mb-1">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai"
                                        class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        value="{{ old('tanggal_mulai') }}" min="{{ now()->format('Y-m-d') }}">
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small mb-1">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai"
                                        class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                        value="{{ old('tanggal_selesai') }}" min="{{ now()->format('Y-m-d') }}">
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small mb-1">Jumlah Hari</label>
                                    <input type="number" id="jumlah_hari" class="form-control" value="0" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small mb-1">Keterangan</label>
                                    <textarea name="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror"
                                        placeholder="Tulis alasan pengajuan Anda">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="bukti-field" class="mb-3 d-none">
                                    <label class="form-label small mb-1">Upload Bukti</label>
                                    <input type="file" name="bukti"
                                        class="form-control @error('bukti') is-invalid @enderror"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    @error('bukti')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i data-feather="send"></i> Kirim Pengajuan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-7">
                    <div class="card border-left-primary shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1 fw-bold">Kuota Cuti</h5>
                                    <small class="text-muted">Sisa kuota hanya akan berkurang jika cuti disetujui.</small>
                                </div>
                                <span class="badge bg-primary fs-6 ms-2">{{ $sisaIzin }} Sisa</span>
                            </div>

                            <div class="row text-center mb-3 g-2">
                                <div class="col-4">
                                    <h3 class="fw-bold text-primary mb-0">{{ $kuotaIzin }}</h3>
                                    <small class="text-muted">Total Kuota</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="fw-bold text-warning mb-0">{{ $cutiDisetujui }}</h3>
                                    <small class="text-muted">Terpakai</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="fw-bold text-success mb-0">{{ $sisaIzin }}</h3>
                                    <small class="text-muted">Sisa</small>
                                </div>
                            </div>

                            @php
                                $persen = $kuotaIzin > 0 ? ($cutiDisetujui / $kuotaIzin) * 100 : 0;
                            @endphp

                            <div class="progress" style="height: 18px">
                                <div class="progress-bar {{ $persen > 80 ? 'bg-danger' : ($persen > 50 ? 'bg-warning' : 'bg-success') }}"
                                    style="width: {{ min($persen, 100) }}%">
                                    <span class="d-none d-sm-inline">{{ round($persen) }}%</span>
                                </div>
                            </div>

                            @if ($sisaIzin <= 0)
                                <div class="alert alert-danger mt-3 mb-0">Kuota cuti Anda habis. Tidak dapat mengajukan
                                    cuti baru.</div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="fw-bold">Riwayat Pengajuan</div>
                            </div>
                            <span class="badge bg-primary">{{ $izin->count() }} data</span>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <div class="table-responsive">
                                <table id="datatablesSimple" data-simple-datatable
                                    class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Periode</th>
                                            <th>Hari</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($izin as $iz)
                                            @php
                                                $badge = match ($iz->status_approval) {
                                                    'pending' => 'warning',
                                                    'disetujui' => 'success',
                                                    'ditolak' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <tr>
                                                <td><span
                                                        class="badge bg-light text-dark border text-capitalize">{{ $iz->jenis_pengajuan === 'cuti' ? 'Cuti' : 'Izin Khusus' }}</span>
                                                </td>
                                                <td>{{ $iz->tanggal_mulai ? \Carbon\Carbon::parse($iz->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                                                    @if ($iz->tanggal_selesai && $iz->tanggal_mulai != $iz->tanggal_selesai)
                                                        -
                                                        {{ \Carbon\Carbon::parse($iz->tanggal_selesai)->translatedFormat('d M Y') }}
                                                    @endif
                                                </td>
                                                <td>{{ $iz->jumlah_hari ?? 0 }} hari</td>
                                                <td><span
                                                        class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">{{ $iz->status_approval }}</span>
                                                </td>
                                                <td class="text-muted">{{ $iz->keterangan ?: '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">Belum ada
                                                    pengajuan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleFormType() {
            const selected = document.querySelector('input[name="jenis_pengajuan"]:checked')?.value ?? 'cuti';
            const cutiFields = document.getElementById('cuti-fields');
            const khususFields = document.getElementById('izin-khusus-fields');
            const buktiField = document.getElementById('bukti-field');

            cutiFields.classList.toggle('d-none', selected !== 'cuti');
            khususFields.classList.toggle('d-none', selected !== 'izin_khusus');
            buktiField.classList.toggle('d-none', selected !== 'izin_khusus');
        }

        function updateJumlahHari() {
            const mulai = document.querySelector('input[name="tanggal_mulai"]').value;
            const selesai = document.querySelector('input[name="tanggal_selesai"]').value;
            const output = document.getElementById('jumlah_hari');

            if (!mulai || !selesai) {
                output.value = 0;
                return;
            }

            const start = new Date(mulai);
            const end = new Date(selesai);
            if (end < start) {
                output.value = 0;
                return;
            }

            let diff = 0;
            for (let current = new Date(start); current <= end; current.setDate(current.getDate() + 1)) {
                const day = current.getDay();
                if (day !== 0 && day !== 6) {
                    diff++;
                }
            }

            output.value = diff;
        }

        document.querySelectorAll('input[name="jenis_pengajuan"]').forEach((radio) => {
            radio.addEventListener('change', toggleFormType);
        });
        document.querySelector('input[name="tanggal_mulai"]').addEventListener('change', updateJumlahHari);
        document.querySelector('input[name="tanggal_selesai"]').addEventListener('change', updateJumlahHari);
        toggleFormType();
        updateJumlahHari();
    </script>
@endsection
