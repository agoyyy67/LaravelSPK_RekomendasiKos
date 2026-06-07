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
     * Menerima GET (initial load) dan POST (setelah pilih kriteria).
     */
    public function index(Request $request)
    {
        $csvPath = storage_path('app/data/respon_gform.csv');

        // Label kriteria untuk tampilan checkbox
        $labelKriteria = SawService::LABEL_KRITERIA;

        // Default: semua kriteria terpilih
        $defaultIndices = array_keys($labelKriteria); // [0, 1, 2, 3, 4]

        // Ambil kriteria terpilih dari request, default semua
        $selectedIndices = $request->input('kriteria', null);
        $sudahProses = $request->isMethod('post');

        // Jika belum submit form, tampilkan halaman awal
        if (!$sudahProses) {
            return view('dashboard', [
                'labelKriteria'   => $labelKriteria,
                'selectedIndices' => $defaultIndices,
                'sudahProses'     => false,
                'hasil'           => null,
                'error'           => null,
            ]);
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

        // Konversi ke integer
        $selectedIndices = array_map('intval', $selectedIndices);

        // Validasi indeks valid (0-4)
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
}
