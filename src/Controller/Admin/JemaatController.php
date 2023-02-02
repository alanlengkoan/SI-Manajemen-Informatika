<?php

namespace App\Controller\Admin;

use App\Entity\TbJemaat;
use App\Entity\TbPengurus;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JemaatController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }

    /**
     * @Route("/admin/jemaat", name="admin_jemaat")
     */
    public function index()
    {
        $idu = $this->getUser()->id_users;

        $data = [
            'halaman' => 'Jemaat',
            'data'    => $this->getDoctrine()->getRepository(TbJemaat::class)->findBy(['id_gereja' => $idu])
        ];

        return $this->render('admin/jemaat/view.html.twig', $data);
    }

    /**
     * @Route("/admin/jemaat/get", name="admin_jemaat_get")
     */
    // untuk ambil data by id
    public function get_data(Request $post)
    {
        $id = $post->request->get('id');

        $jemaat = $this->mng->getRepository(TbJemaat::class)->findOneBy(['id_jemaat' => $id]);

        $data = [
            'id_jemaat' => $jemaat->getIdJemaat(),
            'id_gereja' => $jemaat->getIdGereja(),
            'nik'       => $jemaat->getNik(),
            'nama'      => $jemaat->getNama(),
            'tmp_lahir' => $jemaat->getTmpLahir(),
            'tgl_lahir' => $jemaat->getTglLahir()->format('Y-m-d'),
            'jen_kel'   => $jemaat->getJenKel(),
            'pekerjaan' => $jemaat->getPekerjaan(),
            'no_telpon' => $jemaat->getNoTelpon(),
            'alamat'    => $jemaat->getAlamat(),
        ];

        return $this->render('admin/jemaat/upd.html.twig', $data);
    }

    /**
     * @Route("/admin/jemaat/acc", name="admin_jemaat_acc")
     */
    // untuk hapus jemaat
    public function acc(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $jemaat = $this->mng->getRepository(TbJemaat::class)->findOneBy(['id_jemaat' => $id]);
            $jemaat->setStatus('1');

            $this->mng->persist($jemaat);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/jemaat/upd", name="admin_jemaat_upd")
     */
    // untuk ubah data
    public function upd(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('upd', $token)) {
                try {
                    $id = $post->request->get('inpidjemaat');
                    $jemaat = $this->mng->getRepository(TbJemaat::class)->findOneBy(['id_jemaat' => $id]);

                    $tglLahir = date('Y-m-d', strtotime($post->request->get('inptgl_lhr')));

                    $jemaat->setNik($post->request->get('inpnik'));
                    $jemaat->setNama($post->request->get('inpnama'));
                    $jemaat->setTmpLahir($post->request->get('inptmp_lhr'));
                    $jemaat->setTglLahir(\DateTime::createFromFormat('Y-m-d', $tglLahir));
                    $jemaat->setJenKel($post->request->get('inpjen_kel'));
                    $jemaat->setPekerjaan($post->request->get('inppekerjaan'));
                    $jemaat->setNoTelpon($post->request->get('inpno_telpon'));
                    $jemaat->setAlamat($post->request->get('inpalamat'));
                    $jemaat->setUpd(date_create());

                    $this->mng->persist($jemaat);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil ubah data', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $th) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal ubah data', 'type' => 'error', 'button' => 'Ok!'];
                }
            } else {
                $response = ['title' => 'Gagal!', 'text' => 'Jangan nakal ya!', 'type' => 'error', 'button' => 'Ok!'];
            }
        } else {
            $response = ['title' => 'Gagal!', 'text' => 'Tidak ada request!', 'type' => 'error', 'button' => 'Ok!'];
        }
        // untuk response json
        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/jemaat/del", name="admin_jemaat_del")
     */
    // untuk hapus jemaat
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $jemaat = $this->mng->getRepository(TbJemaat::class)->findOneBy(['id_jemaat' => $id]);

            $this->mng->remove($jemaat);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
