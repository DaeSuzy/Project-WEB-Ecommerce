<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTransaction extends Model
{
      use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'booking_trx_id',
        'city',
        'post_code',
        'address',
        'quantity',
        'sub_total_amount',
        'grand_total_amount',
        'discount_amount',
        'is_paid',
        'dompet_id',
        'dompet_size',
        'promo_code_id',
        'proof',
    ];

    public static function generateUniqueTrxId()
    {
        $prefix = 'DD';
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::where('booking_trx_id', $randomString)->exists());
        return $randomString;
    }

    public function dompet(): BelongsTo{
        return $this->belongsTo(Dompet::class, 'dompet_id');
    }

    public function promoCode():BelongsTo{
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }
}
