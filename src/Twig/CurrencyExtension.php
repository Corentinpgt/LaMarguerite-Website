<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CurrencyExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('currency', [$this, 'formatCurrency']),
            new TwigFilter('currency_no_symbol', [$this, 'formatCurrencyNoSymbol']),
        ];
    }

    public function formatCurrency($number, $decimals = 2)
    {
		return number_format($number, 2, '.', ' ')." €";
    }

    public function formatCurrencyNoSymbol($number, $decimals = 2)
    {
		return number_format($number, 2, '.', ' ');
    }
}

/*

$number = 1234.56;

// english notation (default)
$english_format_number = number_format($number);
// 1,235

// French notation
$nombre_format_francais = number_format($number, 2, ',', ' ');
// 1 234,56

$number = 1234.5678;

// english notation without thousands separator
$english_format_number = number_format($number, 2, '.', '');
// 1234.57

*/
