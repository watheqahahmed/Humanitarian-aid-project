<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use Illuminate\Http\Request;

class AidRequestController extends Controller
{
    /**
     * Display a listing of the aid requests.
     */
    public function index()
    {
        $this->authorize('viewAny', AidRequest::class); // Authorization
        $requests = AidRequest::with('beneficiary')->get();
        return response()->json($requests);
    }

    /**
     * Show the specified aid request.
     */
    public function show(AidRequest $aidRequest)
    {
        $this->authorize('view', $aidRequest); // Authorization
        $aidRequest->load('beneficiary');
        return response()->json($aidRequest);
    }

    /**
     * Update the specified aid request.
     */
    public function update(Request $request, AidRequest $aidRequest)
    {
        $this->authorize('update', $aidRequest); // Authorization

        $validatedData = $request->validate([
            'status' => 'required|in:pending,approved,denied',
        ]);

        $aidRequest->update($validatedData);

        return response()->json($aidRequest);
    }

    /**
     * Remove the specified aid request.
     */
    public function destroy(AidRequest $aidRequest)
    {
        $this->authorize('delete', $aidRequest); // Authorization
        $aidRequest->delete();

        return response()->json(null, 204);
    }
}
