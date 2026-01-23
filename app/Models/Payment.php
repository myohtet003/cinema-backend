<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['booking_id', 'payment_method_id', 'transaction_id', 'amount', 'status'];

    public function paymentMethod() 
    {
        // This links payment_method_id to the PaymentMethod table
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function booking() 
    {
        return $this->belongsTo(Booking::class);
    }
}
