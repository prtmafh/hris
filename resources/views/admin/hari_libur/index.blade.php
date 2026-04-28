@extends('admin.layouts.app')

@section('title', 'Hari Libur')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.is-libur {
        background: #0061f2 !important;
        color: #fff !important;
        border-radius: 50% !important;
        font-weight: 600;
    }

    .flatpickr-day.is-libur:hover {
        background: #0044cc !important;
    }

    #kalenderLibur .flatpickr-calendar {
        width: 100% !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0;
    }

    .legend-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 ">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="sun"></i></div>
                            Hari Libur
                        </h1>
                    </div>
                    <div class="col-auto mb-3">
                        <button class="btn btn-light text-primary btn-sm" onclick="bukaModalTambah()">
                            <i data-feather="plus" style="width:14px;height:14px;"></i> Tambah Hari Libur
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        <div class="row g-4">

            {{-- KIRI: Kalender --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        Kalender Hari Libur
                        <div class="text-muted small mt-1 fw-normal">Klik tanggal untuk tambah / edit</div>
                    </div>
                    <div class="card-body pb-2">
                        <div id="kalenderLibur"></div>

                        <hr class="my-3">

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="legend-dot" style="background:#0061f2;"></span>
                            <span class="text-muted small">Tanggal libur</span>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <div class="border rounded p-2 text-center">
                                    <div class="small text-muted">Total Libur</div>
                                    <div class="fs-4 fw-bold text-primary" id="statTotal">0</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2 text-center">
                                    <div class="small text-muted">Bulan Ini</div>
                                    <div class="fs-4 fw-bold text-primary" id="statBulanIni">0</div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info small mt-3 mb-0 py-2 px-3 border-0">
                            <i data-feather="info" style="width:12px;height:12px;"></i>
                            Klik tanggal <strong>biru</strong> untuk edit. Klik tanggal kosong untuk tambah.
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: Tabel --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">Daftar Hari Libur</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tabelHariLibur">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Berulang</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelBody">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                            Memuat data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Tambah / Edit --}}
    <div class="modal fade" id="modalLibur" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalJudul">Tambah Hari Libur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="liburId">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="inputTanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" id="inputNama" class="form-control"
                            placeholder="Contoh: Hari Raya Idul Fitri" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select id="inputJenis" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="nasional">Nasional</option>
                            <option value="cuti_bersama">Cuti Bersama</option>
                            <option value="perusahaan">Perusahaan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea id="inputKeterangan" class="form-control" rows="2" maxlength="500"
                            placeholder="Opsional"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="inputBerulang">
                        <label class="form-check-label" for="inputBerulang">Berulang setiap tahun (tanggal
                            tetap)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btnSimpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    const csrf      = '{{ csrf_token() }}';
const urlData   = '{{ route("admin.hari_libur.data") }}';
const urlStore  = '{{ route("admin.hari_libur.store") }}';
const urlBase   = '{{ url("admin/hari-libur") }}';

const bsModal   = new bootstrap.Modal(document.getElementById('modalLibur'));
const namaHari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

let liburList = [];
let liburMap  = new Map();
let kalender  = null;

function getHari(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return namaHari[d.getDay()];
}

function formatTanggal(dateStr) {
    const [y, m, d] = dateStr.split('-');
    return `${d}/${m}/${y}`;
}

function jenisBadge(jenis) {
    const map = {
        nasional      : ['bg-primary-soft text-primary', 'Nasional'],
        cuti_bersama  : ['bg-success-soft text-success', 'Cuti Bersama'],
        perusahaan    : ['bg-warning-soft text-warning', 'Perusahaan'],
    };
    const [cls, label] = map[jenis] || ['bg-secondary-soft text-secondary', jenis];
    return `<span class="badge ${cls}">${label}</span>`;
}

function updateStats() {
    document.getElementById('statTotal').textContent = liburList.length;

    const bulan = kalender ? kalender.currentMonth + 1 : new Date().getMonth() + 1;
    const tahun = kalender ? kalender.currentYear     : new Date().getFullYear();

    const count = liburList.filter(x => {
        const [yy, mm] = x.tanggal.split('-').map(Number);
        return yy === tahun && mm === bulan;
    }).length;

    document.getElementById('statBulanIni').textContent = count;
}

function renderTabel() {
    const tbody = document.getElementById('tabelBody');

    if (!liburList.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada hari libur.</td></tr>';
        return;
    }

    const sorted = liburList.slice().sort((a, b) => b.tanggal.localeCompare(a.tanggal));

    tbody.innerHTML = sorted.map((item, i) => `
        <tr>
            <td>${i + 1}</td>
            <td class="fw-semibold">${formatTanggal(item.tanggal)}</td>
            <td>${getHari(item.tanggal)}</td>
            <td>${item.nama}</td>
            <td>${jenisBadge(item.jenis)}</td>
            <td>${item.berulang_tahunan
                ? '<span class="badge bg-success-soft text-success">Ya</span>'
                : '<span class="badge bg-secondary-soft text-secondary">Tidak</span>'}</td>
            <td class="text-end">
                <button class="btn btn-datatable btn-icon btn-transparent-dark me-1" onclick="bukaEdit('${item.tanggal}')" title="Edit">
                    <i data-feather="edit"></i>
                </button>
                <button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="hapus(${item.id})" title="Hapus">
                    <i data-feather="trash"></i>
                </button>
            </td>
        </tr>
    `).join('');

    feather.replace();
}

function refreshCalendar() {
    kalender?.redraw();
}

async function fetchData() {
    const res  = await fetch(urlData, { headers: { 'Accept': 'application/json' } });
    const json = await res.json();
    liburList  = json.data || [];
    liburMap   = new Map(liburList.map(x => [x.tanggal, x]));
    renderTabel();
    updateStats();
    refreshCalendar();
}

window.bukaModalTambah = function(dateStr = '') {
    document.getElementById('modalJudul').textContent = 'Tambah Hari Libur';
    document.getElementById('liburId').value          = '';
    document.getElementById('inputTanggal').value     = dateStr;
    document.getElementById('inputNama').value        = '';
    document.getElementById('inputJenis').value       = '';
    document.getElementById('inputKeterangan').value  = '';
    document.getElementById('inputBerulang').checked  = false;
    bsModal.show();
};

window.bukaEdit = function(dateStr) {
    const item = liburMap.get(dateStr);
    if (!item) return bukaModalTambah(dateStr);

    document.getElementById('modalJudul').textContent = 'Edit Hari Libur';
    document.getElementById('liburId').value          = item.id;
    document.getElementById('inputTanggal').value     = item.tanggal;
    document.getElementById('inputNama').value        = item.nama ?? '';
    document.getElementById('inputJenis').value       = item.jenis ?? '';
    document.getElementById('inputKeterangan').value  = item.keterangan ?? '';
    document.getElementById('inputBerulang').checked  = !!item.berulang_tahunan;
    bsModal.show();
};

document.getElementById('btnSimpan').addEventListener('click', async () => {
    const id     = document.getElementById('liburId').value;
    const isEdit = !!id;

    const payload = {
        tanggal          : document.getElementById('inputTanggal').value,
        nama             : document.getElementById('inputNama').value,
        jenis            : document.getElementById('inputJenis').value,
        keterangan       : document.getElementById('inputKeterangan').value,
        berulang_tahunan : document.getElementById('inputBerulang').checked ? 1 : 0,
    };

    const res = await fetch(isEdit ? `${urlBase}/${id}` : urlStore, {
        method  : isEdit ? 'PUT' : 'POST',
        headers : { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body    : JSON.stringify(payload),
    });

    if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        if (res.status === 422 && err.errors) {
            const key = Object.keys(err.errors)[0];
            return Swal.fire({ icon: 'error', title: 'Oops...', text: err.errors[key][0] });
        }
        return Swal.fire({ icon: 'error', title: 'Oops...', text: err.message ?? 'Gagal menyimpan.' });
    }

    bsModal.hide();
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: isEdit ? 'Hari libur diperbarui.' : 'Hari libur ditambahkan.', timer: 2000, showConfirmButton: false });
    await fetchData();
});

