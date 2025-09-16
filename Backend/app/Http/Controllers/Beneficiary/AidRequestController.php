<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AidRequestController extends Controller
{
    /**
     * Display a listing of the authenticated beneficiary's aid requests.
     */
    public function index()
    {
        $this->authorize('viewAny', AidRequest::class); // Authorization
        $requests = Auth::user()->aidRequests;
        return response()->json($requests);
    }

    /**
     * Store a newly created aid request.
     */
    public function store(Request $request)
    {
        $this->authorize('create', AidRequest::class); // Authorization

        $validatedData = $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document_url' => ['nullable', 'string'],
        ]);

        $aidRequest = Auth::user()->aidRequests()->create($validatedData);

        return response()->json($aidRequest, 201);
    }

    /**
     * Display the specified aid request.
     */
    public function show(AidRequest $aidRequest)
    {
        $this->authorize('view', $aidRequest); // Authorization
        return response()->json($aidRequest);
    }
}
