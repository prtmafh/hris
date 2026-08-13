@extends('admin.layouts.app')

@section('title', 'Hasil Tes Rekrutmen')

@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="clipboard"></i></div>
                                Hasil Tes Rekrutmen
                            </h1>
                        </div>
                        <div class="col-auto mb-3">
                            <a href="{{ route('admin.pelamar') }}" class="btn btn-sm btn-light me-2">
                                <i data-feather="arrow-left"></i> Kembali
                            </a>
                            @if($pelamarDipilih && $hasilTes->isNotEmpty())
                                <a href="{{ route('admin.hasil-tes-rekrutmen.pdf', $pelamarDipilih) }}"
                                    class="btn btn-sm btn-light text-danger me-2">
                                    <i data-feather="file-text"></i> Unduh PDF
                                </a>
                            @endif
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalTambah">
                                <i data-feather="plus"></i> Tambah Hasil Tes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4">
            @if($pelamarDipilih)
                @php
                    $inisial = collect(explode(' ', $pelamarDipilih->nama))->filter()->take(2)
                        ->map(fn ($nama) => strtoupper(substr($nama, 0, 1)))->implode('');
                    $jumlahLulus = $hasilTes->where('hasil', 'lulus')->count();
                    $jumlahPertimbangan = $hasilTes->where('hasil', 'dipertimbangkan')->count();
                    $jumlahTidakLulus = $hasilTes->where('hasil', 'tidak_lulus')->count();
                @endphp
                <div class="card border-0 shadow-sm mb-4 overflow-hidden applicant-summary">
                    <div class="card-body p-4">
                        <div class="row align-items-center g-4">
                            <div class="col-lg">
                                <div class="d-flex align-items-center">
                                    <div class="applicant-avatar me-3">{{ $inisial }}</div>
                                    <div>
                                        <div class="small text-uppercase fw-bold text-primary mb-1">Ringkasan Pelamar</div>
                                        <h4 class="mb-1">{{ $pelamarDipilih->nama }}</h4>
                                        <div class="text-muted">
                                            <i data-feather="briefcase" class="icon-xs me-1"></i>
                                            {{ $pelamarDipilih->lowongan->judul ?? 'Lowongan tidak tersedia' }}
                                            <span class="mx-2">&bull;</span>{{ $pelamarDipilih->email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-auto">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="summary-metric">
                                        <span class="metric-value text-primary">{{ $hasilTes->count() }}</span>
                                        <span class="metric-label">Total Tes</span>
                                    </div>
                                    <div class="summary-metric">
                                        <span class="metric-value text-success">{{ $jumlahLulus }}</span>
                                        <span class="metric-label">Lulus</span>
                                    </div>
                                    <div class="summary-metric">
                                        <span class="metric-value text-warning">{{ $jumlahPertimbangan }}</span>
                                        <span class="metric-label">Pertimbangan</span>
                                    </div>
                                    <div class="summary-metric">
                                        <span class="metric-value text-danger">{{ $jumlahTidakLulus }}</span>
                                        <span class="metric-label">Tidak Lulus</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $rekomendasi = $pelamarDipilih->rekomendasiHasilTes();
                    $rekomendasiData = match($rekomendasi) {
                        'direkomendasikan' => [
                            'label' => 'Direkomendasikan',
                            'description' => 'Seluruh tahapan tes dinyatakan lulus. Pelamar dapat dilanjutkan sebagai kandidat yang diterima.',
                            'icon' => 'check-circle',
                            'class' => 'recommendation-success',
                        ],
                        'tidak_direkomendasikan' => [
                            'label' => 'Tidak Direkomendasikan',
                            'description' => 'Terdapat tahapan tes yang tidak lulus. Pelamar direkomendasikan untuk tidak melanjutkan proses.',
                            'icon' => 'x-circle',
                            'class' => 'recommendation-danger',
                        ],
                        'dipertimbangkan' => [
                            'label' => 'Perlu Dipertimbangkan',
                            'description' => 'Terdapat hasil yang masih dalam pertimbangan. HR perlu melakukan evaluasi sebelum menentukan keputusan akhir.',
                            'icon' => 'alert-circle',
                            'class' => 'recommendation-warning',
                        ],
                        default => [
                            'label' => 'Belum Ada Rekomendasi',
                            'description' => 'Tambahkan hasil tes terlebih dahulu untuk menghasilkan rekomendasi akhir pelamar.',
                            'icon' => 'info',
                            'class' => 'recommendation-neutral',
                        ],
                    };
                @endphp
                <div class="recommendation-card {{ $rekomendasiData['class'] }} mb-4">
                    <div class="recommendation-icon"><i data-feather="{{ $rekomendasiData['icon'] }}"></i></div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase fw-bold mb-1">Output Akhir Seleksi</div>
                        <h5 class="mb-1">{{ $rekomendasiData['label'] }}</h5>
                        <p class="mb-0">{{ $rekomendasiData['description'] }}</p>
                    </div>
                    @if(in_array($rekomendasi, ['direkomendasikan', 'tidak_direkomendasikan'], true))
                        <form method="POST" action="{{ route('admin.hasil-tes-rekrutmen.terapkan', $pelamarDipilih) }}">
                            @csrf
                            <button class="btn btn-sm btn-primary text-nowrap"
                                onclick="return confirm('Terapkan rekomendasi ini sebagai keputusan akhir pelamar?')">
                                Terapkan Keputusan
                            </button>
                        </form>
                    @endif
                </div>
            @endif
            {{-- <div class="card mb-4">
                <div class="card-header">Filter Data</div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.hasil-tes-rekrutmen.index') }}"
                        class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small">Pelamar</label>
                            <select name="pelamar_id" class="form-select">
                                <option value="">Semua pelamar</option>
                                @foreach ($pelamar as $item)
                                    <option value="{{ $item->id }}" @selected((string) request('pelamar_id') === (string) $item->id)>
                                        {{ $item->nama }} — {{ $item->lowongan->judul ?? 'Lowongan tidak tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Jenis Tes</label>
                            <select name="jenis_tes" class="form-select">
                                <option value="">Semua jenis</option>
                                @foreach ($jenisTes as $jenis)
                                    <option value="{{ $jenis }}" @selected(request('jenis_tes') === $jenis)>{{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Hasil</label>
                            <select name="hasil" class="form-select">
                                <option value="">Semua hasil</option>
                                <option value="lulus" @selected(request('hasil') === 'lulus')>Lulus</option>
                                <option value="dipertimbangkan" @selected(request('hasil') === 'dipertimbangkan')>Dipertimbangkan</option>
                                <option value="tidak_lulus" @selected(request('hasil') === 'tidak_lulus')>Tidak Lulus</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-primary flex-fill">Filter</button>
                            <a href="{{ route('admin.hasil-tes-rekrutmen.index') }}" class="btn btn-light">Reset</a>
                        </div>
                    </form>
                </div>
            </div> --}}

            <div class="card border-0 shadow-sm result-card">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">Riwayat Hasil Tes</h5>
                        <div class="small text-muted">Catatan penilaian pada setiap tahap seleksi</div>
                    </div>
                    <span class="badge bg-primary-soft text-primary px-3 py-2">{{ $hasilTes->count() }} hasil</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pelamar</th>
                                    <th>Tes</th>
                                    <th>Nilai</th>
                                    <th>Hasil</th>
                                    <th>Penguji & Catatan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilTes as $i => $item)
                                    <tr>
                                        <td><span class="row-number">{{ $i + 1 }}</span></td>
                                        <td>
                                            <div class="fw-semibold">{{ $item->pelamar->nama }}</div>
                                            <div class="small text-muted">{{ $item->pelamar->lowongan->judul ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="test-icon me-2"><i data-feather="clipboard"></i></div>
                                                <div>
                                                    <div class="fw-semibold">{{ $item->jenis_tes }}</div>
                                                    <div class="small text-muted">{{ $item->tanggal_tes->translatedFormat('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->nilai !== null)
                                                <span class="score-pill">{{ rtrim(rtrim(number_format((float) $item->nilai, 2, ',', '.'), '0'), ',') }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badge = match ($item->hasil) {
                                                    'lulus' => 'bg-green-soft text-green',
                                                    'tidak_lulus' => 'bg-red-soft text-red',
                                                    default => 'bg-yellow-soft text-yellow',
                                                };
                                                $label = match ($item->hasil) {
                                                    'lulus' => 'Lulus',
                                                    'tidak_lulus' => 'Tidak Lulus',
                                                    default => 'Dipertimbangkan',
                                                };
                                            @endphp
                                            <span class="badge rounded-pill {{ $badge }} px-3 py-2">{{ $label }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $item->penguji ?: 'Belum ditentukan' }}</div>
                                            <div class="small text-muted text-wrap" style="max-width: 280px">
                                                {{ $item->catatan ?: 'Tidak ada catatan' }}</div>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                                data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"
                                                title="Edit"><i data-feather="edit"></i></button>
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                                onclick="confirmDelete({{ $item->id }})" title="Hapus"><i
                                                    data-feather="trash-2"></i></button>
                                            <form id="delete-form-{{ $item->id }}" method="POST"
                                                action="{{ route('admin.hasil-tes-rekrutmen.destroy', $item) }}"
                                                class="d-none">
                                                @csrf @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <form method="POST"
                                                action="{{ route('admin.hasil-tes-rekrutmen.update', $item) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Hasil Tes</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @include(
                                                            'admin.hasil_tes_rekrutmen.partials.form',
                                                            ['hasil' => $item]
                                                        )
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">Belum ada hasil tes
                                            rekrutmen.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('admin.hasil-tes-rekrutmen.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Hasil Tes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('admin.hasil_tes_rekrutmen.partials.form', ['hasil' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .applicant-summary {
            background: linear-gradient(135deg, rgba(0, 97, 242, .08), rgba(105, 0, 199, .04));
            border-left: 4px solid #0061f2 !important;
        }

        .applicant-avatar {
            width: 64px;
            height: 64px;
            flex: 0 0 64px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0061f2, #6900c7);
            box-shadow: 0 .4rem 1rem rgba(0, 97, 242, .22);
        }

        .summary-metric {
            min-width: 92px;
            padding: .75rem 1rem;
            border: 1px solid rgba(33, 40, 50, .08);
            border-radius: .75rem;
            background: rgba(255, 255, 255, .72);
            text-align: center;
        }

        .metric-value,
        .metric-label {
            display: block;
        }

        .metric-value {
            font-size: 1.35rem;
            line-height: 1.2;
            font-weight: 700;
        }

        .metric-label {
            margin-top: .2rem;
            color: #69707a;
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .result-card .card-header {
            border-bottom: 1px solid rgba(33, 40, 50, .08);
        }

        .recommendation-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            border: 1px solid;
            border-radius: .75rem;
        }

        .recommendation-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: grid;
            place-items: center;
            border-radius: 50%;
        }

        .recommendation-icon svg {
            width: 24px;
            height: 24px;
        }

        .recommendation-success {
            color: #0f6848;
            border-color: rgba(0, 172, 105, .25);
            background: rgba(0, 172, 105, .08);
        }

        .recommendation-success .recommendation-icon {
            background: rgba(0, 172, 105, .15);
        }

        .recommendation-danger {
            color: #a12622;
            border-color: rgba(232, 21, 0, .22);
            background: rgba(232, 21, 0, .07);
        }

        .recommendation-danger .recommendation-icon {
            background: rgba(232, 21, 0, .14);
        }

        .recommendation-warning {
            color: #805b10;
            border-color: rgba(244, 161, 0, .3);
            background: rgba(244, 161, 0, .1);
        }

        .recommendation-warning .recommendation-icon,
        .recommendation-neutral .recommendation-icon {
            background: rgba(244, 161, 0, .16);
        }

        .recommendation-neutral {
            color: #495057;
            border-color: rgba(105, 112, 122, .2);
            background: rgba(105, 112, 122, .06);
        }

        .result-card tbody tr {
            transition: background-color .15s ease, transform .15s ease;
        }

        .row-number {
            display: inline-grid;
            width: 30px;
            height: 30px;
            place-items: center;
            border-radius: 50%;
            color: #69707a;
            background: #f2f6fc;
            font-weight: 600;
        }

        .test-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            color: #0061f2;
            background: rgba(0, 97, 242, .1);
        }

        .test-icon svg,
        .icon-xs {
            width: 17px;
            height: 17px;
        }

        .score-pill {
            display: inline-block;
            min-width: 48px;
            padding: .35rem .65rem;
            border-radius: .55rem;
            color: #0061f2;
            background: rgba(0, 97, 242, .1);
            font-weight: 700;
            text-align: center;
        }

        html[data-sb-theme="dark"] .summary-metric,
        html[data-sb-theme="dark"] .result-card .card-header {
            background: var(--sb-dark-surface-soft) !important;
            border-color: var(--sb-dark-border) !important;
        }

        html[data-sb-theme="dark"] .row-number {
            color: var(--sb-dark-text);
            background: var(--sb-dark-surface-soft);
        }

        html[data-sb-theme="dark"] .metric-label {
            color: var(--sb-dark-muted);
        }

        @media (max-width: 767.98px) {
            .recommendation-card {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .recommendation-card form,
            .recommendation-card form .btn {
                width: 100%;
            }

            .summary-metric {
                min-width: calc(50% - .5rem);
                flex-grow: 1;
            }
        }
    </style>
@endpush

@push('scripts')
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Data belum valid',
                html: @json(implode('<br>', $errors->all()))
            });
        </script>
    @endif
@endpush
