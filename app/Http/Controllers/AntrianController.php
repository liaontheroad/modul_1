<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Antrian;

class AntrianController extends Controller
{

    public function guest()
    {
        return view('antrian.guest');
    }

    public function daftar(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'ruangan' => 'required|string|max:100',
        ]);

        $lastNomor = Antrian::max('nomor_antrian') ?? 0;
        $nomor     = $lastNomor + 1;

        $antrian = Antrian::create([
            'nomor_antrian' => $nomor,
            'nama'          => $request->nama,
            'ruangan'       => $request->ruangan,
            'status'        => 'menunggu',
        ]);

        $this->broadcastUpdate();

        return response()->json([
            'id'            => $antrian->id,
            'nomor_antrian' => $antrian->nomor_antrian,
            'nama'          => $antrian->nama,
            'ruangan'       => $antrian->ruangan,
        ]);
    }

    public function tiket($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.tiket', compact('antrian'));
    }

    public function admin()
    {
        $menunggu  = Antrian::where('status', 'menunggu')
                        ->orderBy('nomor_antrian')->get();
        $terlambat = Antrian::where('status', 'terlambat')
                        ->orderBy('nomor_antrian')->get();
        $dipanggil = Antrian::whereIn('status', ['dipanggil', 'selesai'])
                        ->orderByDesc('updated_at')->first();

        return view('antrian.admin', compact('menunggu', 'terlambat', 'dipanggil'));
    }

    public function panggil(Request $request)
    {
        \DB::reconnect();

        $antrian = Antrian::where('status', 'menunggu')
                        ->orderBy('nomor_antrian')
                        ->first();

        if (!$antrian) {
            return response()->json(['message' => 'Tidak ada antrian menunggu.'], 404);
        }

        Antrian::where('status', 'dipanggil')
               ->update(['status' => 'selesai']);

        $antrian->update(['status' => 'dipanggil']);

        $this->broadcastUpdate();

        return response()->json([
            'nomor_antrian' => $antrian->nomor_antrian,
            'nama'          => $antrian->nama,
            'ruangan'       => $antrian->ruangan,
        ]);
    }

    public function panggilTerlambat(Request $request)
    {
        $request->validate(['id' => 'required|exists:antrian,id']);

        $antrian = Antrian::findOrFail($request->id);

        Antrian::where('status', 'dipanggil')
               ->update(['status' => 'selesai']);

        $antrian->update(['status' => 'dipanggil']);

        $this->broadcastUpdate();

        return response()->json([
            'nomor_antrian' => $antrian->nomor_antrian,
            'nama'          => $antrian->nama,
            'ruangan'       => $antrian->ruangan,
        ]);
    }

    public function terlambat(Request $request)
    {
        if ($request->id) {
            $antrian = Antrian::findOrFail($request->id);
            $antrian->update(['status' => 'terlambat']);
        } else {
            $antrian = Antrian::where('status', 'dipanggil')->first();
            if (!$antrian) {
                return response()->json(['message' => 'Tidak ada yang sedang dipanggil.'], 404);
            }
            $antrian->update(['status' => 'terlambat']);
        }

        $this->broadcastUpdate();
        return response()->json(['message' => 'Ditandai terlambat.']);
    }

    public function reset()
    {
        Antrian::truncate();
        Cache::forget('antrian_state');
        $this->broadcastUpdate();
        return response()->json(['message' => 'Antrian direset.']);
    }

    public function papan()
    {
        return view('antrian.papan');
    }

    public function stream()
    {
        return response()->stream(function () {
            set_time_limit(60);
            $lastSent = null;

            while (true) {
                $dipanggil = Antrian::where('status', 'dipanggil')
                                ->orderByDesc('updated_at')->first();

                $menunggu = Antrian::where('status', 'menunggu')
                                ->orderBy('nomor_antrian')
                                ->get(['id', 'nomor_antrian', 'nama', 'ruangan']);

                $terlambat = Antrian::where('status', 'terlambat')
                                ->orderBy('nomor_antrian')
                                ->get(['id', 'nomor_antrian', 'nama', 'ruangan']);

                $state = [
                    'dipanggil' => $dipanggil ? [
                        'nomor_antrian' => $dipanggil->nomor_antrian,
                        'nama'          => $dipanggil->nama,
                        'ruangan'       => $dipanggil->ruangan,
                    ] : null,
                    'menunggu'  => $menunggu,
                    'terlambat' => $terlambat,
                    'total'     => $menunggu->count(),
                ];

                $json = json_encode($state);

                if ($json !== $lastSent) {
                    echo "event: queue-update\n";
                    echo "data: " . $json . "\n\n";
                    ob_flush();
                    flush();
                    $lastSent = $json;
                }

                if (connection_aborted()) break;

                sleep(2);
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }

    private function broadcastUpdate()
    {
        Cache::put('antrian_last_update', now()->timestamp, 300);
    }
}