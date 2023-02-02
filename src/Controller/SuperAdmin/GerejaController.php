<?php

namespace App\Controller\SuperAdmin;

use App\Entity\TbGereja;
use App\Entity\User;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/superadmin/gereja", name="superadmin_gereja")
     */
    // fungsi default
    public function index()
    {
        $data = [
            'halaman' => 'Gereja',
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
        ];

        return $this->render('superadmin/gereja/view.html.twig', $data);
    }

    /**
     * @Route("/superadmin/gereja/add", name="superadmin_gereja_add")
     */
    // fungsi untuk tambah data
    public function add(Request $post, UserPasswordEncoderInterface $pass_enkrip)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            // untuk mengecek token
            if ($this->isCsrfTokenValid('add', $token)) {
                $id_gereja = $this->myfun->getIdOtomatis('user');

                try {
                    // insert ke tabel user
                    $user = new User();
                    $user->setIdUsers($id_gereja);
                    $user->setNama($post->request->get('inpnama'));
                    $user->setEmail($post->request->get('inpemail'));
                    $user->setUsername($post->request->get('inpusername'));
                    $user->setPassword($pass_enkrip->encodePassword($user, $post->request->get('inppassword')));
                    $user->setAlamat($post->request->get('inpalamat'));
                    $user->setTelepon($post->request->get('inptelepon'));
                    $user->setRoles(["ROLE_ADMIN"]);
                    $user->setIns(date_create());
                    $user->setUpd(date_create());

                    // insert ke tabel gereja
                    $gereja = new TbGereja();
                    $gereja->setIdGereja($id_gereja);
                    $gereja->setTentang($post->request->get('inptentang'));
                    $gereja->setTwitter($post->request->get('inptwitter'));
                    $gereja->setInstagram($post->request->get('inpinstagram'));
                    $gereja->setFacebook($post->request->get('inpfacebook'));
                    $gereja->setYoutube($post->request->get('inpyoutube'));
                    $gereja->setIns(date_create());
                    $gereja->setUpd(date_create());

                    $this->mng->persist($user);
                    $this->mng->persist($gereja);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data!', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $e) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal tambah data!', 'type' => 'error', 'button' => 'Ok!'];
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
     * @Route("/superadmin/gereja/del", name="superadmin_gereja_del")
     */
    // untuk hapus data
    public function del(Request $post)
    {
        try {
            $id = $post->request->get('id');

            $gereja = $this->mng->getRepository(TbGereja::class)->findOneBy(['id_gereja' => $id]);
            $user = $this->mng->getRepository(User::class)->findOneBy(['id_users' => $id]);

            if ($user->getFoto() != null) {
                // untuk menghapus foto
                unlink($this->getParameter('folder_akun') . "/" . $user->getFoto());
            }

            $this->mng->remove($gereja);
            $this->mng->remove($user);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/superadmin/gereja/res_pass", name="superadmin_gereja_res_pass")
     */
    // untuk hapus data
    public function reset_password(Request $post, UserPasswordEncoderInterface $pass_enkrip)
    {
        try {
            $id   = $post->request->get('id');
            $user = $this->mng->getRepository(User::class)->findOneBy(['id_users' => $id]);

            $user->setPassword($pass_enkrip->encodePassword($user, '12345678'));

            $this->mng->persist($user);
            $this->mng->flush();

            $response = ['title' => 'Berhasil!', 'text' => 'Berhasil dihapus!', 'type' => 'success', 'button' => 'Ok!'];
        } catch (Exception $e) {
            $response = ['title' => 'Gagal!', 'text' => 'Gagal dihapus!', 'type' => 'error', 'button' => 'Ok!'];
        }

        return new JsonResponse($response);
    }
}
