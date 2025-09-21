<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Models\AidRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AidRequestController extends Controller
{
    /**
     * عرض جميع طلبات المساعدة للـ Admin أو للمستفيد المصادق عليه
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $requests = AidRequest::all();
        } elseif ($user->role === 'beneficiary') {
            $requests = $user->aidRequests;
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($requests);
    }

    /**
     * إنشاء طلب مساعدة جديد
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'beneficiary') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document_url' => ['nullable', 'string'],
        ]);

        // إنشاء طلب المساعدة
        $aidRequest = $user->aidRequests()->create($validatedData);

        // إنشاء إشعار لكل Admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => "تم إنشاء طلب مساعدة جديد بواسطة {$user->name} (#{$aidRequest->id})",
                'type' => 'new_aid_request',
                'status' => 'unread',
            ]);
        }

        return response()->json($aidRequest, 201);
    }

    /**
     * عرض طلب مساعدة محدد
     */
    public function show(AidRequest $aidRequest)
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->id === $aidRequest->beneficiary_id) {
            return response()->json($aidRequest);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
