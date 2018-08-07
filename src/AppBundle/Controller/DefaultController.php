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
     * @Route("/noticias.{_format}", 
     *          name="noticias_json_xml",
     *          requirements={"_format": "json|xml"}
     *              )
     */
    public function noticiasJsonAction($_format)
    {
        
        $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');
        $noticia = $repository->findAll();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContenido=$serializer->serialize($noticia, 'json');

        $response = new Response();
        $response->headers->set('Content-type', 'application/json');
        $response->setContent($jsonContenido);
        return $response;
    }

          /**
     * @Route("/noticia.{_format}/{id}", requirements={"id"="\d+"}, 
     *          name="noticia_json_xml",
     *          requirements={"_format": "json|xml"}
     *              )
     */
          public function noticiaJsonXmlAction($id, $_format)
          {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Noticia');
            $noticia = $repository->findOneById($id);
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $Contenido = $serializer->serialize($noticia, $_format);
            
            $response = new Response();
            $response->headers->set('Content-type', 'application/'.$_format);
            $response->setContent($Contenido);
            return $response;
        }


    
   

  




}
