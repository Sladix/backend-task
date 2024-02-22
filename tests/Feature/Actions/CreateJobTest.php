<?php

use App\Actions\ProcessingJob\CreateJob;
use App\Actions\ProcessingJob\ValidateJob;
use App\Actions\Task\ProcessTask;
use App\Exceptions\JobValidationException;
use App\Models\ProcessingJob;
use App\Value\TaskEnum;
use Illuminate\Support\Facades\Queue;

describe('CreateJob', function () {
    it('should throw if the validation action throws', function () {
        $validationMock = Mockery::mock(ValidateJob::class);
        $validationMock->shouldReceive('handle')->andThrow(JobValidationException::class);

        $action = new CreateJob($validationMock);
        $action->handle('', []);
    })->throws(JobValidationException::class);

    it('should create a processing job and dispatch processing task', function () {
        Queue::fake();

        $validationMock = Mockery::mock(ValidateJob::class);
        $validationMock->shouldReceive('handle')->andReturn(null);

        $action = new CreateJob($validationMock);
        $result = $action->handle('test', [TaskEnum::call_reason->value, TaskEnum::satisfaction->value]);

        expect($result)->toBeInstanceOf(ProcessingJob::class);
        ProcessTask::assertPushed(2);
    });
});
