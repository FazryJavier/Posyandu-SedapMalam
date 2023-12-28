<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOrangtua extends Model
{
    use HasFactory;
    protected $table = 'data_orangtuas';
    protected $guarded = [];

    public function dataanak()
    {
        return $this->hasMany(DataAnak::class, 'IdOrangtua')->cascadeDeletes();
    }
}
