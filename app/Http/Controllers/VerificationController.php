<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\OtpMail;

class VerificationController extends Controller
{
    public function index(){
        return view('verification.index');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $user = Auth::user(); 

        
        if ($user->otp == $request->otp) {
            
            User::where('id', $user->id)->update([
                'status' => 'active',
                'otp' => null 
            ]);

            return redirect('/home')->with('success', 'Hore! Verifikasi berhasil!');
        }

       return back()->with('error', 'Yah, Kode OTP yang Anda masukkan salah.');
    }

    public function sendOtp()
    {
        $user = Auth::user();
        $otp = rand(100000, 999999);

        User::where('id', $user->id)->update(['otp' => $otp]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return back()->with('success', 'Kode OTP telah dikirim ke email Anda!');
    }
}
