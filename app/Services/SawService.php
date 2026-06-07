<?php

namespace App\Services;

/**
 * SawService
 * ==========
 * Service class untuk membaca CSV dan menghitung perangkingan
 * menggunakan metode Simple Additive Weighting (SAW).
 *
 * Port langsung dari Python:
 *   - src/csv_handler.py  -> bacaCsv()
 *   - src/saw_processor.py -> hitungSaw()
 *   - app.py              -> buatSubsetKriteria()
 */
class SawService
{
    /**
     * Konfigurasi kriteria default (sama persis dengan app.py Python).
     */
    public const LABEL_KRITERIA = [
        'C1 (Harga)',
        'C2 (Jarak)',
        'C3 (Fasilitas)',
        'C4 (Kebersihan)',
        'C5 (Keamanan)',
    ];

    public const BOBOT = [0.2, 0.2, 0.2, 0.2, 0.2];

    public const JENIS_KRITERIA = ['cost', 'benefit', 'cost', 'cost', 'cost'];

    /**
     * Membaca file CSV dan parsing data kos.
     *
     * Port dari csv_handler.py -> baca_csv()
     *
     * @param  string  $filepath  Path absolut ke file CSV.
     * @return array  List data kos: [['nama' => ..., 'alamat' => ..., 'kriteria' => [...]]]
     *
     * @throws \RuntimeException
     */
    public function bacaCsv(string $filepath): array
    {
        if (!file_exists($filepath)) {
            throw new \RuntimeException(
                "[ERROR] File CSV tidak ditemukan: '{$filepath}'. "
                . "Pastikan file sudah diletakkan di folder storage/app/data/."
            );
        }

        $handle = fopen($filepath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("[ERROR] Gagal membuka file CSV: '{$filepath}'.");
        }

        // Baca header
        $header = fgetcsv($handle);
        if ($header === false || empty($header)) {
            fclose($handle);
            throw new \RuntimeException("[ERROR] File CSV kosong atau tidak memiliki header.");
        }

        // Hapus BOM (Byte Order Mark) jika ada
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        // Mapping kolom
        $kolomWajib = [
            'Nama Rumah Kos',
            'Alamat Lengkap Kos',
            'Harga Sewa Kos per Tahun',
            'Kondisi Jarak / Lokasi Kos',
            'Kelengkapan Fasilitas Kos',
            'Tingkat Kebersihan Lingkungan Kos',
            'Tingkat Keamanan Kos',
        ];

        $headerMap = array_flip($header);
        foreach ($kolomWajib as $kolom) {
            if (!isset($headerMap[$kolom])) {
                fclose($handle);
                throw new \RuntimeException(
                    "[ERROR] Kolom '{$kolom}' tidak ditemukan di file CSV. "
                    . "Kolom yang tersedia: " . implode(', ', $header)
                );
            }
        }

        $dataKos = [];
        $nomorBaris = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $nomorBaris++;

            // Skip baris kosong
            if (count($row) < count($header)) {
                continue;
            }

            $rowAssoc = array_combine($header, $row);

            try {
                $nama = trim($rowAssoc['Nama Rumah Kos']);
                $alamat = trim($rowAssoc['Alamat Lengkap Kos']);

                // Parsing Harga: format "1 - Kurang dari Rp 3.000.000"
                // Ambil karakter pertama sebagai skor integer
                $rawHarga = trim($rowAssoc['Harga Sewa Kos per Tahun']);
                $skorHarga = (int) $rawHarga[0];

                $skorJarak = (int) trim($rowAssoc['Kondisi Jarak / Lokasi Kos']);
                $skorFasilitas = (int) trim($rowAssoc['Kelengkapan Fasilitas Kos']);
                $skorKebersihan = (int) trim($rowAssoc['Tingkat Kebersihan Lingkungan Kos']);
                $skorKeamanan = (int) trim($rowAssoc['Tingkat Keamanan Kos']);

                $dataKos[] = [
                    'nama'     => $nama,
                    'alamat'   => $alamat,
                    'kriteria' => [
                        $skorHarga,       // C1: Harga Sewa
                        $skorJarak,       // C2: Jarak/Lokasi
                        $skorFasilitas,   // C3: Fasilitas
                        $skorKebersihan,  // C4: Kebersihan
                        $skorKeamanan,    // C5: Keamanan
                    ],
                ];
            } catch (\Throwable $e) {
                fclose($handle);
                throw new \RuntimeException(
                    "[ERROR] Gagal parsing data pada baris ke-{$nomorBaris}: " . $e->getMessage()
                );
            }
        }

