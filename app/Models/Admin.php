<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $guard = 'admin';

    protected $table = 'administrations';

    protected $fillable = [
        'id', 'name', 'email', 'password', 'remember_token',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $timestamps = true;
}
