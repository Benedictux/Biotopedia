<?php
// src/Biotopedia/PisciothequeBundle/Controller/PoissonController.php

namespace Biotopedia\PisciothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\CoreBundle\Entity\Image;
use Biotopedia\PisciothequeBundle\Entity\Poisson;
use Biotopedia\PisciothequeBundle\Form\Type\PoissonType;
use Biotopedia\PisciothequeBundle\Entity\Famille;

class PoissonController extends Controller
{
  public function indexAction()
  {
    //Récupère en B.D, tous les poissons présents dans l'ordre alphabetique.
   	$poissons = $this->getDoctrine()
   		->getRepository('BiotopediaPisciothequeBundle:Poisson')
   		->findAllOrderedByName();

    return $this->render('BiotopediaPisciothequeBundle:Poisson:index.html.twig', 
      array('poissons' => $poissons));
  }

  public function showPoissonAction(Poisson $poisson)
  {   
    if (null === $poisson) {
      throw new NotFoundHttpException("Le poisson d'id ".$id." n'existe pas.");
    }
    //Pas besoin de récupèrer en B.D, les data de la famille selon son ID. Le ParamConverter "Famille $famille"
    //s'en occupe deja et gére egalement la 404 error sir l'id en roouting n'existe pas. 

   	return $this->render('BiotopediaPisciothequeBundle:Poisson:showPoisson.html.twig', 
      array(
        'poisson' => $poisson
        ));
  }

	public function addPoissonAction(Famille $famille, Request $request)
	{
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
    if (!$this->get('security.context')->isGranted('ROLE_AUTEUR')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux auteurs.');
    }
    if (null === $famille) {
      throw new NotFoundHttpException("La famille d'id ".$id." n'existe pas.");
    }
    
    //Initialisation d'un objet $poisson d'entité Poisson
		$poisson = new Poisson();
    //Initialisation d'un objet $image d'entité Image
    $image = new Image();

    // On lie l'image au poisson
    $poisson->setImage($image);
    // On ajoute le poisson à la famille
    $famille->addPoisson($poisson);
    // Pas besoin de liée la famille existente au poisson car le $poisson->setFamille($famille); 
    // a deja lieu dans le $famille->addPoisson($poisson); de l'entité Famille.
    // Rajoute l'utilisateur courant comme auteur du poisson
    $auteur = $this->container->get('security.context')->getToken()->getUser();
    $poisson->addAuteur($auteur);

		//Création de l'objet formulaire dans $form, 'poisson' est un service déclaré
    $form = $this->createForm('poissonFamilleType', $poisson)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas.
    // On fait le lien Requête <-> Formulaire
    // Now, $poisson contient les valeurs entrées dans le $form par le visiteur
    $form->handleRequest($request);

    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->isValid()) {
      // Inscrit en B.D.l'objet $poisson obtenu via le formulaire $form
      $em = $this->getDoctrine()->getManager();
      $em->persist($poisson);
      $em->persist($image);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Poisson bien enregistré.');
      
      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
    }

    return $this->render('BiotopediaPisciothequeBundle:Poisson:addPoisson.html.twig',
      array(
        'form' => $form->createView(),
        'famille'=> $famille));
	}

  public function editPoissonAction(Poisson $poisson, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
    if (!$this->get('security.context')->isGranted('ROLE_AUTEUR')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux auteurs.');
    }
    if (null === $poisson) {
      throw new NotFoundHttpException("Le poisson d'id ".$id." n'existe pas.");
    }

    //Création de l'objet formulaire dans $form, 'poisson' est un service déclaré
    $form = $this->createForm('poissonFamilleType', $poisson)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas.
    // On fait le lien Requête <-> Formulaire
    // Now, $poisson contient les valeurs entrées dans le $form par le visiteur
    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();

      // La méthode findAll retourne toutes les auteurs de la base de données
      $listAuteurs = $em->getRepository('BiotopediaUserBundle:User')->findAll();
      // Récupéere l'utilisateur courant en tant que auteur
      $auteur = $this->container->get('security.context')->getToken()->getUser();
      // Rajoute l'utilisateur courant comme auteur du poisson
      if (in_array($auteur, $listAuteurs)) {
        $poisson->addAuteur($auteur);
      }

      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Poisson bien modifié.');
      
      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_indexPoisson',
        array('id' => $poisson->getId())));
    }

    return $this->render('BiotopediaPisciothequeBundle:Poisson:editPoisson.html.twig',
      array(
        'form' => $form->createView(),
        'poisson' => $poisson));// Je passe également le poisson à la vue si jamais je veut l'afficher
  }

  public function deletePoissonAction(Poisson $poisson, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
    if (!$this->get('security.context')->isGranted('ROLE_AUTEUR')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux auteurs.');
    }
    if (null === $poisson) {
      throw new NotFoundHttpException("Le poisson d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression de poisson contre cette faille
    $form = $this->createFormBuilder()->getForm();
    if ($form->handleRequest($request)->isValid()) {
      $em->remove($poisson);
      $em->flush();
      $request->getSession()->getFlashBag()->add('info', "Le poisson a bien été supprimé.");

      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
    }
    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('BiotopediaPisciothequeBundle:Poisson:deletePoisson.html.twig', array(
      'poisson' => $poisson,
      'form'   => $form->createView()
      ));
  }
}