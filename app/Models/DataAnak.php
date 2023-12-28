<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAnak extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table='data_anaks';
    protected $primaryKey = 'nik_anak';
    protected $guarded=[];
    protected $cascade = ['deleting'];

    public function dataorangtua()
    {
        return $this->belongsTo(DataOrangtua::class, 'IdOrangtua');
    }
}
