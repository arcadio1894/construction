<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralGuideDetail extends Model
{
    protected $fillable = [
        'referral_guide_id',
        'quote_id',
        'material_id',
        'quantity',
    ];

    public function guide()
    {
        return $this->belongsTo('App\ReferralGuide');
    }

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }

    public function material()
    {
        return $this->belongsTo('App\Material');
    }
}
