<?php

namespace App\Http\Controllers;

use App\Models\AbsensiNfc;
use App\Models\NfcCard;
use App\Models\User;
use Illuminate\Http\Request;

class NfcAttendanceController extends Controller
{
    public function scanner()
    {
        return view('nfc.scanner');
    }

    public function registerPage()
    {
        $users = User::orderBy('name')->get();

        return view('nfc.register', compact('users'));
    }

    public function registerCard(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'serial_number' => 'required|string|max:100|unique:nfc_cards,serial_number',
            'card_uid' => 'nullable|string|max:100',
        ]);

        $card = NfcCard::create([
            'user_id' => $request->user_id,
            'serial_number' => $request->serial_number,
            'card_uid' => $request->card_uid,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kartu NFC berhasil didaftarkan.',
            'card' => $card,
        ]);
    }

    public function scan(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
        ]);

        $card = NfcCard::with('user')
            ->where('serial_number', $request->serial_number)
            ->where('status', 'active')
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu NFC belum terdaftar atau tidak aktif.',
            ], 404);
        }

        $today = now()->toDateString();

        $absensi = AbsensiNfc::firstOrCreate(
            [
                'user_id' => $card->user_id,
                'tanggal' => $today,
            ],
            [
                'nfc_card_id' => $card->id,
                'waktu_scan' => now(),
                'status' => 'hadir',
                'keterangan' => 'Scan NFC berhasil',
            ]
        );

        if (!$absensi->wasRecentlyCreated) {
            return response()->json([
                'success' => false,
                'message' => 'User ini sudah absen hari ini.',
                'user' => $card->user,
                'absensi' => $absensi,
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan.',
            'user' => $card->user,
            'absensi' => $absensi,
        ]);
    }

    public function index()
    {
        $absensi = AbsensiNfc::with(['user', 'nfcCard'])
            ->latest('waktu_scan')
            ->get();

        return view('nfc.index', compact('absensi'));
    }
}