window.hapus = function(id) {
    Swal.fire({
        title: 'Hapus hari libur ini?',
        text: 'Data akan dihapus permanen.',
        icon: 'warning',
        showCancelButton : true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText : 'Batal',
        reverseButtons   : true,
        customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light' },
        buttonsStyling   : false,
    }).then(async result => {
        if (!result.isConfirmed) return;
        const res = await fetch(`${urlBase}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        if (!res.ok) return Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menghapus hari libur.' });
        Swal.fire({ icon: 'success', title: 'Dihapus!', timer: 1800, showConfirmButton: false });
        await fetchData();
    });
};

// Inisialisasi kalender Flatpickr inline
kalender = flatpickr('#kalenderLibur', {
    inline    : true,
    static    : true,
    dateFormat: 'Y-m-d',
    locale    : { ...flatpickr.l10ns.id, firstDayOfWeek: 1 },

    onDayCreate(dObj, dStr, fp, dayElem) {
        const date = fp.formatDate(dayElem.dateObj, 'Y-m-d');
        if (liburMap.has(date)) {
            dayElem.classList.add('is-libur');
            dayElem.title = liburMap.get(date).nama || 'Hari Libur';
        }
    },

    onChange(selectedDates, dateStr) {
        if (!dateStr) return;
        liburMap.has(dateStr) ? bukaEdit(dateStr) : bukaModalTambah(dateStr);
    },

    onMonthChange() { updateStats(); refreshCalendar(); },
    onYearChange()  { updateStats(); refreshCalendar(); },
});

fetchData();
</script>
@endpush