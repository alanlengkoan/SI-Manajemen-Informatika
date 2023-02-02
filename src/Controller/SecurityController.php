<?php

namespace App\Controller;

use App\Entity\TbGereja;
use App\Entity\TbJemaat;
use App\Service\MyfunctionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $mng;
    private $myfun;

    public function __construct(EntityManagerInterface $mng, MyfunctionHelper $myfun)
    {
        $this->mng   = $mng;
        $this->myfun = $myfun;
    }
    
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $data = [
            'halaman'       => 'Login',
            'last_username' => $lastUsername,
            'error'         => $error,
        ];

        return $this->render('login.html.twig', $data);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register()
    {
        $data = [
            'halaman' => 'Register',
            'gereja'  => $this->mng->getRepository(TbGereja::class)->getAll(),
        ];

        return $this->render('register.html.twig', $data);
    }

    /**
     * @Route("/register/add", name="app_register_add")
     */
    // untuk tambah data
    public function add(Request $post)
    {
        if ($post->request->count() > 0) {
            $token = $post->request->get('_csrf_token');

            if ($this->isCsrfTokenValid('register', $token)) {
                try {
                    $tglLahir = date('Y-m-d', strtotime($post->request->get('inptgl_lhr')));

                    $jemaat = new TbJemaat();
                    $jemaat->setIdJemaat($this->myfun->getIdOtomatis('tb_jemaat'));
                    $jemaat->setIdGereja($post->request->get('inpgereja'));
                    $jemaat->setNik($post->request->get('inpnik'));
                    $jemaat->setNama($post->request->get('inpnama'));
                    $jemaat->setTmpLahir($post->request->get('inptmp_lhr'));
                    $jemaat->setTglLahir(\DateTime::createFromFormat('Y-m-d', $tglLahir));
                    $jemaat->setJenKel($post->request->get('inpjen_kel'));
                    $jemaat->setPekerjaan($post->request->get('inppekerjaan'));
                    $jemaat->setNoTelpon($post->request->get('inpno_telpon'));
                    $jemaat->setAlamat($post->request->get('inpalamat'));
                    $jemaat->setStatus('0');
                    $jemaat->setIns(date_create());
                    $jemaat->setUpd(date_create());

                    $this->mng->persist($jemaat);
                    $this->mng->flush();

                    $response = ['title' => 'Berhasil!', 'text' => 'Berhasil tambah data', 'type' => 'success', 'button' => 'Ok!'];
                } catch (Exception $th) {
                    $response = ['title' => 'Gagal!', 'text' => 'Gagal tambah data', 'type' => 'error', 'button' => 'Ok!'];
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
     * @Route("register/check_nik", name="check_nik")
     */
    // untuk check nik jemaat
    public function check_nik(Request $post)
    {
        $nik = $post->request->get('nik');
        $get = $this->mng->getRepository(TbJemaat::class)->checkNik($nik);
        $sum = count($get);

        if ($sum > 0) {
            $response = ['status' => false, 'text' => 'Nomor NIK yang Anda masukkan sudah terdaftar!'];
        } else {
            $response = ['status' => true, 'text' => 'Nomor NIK yang Anda masukkan belum terdaftar!'];
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
