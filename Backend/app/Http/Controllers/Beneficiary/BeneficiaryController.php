<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use App\Models\User; // لإحضار المستفيدين
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{
    /**
     * Show distribution details for a specific aid request.
     */
    public function showDistributionDetails(AidRequest $aidRequest)
    {
        // التحقق من أن الطلب ينتمي للمستفيد الحالي
        if ($aidRequest->beneficiary_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // جلب بيانات التوزيع المرتبطة بالطلب مع بيانات المتطوع
        $distribution = $aidRequest->distribution()->with('volunteer')->first();

        return response()->json($distribution);
    }

    /**
     * Return all beneficiaries (for admin).
     */
    public function index()
    {
        // نفترض أن المستفيدين لديهم role = 'beneficiary'
        $beneficiaries = User::where('role', 'beneficiary')->get();

        return response()->json($beneficiaries);
    }
}
