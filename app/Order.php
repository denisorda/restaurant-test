<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App
 * @property integer $id
 * @property integer $table
 * @property integer $waiter
 * @property string $status
 * @property string $time
 * @property integer $is_pay
 *
 */
class Order extends Model
{
    protected $table="order";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'table', 'waiter', 'status', 'created_at', 'updated_at', 'time', 'is_pay'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'waiter');
    }
}
