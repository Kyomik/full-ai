<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportDownload extends Model
{
    protected $fillable = [
        'user_id',
        'report_type',
        'period_from',
        'period_until',
        'status',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];
}
