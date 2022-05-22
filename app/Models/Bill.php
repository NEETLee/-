<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Bill
 *
 * @property int $id
 * @property string $bill_no
 * @property int $mid
 * @property int $bid
 * @property int $delay
 * @property float|null $money
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon $ended_at
 * @property int $duration
 * @property int $return
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Book $book
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\Penalty|null $penalty
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereBid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereBillNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereMid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereReturn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bill extends Model
{

    const RETURN_YES = 1;
    const RETURN_NO = 0;

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime:Y-m-d H:i:s',
        'ended_at'   => 'date:Y-m-d'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'mid');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'bid');
    }

    public function penalty()
    {
        return $this->hasOne(Penalty::class, 'bill_id');
    }
}
