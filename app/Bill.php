<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
   protected $fillable = ['user_id','bill_date','amount'];
       public function user(){
         return $this->belongsTo('\App\User');
       }

       public function payment(){
       	return $this->hasMany('\App\Payment');
       }
}
