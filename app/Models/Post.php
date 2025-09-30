<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, InteractsWithMedia, SoftDeletes;


    // protected $fillable = [
    //     'title',
    //     'content',
    //     'ac',
    //     'create_by',
    //     'type',
    //     'status',
    // ];

    protected $guarded = [
        'id',
    ];

    public function staffCreator()
    {
        return $this->belongsTo(Staff::class, 'create_by', 'id');
    }


    public function studentCreator()
    {
        return $this->belongsTo(Student::class, 'create_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
