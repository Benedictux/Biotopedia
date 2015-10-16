<?php
// src/Biotopedia/PisciothequeBundle/Controller/FamilleController.php

namespace Biotopedia\PisciothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\CoreBundle\Entity\Image;
use Biotopedia\PisciothequeBundle\Entity\Famille;
use Biotopedia\PisciothequeBundle\Form\Type\FamilleType;
use Biotopedia\PisciothequeBundle\Entity\Poisson;
use Biotopedia\CoreBundle\Bigbrother\BigbrotherEvents;
use Biotopedia\CoreBundle\Bigbrother\MessagePostEvent;

class FamilleController extends Controller
{
  public function indexAction()
  {
    //Récupère en B.D, toutes les familles présentes dans l'ordre alphabetique.
   	$familles = $this->getDoctrine()
   		->getRepository('BiotopediaPisciothequeBundle:Famille')
   		->findAllOrderedByName();

    return $this->render('BiotopediaPisciothequeBundle:Famille:index.html.twig', 
      array('familles' => $familles));
  }

  /**
  * @Route("/pisciotheque/showFamille/{id}")
  * @ParamConverter("famille", class="BiotopediaPisciothequeBundle:Famille", options={"repository_method" = "findWithJoins"})
  */
  public function showFamilleAction(Famille $famille)
  {
    //Pas besoin de récupèrer en B.D, les data de la famille selon son ID. Le ParamConverter "Famille $famille"
    //s'en occupe deja et gére egalement la 404 error sir l'id en roouting n'existe pas. 
    return $this->render('BiotopediaPisciothequeBundle:Famille:showFamille.html.twig', 
      array(
        'famille' => $famille
      ));
  }

	public function addFamilleAction(Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_USER
    if (!$this->get('security.context')->isGranted('ROLE_USER')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux membres et administrateurs du site.');
    }
    
    //Initialisation d'un objet $famille d'entité Famille
    $famille = new Famille();
    //Initialisation d'un objet $image d'entité Image
    $image = new Image();

    // On lie l'image à la famille
    $famille->setImage($image);
    // Rajoute l'utilisateur courant comme auteur de la famille
    $auteur = $this->getUser();
    $famille->addAuteur($auteur);


    //Création de l'objet formulaire dans $form, 'famille' est un service déclaré
    $form = $this->createForm('familleType', $famille)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas.
    // On fait le lien Requête <-> Formulaire
    // Now, $famille contient les valeurs entrées dans le $form par le visiteur
    $form->handleRequest($request);
    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->isValid()) {
      //Rajoute l'auteur courant comme auteur de chaque poisson créer.
      foreach ($famille->getPoissons() as $poisson) {$poisson->addAuteur($auteur);}

      // On crée l'évènement pour la censure avec ses 2 arguments
      $event = new MessagePostEvent($famille->getDescription(), $auteur);
      // On déclenche l'évènement
      $this
        ->get('event_dispatcher')
        ->dispatch(BigbrotherEvents::onMessagePost, $event);
      // On récupère ce qui a été modifié par le ou les listeners, ici le message (description)
      $famille->setDescription($event->getMessage());

      // Inscrit en B.D.l'objet $famille obtenu via le formulaire $form
      $em = $this->getDoctrine()->getManager();
      $em->persist($famille);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Famille bien enregistrée.');

      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_showFamille', array('id' => $famille->getId())));
    }

