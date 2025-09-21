<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AidRequestController extends Controller
{
    /**
     * Display a listing of aid requests for admin or authenticated beneficiary.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin يرى جميع طلبات المساعدة
            $requests = AidRequest::all();
        } elseif ($user->role === 'beneficiary') {
            // المستفيد يرى فقط طلباته الخاصة
            $requests = $user->aidRequests;
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($requests);
    }

    /**
     * Store a newly created aid request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'beneficiary') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document_url' => ['nullable', 'string'],
        ]);

        $aidRequest = $user->aidRequests()->create($validatedData);

        return response()->json($aidRequest, 201);
    }

    /**
     * Display the specified aid request.
     */
    public function show(AidRequest $aidRequest)
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->id === $aidRequest->beneficiary_id) {
            return response()->json($aidRequest);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
