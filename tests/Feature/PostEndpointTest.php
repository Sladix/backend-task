<?php

use App\Actions\ProcessingJob\CreateJob;
use Lorisleiva\Actions\ActionRequest;

describe('POST endpoint', function () {

    beforeEach(function(){
            $this->action = app(CreateJob::class);
    });

    it('should fail with wrong request payload', function (array $payload, array $expectedValidationErrors) {
        $request = Mockery::mock(ActionRequest::class);
        $request->shouldReceive('all')->andReturn($payload);
        $response = $this->action->asController($request);
        expect($response->status())->toBe(422);
        expect($response->getData(true))->toBe($expectedValidationErrors);
    })->with([
        [
            ['text' => 'a'],
            ['error' => 'Minimum text length is 2 characters, got 1']
        ],
        [
            ['text' => join(array_map(fn () => 'a', range(0, 3000)))],
            ['error' => 'Maximum text length is 3000 characters, got 3001']
        ],
        [
            ['text' => 'some text'],
            ['error' => 'You must specify at least one task']
        ],
        [
            ['text' => 'some text', 'tasks' => ['notexisting']],
            ['error' => 'notexisting is not a valid task']
        ],
    ]);

    it('should succeed with valid request', function (array $payload) {
        $request = Mockery::mock(ActionRequest::class);
        $request->shouldReceive('all')->andReturn($payload);
        $response = $this->action->asController($request);
        expect($response->status())->toBe(200);
        expect($response->getData(true))->toHaveKey('id');
    })->with([
        [
            [
                'text' => 'un texte',
                'tasks' => ['call_reason']
            ]
        ],
        [
            [
                'text' => 'un autre texte',
                'tasks' => ['call_reason', 'satisfaction']
            ]
        ],
    ]);
});
