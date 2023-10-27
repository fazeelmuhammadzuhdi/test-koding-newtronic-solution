<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class AntrianController extends Controller
{

    private function getAntrianByLayananAndStatus($layanan, $status)
    {
        return Antrian::with('nasabah')
            ->where('layanan', $layanan)
            ->where('status', $status)
            ->orderBy('no_antrian', 'asc')
            ->limit(1)
            ->get();
    }

    private function getAntrian($layanan)
    {
        return Antrian::with('nasabah')
            ->where('layanan', $layanan)
            ->orderBy('no_antrian', 'asc')
            ->paginate(5);
    }

    public function store(Request $request)
    {
        $layanan = $request->layanan;
        $userId = auth()->user()->id;

        $getDescAntrianTeller = Antrian::getNoAntrianTerakhirBerdasarkanLayanan('teller');
        $getDescAntrianCs =  Antrian::getNoAntrianTerakhirBerdasarkanLayanan('cs');

        // Cek nomor antrian terakhir berdasarkan jenis layanan
        if ($layanan == 'teller') {
            $nomorAntrianTerakhir = $getDescAntrianTeller;
        } else {
            $nomorAntrianTerakhir = $getDescAntrianCs;
        }

        $nomorAntrian = $nomorAntrianTerakhir + 1;

        // Simpan data ke table antrian
        $nasabah = Nasabah::where('user_id', $userId)->first();
        Antrian::create([
            'nasabah_id' => $nasabah->id,
            'no_antrian' => $nomorAntrian,
            'layanan' => $layanan
        ]);

        // return response()->json(['nomor_antrian' => $nomorAntrian]);
        return response()->json([
            'nomor_antrian' => $nomorAntrian,
            'message' => "Anda telah berhasil mengambil nomor Antrian $layanan dengan nomor antrian $nomorAntrian"
        ]);
    }

    public function cetakPdfTeller()
    {
        $userId = auth()->user()->id;

        // membuat pdf untuk mencetak no antrian berdasarkan user yang login dan jenis layanan
        $noAntrianTeller = Antrian::with('nasabah')->where('layanan', 'teller')
            ->whereHas('nasabah', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        // dd($noAntrianTeller);
        return view('antrian.cetak-pdf', compact('noAntrianTeller'));
    }

    public function cetakPdfCs()
    {
        $userId = auth()->user()->id;

        // membuat pdf untuk mencetak no antrian berdasarkan user yang login dan jenis layanan
        $noAntrianTeller = Antrian::with('nasabah')->where('layanan', 'cs')
            ->whereHas('nasabah', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        // dd($noAntrianTeller);
        return view('antrian.cetak-pdf', compact('noAntrianTeller'));
    }

    public function daftarAntrianNasabahTeller()
    {
        $title = 'Daftar Antrian Nasabah Teller';
        // refactor code
        $nasabahTeller = $this->getAntrianByLayananAndStatus('teller', 0);

        // Ambil nomor antrian yang dipanggil terakhir berdasarkan jenis layanan
        $noAntrianTerakhir = Antrian::getNoAntrianTerakhirBerdasarkanLayananDanStatus('teller', 1);

        // menampilkan daftar antrian berdasarkan jenis layanan
        $daftarAntrian = $this->getAntrian('teller');

        // menghitung jumlah antrian yang sudah dipanggil
        $jumlahAntrianDipanggil = Antrian::countJumlahNasabahYangSudahDiPanggil('teller', 1);

        return view('antrian.teller', compact('nasabahTeller', 'title', 'noAntrianTerakhir', 'daftarAntrian', 'jumlahAntrianDipanggil'));
    }

    public function daftarAntrianNasabahCs()
    {
        // refactor code
        $title = 'Daftar Antrian Nasabah Customer Service (CS)';
        $nasabahTeller = $this->getAntrianByLayananAndStatus('cs', 0);
        $noAntrianTerakhir = Antrian::getNoAntrianTerakhirBerdasarkanLayananDanStatus('cs', 1);
        $daftarAntrian = $this->getAntrian('cs');
        $jumlahAntrianDipanggil = Antrian::countJumlahNasabahYangSudahDiPanggil('cs', 1);

        // dd($nasabahTeller);
        return view('antrian.teller', compact('nasabahTeller', 'title', 'daftarAntrian', 'noAntrianTerakhir', 'jumlahAntrianDipanggil'));
    }

    public function panggilAntrian(Request $request)
    {
        $antrianId = $request->input('antrian_id');

        $antrian = Antrian::find($antrianId);
        // dd($antrian);
        if ($antrian) {
            $antrian->status = 1;
            $antrian->save();

            // Ambil nomor antrian yang dipanggil
            $nomorAntrian = $antrian->no_antrian;
        } else {
            $nomorAntrian = 0;
        }

        return response()->json(['message' => 'Antrian Berhasil DiPanggil', 'nomor_antrian' => $nomorAntrian]);
    }

    // API
    public function getDataCs()
    {
        $layanan = 'cs';

        $dataCount = Antrian::where('layanan', $layanan)
            ->where('status', 0)
            ->count();

        $csData = [];

        if ($dataCount > 0) {
            $csData = Antrian::where('layanan', $layanan)
                ->where('status', 0)
                ->take($dataCount)
                ->get()
                ->map(function ($item) {
                    return [
                        'antrian' => $item->no_antrian,
                        'nama' => $item->nasabah->nama,
                        'telepon' => $item->nasabah->telepon
                    ];
                });
        }

        $response = [
            'dataCount' => $dataCount,
            'layanan' => $layanan,
            'data' => $csData
        ];

        return response()->json($response);
    }

    public function getDataTeller()
    {
        $layanan = 'teller';

        $dataCount = Antrian::where('layanan', $layanan)
            ->where('status', 0)
            ->count();

        $tellerData = [];

        if ($dataCount > 0) {
            $tellerData = Antrian::where('layanan', $layanan)
                ->where('status', 0)
                ->take($dataCount)
                ->get()
                ->map(function ($item) {
                    return [
                        'antrian' => $item->no_antrian,
                        'nama' => $item->nasabah->nama,
                        'telepon' => $item->nasabah->telepon
                    ];
                });
        }

        $response = [
            'dataCount' => $dataCount,
            'layanan' => $layanan,
            'data' => $tellerData
        ];

        return response()->json($response);
    }
}
