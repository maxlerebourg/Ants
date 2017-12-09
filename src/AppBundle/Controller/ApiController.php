<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28/11/2017
 * Time: 20:31
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Fourmis;
use AppBundle\Form\FourmisType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/fourmis")
     */
    public function getFourmisAction(Request $request)
    {
        return $this->getDoctrine()
                ->getRepository('AppBundle:Fourmis')
                ->findAll();
    }
    /**
     * @Rest\View()
     * @Rest\Get("/fourmi/{id}")
     */
    public function getFourmiAction(Request $request)
    {
        /* @var $fourmi Fourmis */
        $fourmi = $this->getDoctrine()
            ->getRepository('AppBundle:Fourmis')
            ->find($request->get('id'));
        if($fourmi==null)return new JsonResponse(['message' => 'Fourmi not found'], Response::HTTP_NOT_FOUND);
        return $fourmi;
    }
    /**
     * @Rest\View()
     * @Rest\Get("/add/{id}")
     */
    public function addFriendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ant = $em->getRepository('AppBundle:Fourmis')->find($request->get('id'));
        $user = $this->getUser();
        if ($user->getId() == $ant->getId()) return new JsonResponse(['status'=>'you can not become friend with yourself'], Response::HTTP_INTERNAL_SERVER_ERROR);
        $user->addFamille($ant);
        $em->flush();
        return $user;
    }
    /**
     * @Rest\View()
     * @Rest\Get("/remove/{id}")
     */
    public function removeFriendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ant = $em->getRepository('AppBundle:Fourmis')->find($request->get('id'));
        $user = $this->getUser();
        $user->removeFamille($ant);
        $em->flush();
        return $user;
    }
    /**
     * @Rest\View()
     * @Rest\Get("/verif")
     */
    public function verifAction(Request $request)
    {
        $user = $this->getUser();
        return new JsonResponse(['status'=>'connecté']);
    }
    /**
     * @Rest\View()
     * @Rest\Get("/search/{query}")
     */
    public function searchAction(Request $request)
    {
        $finded = new ArrayCollection();
        $list = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Fourmis')->findAll();
        foreach ($list as $user){
            /* @var $user Fourmis */
            if(!strcmp(strpbrk($user->getUsername(),$request->get('query')),$user->getUsername()))
                $finded->add($user) ;
        }
        return $finded;
    }
    /**
     * @Rest\View()
     * @Rest\Post("/register")
     */
    public function registerAction(Request $request)
    {

        $userManager = $this->get('fos_user.user_manager');
        $ant = new Fourmis();
        $ant->setPlainPassword($request->get('plainPassword'));
        $ant->setEnabled(true);
        $form = $this->createForm(FourmisType::class, $ant);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $userManager->updateUser($ant);
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($ant);
            $em->flush();
            return $ant;
        } else {
            return $form;
        }
    }
    /**
     * @Rest\View()
     * @Rest\Put("/update")
     */
    public function updateAction(Request $request)
    {
        $ant = $this->getUser();
        $form = $this->createForm(FourmisType::class, $ant);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($ant);
            $em->flush();
            return $ant;
        } else {
            return $form;
        }
    }
    /**
     * @Rest\View()
     * @Rest\Post("/token")
     */
    public function newTokenAction(Request $request)
    {
        $user = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Fourmis')
            ->findOneBy(['username' => $request->get('username')]);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->get('password'));
        if (!$isValid) {
            return new JsonResponse(['status' => 'Invalid credentials.'], Response::HTTP_INTERNAL_SERVER_ERROR);;
        }
        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 1800 // 3600 = 1 hour expiration
            ]);
        return new JsonResponse(['username' => $user->getUsername(), 'token' => $token, 'id'=>$user->getId()]);
    }

}