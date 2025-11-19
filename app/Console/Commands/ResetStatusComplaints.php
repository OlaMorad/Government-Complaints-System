<?php

namespace App\Console\Commands;

use App\Enums\ComplaintStatusEnum;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetStatusComplaints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'complaints:reset';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إعادة الشكاوى لحالة انتظار إذا بقيت قيد المعالجة لمدة أسبوع دون تغيير';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subWeek();

        // جلب الشكاوى قيد المعالجة
        $complaints = Complaint::where('status', ComplaintStatusEnum::IN_PROGRESS->value)
            ->with(['history' => function ($q) {
                $q->latest();
            }])
            ->get()
            ->filter(function ($complaint) use ($oneWeekAgo) {
                $lastHistory = $complaint->history->first();

                // إذا آخر تعديل كان من أسبوع أو أكثر
                return $lastHistory &&
                    $lastHistory->new_status == ComplaintStatusEnum::IN_PROGRESS->value &&
                    $lastHistory->created_at <= $oneWeekAgo;
            });

        foreach ($complaints as $complaint) {

            $oldStatus = $complaint->status;

            $complaint->update([
                'status' => ComplaintStatusEnum::PENDING->value
            ]);

            ComplaintHistory::create([
                'complaint_id' => $complaint->id,
                'changed_by'   => null, // النظام
                'old_status'   => $oldStatus,
                'new_status'   => ComplaintStatusEnum::PENDING->value,
            ]);
        }

        $this->info("تم تحديث {$complaints->count()} شكوى.");
    }
}
