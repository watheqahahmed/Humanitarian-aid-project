<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewNotificationMail;

class DistributionController extends Controller
{
    /**
     * Display a listing of distributions.
     */
    public function index()
    {
        $distributions = Distribution::with(['volunteer', 'beneficiary', 'donation'])->get();

        // أضف رابط الملف ليكون صالحاً للعرض
        $distributions->transform(function ($distribution) {
            if ($distribution->proof_file) {
                $distribution->proof_file_url = asset('storage/' . $distribution->proof_file);
            } else {
                $distribution->proof_file_url = null;
            }
            return $distribution;
        });

        return response()->json($distributions);
    }

    /**
     * Store a newly created distribution in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Distribution::class);

        $validatedData = $request->validate([
            'volunteer_id' => [
                'required',
                'exists:users,id',
                Rule::exists('users', 'id')->where('role', 'volunteer')
            ],
            'beneficiary_id' => 'required|exists:users,id',
            'donation_id' => 'required|exists:donations,id',
            'delivery_status' => 'required|in:assigned,in_progress,completed',
        ]);

        $distribution = Distribution::create($validatedData);

        // إشعار للمتطوع
        Notification::create([
            'user_id' => $distribution->volunteer_id,
            'message' => 'تم تعيين مهمة توزيع جديدة لك.',
            'type' => 'new_delivery_assignment',
            'status' => 'unread',
        ]);

        // إشعار لجميع الـ Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم إنشاء توزيع جديد بواسطة المتطوع ID: {$distribution->volunteer_id}",
                'type' => 'distribution_update',
                'status' => 'unread',
            ]);

            // إرسال البريد الإلكتروني (اختياري)
            try {
                Mail::to($admin->email)->send(new NewNotificationMail(
                    "تم إنشاء توزيع جديد بواسطة المتطوع ID: {$distribution->volunteer_id}"
                ));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }

        return response()->json($distribution, 201);
    }

    /**
     * Show the specified distribution.
     */
    public function show(Distribution $distribution)
    {
        $this->authorize('view', $distribution);
        $distribution->load(['volunteer', 'beneficiary', 'donation']);

        if ($distribution->proof_file) {
            $distribution->proof_file_url = asset('storage/' . $distribution->proof_file);
        } else {
            $distribution->proof_file_url = null;
        }

        return response()->json($distribution);
    }

    /**
     * Update the specified distribution in storage.
     */
    public function update(Request $request, Distribution $distribution)
    {
        $this->authorize('update', $distribution);

        $validatedData = $request->validate([
            'delivery_status' => 'required|in:assigned,in_progress,completed',
        ]);

        $distribution->update($validatedData);

        // إشعار لجميع الـ Admins عن تحديث الحالة
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم تحديث حالة التوزيع رقم {$distribution->id} إلى: {$distribution->delivery_status}",
                'type' => 'distribution_update',
                'status' => 'unread',
            ]);

            try {
                Mail::to($admin->email)->send(new NewNotificationMail(
                    "تم تحديث حالة التوزيع رقم {$distribution->id} إلى: {$distribution->delivery_status}"
                ));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }

        return response()->json($distribution);
    }

    /**
     * Remove the specified distribution from storage.
     */
    public function destroy(Distribution $distribution)
    {
        $this->authorize('delete', $distribution);
        $distribution->delete();

        // إشعار للـ Admins عن الحذف
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم حذف التوزيع رقم {$distribution->id}.",
                'type' => 'distribution_delete',
                'status' => 'unread',
            ]);

            try {
                Mail::to($admin->email)->send(new NewNotificationMail(
                    "تم حذف التوزيع رقم {$distribution->id}"
                ));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }

        return response()->json(null, 204);
    }
}
