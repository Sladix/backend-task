<?php

use App\Actions\ProcessingJob\ValidateJob;
use App\Exceptions\JobValidationException;

describe('ValidateJob', function () {
    it('should not be valid ', function (string $text, array $tasks) {
        $action = new ValidateJob();
        expect(fn () => $action->handle($text, $tasks))->toThrow(JobValidationException::class);
    })->with([
        ['', []],
        ['a', ['statisfaction']],
        [join(array_map(fn () => 'a', range(0, 155))), []],
        ['normal', ['a']]
    ]);

    it('should not throw if valid', function () {
        $action = new ValidateJob();
        $action->handle('bonjour', ['call_reason']);
    })->throwsNoExceptions();
});
