<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 * @property integer $id
 * @property integer $role
 * @property string $name
 * @property string $password
 * @property integer $is_active
 * @property string $remember_token
 *
 */
class User extends Authenticatable
{

    protected $table="user";
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'role', 'name', 'password', 'is_active', 'remember_token', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roleName()
    {
        return $this->hasOne(UserRole::class, 'id', 'role');
    }
}
