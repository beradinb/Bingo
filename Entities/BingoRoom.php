<?php

namespace Modules\Bingo\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

class BingoRoom extends Model
{
    use LogsActivity;
    protected static $logName = 'bingo room';
    protected static $logOnlyDirty = true;
    protected $logAttributes = ['title','bingo_type','image','room_888_id','category_id','status'];

    protected $table = 'bingo_rooms';
    protected $fillable = ['title','bingo_type','image','room_888_id','category_id','status'];


    public function category()
    {
        return $this->belongsTo('Modules\Bingo\Entities\BingoCategory');
    }
}
