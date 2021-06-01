<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fac_det_servicio extends Model
{
    protected $table = 'fac_det_servicio';
    protected $fillable = [
        'id','nombre','valor','id_oficina','desc','id_fac_servicio','edo'
    ];
    public $timestamps=false;
    public function fac_servicio(){
        return $this -> belongsTo('App\fac_servicios');
    }
}
