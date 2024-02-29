<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;

class PresenceController extends Controller
{
    public function generateQRCode()
{
    $user = auth()->user();

    // Créer un tableau avec les informations nécessaires
    $qrData = [
        'user_id' => $user->id
    ];

    // Convertir le tableau en une chaîne JSON et générer le QR Code
    $qrCode = QrCode::size(300)->generate(json_encode($qrData));

    return view('presence.qrcode', compact('qrCode', 'qrData'));
}



public function checkIn(Request $request)
{
    $validator = Validator::make($request->all(), [
        'userId' => 'required|exists:users,id'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    if (auth()->check()) {
        $userId = $request->input('userId');

        // Vérifier si l'utilisateur a déjà marqué sa présence aujourd'hui
        $existingPresence = Presence::where('user_id', $userId)->whereDate('check_in', now()->toDateString())->whereNull('check_out')->first();

        if (!$existingPresence) {
            Presence::create([
                'user_id' => $userId,
                'check_in' => now(),
            ]);

            return view('dashboard')->with('message', 'Check-in successful');
        }

        return view('dashboard')->with('message', 'User has already checked in today');
    }

    return response()->json(['message' => 'Authentication failed'], 401);
}


public function checkOut($userId)
{
    $user = User::find($userId);

    if (!$user) {
        return view('dashboard')->with('message' ,'User not found');
    }

    $presence = Presence::where('user_id', $userId)
        ->whereDate('check_in', now()->toDateString())
        ->whereNull('check_out')
        ->first();

    if ($presence) {
        $presence->update(['check_out' => now()]);

        return view('dashboard')->with('message' ,'Check-out successful');
    }

    return response()->json(['message' => 'No active check-in found for the user today'], 400);
}




}
