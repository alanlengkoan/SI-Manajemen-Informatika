<?php

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{
    private $mng;

    public function __construct(EntityManagerInterface $mng)
    {
        $this->mng   = $mng;
    }

    // untuk separator harga
    public function separatorHarga($number, $decimals = 0, $decPoint = ',', $thousandsSep = '.')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price;

        return $price;
    }

    // untuk hitung umur
    public function hitungUmur($tanggal)
    {
        list($tahun, $bulan, $hari) = explode("-", $tanggal);
        $tahun_diff = date("Y") - $tahun;
        $bulan_diff = date("m") - $bulan;
        $hari_diff  = date("d") - $hari;

        if ($bulan_diff < 0) {
            $tahun_diff--;
        } else if (($bulan_diff == 0) && ($hari_diff < 0)) {
            $tahun_diff--;
        }

        return $tahun_diff;
    }
}
