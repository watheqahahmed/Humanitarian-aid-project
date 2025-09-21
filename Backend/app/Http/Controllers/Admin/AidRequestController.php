<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewNotificationMail;

class AidRequestController extends Controller
{
    /**
     * Display a listing of the aid requests.
     */
    public function index()
    {
        $this->authorize('viewAny', AidRequest::class);
        $requests = AidRequest::with('beneficiary')->get();
        return response()->json($requests);
    }

    /**
     * Show the specified aid request.
     */
    public function show(AidRequest $aidRequest)
    {
        $this->authorize('view', $aidRequest);
        $aidRequest->load('beneficiary');
        return response()->json($aidRequest);
    }

    /**
     * Update the specified aid request.
     */
    public function update(Request $request, AidRequest $aidRequest)
    {
        $this->authorize('update', $aidRequest);

        $validatedData = $request->validate([
            'status' => 'required|in:pending,approved,denied',
        ]);

        $oldStatus = $aidRequest->status;
        $aidRequest->update($validatedData);

        // إذا تغيرت الحالة، إنشاء إشعار للمستفيد + إشعار للـ Admin
        if ($oldStatus !== $aidRequest->status) {
            $message = "تم تحديث حالة طلب المساعدة رقم {$aidRequest->id} إلى: {$aidRequest->status}";

            // إشعار للمستفيد
            Notification::create([
                'user_id' => $aidRequest->beneficiary_id,
                'message' => $message,
                'type' => 'status_update',
                'status' => 'unread',
            ]);

            // إشعار لجميع الـ Admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'message' => "تم تحديث حالة طلب المساعدة رقم {$aidRequest->id} من قبل المسؤول.",
                    'type' => 'status_update',
                    'status' => 'unread',
                ]);

                // إرسال بريد إلكتروني للـ Admin
                try {
                    Mail::to($admin->email)->send(new NewNotificationMail("تم تحديث حالة طلب المساعدة رقم {$aidRequest->id}"));
                } catch (\Exception $e) {
                    \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
                }
            }
        }

        return response()->json($aidRequest);
    }

    /**
     * Remove the specified aid request.
     */
    public function destroy(AidRequest $aidRequest)
    {
        $this->authorize('delete', $aidRequest);
        $aidRequest->delete();

        return response()->json(null, 204);
    }
}
