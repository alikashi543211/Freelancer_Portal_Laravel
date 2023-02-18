<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreelancerAccount extends Model
{
    protected $fillable = ['name', 'app_id', 'app_secret', 'access_token'];
}
