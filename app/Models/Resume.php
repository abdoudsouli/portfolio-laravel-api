<?php

namespace App\Models;

use App\Models\User;
use App\Services\FormatDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
  use SoftDeletes;
  protected $fillable=[
    'start_date',
    'end_date',
    'title',
    'company',
    'description',
    'user_id'
  ];

  public function user(){
    return $this->belongsTo(User::class,'user_id');
  }

     protected $appends = ['created_at_human','updated_at_human'];

  public function getCreatedAtHumanAttribute()
    {
        $fromatdate = new FormatDate();
        return $fromatdate->toHuman($this->created_at);
    }

  public function getUpdatedAtHumanAttribute()
    {
        $fromatdate = new FormatDate();
        return $fromatdate->toHuman($this->updated_at);
    }
}
