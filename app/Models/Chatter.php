<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatter extends Model
{
    protected $fillable = ['username', 'email', 'password','status'];
    use HasFactory;
}
