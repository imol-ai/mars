<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Transaction
 *
 * @property PaymentType $type
 * @property User $receiver
 * @property Checkout $checkout
 * @property User $payer
 * @property File|null $receipt
 * @property int $id
 * @property int $checkout_id
 * @property int|null $receiver_id
 * @property int|null $payer_id
 * @property int $semester_id
 * @property int $amount
 * @property int $payment_type_id
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $moved_to_checkout
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Semester $semester
 * @method static \Database\Factories\TransactionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|Transaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCheckoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMovedToCheckout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Transaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Transaction withoutTrashed()
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'checkout_id',
        'receiver_id',
        'payer_id',
        'semester_id',
        'amount',
        'payment_type_id',
        'comment',
        'moved_to_checkout',
        'paid_at',
    ];

    protected $dates = ['moved_to_checkout', 'paid_at'];

    public function receiver()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function payer()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function checkout()
    {
        return $this->belongsTo('App\Models\Checkout');
    }

    public function semester()
    {
        return $this->belongsTo('App\Models\Semester');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\PaymentType', 'payment_type_id');
    }

    /**
     * @return string the comment for income/expenses, or the transaction type for other transactions
     */
    public function getCommentAttribute($value)
    {
        if (in_array($this->type->name, [PaymentType::INCOME, PaymentType::EXPENSE])) {
            return $value;
        }
        return __('checkout.'.$this->type->name);
    }

    /**
     * The receipt uploaded for the transaction, if there is one.
     * @return HasOne
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(File::class);
    }
}
