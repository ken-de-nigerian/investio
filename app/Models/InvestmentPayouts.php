<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPayouts extends Model
{
    public function user_investment()
    {
        return $this->belongsTo(UserInvestment::class);
    }
}
