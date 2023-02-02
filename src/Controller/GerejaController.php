<?php

namespace App\Controller;

use App\Entity\TbGereja;
use App\Entity\TbInformasi;
use App\Entity\TbJadwal;
use App\Entity\TbJadwalMinggu;
use App\Entity\TbJadwalRincian;
use App\Entity\TbJemaat;
use App\Entity\TbKeuanganRincian;
use App\Entity\TbPengurus;
use App\Entity\User;
use App\Service\MyfunctionHelper;
use Dompdf\Dompdf;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GerejaController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/gereja", name="gereja")
     */
    public function index()
    {
        $data = [
            'halaman' => 'Gereja',
            'klasis'  => $this->mng->getRepository(User::class)->getDetail('1'),
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
        ];

        return $this->render('gereja.html.twig', $data);
    }
    
    /**
     * @Route("/gereja/{id}", name="gereja_detail")
     */
    public function detail(int $id)
    {
        $data = [
            'halaman'       => 'Detail Gereja',
            'klasis'        => $this->mng->getRepository(User::class)->getDetail('1'),
            'gereja'        => $this->mng->getRepository(TbGereja::class)->getAll(),
            'detail'        => $this->mng->getRepository(TbGereja::class)->getDetail($id),
            'jadwal_minggu' => $this->mng->getRepository(TbJadwalMinggu::class)->getDetailJadwal($id),
            'jadwal'        => $this->mng->getRepository(TbJadwalRincian::class)->getDetailJadwal($id),
            'pengurus'      => $this->mng->getRepository(TbPengurus::class)->getDetail($id),
            'galeri'        => $this->mng->getRepository(TbInformasi::class)->getGaleri(),
            'jumlah_jemaat' => $this->getDoctrine()->getRepository(TbJemaat::class)->count(['id_gereja' => $id])
        ];

        return $this->render('gereja_det.html.twig', $data);
    }

    /**
     * @Route("/gereja/warta/{id}", name="gereja_warta")
     */
    public function warta(int $id)
    {
        $tanggal_awal_sesudah  = date('Y-m-d');
        $tanggal_akhir_sesudah = date('Y-m-d', strtotime('+7 days'));

        $tanggal_awal_sebelum  = date('Y-m-d',strtotime('-7 days'));
        $tanggal_akhir_sebelum = date('Y-m-d');

        $jenis_ibadah = $this->mng->getRepository(TbJadwal::class)->getAll();
        $jadwal_ibadah_harian = [];
        foreach ($jenis_ibadah as $key => $value) {
            $jadwal_ibadah = $this->mng->getRepository(TbJadwalRincian::class)->getDetailDate($id, $value['id_jadwal'], $tanggal_awal_sesudah, $tanggal_akhir_sesudah);

            $jadwal_ibadah_harian[$value['nama']] = $jadwal_ibadah;
        }

        $data = [
            'tanggal'              => $tanggal_akhir_sesudah,
            'detail'               => $this->mng->getRepository(TbGereja::class)->getDetail($id),
            'jadwal_ibadah_harian' => $jadwal_ibadah_harian,
            'jadwal_ibadah_minggu' => $this->mng->getRepository(TbJadwalMinggu::class)->getDetailDate($id, $tanggal_awal_sesudah, $tanggal_akhir_sesudah),
            'ulang_tahun'          => $this->mng->getRepository(TbJemaat::class)->getDetailDate($id, $tanggal_awal_sesudah, $tanggal_akhir_sesudah),
            'keuangan_pemasukan'   => $this->mng->getRepository(TbKeuanganRincian::class)->getDetailPemasukan($id, $tanggal_awal_sebelum, $tanggal_akhir_sebelum),
            'keuangan_pengeluaran' => $this->mng->getRepository(TbKeuanganRincian::class)->getDetailPengeluaran($id, $tanggal_awal_sebelum, $tanggal_akhir_sebelum),
        ];

        // untuk membuat pdf
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        
        $dompdf = new Dompdf($options);
        $html = $this->render('warta.html.twig', $data)->getContent();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('legal', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan.pdf', ['Attachment' => false]);
        exit(0);
    }
}
