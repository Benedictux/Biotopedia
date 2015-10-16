<?php
// src/Biotopedia/PisciothequeBundle/Controller/PoissonController.php

namespace Biotopedia\PisciothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\CoreBundle\Entity\Image;
use Biotopedia\CoreBundle\Entity\Source;
use Biotopedia\PisciothequeBundle\Entity\Poisson;
use Biotopedia\PisciothequeBundle\Form\Type\PoissonType;
use Biotopedia\PisciothequeBundle\Entity\Famille;
use Biotopedia\CoreBundle\Bigbrother\BigbrotherEvents;
use Biotopedia\CoreBundle\Bigbrother\MessagePostEvent;

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
    //Pas besoin de récupèrer en B.D, les data de la famille selon son ID. Le ParamConverter "Famille $famille"
    //s'en occupe deja et gére egalement la 404 error sir l'id en roouting n'existe pas. 
   	return $this->render('BiotopediaPisciothequeBundle:Poisson:showPoisson.html.twig', 
      array(
        'poisson' => $poisson
        ));
  }

	public function addPoissonAction(Famille $famille, Request $request)
	{
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_USER
    if (!$this->get('security.context')->isGranted('ROLE_USER')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux membres et administrateurs du site.');
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
    $auteur = $this->getUser();
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

      $request->getSession()->getFlashBag()->add(
        'info', 'Le poisson '.$poisson->getScientificName().' a bien été enregistré dans la base de données.');
      
      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
    }

    return $this->render('BiotopediaPisciothequeBundle:Poisson:addPoisson.html.twig',
      array(
        'form' => $form->createView(),
        'famille'=> $famille));
	}

  public function editPoissonAction(Poisson $poisson, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_USER
    if (!$this->get('security.context')->isGranted('ROLE_USER')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux membres et administrateurs du site.');
    }

    // Crée un tableau contenant les objets Source courants de la base de données
    $originalSources = new ArrayCollection();
    foreach ($poisson->getSources() as $source) {$originalSources->add($source);}

    //Création de l'objet formulaire dans $form, 'poisson' est un service déclaré
    $form = $this->createForm('poissonEditType', $poisson)
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
      $auteur = $this->getUser();
      // Rajoute l'utilisateur courant comme auteur du poisson
      if (!in_array($auteur, $listAuteurs)) {
        $poisson->addAuteur($auteur);
      }

      // supprime la relation entre la source et le poisson
      foreach ($originalSources as $source) {
        if ($poisson->getSources()->contains($source) == false) {
          // supprime le « Poisson de la source
          $source->setPoisson(null);

          //$em->persist($source);
          // si vous souhaitiez supprimer totalement la Source, vous pourriez
          // aussi faire comme cela
          $em->remove($source);
        }
      }
      
      // On crée l'évènement avec ses 2 arguments
      $event = new MessagePostEvent($poisson->getDescription(), $auteur);
      // On déclenche l'évènement
      $this
        ->get('event_dispatcher')
        ->dispatch(BigbrotherEvents::onMessagePost, $event);
      // On récupère ce qui a été modifié par le ou les listeners, ici le message (description)
      $poisson->setDescription($event->getMessage());

      $originalImage = $poisson->getImage();
      $originalImage->setPath("uploads/img/Poissons/".$poisson->getScientificName());
      $originalImage->setUploadDir(__DIR__.'/../../../../web/uploads/img/Poissons/'.$poisson->getScientificName());
      $poisson->setImage($originalImage);

      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Le Poisson a bien été modifié dans la base de données.');
      
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
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN
    if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
    {
      // On crée un formulaire vide, qui ne contiendra que le champ CSRF
      // Cela permet de protéger la suppression de poisson contre cette faille
      $form = $this->createFormBuilder()->getForm();
      if ($form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($poisson);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', "Le poisson a bien été supprimé de la base de données.");

        return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
      }
      // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
      return $this->render('BiotopediaPisciothequeBundle:Poisson:deletePoisson.html.twig', array(
        'poisson' => $poisson,
        'form'   => $form->createView()
        ));
    }
    elseif ($poisson->getAuteurs()->contains($this->getUser()) == true)
    {
      //M'envoyer un mail de suppression du poisson
      $message = \Swift_Message::newInstance()
        ->setSubject('Demande urilisateur de suppression du poisson : '.$poisson->getScientificName())
        ->setFrom($this->container->getParameter('app_webcontact_site'))
        ->setTo($this->container->getParameter('app_webcontact_site'))
        ->setBody($this->renderView('BiotopediaCoreBundle:Email:emailDeletePoissonFromUser.txt.twig'));
      // And optionally an alternative body
        //->addPart('<q>Here is the message itself</q>', 'text/html')
        // Optionally add any attachments
        //->attach(Swift_Attachment::fromPath('my-document.pdf'))
      $this->get('mailer')->send($message);

      $request->getSession()->getFlashBag()->add(
        'info', "La demande de suppression du poisson a bien été envoyée et sera prise en considération
        dans les plus brefs délais."
        );

      return $this->forward('BiotopediaPisciothequeBundle:Poisson:index');
    }
    else
    {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux administrateurs.');
    }
  }
}