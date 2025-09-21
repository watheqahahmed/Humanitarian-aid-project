<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewNotificationMail;

class DonationController extends Controller
{
    /**
     * Display a listing of the donations (Admin only).
     */
    public function index()
    {
        $this->authorize('viewAny', Donation::class);
        $donations = Donation::all();
        return response()->json($donations);
    }

    /**
     * Store a newly created donation (Admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Donation::class);

        $validatedData = $request->validate([
            'donor_name' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,received,distributed',
        ]);

        $donation = Donation::create($validatedData);

        // إشعار لجميع الـ Admins عند إضافة تبرع جديد
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم إضافة تبرع جديد: {$donation->type}, الكمية: {$donation->quantity}",
                'type' => 'donation_update',
                'status' => 'unread',
            ]);

            try {
                Mail::to($admin->email)->send(new NewNotificationMail(
                    "تم إضافة تبرع جديد: {$donation->type}, الكمية: {$donation->quantity}"
                ));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }

        return response()->json($donation, 201);
    }

    /**
     * Display the specified donation (Admin only).
     */
    public function show(Donation $donation)
    {
        $this->authorize('view', $donation);
        return response()->json($donation);
    }

    /**
     * Update the specified donation (Admin only).
     */
    public function update(Request $request, Donation $donation)
    {
        $this->authorize('update', $donation);

        $validatedData = $request->validate([
            'donor_name' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,received,distributed',
        ]);

        $donation->update($validatedData);

        // إشعار جميع الـ Admins عند تحديث التبرع
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم تحديث التبرع رقم {$donation->id}: الحالة الآن {$donation->status}",
                'type' => 'donation_update',
                'status' => 'unread',
            ]);

            try {
                Mail::to($admin->email)->send(new NewNotificationMail(
                    "تم تحديث التبرع رقم {$donation->id}: الحالة الآن {$donation->status}"
                ));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }

        return response()->json($donation);
    }

    /**
     * Remove the specified donation (Admin only).
     */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);
        $donation->delete();

        // إشعار جميع الـ Admins عند حذف التبرع
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم حذف التبرع رقم {$donation->id}",
                'type' => 'donation_delete',
                'status' => 'unread',
            ]);

            try {
                Mail::to($admin->email)->send(new NewNotificationMail(
                    "تم حذف التبرع رقم {$donation->id}"
                ));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }

        return response()->json(null, 204);
    }

    /**
     * Store a new donation from any visitor (Public access).
     */
    public function storePublic(Request $request)
    {
        $validatedData = $request->validate([
            'donor_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $validatedData['status'] = 'pending'; // الحالة الافتراضية

        $donation = Donation::create($validatedData);

        // إشعار جميع الـ Admins عند استقبال تبرع عام
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم استلام تبرع جديد من زائر: {$donation->type}, الكمية: {$donation->quantity}",
                'type' => 'donation_public',
                'status' => 'unread',
            ]);

            try {
                Mail::to($admin->email)->send(new NewNotificationMail(
                    "تم استلام تبرع جديد من زائر: {$donation->type}, الكمية: {$donation->quantity}"
                ));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }

        return response()->json([
            'message' => 'تم استلام التبرع بنجاح',
            'donation' => $donation,
        ], 201);
    }
}
