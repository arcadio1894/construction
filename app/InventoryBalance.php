<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryBalance extends Model
{
    protected $fillable = [
        'executed_at',
        'user_id',
        'total_materials',
        'total_entries',
        'total_outputs',
        'excel_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
