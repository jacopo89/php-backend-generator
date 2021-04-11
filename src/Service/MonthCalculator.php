<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\DateException;

class MonthCalculator
{
    public static function calculate(\DateTimeInterface $start, \DateTimeInterface $end): int
    {
        if ($end <= $start) throw DateException::invalidDates('End date must be greater than start date');

        $months = 0;
        $interval = $end->diff($start);
        $months+= $interval->y * 12 + $interval->m;
        if ($interval->d > 0) $months++;

        return $months;
    }
}