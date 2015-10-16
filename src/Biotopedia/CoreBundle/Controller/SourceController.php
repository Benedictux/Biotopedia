<?php
// src/Biotopedia/CoreBundle/Controller/SourceController.php

namespace Biotopedia\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Biotopedia\CoreBundle\Entity\Source;

class SourceController extends Controller
{
	public function showSourceAction(Source $source)
  	{
    	return $this->render('BiotopediaCoreBundle:Source:showSource.html.twig', 
      		array(
        		'source' => $source
        	));
  	}

	public function addSourceAction(Request $request)
	{
    
    	//Initialisation d'un objet $source d'entité Source
		$source = new Source();

		//Création de l'objet formulaire dans $form, 'poisson' est un service déclaré
    $form = $this->createForm('sourceType', $source)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas.
    // On fait le lien Requête <-> Formulaire
    // Now, $poisson contient les valeurs entrées dans le $form par le visiteur
    $form->handleRequest($request);

    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->isValid()) {
      // Inscrit en B.D.l'objet $poisson obtenu via le formulaire $form
      $em = $this->getDoctrine()->getManager();
      $em->persist($source);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Source bien enregistré.');
      
      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
    }

    return $this->render('BiotopediaCoreBundle:Source:addSource.html.twig',
      array(
        'form' => $form->createView(),
        'source'=> $source));
	}
}