<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use App\Models\Notification; // إضافة Notification
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

        $oldStatus = $aidRequest->status; // حفظ الحالة القديمة
        $aidRequest->update($validatedData);

        // إذا تغيرت الحالة، أنشئ إشعار للمستفيد
        if ($oldStatus !== $aidRequest->status) {
            $message = "تم تحديث حالة طلب المساعدة رقم " . $aidRequest->id . " إلى: " . $aidRequest->status;
            Notification::create([
                'user_id' => $aidRequest->beneficiary_id,
                'message' => $message,
                'type' => 'status_update',
                'status' => 'unread',
            ]);
        }

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
