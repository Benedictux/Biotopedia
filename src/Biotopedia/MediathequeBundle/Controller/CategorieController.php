<?php
// src/Biotopedia/MediathequeBundle/Controller/CategorieController.php

namespace Biotopedia\MediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\MediathequeBundle\Entity\Categorie;
use Biotopedia\MediathequeBundle\Form\Type\CategorieType;
use Biotopedia\PisciothequeBundle\Entity\Image;

class CategorieController extends Controller
{
  public function indexAction()
  {
    //Récupère en B.D, toutes les categories présentes dans l'ordre alphabetique.
   	$categories = $this->getDoctrine()
   		->getRepository('BiotopediaMediathequeBundle:Categorie')
   		->findAllOrderedByName();

      return $this->render('BiotopediaMediathequeBundle:Categorie:index.html.twig', 
        array('categories' => $categories));
  }

  public function showCategorieAction(Categorie $categorie)
  {
    if (null === $categorie) {
      throw new NotFoundHttpException("a categorie' d'id ".$id." n'existe pas.");
    }
    //Pas besoin de récupèrer en B.D, les data de la famille selon son ID. Le ParamConverter "Categorie $categorie"
    //s'en occupe deja et gére egalement la 404 error sir l'id en roouting n'existe pas. 
    return $this->render('BiotopediaMediathequeBundle:Categorie:showCategorie.html.twig', 
      array('categorie' => $categorie));
  }

	public function addCategorieAction(Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN
    if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux administrateurs.');
    }
    
    //Initialisation d'un objet $categorie
    $categorie = new Categorie();
    //Initialisation d'un objet $image d'entité Image
    $image = new Image();

    // On lie l'image à la famille
    $categorie->setImage($image);

    //Création de l'objet formulaire dans $form, 'categorie' est un service déclaré
    $form = $this->createForm('categorieType', $categorie)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas.
    // On fait le lien Requête <-> Formulaire
    // Now, $categorie contient les valeurs entrées dans le $form par le visiteur
    $form->handleRequest($request);

    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->isValid()) {
      // Inscrit en B.D.l'objet $categorie obtenu via le formulaire $form
      $em = $this->getDoctrine()->getManager();
      $em->persist($categorie);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Categorie bien enregistrée.');

      return $this->redirect($this->generateUrl('biotopedia_mediatheque_showCategorie', array('id' => $categorie->getId())));
    }

    return $this->render('BiotopediaMediathequeBundle:Categorie:addCategorie.html.twig',
      array('form' => $form->createView(),));
  }

  public function editCategorieAction(Categorie $categorie, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN
    if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux administrateurs.');
    }
    if (null === $categorie) {
      throw new NotFoundHttpException("La categorie d'id ".$id." n'existe pas.");
    }

    //Création de l'objet formulaire dans $form, 'categorie' est un service déclaré
    $form = $this->createForm('categorieEditType', $categorie)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas.
    // On fait le lien Requête <-> Formulaire
    // Now, $categorie contient les valeurs entrées dans le $form par le visiteur
    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->handleRequest($request)->isValid()) {
      // Pas besoin de persisté $categorie obtenu via le formulaire $form, il existe deja.
      $em = $this->getDoctrine()->getManager();
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Categorie bien modifié.');
      
      return $this->redirect($this->generateUrl('biotopedia_mediatheque_showCategorie',
        array('id' => $categorie->getId())));
    }

    return $this->render('BiotopediaMediathequeBundle:Categorie:editCategorie.html.twig',
      array(
        'form' => $form->createView(),
        'categorie' => $categorie));// Je passe également la categorie à la vue si jamais je veut l'afficher
  }

  public function deleteCategorieAction(Categorie $categorie, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN
    if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux administrateurs.');
    }
    if (null === $categorie) {
      throw new NotFoundHttpException("La categorie d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression de poisson contre cette faille
    $form = $this->createFormBuilder()->getForm();
    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($categorie);
      $em->flush();
      $request->getSession()->getFlashBag()->add('info', "La categorie a bien été supprimée.");

      return $this->redirect($this->generateUrl('biotopedia_mediatheque_homepage'));
    }
    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('BiotopediaMediathequeBundle:Categorie:deleteCategorie.html.twig', array(
      'categorie' => $categorie,
      'form'   => $form->createView()
      ));
  }
    public function listAction()
    {
      $listFamilles = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('BiotopediaPisciothequeBundle:Famille')
      ->getFamilleWithPoissons();

      foreach ($listFamilles as $article) {
      // Ne déclenche pas de requête : les poissons sont déjà chargés !
      // Vous pourriez faire une boucle dessus pour les afficher toutes
        $article->getPoissons();
      }
    }
}