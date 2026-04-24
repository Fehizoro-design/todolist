<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés massivement.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'detail', 'state', 'user_id'];
}
