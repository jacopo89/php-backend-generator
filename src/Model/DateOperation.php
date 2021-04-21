<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\Model;

class DateOperation
{
    public static function addMonths(\DateTimeInterface $date, int $months): \DateTimeInterface
    {
        return self::calculateMonths($date, $months);
    }
    public static function subMonths(\DateTimeInterface $date, int $months): \DateTimeInterface
    {
        return self::calculateMonths($date, $months, '-');
    }
    public static function calculateMonths(\DateTimeInterface $date, int $months, string $sign = '+'): \DateTimeInterface
    {
        $dt = clone $date;
        $day = $dt->format('j');
        $modifier = sprintf("first day of %s%d month", $sign, $months);
        $dt = $dt->modify($modifier);
        $secondModifier = sprintf("+%s days", min($day, $dt->format('t'))-1);
        $dt = $dt->modify($secondModifier);
        return $dt;
    }

    public static function calculateRecurringWithDefault(\DateTimeInterface $startDate, int $monthsToAdd, int $defaultDay)
    {
        return self::fixDateForDefault(self::addMonths($startDate, $monthsToAdd), $defaultDay);
    }

    private static function fixDateForDefault(\DateTimeInterface $paymentDate, int $defaultDay)
    {
        $totalDays = $paymentDate->format('t');

        $format = $defaultDay <= (int)$totalDays ?
            sprintf("%s-%s-%d", $paymentDate->format('Y'), $paymentDate->format('m'), $defaultDay) :
            sprintf("%s-%s-%s", $paymentDate->format('Y'), $paymentDate->format('m'), $totalDays);

        return new \DateTime($format);
    }
}