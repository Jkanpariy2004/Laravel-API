<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table= 'users';

    protected $fillable = [
        'firstname',
        'lastname',
        'mobile_no',
        'email',
        'gender',
        'profile_photo',
        'password'
    ];
}
