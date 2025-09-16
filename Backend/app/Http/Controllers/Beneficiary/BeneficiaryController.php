<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{
    /**
     * Show distribution details for a specific aid request.
     */
    public function showDistributionDetails(AidRequest $aidRequest)
    {
        if ($aidRequest->beneficiary_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $distribution = $aidRequest->distribution()->with('volunteer')->first();

        return response()->json($distribution);
    }
}
