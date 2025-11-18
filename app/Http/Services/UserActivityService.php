<?php
namespace app\Http\Services;

use App\Models\UserActivity;

class UserActivityService{
    public function add_activity($user_id,$activity){

        UserActivity::create([
            'user_id' => $user_id,
            'activity' => $activity,
        ]);
        }
}
