<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderItem> $items
 */
class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_amount',
        'total',
        'coupon_id',
        'coupon_code',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address_line1',
        'shipping_address_line2',
        'shipping_city',
        'shipping_state',
        'shipping_postal',
        'shipping_country',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Coupon, $this> */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /** @return HasMany<OrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @return HasOne<Payment, $this> */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /** @return HasOne<Refund, $this> */
    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }

    public function statusEnum(): OrderStatus
    {
        return OrderStatus::from($this->status);
    }

    public function canBeCancelledByCustomer(): bool
    {
        if ($this->status === OrderStatus::Cancelled->value) {
            return false;
        }

        if (in_array($this->status, [OrderStatus::Shipped->value, OrderStatus::Delivered->value], true)) {
            return false;
        }

        $paymentStatus = $this->payment?->status;

        if ($paymentStatus === PaymentStatus::Refunded->value) {
            return false;
        }

        if ($paymentStatus === PaymentStatus::Pending->value) {
            return $this->status === OrderStatus::Pending->value;
        }

        return in_array($this->status, [OrderStatus::Placed->value, OrderStatus::Processing->value], true)
            && $paymentStatus === PaymentStatus::Paid->value;
    }

    public function canBeRefundedByAdmin(): bool
    {
        return $this->status === OrderStatus::Cancelled->value
            && $this->payment?->status === PaymentStatus::Paid->value
            && ! $this->refund()->exists();
    }
}
