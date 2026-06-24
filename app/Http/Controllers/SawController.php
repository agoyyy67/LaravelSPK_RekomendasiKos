<?php

namespace App\Http\Controllers;

use App\Services\SawService;
use Illuminate\Http\Request;

class SawController extends Controller
{
    protected SawService $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }

    /**
     * Menampilkan dashboard utama SPK SAW.
     * GET  → auto-proses dengan semua kriteria.
     * POST → proses dengan kriteria yang dipilih user.
     */
    public function index(Request $request)
    {
        $csvPath = storage_path('app/data/respon_gform.csv');

        $labelKriteria = SawService::LABEL_KRITERIA;
        $defaultIndices = array_keys($labelKriteria);

        // Ambil kriteria terpilih
        if ($request->isMethod('post')) {
            $selectedIndices = $request->input('kriteria', []);
            $sudahProses = true;
        } else {
            // GET: auto-proses dengan semua kriteria
            $selectedIndices = $defaultIndices;
            $sudahProses = true;
        }

        // Validasi: minimal satu kriteria harus dipilih
        if (empty($selectedIndices)) {
            return view('dashboard', [
                'labelKriteria'   => $labelKriteria,
                'selectedIndices' => [],
                'sudahProses'     => true,
                'hasil'           => null,
                'error'           => 'Pilih minimal satu kriteria untuk menjalankan SAW.',
            ]);
        }

        // Konversi ke integer & validasi range
        $selectedIndices = array_map('intval', $selectedIndices);
        $selectedIndices = array_filter($selectedIndices, fn($i) => $i >= 0 && $i < count($labelKriteria));

        if (empty($selectedIndices)) {
            return view('dashboard', [
                'labelKriteria'   => $labelKriteria,
                'selectedIndices' => [],
                'sudahProses'     => true,
                'hasil'           => null,
                'error'           => 'Indeks kriteria tidak valid.',
            ]);
        }

        try {
            $hasil = $this->sawService->proses($csvPath, array_values($selectedIndices));

            return view('dashboard', [
                'labelKriteria'   => $labelKriteria,
                'selectedIndices' => $selectedIndices,
                'sudahProses'     => true,
                'hasil'           => $hasil,
                'error'           => null,
            ]);
        } catch (\Throwable $e) {
            return view('dashboard', [
                'labelKriteria'   => $labelKriteria,
                'selectedIndices' => $selectedIndices,
                'sudahProses'     => true,
                'hasil'           => null,
                'error'           => $e->getMessage(),
            ]);
        }
    }

    /**
     * Export hasil ranking ke CSV.
     */
    public function exportCsv(Request $request)
    {
        $csvPath = storage_path('app/data/respon_gform.csv');
        $labelKriteria = SawService::LABEL_KRITERIA;
        $defaultIndices = array_keys($labelKriteria);

        $selectedIndices = $request->input('kriteria', $defaultIndices);
        $selectedIndices = array_map('intval', $selectedIndices);
        $selectedIndices = array_filter($selectedIndices, fn($i) => $i >= 0 && $i < count($labelKriteria));

        if (empty($selectedIndices)) {
            return back()->with('error', 'Kriteria tidak valid.');
        }

        try {
            $hasil = $this->sawService->proses($csvPath, array_values($selectedIndices));
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        $filename = 'ranking_kos_saw_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($hasil) {
            $out = fopen('php://output', 'w');
            // BOM for Excel
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, ['Rank', 'Nama Kos', 'Alamat', 'Skor Preferensi (V)']);

            foreach ($hasil['ranking'] as $i => $item) {
                fputcsv($out, [
                    $i + 1,
                    $item['nama'],
                    $item['alamat'],
                    number_format($item['skor_v'], 4),
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
