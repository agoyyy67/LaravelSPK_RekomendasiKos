<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Kos Purbalingga — Metode SAW</title>
    <meta name="description" content="Sistem Pendukung Keputusan Pemilihan Rumah Kos di Purbalingga menggunakan metode Simple Additive Weighting (SAW).">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-slate-100 antialiased">

    {{-- ============================================================ --}}
    {{-- HEADER --}}
    {{-- ============================================================ --}}
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/70 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center gap-3 px-6 py-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-lg shadow-lg shadow-indigo-500/25">🏠</span>
            <div>
                <h1 class="text-lg font-semibold tracking-tight text-white">SPK Pemilihan Kos — SAW</h1>
                <p class="text-xs text-slate-400">Simple Additive Weighting • Purbalingga</p>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[320px_1fr]">

            {{-- ======================================================== --}}
            {{-- SIDEBAR: Form Pilih Kriteria --}}
            {{-- ======================================================== --}}
            <aside>
                <form method="POST" action="{{ route('dashboard.proses') }}" id="form-saw">
                    @csrf
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-sm">
                        <h2 class="mb-1 text-base font-semibold text-white">Pilih Kriteria</h2>
                        <p class="mb-5 text-xs text-slate-400">Centang kriteria yang ingin digunakan dalam perhitungan SAW.</p>

                        <div class="space-y-3">
                            @foreach ($labelKriteria as $index => $label)
                                <label for="kriteria-{{ $index }}" class="group flex cursor-pointer items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-3 transition-all duration-200 hover:border-indigo-500/40 hover:bg-indigo-500/10 has-[:checked]:border-indigo-500/50 has-[:checked]:bg-indigo-500/15">
                                    <input
                                        type="checkbox"
                                        id="kriteria-{{ $index }}"
                                        name="kriteria[]"
                                        value="{{ $index }}"
                                        class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-indigo-500 accent-indigo-500 focus:ring-indigo-500 focus:ring-offset-0"
                                        {{ in_array($index, $selectedIndices) ? 'checked' : '' }}
                                    >
                                    <span class="text-sm text-slate-200 group-hover:text-white">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" id="btn-proses" class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all duration-200 hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/40 active:scale-[0.98]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Proses SAW
                        </button>
                    </div>
                </form>

                {{-- Info Metode --}}
                <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm">
                    <h3 class="mb-2 text-sm font-semibold text-slate-300">Tentang Metode SAW</h3>
                    <p class="text-xs leading-relaxed text-slate-400">
                        <strong class="text-slate-300">Simple Additive Weighting (SAW)</strong> menghitung nilai preferensi setiap alternatif melalui normalisasi matriks keputusan berdasarkan jenis kriteria (Cost / Benefit), kemudian menjumlahkan perkalian bobot dengan nilai ternormalisasi.
                    </p>
                    <div class="mt-3 rounded-lg bg-slate-800/50 px-3 py-2">
                        <code class="text-xs text-indigo-300">V<sub>i</sub> = Σ (W<sub>j</sub> × R<sub>ij</sub>)</code>
                    </div>
                </div>
            </aside>

            {{-- ======================================================== --}}
            {{-- MAIN CONTENT --}}
            {{-- ======================================================== --}}
            <section class="space-y-6">

                {{-- Error Alert --}}
                @if ($error)
                    <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-4 text-sm text-red-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $error }}
                    </div>
                @endif

                {{-- Initial State --}}
                @if (!$sudahProses)
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-8 py-20 text-center backdrop-blur-sm">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-500/15 text-3xl">📊</div>
                        <h2 class="mb-2 text-xl font-semibold text-white">Selamat Datang</h2>
                        <p class="max-w-md text-sm text-slate-400">Pilih kriteria yang ingin digunakan pada panel sebelah kiri, lalu tekan <strong class="text-indigo-400">Proses SAW</strong> untuk menampilkan hasil perhitungan dan rekomendasi kos terbaik.</p>
                    </div>
                @endif

                {{-- HASIL PERHITUNGAN --}}
                @if ($sudahProses && $hasil)

                    {{-- Metric Cards --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="group rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition-all duration-200 hover:border-indigo-500/30 hover:bg-indigo-500/5">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Alternatif</p>
                            <p class="mt-1 text-3xl font-bold text-white">{{ count($hasil['data_kos_awal']) }}</p>
                            <p class="mt-1 text-xs text-slate-500">Data dari CSV</p>
                        </div>
                        <div class="group rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition-all duration-200 hover:border-violet-500/30 hover:bg-violet-500/5">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Alternatif Diproses</p>
                            <p class="mt-1 text-3xl font-bold text-white">{{ count($hasil['data_kos']) }}</p>
                            <p class="mt-1 text-xs text-slate-500">Yang dievaluasi</p>
                        </div>
                        <div class="group rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition-all duration-200 hover:border-emerald-500/30 hover:bg-emerald-500/5">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Kriteria Aktif</p>
                            <p class="mt-1 text-3xl font-bold text-white">{{ count($hasil['labels_aktif']) }}</p>
                            <p class="mt-1 text-xs text-slate-500">Dari {{ count($labelKriteria) }} total</p>
                        </div>
                    </div>

                    {{-- Rekomendasi Terbaik --}}
                    @if (!empty($hasil['ranking']))
                        @php $terbaik = $hasil['ranking'][0]; @endphp
                        <div class="relative overflow-hidden rounded-2xl border border-emerald-500/30 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 p-6 backdrop-blur-sm">
                            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl"></div>
                            <div class="relative flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-500/20 text-2xl">🏆</div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-400">Rekomendasi Terbaik</p>
                                    <h3 class="mt-1 text-xl font-bold text-white">{{ $terbaik['nama'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-300">{{ $terbaik['alamat'] }}</p>
                                    <div class="mt-3 inline-flex items-center gap-2 rounded-lg bg-emerald-500/15 px-3 py-1.5">
                                        <span class="text-xs text-emerald-300">Skor Preferensi (V)</span>
                                        <span class="text-sm font-bold text-emerald-200">{{ number_format($terbaik['skor_v'], 4) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Konfigurasi Kriteria Aktif --}}
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                        <h2 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-500/20 text-xs">⚙️</span>
                            Konfigurasi Kriteria Aktif
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" id="tabel-konfigurasi">
                                <thead>
                                    <tr class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-400">
                                        <th class="px-4 py-3">Kriteria</th>
                                        <th class="px-4 py-3">Bobot</th>
                                        <th class="px-4 py-3">Jenis</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($hasil['labels_aktif'] as $i => $label)
                                        <tr class="transition-colors hover:bg-white/5">
                                            <td class="px-4 py-3 font-medium text-slate-200">{{ $label }}</td>
                                            <td class="px-4 py-3 text-slate-300">{{ number_format($hasil['bobot_aktif'][$i], 4) }}</td>
                                            <td class="px-4 py-3">
                                                @if ($hasil['jenis_aktif'][$i] === 'cost')
                                                    <span class="inline-flex items-center rounded-full bg-amber-500/15 px-2.5 py-0.5 text-xs font-medium text-amber-300">Cost</span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-emerald-500/15 px-2.5 py-0.5 text-xs font-medium text-emerald-300">Benefit</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Data Alternatif --}}
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                        <h2 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-500/20 text-xs">🏘️</span>
                            Data Alternatif
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" id="tabel-alternatif">
                                <thead>
                                    <tr class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-400">
                                        <th class="px-4 py-3 text-center">No</th>
                                        <th class="px-4 py-3">Nama Kos</th>
                                        <th class="px-4 py-3">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($hasil['data_kos'] as $i => $kos)
                                        <tr class="transition-colors hover:bg-white/5">
                                            <td class="px-4 py-3 text-center text-slate-400">{{ $i + 1 }}</td>
                                            <td class="px-4 py-3 font-medium text-slate-200">{{ $kos['nama'] }}</td>
                                            <td class="px-4 py-3 text-slate-300">{{ $kos['alamat'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Hasil Perangkingan --}}
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                        <h2 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500/20 text-xs">🏅</span>
                            Hasil Perangkingan
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" id="tabel-ranking">
                                <thead>
                                    <tr class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-400">
                                        <th class="px-4 py-3 text-center">Rank</th>
                                        <th class="px-4 py-3">Nama Kos</th>
                                        <th class="px-4 py-3">Skor (V)</th>
                                        <th class="px-4 py-3">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($hasil['ranking'] as $i => $item)
                                        <tr class="transition-colors hover:bg-white/5 {{ $i === 0 ? 'bg-emerald-500/5' : '' }}">
                                            <td class="px-4 py-3 text-center">
                                                @if ($i === 0)
                                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-xs font-bold text-white shadow-lg shadow-amber-500/25">1</span>
                                                @elseif ($i === 1)
                                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-slate-300 to-slate-500 text-xs font-bold text-white">2</span>
                                                @elseif ($i === 2)
                                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-amber-600 to-amber-800 text-xs font-bold text-white">3</span>
                                                @else
                                                    <span class="text-slate-400">{{ $i + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-medium text-slate-200">{{ $item['nama'] }}</td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-2 w-20 overflow-hidden rounded-full bg-slate-700">
                                                        @php
                                                            $maxSkor = $hasil['ranking'][0]['skor_v'];
                                                            $persen = $maxSkor > 0 ? ($item['skor_v'] / $maxSkor) * 100 : 0;
                                                        @endphp
                                                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500" style="width: {{ $persen }}%"></div>
                                                    </div>
                                                    <span class="font-mono text-sm text-slate-200">{{ number_format($item['skor_v'], 4) }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-slate-400">{{ $item['alamat'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Matriks Keputusan Awal (X) --}}
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                        <h2 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-cyan-500/20 text-xs">📐</span>
                            Matriks Keputusan Awal (X)
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" id="tabel-matriks-x">
                                <thead>
                                    <tr class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-400">
                                        <th class="px-4 py-3">Alternatif</th>
                                        @foreach ($hasil['labels_aktif'] as $label)
                                            <th class="px-4 py-3 text-center">{{ $label }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($hasil['data_kos'] as $i => $kos)
                                        <tr class="transition-colors hover:bg-white/5">
                                            <td class="px-4 py-3 font-medium text-slate-200">{{ $kos['nama'] }}</td>
                                            @foreach ($hasil['matriks_x'][$i] as $val)
                                                <td class="px-4 py-3 text-center font-mono text-slate-300">{{ $val }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Matriks Ternormalisasi (R) --}}
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                        <h2 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-pink-500/20 text-xs">📊</span>
                            Matriks Ternormalisasi (R)
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" id="tabel-matriks-r">
                                <thead>
                                    <tr class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-400">
                                        <th class="px-4 py-3">Alternatif</th>
                                        @foreach ($hasil['labels_aktif'] as $label)
                                            <th class="px-4 py-3 text-center">{{ $label }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($hasil['data_kos'] as $i => $kos)
                                        <tr class="transition-colors hover:bg-white/5">
                                            <td class="px-4 py-3 font-medium text-slate-200">{{ $kos['nama'] }}</td>
                                            @foreach ($hasil['matriks_r'][$i] as $val)
                                                <td class="px-4 py-3 text-center font-mono text-slate-300">{{ number_format($val, 4) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                @endif

            </section>
        </div>
    </main>

    {{-- ============================================================ --}}
    {{-- FOOTER --}}
    {{-- ============================================================ --}}
    <footer class="mt-12 border-t border-white/5 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} SPK Pemilihan Kos Purbalingga — Metode SAW
    </footer>

</body>
</html>
