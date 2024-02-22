<?php

declare(strict_types=1);

namespace App\Actions\ProcessingJob;

use App\Actions\Task\ProcessTask;
use App\Exceptions\JobValidationException;
use App\Models\ProcessingJob;
use App\Value\TaskEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateJob
{
    use AsAction;

    public function __construct(private readonly ValidateJob $validateJob)
    {
    }

    public static function routes(Router $router): void
    {
        $router->post('/', static::class)->name('create-processing-job');
    }

    /**
     * @throws JobValidationException
     */
    public function handle(string $text, array $tasks): ProcessingJob
    {
        $this->validateJob->handle($text, $tasks);
        $job = ProcessingJob::create();

        foreach ($tasks as $task) {
            ProcessTask::dispatch($job, $text, TaskEnum::from($task));
        }

        return $job;
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $data = $request->all();
        try {
            $job = $this->handle($data['text'] ?? '', $data['tasks'] ?? []);

            return response()->json(['id' => $job->id]);
        } catch (JobValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }
    }
}
