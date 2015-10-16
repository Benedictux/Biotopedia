<?php
// src/Biotopedia/CoreBundle/Controller/ImageController.php

namespace Biotopedia\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Biotopedia\CoreBundle\Entity\Image;

class ImageController extends Controller
{
  public function showImageAction(Image $image)
  {
    //Pas besoin de récupèrer en B.D, les data de la famille selon son ID. Le ParamConverter "Image $image"
    //s'en occupe deja et gére egalement la 404 error sir l'id en roouting n'existe pas.
  
    return $this->render('BiotopediaCoreBundle:Image:showImage.html.twig', 
      array(
        'image' => $image
        ));
  }
}