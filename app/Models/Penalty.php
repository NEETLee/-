<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Penalty
 *
 * @property int $id
 * @property float $money
 * @property int $bill_id
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bill $bill
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty query()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Penalty extends Model
{
    protected $guarded = [];

    const TYPE_BALANCE = 0;

    const TYPE_DEPOSIT = 1;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
