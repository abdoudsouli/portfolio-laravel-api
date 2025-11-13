<?php

namespace App\Models;

use App\Models\Projects;
use App\Services\FormatDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project_img extends Model
{
    use SoftDeletes;
    protected $fillable =[
        'path',
        'project_id'
    ];

    public function project()
{
    return $this->belongsTo(Projects::class,'project_id');
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
