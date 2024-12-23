<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logActivity')) {
    function logActivity($activity)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'store_id' => Auth::user()->store_id,
            'activity' => $activity,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
