<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogHelper
{
    public static function log($activity, $model = null, $new_data = [], $old_data = [])
    {
        $properties = [
            'new' => $new_data,
            'old' => $old_data
        ];

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'properties' => $properties,
            'ip_address' => Request::ip()
        ]);
    }
}
