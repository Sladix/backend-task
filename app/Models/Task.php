<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $name
 * @property string $processing_job_id
 * @property array $result
 */
class Task extends Model
{
    use HasFactory;
    use HasUuids;

    protected $casts = [
        'result' => 'array',
    ];

    protected $guarded = ["id"];

    protected $visibles = [
        "result",
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(ProcessingJob::class, 'processing_job_id');
    }
}