        fclose($handle);

        if (empty($dataKos)) {
            throw new \RuntimeException("[ERROR] File CSV tidak memiliki data (hanya header).");
        }

        return $dataKos;
    }

    /**
     * Membentuk data dan konfigurasi baru berdasarkan kriteria yang dipilih.
     *
     * Port dari app.py -> buat_subset_kriteria()
     *
     * @param  array  $dataKos         Data kos hasil bacaCsv().
     * @param  array  $indeksTerpilih  Array indeks kriteria yang dipilih (0-4).
     * @return array  ['data' => ..., 'labels' => ..., 'bobot' => ..., 'jenis' => ...]
     *
     * @throws \RuntimeException
     */
    public function buatSubsetKriteria(array $dataKos, array $indeksTerpilih): array
    {
        $labels = [];
        $bobot = [];
        $jenis = [];

        foreach ($indeksTerpilih as $i) {
            $labels[] = self::LABEL_KRITERIA[$i];
            $bobot[]  = self::BOBOT[$i];
            $jenis[]  = self::JENIS_KRITERIA[$i];
        }

        $totalBobot = array_sum($bobot);
        if ($totalBobot == 0) {
            throw new \RuntimeException('Total bobot subset bernilai 0.');
        }

        // Normalisasi bobot agar totalnya = 1
        $bobotTernormalisasi = array_map(fn($b) => $b / $totalBobot, $bobot);

        $dataSubset = [];
        foreach ($dataKos as $kos) {
            $kriteriaSubset = [];
            foreach ($indeksTerpilih as $i) {
                $kriteriaSubset[] = $kos['kriteria'][$i];
            }
            $dataSubset[] = [
                'nama'     => $kos['nama'],
                'alamat'   => $kos['alamat'],
                'kriteria' => $kriteriaSubset,
            ];
        }

        return [
            'data'   => $dataSubset,
            'labels' => $labels,
            'bobot'  => $bobotTernormalisasi,
            'jenis'  => $jenis,
        ];
    }

    /**
     * Menghitung perangkingan alternatif menggunakan metode SAW.
     *
     * Port dari saw_processor.py -> hitung_saw()
     *
     * @param  array  $dataKos        Data kos (nama, alamat, kriteria).
     * @param  array  $bobot          Array bobot (harus berjumlah ~1.0).
     * @param  array  $jenisKriteria  Array jenis ('cost' atau 'benefit').
     * @return array  ['matriks_x' => ..., 'matriks_r' => ..., 'ranking' => ...]
     *
     * @throws \RuntimeException
     */
    public function hitungSaw(array $dataKos, array $bobot, array $jenisKriteria): array
    {
        $jumlahAlternatif = count($dataKos);
        $jumlahKriteria   = count($bobot);

        // === Validasi Input ===
        if (count($jenisKriteria) !== $jumlahKriteria) {
            throw new \RuntimeException(
                "[ERROR] Jumlah jenis kriteria (" . count($jenisKriteria)
                . ") tidak sama dengan jumlah bobot ({$jumlahKriteria})."
            );
        }

        foreach ($dataKos as $kos) {
            if (count($kos['kriteria']) !== $jumlahKriteria) {
                throw new \RuntimeException(
                    "[ERROR] Jumlah kriteria pada '{$kos['nama']}' ("
                    . count($kos['kriteria']) . ") tidak sesuai dengan jumlah bobot ({$jumlahKriteria})."
                );
            }
        }

        $totalBobot = array_sum($bobot);
        if (abs($totalBobot - 1.0) > 0.001) {
            throw new \RuntimeException(
                "[ERROR] Total bobot (" . round($totalBobot, 4) . ") tidak sama dengan 1.0."
            );
        }

        foreach ($jenisKriteria as $jenis) {
            if (!in_array($jenis, ['cost', 'benefit'], true)) {
                throw new \RuntimeException(
                    "[ERROR] Jenis kriteria '{$jenis}' tidak valid. Gunakan 'cost' atau 'benefit'."
                );
            }
        }

        // === LANGKAH 1: Bangun Matriks Keputusan (X) ===
        $matriksX = [];
        foreach ($dataKos as $kos) {
            $matriksX[] = $kos['kriteria'];
        }

        // === LANGKAH 2: Hitung Nilai Min & Max Setiap Kolom Kriteria ===
        $minPerKolom = [];
        $maxPerKolom = [];

        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $kolomJ = array_column($matriksX, $j);
            $minPerKolom[] = min($kolomJ);
            $maxPerKolom[] = max($kolomJ);
        }

        // === LANGKAH 3: Normalisasi Matriks (R) ===
        //   Benefit: R_ij = X_ij / Max(X_j)
        //   Cost   : R_ij = Min(X_j) / X_ij
        $matriksR = [];

        for ($i = 0; $i < $jumlahAlternatif; $i++) {
            $barisR = [];
            for ($j = 0; $j < $jumlahKriteria; $j++) {
                $xij = $matriksX[$i][$j];

                if ($jenisKriteria[$j] === 'benefit') {
                    $rij = $xij / $maxPerKolom[$j];
                } else { // cost
                    $rij = $minPerKolom[$j] / $xij;
                }

                $barisR[] = round($rij, 4);
            }
            $matriksR[] = $barisR;
        }

        // === LANGKAH 4: Hitung Nilai Preferensi (V) & Perangkingan ===
        //   V_i = Σ (W_j * R_ij)
        $hasilRanking = [];

        for ($i = 0; $i < $jumlahAlternatif; $i++) {
            $skorV = 0.0;
            for ($j = 0; $j < $jumlahKriteria; $j++) {
                $skorV += $bobot[$j] * $matriksR[$i][$j];
            }

            $hasilRanking[] = [
                'nama'   => $dataKos[$i]['nama'],
                'alamat' => $dataKos[$i]['alamat'],
                'skor_v' => round($skorV, 4),
            ];
        }

        // Urutkan descending berdasarkan skor_v
        usort($hasilRanking, fn($a, $b) => $b['skor_v'] <=> $a['skor_v']);

        return [
            'matriks_x' => $matriksX,
            'matriks_r' => $matriksR,
            'ranking'   => $hasilRanking,
        ];
    }

    /**
     * Proses lengkap: baca CSV, buat subset, hitung SAW.
     *
     * @param  string  $csvPath         Path ke file CSV.
     * @param  array   $indeksTerpilih  Indeks kriteria yang dipilih (0-4).
     * @return array   Semua data yang diperlukan view.
     */
    public function proses(string $csvPath, array $indeksTerpilih): array
    {
        $dataKosAwal = $this->bacaCsv($csvPath);

        $subset = $this->buatSubsetKriteria($dataKosAwal, $indeksTerpilih);

        $hasil = $this->hitungSaw($subset['data'], $subset['bobot'], $subset['jenis']);

        return [
            'data_kos_awal'  => $dataKosAwal,
            'data_kos'       => $subset['data'],
            'labels_aktif'   => $subset['labels'],
            'bobot_aktif'    => $subset['bobot'],
            'jenis_aktif'    => $subset['jenis'],
            'matriks_x'      => $hasil['matriks_x'],
            'matriks_r'      => $hasil['matriks_r'],
            'ranking'        => $hasil['ranking'],
        ];
    }
}