    return $this->render('BiotopediaPisciothequeBundle:Famille:addFamille.html.twig',
      array('form' => $form->createView()));
  }

  public function editFamilleAction(Famille $famille, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_USER
    if (!$this->get('security.context')->isGranted('ROLE_USER')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux membres et administrateurs du site.');
    }
    
    // Crée un tableau contenant les objets Poissons courants de la base de données
    $originalPoissons = new ArrayCollection();
    foreach ($famille->getPoissons() as $poisson) {$originalPoissons->add($poisson);}

    //Création de l'objet formulaire dans $form, 'famille' est un service déclaré
    $form = $this->createForm('familleEditType', $famille)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas. On fait le lien Requête <-> Formulaire
    // On fait le lien Requête <-> Formulaire
    // Now, $famille contient les valeurs entrées dans le $form par le visiteur
    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();

      // La méthode findAll retourne toutes les auteurs de la base de données
      $listAuteurs = $em->getRepository('BiotopediaUserBundle:User')->findAll();
      // Récupéere l'utilisateur courant en tant que auteur
      $auteur = $this->getUser();
      // Rajoute l'utilisateur courant comme auteur du poisson
      if (!in_array($auteur, $listAuteurs)) {
        $famille->addAuteur($auteur);
      }

      // supprime la relation entre le poisson et la « Famille »
      foreach ($originalPoissons as $poisson) {
        if ($famille->getPoissons()->contains($poisson) == false) {
          // supprime la « Famille» du Poisson
          $poisson->setFamille(null);

          //$em->persist($poisson);
          // si vous souhaitiez supprimer totalement le Poisson, vous pourriez
          // aussi faire comme cela
          $em->remove($poisson);
        }
      }

      // On crée l'évènement avec ses 2 arguments
      $event = new MessagePostEvent($famille->getDescription(), $auteur);
      // On déclenche l'évènement
      $this
        ->get('event_dispatcher')
        ->dispatch(BigbrotherEvents::onMessagePost, $event);
      // On récupère ce qui a été modifié par le ou les listeners, ici le message (description)
      $famille->setDescription($event->getMessage());

      $originalImage = $famille->getImage();
      $originalImage->setPath("uploads/img/Familles/".$famille->getScientificName());
      $originalImage->setUploadDir(__DIR__.'/../../../../web/uploads/img/Familles/'.$famille->getScientificName());
      $famille->setImage($originalImage);

      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Famille bien modifié.');
      
      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_showFamille',
        array('id' => $famille->getId())));
    }

    return $this->render('BiotopediaPisciothequeBundle:Famille:editFamille.html.twig',
      array(
        'form' => $form->createView(),
        'famille' => $famille));// Je passe également la famille à la vue si jamais je veut l'afficher
  }

  public function deleteFamilleAction(Famille $famille, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN
    if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
    {
      // On crée un formulaire vide, qui ne contiendra que le champ CSRF
      // Cela permet de protéger la suppression de poisson contre cette faille
      $form = $this->createFormBuilder()->getForm();
      if ($form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($famille);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', "La famille a bien été supprimée.");

        return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
      }
      // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
      return $this->render('BiotopediaPisciothequeBundle:Famille:deleteFamille.html.twig', array(
        'famille' => $famille,
        'form'   => $form->createView()
        ));
    }
    elseif ($famille->getAuteurs()->contains($this->getUser()) == true)
    {
      //M'envoyer un mail de suppression de famille
      $message = \Swift_Message::newInstance()
        ->setSubject('Demande urilisateur de suppression de la famille '.$famille->getScientificName())
        ->setFrom($this->container->getParameter('app_webcontact_site'))
        ->setTo($this->container->getParameter('app_webcontact_site'))
        ->setBody($this->renderView('BiotopediaCoreBundle:Email:emailDeleteFamilleFromUser.txt.twig'));
      // And optionally an alternative body
        //->addPart('<q>Here is the message itself</q>', 'text/html')
        // Optionally add any attachments
        //->attach(Swift_Attachment::fromPath('my-document.pdf'))
      $this->get('mailer')->send($message);

      $request->getSession()->getFlashBag()->add(
        'info', "La demande de suppression de la famille a bien été envoyée et sera prise en considération
        dans les plus brefs délais."
        );

      return $this->forward('BiotopediaPisciothequeBundle:Famille:index');
    }
    else
    {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux administrateurs.');
    }
  }

  public function listAction()
  {
    $listFamilles = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('BiotopediaPisciothequeBundle:Famille')
    ->getFamilleWithPoissons();

    foreach ($listFamilles as $famille) {
    // Ne déclenche pas de requête : les poissons sont déjà chargés !
    // Vous pourriez faire une boucle dessus pour les afficher toutes
      $famille->getPoissons();
    }
  }
}