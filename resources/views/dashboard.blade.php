<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosChoice SAW — Sistem Keputusan Kos</title>
    <meta name="description" content="Sistem Pendukung Keputusan Pemilihan Rumah Kos menggunakan metode Simple Additive Weighting (SAW).">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php use App\Services\SawService; @endphp
<div class="app-layout">

{{-- SIDEBAR --}}
<aside class="sidebar">
<div class="sidebar-inner">
    <div class="sidebar-logo">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
    </div>
    <nav class="sidebar-nav">
        <a href="#" class="sidebar-nav-item active" title="Dashboard"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></a>
        <a href="#tabel-ranking" class="sidebar-nav-item" title="Ranking"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19V13H5v6m10 0V5h-4v14m10 0v-8h-4v8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <a href="#tabel-konfigurasi" class="sidebar-nav-item" title="Kriteria"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <a href="#tabel-matriks-x" class="sidebar-nav-item" title="Matriks X"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke-linecap="round"/></svg></a>
        <a href="#tabel-matriks-r" class="sidebar-nav-item" title="Matriks R"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <a href="#form-saw" class="sidebar-nav-item" title="Proses"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </nav>
    <div class="sidebar-divider"></div>
    <div class="sidebar-bottom">
        <button class="sidebar-nav-item" title="Settings"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3"/></svg></button>
        <button class="sidebar-nav-item" title="Logout"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
    </div>
</div>
</aside>

{{-- MAIN --}}
<div class="main-wrapper">

{{-- TOP HEADER --}}
<header class="top-header">
    <span class="header-brand">KosChoice SAW</span>
    <div class="header-tabs">
        <a href="#" class="header-tab active">Overview</a>
        <a href="#tabel-matriks-x" class="header-tab">Methodology</a>
        <a href="#" class="header-tab">Documentation</a>
    </div>
    <div class="header-search">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>
        <input type="text" placeholder="Search data..." id="search-input">
    </div>
    <div class="header-actions">
        <button class="icon-btn" title="Notifications"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
        <button class="icon-btn" title="Help"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3m.08 4h.01" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
        <a href="{{ route('dashboard.export') }}" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export Report
        </a>
        <div class="avatar">U</div>
    </div>
</header>

{{-- CONTENT --}}
<div class="content-area">

@if ($error)
<div class="alert-error">
    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01" stroke-linecap="round"/></svg>
    {{ $error }}
</div>
@endif

@if ($sudahProses && $hasil)

