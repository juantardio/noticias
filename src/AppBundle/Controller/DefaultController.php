<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/noticias", name="noticias")
     */
    public function noticiasAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');

        $noticias = $repository->findAll();

        return $this->render('default/noticias.html.twig', 
            array(
                'noticias'=>$noticias
            )
        );
    }


    /**
     * @Route("/noticia/{id}", name="noticia", requirements={"id"="\d+"})
     */
    public function noticiaAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');

        $noticia = $repository->findOneById($id);

        $url_atras = $this->generateUrl('homepage');

        return $this->render('default/noticia.html.twig', 
                                array(
                                    'noticia'=>$noticia,
                                    'url_atras'=>$url_atras
                                )
                            );
    }
    /**
     * @Route("/noticias.json", name="noticias_json")
     */
    public function noticiasJsonAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');

        $tareas = $repository->findAllOrderedByDescripcion();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContenido = $serializer->serialize($tareas, 'json');


        $response = new Response();
        $response->headers->set('content-type', 'application/json');
        $response->setContent($jsonContenido);

        return $response;

    }
   

  




}
