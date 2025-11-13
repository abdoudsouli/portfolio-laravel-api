<?php

namespace App\Models;

use App\Services\FormatDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skills extends Model
{
    use SoftDeletes;
   protected $fillable=[
    'name',
    'level',
   ];

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
