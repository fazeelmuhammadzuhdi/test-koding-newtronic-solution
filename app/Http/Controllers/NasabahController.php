<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use App\Models\Nasabah;

class NasabahController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        // hitung jumlah user yang login, apakah dia sudah mengisi biodata atau belum
        $countUser = Nasabah::where('user_id', $userId)->count();

        // cek apakah nasabah sudah ada no antrian atau belum berdasarkan jenis layanan
        $countUserNoAntrianTeller = Antrian::where('layanan', 'teller')
            ->whereHas('nasabah', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        // dd($countUserNoAntrianTeller);

        $countUserNoAntrianCs = Antrian::where('layanan', 'cs')
            ->whereHas('nasabah', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        // dd($countUserNoAntrianTeller, $countUserNoAntrianCs);

        $getMaxNoAntrianTeller = Antrian::getNoAntrianTerakhirBerdasarkanLayanan('teller');
        $getMaxNoAntrianCs = Antrian::getNoAntrianTerakhirBerdasarkanLayanan('cs');
        $noAntrianTerakhirTeller = Antrian::getNoAntrianTerakhirBerdasarkanLayananDanStatus('teller', 1);
        $noAntrianTerakhirCs = Antrian::getNoAntrianTerakhirBerdasarkanLayananDanStatus('cs', 1);


        return view('nasabah.index', compact('countUser', 'getMaxNoAntrianTeller', 'getMaxNoAntrianCs', 'countUserNoAntrianTeller', 'countUserNoAntrianCs', 'noAntrianTerakhirTeller', 'noAntrianTerakhirCs'));
    }

    public function create()
    {
        $authUser = Nasabah::UserId()->first();

        return view('nasabah.create', compact('authUser'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'telepon' => 'required',
        ]);

        Nasabah::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $data
        );

        flash('Data Berhasil disimpan');
        return redirect()->route('nasabah.index');
    }

    // public function store(Request $request)
    // {

    //     // Periksa apakah data Nasabah sudah ada
    //     $nasabah = Nasabah::UserId()->first();

    //     if ($nasabah) {
    //         // Data Nasabah sudah ada, update data
    //         $nasabah->update($request->validate([
    //             'nama' => 'required',
    //             'telepon' => 'required',
    //         ]));
    //     } else {
    //         // Data Nasabah belum ada, buat data baru
    //         $data = $request->validate([
    //             'nama' => 'required',
    //             'telepon' => 'required',
    //         ]);
    //         Nasabah::create($data);
    //     }

    //     return redirect()->route('nasabah.index');
    // }
}
