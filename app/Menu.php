<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App
 * @property integer $id
 * @property string $name
 * @property integer $price
 * @property string $time
 * @property integer $is_deleted
 *
 */
class Menu extends Model
{
    protected $table="menu";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'price', 'time', 'is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
