<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessingJob extends Model
{
    use HasFactory;
    use HasUuids;
    protected $guarded = ["id"];

    protected $visibles = [
        "tasks",
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
