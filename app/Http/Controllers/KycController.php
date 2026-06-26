<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KycRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KycController extends Controller
{
    /**
     * Show KYC status page or upload form.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $kycRequest = $user->kycRequest;

        return view('user.kyc', compact('user', 'kycRequest'));
    }

    /**
     * Store uploaded KYC files.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_front' => ['required', 'image', 'max:5120'], // Max 5MB
            'id_back' => ['required', 'image', 'max:5120'],
            'selfie' => ['required', 'image', 'max:5120'],
        ]);

        $user = $request->user();

        // Save files
        $idFrontPath = $request->file('id_front')->store('kyc', 'public');
        $idBackPath = $request->file('id_back')->store('kyc', 'public');
        $selfiePath = $request->file('selfie')->store('kyc', 'public');

        // Create or update KYC request
        KycRequest::updateOrCreate(
            ['user_id' => $user->id],
            [
                'id_front_path' => $idFrontPath,
                'id_back_path' => $idBackPath,
                'selfie_path' => $selfiePath,
                'status' => 'pending',
                'note' => null,
            ]
        );

        // Update user KYC status
        $user->update([
            'status_kyc' => 'pending'
        ]);

        return redirect()->route('kyc.index')->with('status', 'KYC documents uploaded successfully. Awaiting admin review.');
    }
}
