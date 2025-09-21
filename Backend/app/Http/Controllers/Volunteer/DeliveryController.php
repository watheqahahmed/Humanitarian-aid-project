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
        $this->authorize('viewAny', Distribution::class);

        // جلب التوزيعات الخاصة بالمتطوع مع العلاقات
        $deliveries = Auth::user()
            ->assignedDeliveries()
            ->with(['beneficiary', 'donation'])
            ->get();

        return response()->json($deliveries);
    }

    /**
     * Update the specified delivery in storage.
     * يدعم رفع ملف الإثبات مع تغيير الحالة إلى completed أو in_progress
     */
    public function update(Request $request, Distribution $distribution)
    {
        $this->authorize('update', $distribution);

        // دعم قبول جميع أنواع الملفات الشائعة + docx
        $validatedData = $request->validate([
            'delivery_status' => 'required|in:in_progress,completed',
            'proof_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120', // 5MB
        ]);

        // رفع الملف إذا تم إرفاقه
        if ($request->hasFile('proof_file')) {
            // تخزين الملف في storage/app/public/proofs
            $path = $request->file('proof_file')->store('proofs', 'public');
            $validatedData['proof_file'] = $path;
        }

        // تحديث التوزيع
        $distribution->update($validatedData);

        // إعادة التوزيع مع العلاقات لتحسين الاستجابة
        return response()->json(
            $distribution->load(['beneficiary', 'donation'])
        );
    }
}
