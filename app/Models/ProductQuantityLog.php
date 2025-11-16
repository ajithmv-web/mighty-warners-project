<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductQuantityLog extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'old_quantity',
        'new_quantity',
        'change_amount',
        'action',
        'note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function logQuantityChange(
        int $productId,
        int $oldQty,
        int $newQty,
        string $action,
        ?int $userId = null,
        ?int $orderId = null,
        ?string $note = null
    ): void {
        self::create([
            'product_id' => $productId,
            'user_id' => $userId ?? auth()->id(),
            'order_id' => $orderId,
            'old_quantity' => $oldQty,
            'new_quantity' => $newQty,
            'change_amount' => $newQty - $oldQty,
            'action' => $action,
            'note' => $note,
        ]);
    }
}
