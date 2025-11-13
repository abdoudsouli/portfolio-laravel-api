<?php
namespace App\Services;

use Illuminate\Support\Carbon;

class FormatDate{

   public static function toHuman($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

}
