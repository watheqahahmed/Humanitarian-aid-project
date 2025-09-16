<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the authenticated volunteer's assigned deliveries.
     */
    public function index()
    {
        $this->authorize('viewAny', Distribution::class); // Authorization
        $deliveries = Auth::user()->assignedDeliveries;
        return response()->json($deliveries);
    }

    /**
     * Update the specified delivery in storage.
     */
    public function update(Request $request, Distribution $distribution)
    {
        $this->authorize('update', $distribution); // Authorization

        $validatedData = $request->validate([
            'delivery_status' => 'required|in:in_progress,completed',
            'proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // تحقق مما إذا كان هناك ملف مرفوع
        if ($request->hasFile('proof_file')) {
            // قم بتخزين الملف في مجلد public/proofs
            $path = $request->file('proof_file')->store('proofs', 'public');
            $validatedData['proof_file'] = $path;
        }

        $distribution->update($validatedData);

        return response()->json($distribution);
    }
}
