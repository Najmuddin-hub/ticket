<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['name', 'report_type_id'];

    public function reportType()
    {
        return $this->belongsTo(ReportType::class);
    }
}
