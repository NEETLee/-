<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 * App\Models\Member
 *
 * @property int $id
 * @property string $name
 * @property int $age
 * @property string|null $profession
 * @property int $gender
 * @property string $telephone
 * @property float $deposit
 * @property float $balance
 * @property string $password
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bill[] $bills
 * @property-read int|null $bills_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\BookFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Member extends Authenticatable
{

    use Notifiable, HasFactory;

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    protected $fillable = ['name', 'age', 'profession', 'gender'];

    protected $hidden = ['id', 'password'];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'mid');
    }

}
