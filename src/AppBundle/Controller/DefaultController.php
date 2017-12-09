<?php

namespace AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('AppBundle:Fourmis')->findAll();
        return $this->render('AppBundle::layout.html.twig', array(
            'list' => $list));
    }
    public function addFriendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fr = $em->getRepository('AppBundle:Fourmis');
        $ant = $fr->find($request->get('id'));
        $list = $fr->findAll();
        $user = $this->getUser();
        $user->addFamille($ant);
        $em->flush();
        return $this->render('AppBundle::layout.html.twig', array(
            'list' => $list));
    }
    public function removeFriendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ant = $em->getRepository('AppBundle:Fourmis')->find($request->get('id'));
        $user = $this->getUser();
        $user->removeFamille($ant);
        $em->flush();
        return $this->render('AppBundle::layout.html.twig', array(
            'list' => $em->getRepository('AppBundle:Fourmis')->findAll(),
        ));
    }

}
