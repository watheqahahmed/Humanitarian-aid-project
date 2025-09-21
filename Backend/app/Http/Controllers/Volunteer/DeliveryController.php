<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    /**
     * عرض جميع التوزيعات المكلفة للمتطوع المصادق عليه
     */
    public function index()
    {
        $this->authorize('viewAny', Distribution::class);

        $deliveries = Auth::user()
            ->assignedDeliveries()
            ->with(['beneficiary', 'donation'])
            ->get();

        return response()->json($deliveries);
    }

    /**
     * تحديث التوزيع المحدد مع إشعار الـ Admin عند رفع ملف إثبات أو تغيير الحالة
     */
    public function update(Request $request, Distribution $distribution)
    {
        $this->authorize('update', $distribution);

        $validatedData = $request->validate([
            'delivery_status' => 'required|in:in_progress,completed',
            'proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
        ]);

        // رفع الملف إذا تم إرفاقه
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proofs', 'public');
            $validatedData['proof_file'] = $path;
        }

        // حفظ الحالة القديمة لتحديد ما تم تغييره
        $oldStatus = $distribution->delivery_status;
        $oldProof = $distribution->proof_file;

        // تحديث التوزيع
        $distribution->update($validatedData);

        // إشعار الـ Admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $message = "تم تحديث التوزيع رقم #{$distribution->id} من قبل المتطوع {$distribution->volunteer->name}.";

            if ($oldStatus !== $distribution->delivery_status) {
                $message .= " الحالة تغيرت إلى: {$distribution->delivery_status}.";
            }

            if ($oldProof !== $distribution->proof_file && isset($validatedData['proof_file'])) {
                $message .= " تم رفع ملف إثبات جديد.";
            }

            Notification::create([
                'user_id' => $admin->id,
                'message' => $message,
                'type' => 'delivery_update',
                'status' => 'unread',
            ]);
        }

        return response()->json(
            $distribution->load(['beneficiary', 'donation'])
        );
    }
}
