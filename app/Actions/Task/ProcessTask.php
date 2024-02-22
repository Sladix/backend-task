<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Models\ProcessingJob;
use App\Models\Task;
use App\Value\TaskEnum;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessTask
{
    use AsAction;

    public function handle(ProcessingJob $job, string $input, TaskEnum $task): Task
    {
        $result = $this->getMockResult($input, $task->value);
        $taskModel = new Task();
        $taskModel->result = $result;
        $taskModel->name = $task;
        $taskModel->processing_job_id = $job->id;
        $taskModel->save();
        return $taskModel;
    }

    private function getMockResult(string $input, string $task): array
    {
        $processing_time = random_int(0, 3);
        sleep($processing_time);
        $result =  match ($task) {
            TaskEnum::call_reason->value => Arr::random(['Buy a car', 'Looking for AI projects', 'Looking for music collab', 'Organize a game jam']),
            TaskEnum::call_segments->value => [['start' => 0, 'end' => floor(strlen($input) / 2)], ['start' => floor(strlen($input) / 2), 'end' => strlen($input) - 1]],
            TaskEnum::call_actions->value => Arr::random([['Action A', 'Action B'], ['Action C', 'Action A'], ['Action B', 'Action C']]),
            TaskEnum::satisfaction->value => Arr::random(range(1, 10)),
            TaskEnum::summary->value => Arr::random(['A delighful message', 'Some urgent matter', 'Random chatter']),
        };

        return [
            'output' => $result,
            'processing_time' => $processing_time,
        ];
    }
}
