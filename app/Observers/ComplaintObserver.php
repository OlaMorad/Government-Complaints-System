<?php

namespace App\Observers;

use App\http\Services\FirebaseNotificationService;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use Illuminate\Support\Facades\Auth;

class ComplaintObserver
{
    /**
     * Handle the Complaint "created" event.
     */
    public function created(Complaint $complaint): void
    {
        //
    }

    /**
     * Handle the Complaint "updated" event.
     */
    public function updated(Complaint $complaint): void
    {
        if ($complaint->isDirty('status')) {

            ComplaintHistory::create([
                'complaint_id' => $complaint->id,
                'changed_by'   => Auth::id(),
                'old_status'   => $complaint->getOriginal('status'),
                'new_status'   => $complaint->status,
             //   'notes'        => 'تم تغيير حالة الشكوى من النظام تلقائياً.'
            ]);
            //  إرسال إشعار لصاحب الشكوى
            $notificationService = new FirebaseNotificationService();

            $title = "تم تحديث حالـة الشكوى";
            $body  = "تم تغيير حالة الشكوى الخاصة بك من "
                . $complaint->getOriginal('status') . " إلى " . $complaint->status;

            $notificationService->sendToUser(
                $complaint->user_id, // صاحب الشكوى
                $title,
                $body,
                [
                    'complaint_id' =>$complaint->id,
                    'new_status'   =>$complaint->status
                ]
            );
        }
    }

    /**
     * Handle the Complaint "deleted" event.
     */
    public function deleted(Complaint $complaint): void
    {
        //
    }

    /**
     * Handle the Complaint "restored" event.
     */
    public function restored(Complaint $complaint): void
    {
        //
    }

    /**
     * Handle the Complaint "force deleted" event.
     */
    public function forceDeleted(Complaint $complaint): void
    {
        //
    }
}
