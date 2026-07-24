<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public static function log(string $action, ?string $modelName = null, ?int $modelId = null, ?array $details = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $modelName,
            'model_id' => $modelId,
            'details' => $details ? json_encode($details) : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
