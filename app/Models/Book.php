<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Book
 *
 * @property int $id
 * @property string $name
 * @property string $author
 * @property string $publisher
 * @property string $category
 * @property string|null $edition
 * @property string $ISBN
 * @property float $price
 * @property int $num
 * @property int $lend
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bill[] $bills
 * @property-read int|null $bills_count
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Query\Builder|Book onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereEdition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereISBN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereLend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Book withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Book withoutTrashed()
 * @mixin \Eloquent
 * @method static \Database\Factories\BookFactory factory(...$parameters)
 */
class Book extends Model
{

    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'bid');
    }

}
