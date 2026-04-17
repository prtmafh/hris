@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="settings"></i></div>
                            Pengaturan
                        </h1>
                        <div class="page-header-subtitle">Ringkasan data pengaturan aplikasi dan operasional.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Pengaturan</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Key</th>
                                <th>Label</th>
                                <th>Grup</th>
                                <th>Tipe</th>
                                <th>Value</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengaturan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $item->key }}</code></td>
                                <td>{{ $item->label ?: '-' }}</td>
                                <td><span class="badge bg-light text-dark border text-capitalize">{{ $item->grup }}</span></td>
                                <td><span class="badge bg-primary-soft text-primary text-capitalize">{{ $item->tipe }}</span></td>
                                <td class="text-break">{{ $item->value ?: '-' }}</td>
                                <td>{{ $item->keterangan ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data pengaturan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
