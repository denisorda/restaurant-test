<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App
 * @property integer $id
 * @property integer $order_id
 * @property integer $dish_id
 * @property integer $cnt
 * @property integer $status
 * @property integer $time
 *
 */
class OrderItem extends Model
{
    protected $table="order_item";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'order_id', 'dish_id', 'cnt', 'status', 'time', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function menu()
    {
        return $this->hasOne(Menu::class, 'id', 'dish_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
