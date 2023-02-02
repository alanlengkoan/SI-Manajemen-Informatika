<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyfunctionHelper extends AbstractController
{
    // untuk kode urut otomatis
    public function getKodeUrut($tabel, $kode): string
    {
        // untuk koneksi ke database
        $con = $this->getDoctrine()->getManager()->getConnection();
        $sql = "SELECT * FROM $tabel";
        $qry = $con->prepare($sql);
        $qry->execute();
        $dta = $qry->fetchAll();
        $row = $qry->rowCount();
        if ($dta) {
            $nilkod = substr($row[0], 1);
            $sum    = (int) $nilkod;
            $sum    = $row + 1;
            $add_k  = str_pad($sum, 3, "0", STR_PAD_LEFT);
            return "{$kode}{$add_k}";
        } else {
            return "{$kode}001";
        }
    }

    // fungsi untuk id otomatis
    public function getIdOtomatis($tabel)
    {
        // untuk koneksi ke database
        $con = $this->getDoctrine()->getManager()->getConnection();
        $sql = "SELECT * FROM $tabel";
        $qry = $con->prepare($sql);
        $qry->execute();
        $dta = $qry->fetchAll();
        $row = $qry->rowCount();

        $kodeoto = "";
        $karakter = range('0', '9');
        $max = count($karakter) - 1;
        for ($i = 0; $i < 7; $i++) {
            $rand = mt_rand(0, $max);
            $kodeoto .= $karakter[$rand];
        }
        if ($dta) {
            $nilkode  = substr($row[0], 1);
            $kode     = (int) $nilkode;
            $kode     = $row + 1;
            return "{$kodeoto}{$kode}";
        } else {
            return "{$kodeoto}1";
        }
    }

    // untuk separator harga
    function separatorHarga($harga)
    {
        return number_format($harga, 0, ',', '.');
    }

    // untuk remove separator harga
    function removeSeparatorHarga($harga)
    {
        return str_replace('.', '', $harga);
    }
}
