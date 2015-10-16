<?php
// src/Biotopedia/CoreBundle/Controller/AccueilController.php

namespace Biotopedia\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
   		$nb_articles = $this->getDoctrine()
   		->getRepository('BiotopediaMediathequeBundle:Article')
   		->countArticles();

   		$nb_poissons = $this->getDoctrine()
   		->getRepository('BiotopediaPisciothequeBundle:Poisson')
   		->countPoissons();

   		$nb_aquariophils = $this->getDoctrine()
   		->getRepository('BiotopediaUserBundle:User')
   		->countUsers();

   		return $this->render('BiotopediaCoreBundle:Accueil:index.html.twig',
   			array(
   				'nb_articles' => $nb_articles,
   				'nb_poissons' => $nb_poissons,
   				'nb_aquariophils' => $nb_aquariophils
   				));
    }

    public function descriptionSiteAction()
    {
      return $this->render('BiotopediaCoreBundle:Accueil:descriptionSite.html.twig');
    }

    public function redigerAction()
    {
      return $this->render('BiotopediaCoreBundle:Accueil:rediger.html.twig');
    }

    public function conditionsUtilisationAction()
    {
      return $this->render('BiotopediaCoreBundle:Accueil:conditionsUtilisation.html.twig');
    }

    public function charteEditorialeAction()
    {
      return $this->render('BiotopediaCoreBundle:Accueil:charteEditoriale.html.twig');
    }
}
