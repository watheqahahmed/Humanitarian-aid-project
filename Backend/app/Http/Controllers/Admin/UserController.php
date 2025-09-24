<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * جلب جميع المستخدمين
     */
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * إنشاء مستخدم جديد
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,beneficiary,volunteer'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role']
        ]);

        return response()->json($user, 201);
    }

    /**
     * تحديث بيانات مستخدم محدد
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role'  => 'sometimes|in:admin,beneficiary,volunteer'
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * حذف مستخدم
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(null, 204);
    }

    /**
     * جلب جميع المتطوعين
     */
    public function volunteers(): JsonResponse
    {
        $volunteers = User::where('role', 'volunteer')->get(['id', 'name']);
        return response()->json($volunteers);
    }

    /**
     * جلب جميع المستفيدين
     */
    public function beneficiaries(): JsonResponse
    {
        $beneficiaries = User::where('role', 'beneficiary')->get(['id', 'name']);
        return response()->json($beneficiaries);
    }

    /**
     * جلب مستخدم محدد
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * تغيير كلمة السر
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user(); // المستخدم الحالي

        // التحقق من كلمة السر القديمة
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'كلمة السر الحالية غير صحيحة'], 400);
        }

        // حفظ كلمة السر الجديدة
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'تم تغيير كلمة السر بنجاح']);
    }
}
