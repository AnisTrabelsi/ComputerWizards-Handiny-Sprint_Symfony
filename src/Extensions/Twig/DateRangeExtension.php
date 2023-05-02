<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateRangeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('date_range', [$this, 'dateRangeFilter']),
        ];
    }

    public function dateRangeFilter($start_date, $end_date)
    {
        $interval = new \DateInterval('P1D'); // 1 day interval
        $end_date = (new \DateTime($end_date))->modify('+1 day'); // add one day to the end date
        $period = new \DatePeriod(new \DateTime($start_date), $interval, $end_date);

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
}
