<?php
// src/Biotopedia/MediathequeBundle/Controller/ArticleController.php

namespace Biotopedia\MediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; //affiche les exeption en dev
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\MediathequeBundle\Entity\Article;
use Biotopedia\MediathequeBundle\Form\Type\ArticleType;
use Biotopedia\MediathequeBundle\Entity\Categorie;

class ArticleController extends Controller
{
  public function indexAction()
  {
    //Récupère en B.D, toutes les articles présentes dans l'ordre alphabetique.
   	$articles = $this->getDoctrine()
   		->getRepository('BiotopediaMediathequeBundle:Article')
   		->findAllOrderedByTitre();

      return $this->render('BiotopediaMediathequeBundle:Article:index.html.twig', 
        array('articles' => $articles));
  }

  public function showArticleAction(Article $article)
  {
    if (false === $article->getPublie()) {
      throw new NotFoundHttpException("L'article' d'id ".$id." n'est pas encore rendu publique.");
    }

    //Pas besoin de récupèrer en B.D, les data de la famille selon son ID. Le ParamConverter "Article $article"
    //s'en occupe deja et gére egalement la 404 error sir l'id en roouting n'existe pas. 
    return $this->render('BiotopediaMediathequeBundle:Article:showArticle.html.twig', 
      array(
        'article' => $article
      ));
  }

	public function addArticleAction(Categorie $categorie, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_USER
    if (!$this->get('security.context')->isGranted('ROLE_USER')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux membres et administrateurs du site.');
    }
    
    //Initialisation d'un objet $article
    $article = new Article();
    // On ajoute l'article à la catégorie
    $categorie->addArticle($article);
    // Pas besoin de liée la categorie existente à l'article car le $article->setCategorie($categorie); 
    // a deja lieu dans le $categorie->addArticle($article); de l'entité Categorie.
    // Rajoute l'utilisateur courant comme auteur de l'article
    $auteur = $this->container->get('security.context')->getToken()->getUser();
    $article->setAuteur($auteur);

    //Création de l'objet formulaire dans $form, 'article' est un service déclaré
    $form = $this->createForm('articleType', $article)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas.
    // On fait le lien Requête <-> Formulaire
    // Now, $article contient les valeurs entrées dans le $form par le visiteur
    $form->handleRequest($request);

    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->isValid()) {
      // Inscrit en B.D.l'objet $article obtenu via le formulaire $form
      $em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Article bien enregistrée.');

      return $this->redirect($this->generateUrl('biotopedia_mediatheque_homepage'));
    }

    return $this->render('BiotopediaMediathequeBundle:Article:addArticle.html.twig',
      array(
        'form' => $form->createView(),
        'categorie'=> $categorie));
  }

  public function editArticleAction(Article $article, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN ou qu'il soit l'auteur
    if ($this->get('security.context')->isGranted('ROLE_ADMIN') or $this->getUser() == $article->getAuteur()) {
      //Création de l'objet formulaire dans $form, 'article' est un service déclaré
      $form = $this->createForm('articleEditType', $article)
        ->add('Envoyer', 'submit');

      // handleRequest() détermine si le formulaire a été soumis ou pas.
      // On fait le lien Requête <-> Formulaire
      // Now, $article contient les valeurs entrées dans le $form par le visiteur
      //isValid() retourne false si le formulaire n'a pas été soumis
      if ($form->handleRequest($request)->isValid()) {
        // Pas besoin de persisté $article obtenu via le formulaire $form, il existe deja.
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', 'Article bien modifié.');
        
        return $this->redirect($this->generateUrl('biotopedia_mediatheque_showArticle',
          array('id' => $article->getId())));
      }

      return $this->render('BiotopediaMediathequeBundle:Article:editArticle.html.twig',
        array(
          'form' => $form->createView(),
          'article' => $article));// Je passe également la article à la vue si jamais je veut l'afficher
    }
    else{
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité à l\'auteur de l\'article et aux administarteurs.');
    }
  }

  public function deleteArticleAction(Article $article, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN ou qu'il soit l'auteur
    if ($this->get('security.context')->isGranted('ROLE_ADMIN') or $this->getUser() == $article->getAuteur()) { 
      // On crée un formulaire vide, qui ne contiendra que le champ CSRF
      // Cela permet de protéger la suppression de poisson contre cette faille
      $form = $this->createFormBuilder()->getForm();
      if ($form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', "La article a bien été supprimée.");

        return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
      }
      // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
      return $this->render('BiotopediaPisciothequeBundle:Famille:deleteFamille.html.twig', array(
        'article' => $article,
        'form'   => $form->createView()
        ));
    }
    else{
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité à l\'auteur de l\'article et aux administarteurs.');
    }
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