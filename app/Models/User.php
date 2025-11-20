<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Projects;
use App\Services\FormatDate;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'date_birth',
        'address',
        'phone',
        'abdout_me',
        'avatar',
        'cvfile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function user(){
        return $this->hasMany(Projects::class,'user_id');
    }

    public function user_resume(){
        return $this->hasMany(Resume::class,'user_id');
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
