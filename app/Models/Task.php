<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    public const NOT_STARTED = 'not_started';
    public const STARTED = 'started';
    public const PENDING = 'pending';
    //there is guarded means we allow all
    //if we define fillable we choose only fields that can be added
    protected $fillable = ['title', 'todo_list_id', 'status', 'description', 'label_id'];

    public function todo_list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }
}
