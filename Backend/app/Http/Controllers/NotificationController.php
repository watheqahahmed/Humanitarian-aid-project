<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewNotificationMail;

class NotificationController extends Controller
{
    /**
     * عرض الإشعارات للمستخدم الحالي
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();
        return response()->json($notifications);
    }

    /**
     * تعليم إشعار معين كمقروء
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['status' => 'read']);

        return response()->json($notification);
    }

    /**
     * إنشاء إشعار وإرساله لجميع المستخدمين أو حسب الدور
     */
    public function createNotification($message, $roles = null, $type = 'success')
    {
        // جلب المستخدمين حسب الدور، أو جميعهم إذا لم يحدد دور
        $users = $roles ? User::whereIn('role', (array)$roles)->get() : User::all();

        foreach ($users as $user) {
            // إنشاء الإشعار في قاعدة البيانات
            $notification = Notification::create([
                'user_id' => $user->id,
                'message' => $message,
                'type' => $type,
                'status' => 'unread',
            ]);

            // إرسال البريد لكل مستخدم مع حماية من الأخطاء
            try {
                Mail::to($user->email)->send(new NewNotificationMail($notification->message));
            } catch (\Exception $e) {
                \Log::error('Mail error for user '.$user->email.': '.$e->getMessage());
            }
        }

        return response()->json(['message' => 'Notification sent successfully.']);
    }

    /**
     * إشعار مسؤول عند قيام متطوع برفع إثبات
     */
    public function notifyAdminAboutVolunteerProof($volunteer)
    {
        $message = "قام المتطوع {$volunteer->name} برفع إثبات جديد.";
        // فقط للإدمن
        return $this->createNotification($message, ['admin'], 'info');
    }

    /**
     * إشعار المتطوعين عند وجود طلب جديد من مستفيد
     */
    public function notifyVolunteersAboutRequest($beneficiary)
    {
        $message = "المستفيد {$beneficiary->name} قدم طلب مساعدة جديد.";
        // فقط للمتطوعين
        return $this->createNotification($message, ['volunteer'], 'info');
    }
}
