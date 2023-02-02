<?php

namespace App\Controller\Admin;

use App\Entity\TbKeuangan;
use App\Entity\TbKeuanganRincian;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class KeuanganController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/admin/keuangan", name="admin_keuangan")
     */
    // fungsi default
    public function index()
    {
        $data = [
            'halaman'  => 'Laporan Pemasukan dan Pengeluaran',
        ];

        return $this->render('admin/laporan/laporan_kas/view.html.twig', $data);
    }

    /**
     * @Route("/admin/laporan_keuangan", name="admin_laporan_keuangan")
     */
    // fungsi default
    public function laporan_keuangan(Request $post)
    {
        $idu = $this->getUser()->id_users;
        $tgl_mulai = $post->request->get('inpbegin');
        $tgl_akhir = $post->request->get('inpend');

        $data = [
            'keuangan' => $this->mng->getRepository(TbKeuanganRincian::class)->getAll($idu, $tgl_mulai, $tgl_akhir),
        ];

        return $this->render('admin/laporan/laporan_kas/tabel.html.twig', $data);
    }
    
    // begin:: untuk pemasukan
    /**
     * @Route("/admin/pemasukan", name="admin_pemasukan")
     */
    // fungsi default
    public function pemasukan()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman'  => 'Pemasukan',
            'keuangan' => $this->mng->getRepository(TbKeuangan::class)->getAll(),
            'data'     => $this->mng->getRepository(TbKeuanganRincian::class)->getPemasukan($idu),
        ];

        return $this->render('admin/keuangan/pemasukan/view.html.twig', $data);
    }

    /**
     * @Route("/admin/pemasukan/add", name="admin_pemasukan_add")
     */
    // fungsi untuk tambah data
    public function add_pemasukan(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $gambar = $post->files->get('inpgambar');
                $keuanganDetail = new TbKeuanganRincian();
                $idu = $this->getUser()->id_users;
                $tanggal = date('Y-m-d', strtotime($post->request->get('inptanggal')));
                
                // untuk mengecek apa bila terdapat gambar
                if ($gambar != null) {
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    $gambar->move(
                        $this->getParameter('folder_keuangan'),
                        $namaFile
                    );

                    $keuanganDetail->setIdKeuanganRincian($this->myfun->getIdOtomatis('tb_keuangan_rincian'));
                    $keuanganDetail->setIdGereja($idu);
                    $keuanganDetail->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuanganDetail->setDebit($post->request->get('inpdebit'));
                    $keuanganDetail->setGambar($namaFile);
                    $keuanganDetail->setKeterangan($post->request->get('inpketerangan'));
                    $keuanganDetail->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggal));
                    $keuanganDetail->setStatusU('d');
                } else {
                    $keuanganDetail->setIdKeuanganRincian($this->myfun->getIdOtomatis('tb_keuangan_rincian'));
                    $keuanganDetail->setIdGereja($idu);
                    $keuanganDetail->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuanganDetail->setDebit($post->request->get('inpdebit'));
                    $keuanganDetail->setKeterangan($post->request->get('inpketerangan'));
                    $keuanganDetail->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggal));
                    $keuanganDetail->setStatusU('d');
                }

                $this->mng->persist($keuanganDetail);
                $this->mng->flush();

                $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data', 'type' => 'success', 'button' => 'Ok!'];
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/pemasukan/get", name="admin_pemasukan_get")
     */
    // fungsi untuk mengambil get berdasarkan id
    public function get_pemasukan(Request $post)
    {
        $id = $post->request->get('id');
        $keuagnanRincian = $this->mng->getRepository(TbKeuanganRincian::class)->findOneBy(['id_keuangan_rincian' => $id]);

        $data = [
            'id_keuangan_rincian' => $keuagnanRincian->getIdKeuanganRincian(),
            'id_keuangan'         => $keuagnanRincian->getIdKeuangan(),
            'debit'               => $keuagnanRincian->getDebit(),
            'gambar'              => $keuagnanRincian->getGambar(),
            'keterangan'          => $keuagnanRincian->getKeterangan(),
            'tanggal'             => $keuagnanRincian->getTanggal()->format('Y-m-d'),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/pemasukan/upd", name="admin_pemasukan_upd")
     */
    // fungsi untuk mengambil upd berdasarkan id
    public function upd_pemasukan(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $id = $post->request->get('inpidkeuanganrincian');
                $ubahGambar = $post->request->get('ubah_gambar');
                $tanggal = date('Y-m-d', strtotime($post->request->get('inptanggal')));
                $gambar = $post->files->get('inpgambar');
                
                $keuaganRincian = $this->mng->getRepository(TbKeuanganRincian::class)->findOneBy(['id_keuangan_rincian' => $id]);

                // untuk mengecek apa bila terdapat gambar
                if ($ubahGambar == 'on') {
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    if ($keuaganRincian->getGambar() != null) {
                        // untuk menghapus foto
                        unlink($this->getParameter('folder_keuangan') . "/" . $keuaganRincian->getGambar());
                    }

                    $gambar->move(
                        $this->getParameter('folder_keuangan'),
                        $namaFile
                    );

                    $keuaganRincian->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuaganRincian->setDebit($post->request->get('inpdebit'));
                    $keuaganRincian->setGambar($namaFile);
                    $keuaganRincian->setKeterangan($post->request->get('inpketerangan'));
                    $keuaganRincian->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggal));
                    $keuaganRincian->setStatusU('d');

                    $this->mng->persist($keuaganRincian);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
                } else {
                    $keuaganRincian->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuaganRincian->setDebit($post->request->get('inpdebit'));
                    $keuaganRincian->setKeterangan($post->request->get('inpketerangan'));
                    $keuaganRincian->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggal));
                    $keuaganRincian->setStatusU('d');

                    $this->mng->persist($keuaganRincian);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
                }
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/pemasukan/del", name="admin_pemasukan_del")
     */
    // untuk hapus pemasukan keuangan
    public function del_pemasukan(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $keuangan = $this->mng->getRepository(TbKeuanganRincian::class)->findOneBy(['id_keuangan_rincian' => $id]);

            if ($keuangan->getGambar() != null) {
                // untuk menghapus foto
                unlink($this->getParameter('folder_keuangan') . "/" . $keuangan->getGambar());
            }

            $this->mng->remove($keuangan);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    // begin:: untuk pengeluaran
    /**
     * @Route("/admin/pengeluaran", name="admin_pengeluaran")
     */
    // fungsi default
    public function pengeluaran()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman'  => 'Pengeluaran',
            'keuangan' => $this->mng->getRepository(TbKeuangan::class)->getAll(),   
            'data'     => $this->mng->getRepository(TbKeuanganRincian::class)->getPengeluaran($idu),
        ];

        return $this->render('admin/keuangan/pengeluaran/view.html.twig', $data);
    }

    /**
     * @Route("/admin/pengeluaran/add", name="admin_pengeluaran_add")
     */
    // fungsi untuk tambah data
    public function add_pengeluaran(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $gambar = $post->files->get('inpgambar');
                $keuanganDetail = new TbKeuanganRincian();
                $idu = $this->getUser()->id_users;
                $tanggalIbadah = date('Y-m-d', strtotime($post->request->get('inptanggal')));

                // untuk mengecek apa bila terdapat gambar
                if ($gambar != null) {
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    $gambar->move(
                        $this->getParameter('folder_keuangan'),
                        $namaFile
                    );

                    $keuanganDetail->setIdKeuanganRincian($this->myfun->getIdOtomatis('tb_keuangan_rincian'));
                    $keuanganDetail->setIdGereja($idu);
                    $keuanganDetail->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuanganDetail->setKredit($post->request->get('inpkredit'));
                    $keuanganDetail->setGambar($namaFile);
                    $keuanganDetail->setKeterangan($post->request->get('inpketerangan'));
                    $keuanganDetail->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggalIbadah));
                    $keuanganDetail->setStatusU('k');
                } else {
                    $keuanganDetail->setIdKeuanganRincian($this->myfun->getIdOtomatis('tb_keuangan_rincian'));
                    $keuanganDetail->setIdGereja($idu);
                    $keuanganDetail->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuanganDetail->setKredit($post->request->get('inpkredit'));
                    $keuanganDetail->setKeterangan($post->request->get('inpketerangan'));
                    $keuanganDetail->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggalIbadah));
                    $keuanganDetail->setStatusU('k');
                }

                $this->mng->persist($keuanganDetail);
                $this->mng->flush();

                $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data', 'type' => 'success', 'button' => 'Ok!'];
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/pengeluaran/get", name="admin_pengeluaran_get")
     */
    // fungsi untuk mengambil get berdasarkan id
    public function get_pengeluaran(Request $post)
    {
        $id = $post->request->get('id');
        $keuagnanRincian = $this->mng->getRepository(TbKeuanganRincian::class)->findOneBy(['id_keuangan_rincian' => $id]);

        $data = [
            'id_keuangan_rincian' => $keuagnanRincian->getIdKeuanganRincian(),
            'id_keuangan'         => $keuagnanRincian->getIdKeuangan(),
            'debit'               => $keuagnanRincian->getDebit(),
            'gambar'              => $keuagnanRincian->getGambar(),
            'keterangan'          => $keuagnanRincian->getKeterangan(),
            'tanggal'             => $keuagnanRincian->getTanggal()->format('Y-m-d'),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/pengeluaran/upd", name="admin_pengeluaran_upd")
     */
    // fungsi untuk mengambil upd berdasarkan id
    public function upd_pengeluaran(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('add', $token)) {
                $id = $post->request->get('inpidkeuanganrincian');
                $ubahGambar = $post->request->get('ubah_gambar');
                $tanggal = date('Y-m-d', strtotime($post->request->get('inptanggal')));
                $gambar = $post->files->get('inpgambar');

                $keuaganRincian = $this->mng->getRepository(TbKeuanganRincian::class)->findOneBy(['id_keuangan_rincian' => $id]);

                // untuk mengecek apa bila terdapat gambar
                if ($ubahGambar == 'on') {
                    $namaFile = uniqid() . '.' . $gambar->guessExtension();

                    if ($keuaganRincian->getGambar() != null) {
                        // untuk menghapus foto
                        unlink($this->getParameter('folder_keuangan') . "/" . $keuaganRincian->getGambar());
                    }

                    $gambar->move(
                        $this->getParameter('folder_keuangan'),
                        $namaFile
                    );

                    $keuaganRincian->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuaganRincian->setDebit($post->request->get('inpdebit'));
                    $keuaganRincian->setGambar($namaFile);
                    $keuaganRincian->setKeterangan($post->request->get('inpketerangan'));
                    $keuaganRincian->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggal));
                    $keuaganRincian->setStatusU('k');

                    $this->mng->persist($keuaganRincian);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
                } else {
                    $keuaganRincian->setIdKeuangan($post->request->get('inpidkeuangan'));
                    $keuaganRincian->setDebit($post->request->get('inpdebit'));
                    $keuaganRincian->setKeterangan($post->request->get('inpketerangan'));
                    $keuaganRincian->setTanggal(\DateTime::createFromFormat('Y-m-d', $tanggal));
                    $keuaganRincian->setStatusU('k');

                    $this->mng->persist($keuaganRincian);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
                }
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/pengeluaran/del", name="admin_pengeluaran_del")
     */
    // untuk hapus pengeluaran keuangan
    public function del_pengeluaran(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $keuangan = $this->mng->getRepository(TbKeuanganRincian::class)->findOneBy(['id_keuangan_rincian' => $id]);

            if ($keuangan->getGambar() != null) {
                // untuk menghapus foto
                unlink($this->getParameter('folder_keuangan') . "/" . $keuangan->getGambar());
            }

            $this->mng->remove($keuangan);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
