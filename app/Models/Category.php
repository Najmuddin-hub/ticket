<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'category_id'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function reportTypes()
    {
        return $this->hasMany(ReportType::class);
    }
}
