<?php

namespace Database\Factories;

use App\Models\ProcessingJob;
use App\Value\TaskEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskFactory>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'processing_job_id' => ProcessingJob::factory(),
            'name' => Arr::random(array_column(TaskEnum::cases(), 'value')),
            'result' => [
                'output' => 'yes',
                'processing_time' => 2
            ],
        ];
    }
}
