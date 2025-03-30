<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'stripe_payment_id',
        'payment_intent_id',
        'status',
        'amount',
        'currency',
        'payment_method',
        'metadata'
    ];

}
