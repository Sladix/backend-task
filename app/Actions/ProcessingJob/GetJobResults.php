<?php

declare(strict_types=1);

namespace App\Actions\ProcessingJob;

use App\Http\Resources\ProcessingJob as ProcessingJobResource;
use App\Models\ProcessingJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Concerns\AsAction;

class GetJobResults
{
    use AsAction;

    public static function routes(Router $router): void
    {
        $router->get('/{jobId}', static::class)->name('get-job-results');
    }

    public function handle(string $jobId): JsonResponse
    {
        return response()->json(
            new ProcessingJobResource(
                ProcessingJob::with('tasks')->findOrFail($jobId)
            )
        );
    }
}
