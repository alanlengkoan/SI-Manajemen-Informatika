<?php

namespace App\Twig;

use App\Twig\AppRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        // untuk membuat helper dalam template twig
        return [
            new TwigFilter('format_rp', [AppRuntime::class, 'separatorHarga']),
            new TwigFilter('umur', [AppRuntime::class, 'hitungUmur']),
        ];
    }
}