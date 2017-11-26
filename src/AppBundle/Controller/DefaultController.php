<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $list = $em->getRepository('AppBundle:Fourmis')->findAll();
        return $this->render('AppBundle::layout.html.twig', array(
            'list' => $list));
    }


    public function addFriendAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ant = $em->getRepository('AppBundle:Fourmis')->find($id);
        $list = $em->getRepository('AppBundle:Fourmis')->findAll();
        $user = $this->getUser();
        $user->addFamille($ant);
        $em->flush();
        return $this->render('AppBundle::layout.html.twig', array(
            'list' => $list));
    }
    public function removeFriendAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ant = $em->getRepository('AppBundle:Fourmis')->find($id);
        $list = $em->getRepository('AppBundle:Fourmis')->findAll();
        $user = $this->getUser();
        $user->removeFamille($ant);
        $em->flush();
        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
        ));
    }
}
