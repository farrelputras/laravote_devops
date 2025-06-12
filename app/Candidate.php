<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    public $table = 'candidates';

    protected $fillable = [
        'nama_ketua',
        'nama_wakil',
        'visi',
        'misi',
        'program_kerja',
        'photo_paslon',
    ];

    public function users(){
        return $this->hasMany("App\User");
    }
}
