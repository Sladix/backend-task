<?php

use App\Value\TaskEnum;
use Illuminate\Support\Facades\Http;

use function Pest\Stressless\stress;

describe('Stress test', function () {
    it('should have a 100% success rate on the post endpoint', function () {
        $result = stress(route('create-processing-job'))
            ->post(['text' => 'stress !', 'tasks' => [TaskEnum::call_actions->value, TaskEnum::summary->value]])
            ->concurrency(10)
            ->duration(3)
            ->verbosely();

        expect($result->requests()->failed()->count())->toBe(0);
    });

    it('should have 100% success rate on the get endpoint', function () {
        $response = Http::post(route('create-processing-job'), ['text' => 'super text', 'tasks' => [TaskEnum::call_actions->value, TaskEnum::satisfaction->value]]);

        $result = stress(route('get-job-results', ['jobId' => $response->json()['id']]))
            ->concurrency(10)
            ->duration(3)
            ->verbosely();

        expect($result->requests()->failed()->count())->toBe(0);
    });
});