<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "task";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'status',
        'is_published',
        'attachment',
        'task_id',
        'user_id'
    ];

    // protected $appends = [
    //     'status_name'
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function getStatusAttribute($value){
        $statusArr = Config::get('constant.STATUS');
        return $statusArr[$value];
    }

    protected function setStatusAttribute($value){
        $statusArr = Config::get('constant.STATUS');
        $newStatusArr = array_flip($statusArr);
        $this->attributes['status'] = $newStatusArr[$value];
    }

    public function subTasks(){
        return $this->hasMany(SubTask::class,'task_id','id');
    }

    public static function getTaskCount(){
        return Task::whereNull('task_id')->count();
    }

}