{{-- HERO --}}
<div class="hero-section">
    <div class="hero-text">
        <h1>Sistem Keputusan Kos</h1>
        <p>Simple Additive Weighting (SAW) analysis active. Evaluasi terkini berdasarkan matriks preferensi terbobot.</p>
    </div>
    <div class="hero-metrics">
        <div class="metric-card">
            <div class="metric-label">Total Alternatif</div>
            <div class="metric-value">{{ count($hasil['data_kos_awal']) }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Kriteria Aktif</div>
            <div class="metric-value">{{ count($hasil['labels_aktif']) }}</div>
        </div>
    </div>
</div>

{{-- RECOMMENDATION BANNER --}}
@if (!empty($hasil['ranking']))
@php $terbaik = $hasil['ranking'][0]; @endphp
<div class="rec-banner">
    <div class="rec-left">
        <div class="rec-badge">☆ Rekomendasi Terbaik</div>
        <div class="rec-title">{{ $terbaik['nama'] }}</div>
        <div class="rec-address">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
            {{ $terbaik['alamat'] }}
        </div>
        <div class="rec-actions">
            <div class="rec-rating">
                <div class="rec-rating-circle">R1</div>
                <div class="rec-rating-meta">
                    <span class="rec-rating-label">Rating Penghuni</span>
                    <span class="rec-rating-val">{{ number_format($terbaik['skor_v'] * 7.3, 1) }} / 5.0</span>
                </div>
            </div>
            <a href="#tabel-ranking" class="btn-white">Lihat Detail &rarr;</a>
        </div>
    </div>
    <div class="rec-score-card">
        <div class="rec-score-label">Skor Preferensi (V)</div>
        <div class="rec-score-value">{{ number_format($terbaik['skor_v'], 4) }}</div>
        <div class="rec-score-bar"></div>
    </div>
</div>
@endif

{{-- TWO COLUMN GRID --}}
<div class="two-col-grid" style="display:grid;grid-template-columns:1fr 1.4fr;gap:24px;">

{{-- KONFIGURASI KRITERIA --}}
<div class="card" id="tabel-konfigurasi">
    <div class="card-header">
        <span class="card-title">Konfigurasi Kriteria</span>
        <div class="card-actions">
            <button class="card-action-btn" title="Edit" onclick="document.getElementById('form-saw-section').scrollIntoView({behavior:'smooth'})">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>
    <table class="data-table">
        <thead><tr><th>ID</th><th>Nama Kriteria</th><th></th><th style="text-align:right">Bobot</th></tr></thead>
        <tbody>
        @php
            $icons = ['💰','📍','🏠','✨','🛡️'];
            $ids = ['C1','C2','C3','C4','C5'];
        @endphp
        @foreach ($hasil['labels_aktif'] as $i => $label)
            @php
                $origIdx = array_search($label, $labelKriteria);
                $jenis = $hasil['jenis_aktif'][$i];
            @endphp
            <tr>
                <td><span style="font-weight:600;color:var(--c-on-variant);font-size:12px">{{ $ids[$origIdx] ?? 'C'.($i+1) }}</span></td>
                <td style="display:flex;align-items:center;gap:8px">
                    <span>{{ $icons[$origIdx] ?? '📊' }}</span>
                    <span>{{ str_replace(['C1 (','C2 (','C3 (','C4 (','C5 (',')'], '', $label) }}</span>
                </td>
                <td>
                    <span class="badge {{ $jenis === 'cost' ? 'badge-cost' : 'badge-benefit' }}">{{ ucfirst($jenis) }}</span>
                </td>
                <td style="text-align:right;font-weight:600">{{ number_format($hasil['bobot_aktif'][$i], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- HASIL PERANGKINGAN --}}
<div class="card" id="tabel-ranking">
    <div class="card-header">
        <span class="card-title">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Hasil Perangkingan
        </span>
        <div class="card-actions">
            <button class="card-action-btn" title="Filter"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            <button class="card-action-btn" title="Grid"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></button>
        </div>
    </div>
    <table class="data-table">
        <thead><tr><th>Rank</th><th>Nama Kos</th><th>Skor (V)</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach (array_slice($hasil['ranking'], 0, 5) as $i => $item)
            <tr>
                <td class="col-center">
                    <span class="rank-badge {{ $i === 0 ? 'rank-badge-1' : '' }}">{{ $i + 1 }}</span>
                </td>
                <td style="font-weight:500">{{ $item['nama'] }}</td>
                <td>{{ number_format($item['skor_v'], 4) }}</td>
                <td><a href="#" style="color:var(--c-on-variant);font-size:12px">Detail</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if (count($hasil['ranking']) > 5)
    <div class="view-all-row"><a href="#full-ranking">Lihat Semua {{ count($hasil['ranking']) }} Hasil</a></div>
    @endif
</div>

</div>{{-- end two-col-grid --}}

{{-- FORM PILIH KRITERIA --}}
<div id="form-saw-section">
<form method="POST" action="{{ route('dashboard.proses') }}" id="form-saw">
    @csrf
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Pilih Kriteria &amp; Proses SAW
            </span>
        </div>
        <div class="criteria-list">
            @foreach ($labelKriteria as $index => $label)
            <label class="criteria-item" for="kriteria-{{ $index }}">
                <input type="checkbox" id="kriteria-{{ $index }}" name="kriteria[]" value="{{ $index }}" {{ in_array($index, $selectedIndices) ? 'checked' : '' }}>
                <span class="criteria-id">{{ $ids[$index] }}</span>
                <span>{{ $icons[$index] }}</span>
                <span class="criteria-name">{{ str_replace(['C1 (','C2 (','C3 (','C4 (','C5 (',')'], '', $label) }}</span>
                <span class="badge {{ SawService::JENIS_KRITERIA[$index] === 'cost' ? 'badge-cost' : 'badge-benefit' }}">{{ ucfirst(SawService::JENIS_KRITERIA[$index]) }}</span>
                <span class="criteria-bobot">{{ number_format(SawService::BOBOT[$index], 2) }}</span>
            </label>
            @endforeach
        </div>
        <div class="criteria-form-footer">
            <button type="submit" class="btn-primary" id="btn-proses">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Proses SAW
            </button>
            <span style="font-size:12px;color:var(--c-on-variant)">Centang kriteria lalu klik Proses SAW</span>
        </div>
    </div>
</form>
</div>

{{-- FULL RANKING TABLE --}}
<div class="card section-card" id="full-ranking">
    <div class="card-header"><span class="card-title">Ranking Lengkap</span></div>
    <table class="data-table">
        <thead><tr><th>Rank</th><th>Nama Kos</th><th>Skor (V)</th><th>Alamat</th></tr></thead>
        <tbody>
        @foreach ($hasil['ranking'] as $i => $item)
        <tr>
            <td class="col-center"><span class="rank-badge {{ $i === 0 ? 'rank-badge-1' : '' }}">{{ $i + 1 }}</span></td>
            <td style="font-weight:500">{{ $item['nama'] }}</td>
            <td>
                <div class="score-bar-wrap">
                    @php $maxS = $hasil['ranking'][0]['skor_v']; $pct = $maxS > 0 ? ($item['skor_v']/$maxS)*100 : 0; @endphp
                    <div class="score-bar-track"><div class="score-bar-fill" style="width:{{ $pct }}%"></div></div>
                    <span style="font-family:monospace;font-size:13px">{{ number_format($item['skor_v'], 4) }}</span>
                </div>
            </td>
            <td style="color:var(--c-on-variant);font-size:13px">{{ $item['alamat'] }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- MATRIKS X --}}
<div class="card section-card" id="tabel-matriks-x">
    <div class="card-header"><span class="card-title">Matriks Keputusan Awal (X)</span></div>
    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr><th>Alternatif</th>@foreach ($hasil['labels_aktif'] as $l)<th class="col-center">{{ $l }}</th>@endforeach</tr></thead>
        <tbody>
        @foreach ($hasil['data_kos'] as $i => $kos)
        <tr><td style="font-weight:500">{{ $kos['nama'] }}</td>@foreach ($hasil['matriks_x'][$i] as $v)<td class="col-center" style="font-family:monospace">{{ $v }}</td>@endforeach</tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>

{{-- MATRIKS R --}}
<div class="card section-card" id="tabel-matriks-r">
    <div class="card-header"><span class="card-title">Matriks Ternormalisasi (R)</span></div>
    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr><th>Alternatif</th>@foreach ($hasil['labels_aktif'] as $l)<th class="col-center">{{ $l }}</th>@endforeach</tr></thead>
        <tbody>
        @foreach ($hasil['data_kos'] as $i => $kos)
        <tr><td style="font-weight:500">{{ $kos['nama'] }}</td>@foreach ($hasil['matriks_r'][$i] as $v)<td class="col-center" style="font-family:monospace">{{ number_format($v, 4) }}</td>@endforeach</tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>

@else
{{-- EMPTY STATE --}}
<div class="empty-state">
    <div class="empty-icon">📊</div>
    <h2>Selamat Datang</h2>
    <p>Pilih kriteria di bawah, lalu tekan <strong>Proses SAW</strong> untuk menampilkan hasil perhitungan dan rekomendasi kos terbaik.</p>
</div>

<form method="POST" action="{{ route('dashboard.proses') }}" id="form-saw">
    @csrf
    <div class="card" style="margin-top:24px">
        <div class="card-header"><span class="card-title">Pilih Kriteria</span></div>
        <div class="criteria-list">
            @php $ids = ['C1','C2','C3','C4','C5']; $icons = ['💰','📍','🏠','✨','🛡️']; @endphp
            @foreach ($labelKriteria as $index => $label)
            <label class="criteria-item" for="kriteria-{{ $index }}">
                <input type="checkbox" id="kriteria-{{ $index }}" name="kriteria[]" value="{{ $index }}" {{ in_array($index, $selectedIndices) ? 'checked' : '' }}>
                <span class="criteria-id">{{ $ids[$index] }}</span>
                <span>{{ $icons[$index] }}</span>
                <span class="criteria-name">{{ str_replace(['C1 (','C2 (','C3 (','C4 (','C5 (',')'], '', $label) }}</span>
            </label>
            @endforeach
        </div>
        <div class="criteria-form-footer">
            <button type="submit" class="btn-primary">Proses SAW</button>
        </div>
    </div>
</form>
@endif

</div>{{-- end content-area --}}

<footer style="padding:24px 32px;text-align:center;font-size:12px;color:var(--c-on-variant);border-top:1px solid var(--c-surface-high)">
    &copy; {{ date('Y') }} KosChoice SAW — Sistem Pendukung Keputusan Pemilihan Kos
</footer>

</div>{{-- end main-wrapper --}}
</div>{{-- end app-layout --}}
</body>
</html>
