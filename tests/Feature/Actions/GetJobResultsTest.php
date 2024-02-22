<?php

use App\Actions\ProcessingJob\GetJobResults;
use App\Models\ProcessingJob;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

describe('GetJobResults', function () {
    it('should return a json response with results', function () {

        $job = ProcessingJob::create();
        Task::create(['processing_job_id' => $job->id, 'name' => 'satisfaction', 'result' => ['output' => 10, 'processing_time' => 5]]);
        $action = new GetJobResults($job->id);
        $result = $action->handle($job->id);
        expect($result)->toBeInstanceOf(JsonResponse::class);
        expect($result->getData(true))->toEqual([
            'tasks' => [
                [
                    'name' => 'satisfaction',
                    'result' => [
                        'output' => 10,
                        'processing_time' => 5
                    ]
                ]
            ]
        ]);
    });
});
