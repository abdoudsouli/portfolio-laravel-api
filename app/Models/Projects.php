<?php

namespace App\Models;

use App\Models\User;
use App\Models\Project_img;
use App\Services\FormatDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use SoftDeletes;
   protected $fillable=[
    'name',
    'profile',
    'type',
    'user_id',
   ];

   public function imgs()
{
    return $this->hasMany(Project_img::class, 'project_id');
}

   public function user()
{
    return $this->belongsTo(User::class, 'user_id');
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
