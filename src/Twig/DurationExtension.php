<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DurationExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('duration', [$this, 'formatDuration']),
        ];
    }

    public function formatDuration($minutes)
    {
		$hours = floor($minutes / 60.00);
		$minutes = intval($minutes - $hours * 60.00);

		if ($minutes < 10)
		{
			$minutes = "0".$minutes;
		}

        return $hours."h".$minutes;
    }
}
