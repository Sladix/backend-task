<?php

use App\Actions\Task\ProcessTask;
use App\Models\ProcessingJob;
use App\Models\Task;
use App\Value\TaskEnum;

describe('ProcessTask', function () {
    it('should return a result', function (string $text, TaskEnum $task) {
        $action = new ProcessTask();
        $job = ProcessingJob::create();
        $result = $action->handle($job, $text, $task);
        expect($result)->toBeInstanceOf(Task::class);
        expect($result->result)->toHaveKeys(['output', 'processing_time']);
    })->with([
        ['test', TaskEnum::call_reason],
        ['test', TaskEnum::call_segments],
        ['test', TaskEnum::call_actions],
        ['test', TaskEnum::satisfaction],
        ['test', TaskEnum::summary],
    ]);
});
