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
        $query = $roles ? User::whereIn('role', (array)$roles) : User::query();

        // نستخدم chunk لتقليل استهلاك الذاكرة
        $query->chunk(50, function($users) use ($message, $type) {
            foreach ($users as $user) {
                // إنشاء الإشعار في قاعدة البيانات
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'message' => $message,
                    'type' => $type,
                    'status' => 'unread',
                ]);

                // إرسال البريد في الخلفية باستخدام queue
                Mail::to($user->email)->queue(new NewNotificationMail($notification->message));
            }
        });

        return response()->json(['message' => 'Notification queued successfully.']);
    }

    /**
     * إشعار مسؤول عند قيام متطوع برفع إثبات
     */
    public function notifyAdminAboutVolunteerProof($volunteer)
    {
        $message = "قام المتطوع {$volunteer->name} برفع إثبات جديد.";
        return $this->createNotification($message, ['admin'], 'info');
    }

    /**
     * إشعار المتطوعين عند وجود طلب جديد من مستفيد
     */
    public function notifyVolunteersAboutRequest($beneficiary)
    {
        $message = "المستفيد {$beneficiary->name} قدم طلب مساعدة جديد.";
        return $this->createNotification($message, ['volunteer'], 'info');
    }
}
