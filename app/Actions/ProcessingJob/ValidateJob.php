<?php

declare(strict_types=1);

namespace App\Actions\ProcessingJob;

use App\Exceptions\JobValidationException;
use App\Value\TaskEnum;
use Lorisleiva\Actions\Concerns\AsAction;

class ValidateJob
{
    use AsAction;

    public function handle(string $text, array $tasks): void
    {
        $length = strlen($text);

        if ($length < 2) {
            throw new JobValidationException("Minimum text length is 2 characters, got {$length}");
        }

        if ($length > 3000) {
            throw new JobValidationException("Maximum text length is 3000 characters, got {$length}");
        }

        if (empty($tasks)) {
            throw new JobValidationException('You must specify at least one task');
        }

        foreach ($tasks as $task) {
            $value = TaskEnum::tryFrom($task);
            if (null === $value) {
                throw new JobValidationException("{$task} is not a valid task");
            }
        }
    }
}
