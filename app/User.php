<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MiResetPasswordPersonalizado;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'email', 'password', 'edad','apellido','cedula','path', 'estado',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
public function sendPasswordResetNotification($token)
{
   $this->notify(new MiResetPasswordPersonalizado($token));
}
public function roles(){
    return $this->belongsToMany(Rol::class, 'user_rol', 'user_id', 'rol_id');
}
}
