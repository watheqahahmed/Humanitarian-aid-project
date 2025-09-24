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

        $this->notifyAdmins("تم إضافة تبرع جديد: {$donation->type}, الكمية: {$donation->quantity}", 'donation_update');

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
     * Update the specified donation (Admin only) — full update.
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

        $this->notifyAdmins("تم تحديث التبرع رقم {$donation->id}: الحالة الآن {$donation->status}", 'donation_update');

        return response()->json($donation);
    }

    /**
     * Update only the status of a donation (Admin only).
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        $this->authorize('update', $donation);

        $validatedData = $request->validate([
            'status' => 'required|in:pending,approved,denied,distributed,received',
        ]);

        $donation->status = $validatedData['status'];
        $donation->save();

        $this->notifyAdmins("تم تحديث التبرع رقم {$donation->id}: الحالة الآن {$donation->status}", 'donation_update');

        return response()->json([
            'message' => 'تم تحديث الحالة بنجاح',
            'donation' => $donation,
        ]);
    }

    /**
     * Remove the specified donation (Admin only).
     */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);
        $donation->delete();

        $this->notifyAdmins("تم حذف التبرع رقم {$donation->id}", 'donation_delete');

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

        $this->notifyAdmins("تم استلام تبرع جديد من زائر: {$donation->type}, الكمية: {$donation->quantity}", 'donation_public');

        return response()->json([
            'message' => 'تم استلام التبرع بنجاح',
            'donation' => $donation,
        ], 201);
    }

    /**
     * Helper: Notify all admins with message and type
     */
    private function notifyAdmins(string $message, string $type)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => $message,
                'type' => $type,
                'status' => 'unread',
            ]);

            try {
                Mail::to($admin->email)->send(new NewNotificationMail($message));
            } catch (\Exception $e) {
                \Log::error("Mail error for admin {$admin->email}: {$e->getMessage()}");
            }
        }
    }
}
