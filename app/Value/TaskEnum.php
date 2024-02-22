<?php

declare(strict_types=1);

namespace App\Value;

enum TaskEnum: string
{
    case call_reason = 'call_reason';
    case call_actions = 'call_actions';
    case satisfaction = 'satisfaction';
    case call_segments = 'call_segments';
    case summary = 'summary';
}
