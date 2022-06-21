<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    //there is guarded means we allow all
    //if we define fillable we choose only fields that can be added
    protected $fillable = ['title', 'todo_list_id'];
}
