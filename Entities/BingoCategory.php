<?php

namespace Modules\Bingo\Entities;

use Illuminate\Database\Eloquent\Model;

class BingoCategory extends Model
{
    protected $table = 'bingo_categories';
    protected $fillable = ['title','type','category_888_id'];


    public function rooms()
    {
        return $this->hasMany('Modules\Bingo\Entities\BingoRoom');
    }

}
