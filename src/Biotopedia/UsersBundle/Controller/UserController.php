<?php
// src/Biotopedia/UsersBundle/Controller/UserController.php

namespace Biotopedia\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\UsersBundle\Entity\User;
use Biotopedia\CoreBundle\Entity\Image;
use Biotopedia\PisciothequeBundle\Entity\Famille;
use Biotopedia\PisciothequeBundle\Entity\Poisson;

class UserController extends Controller
{
  /**
  * @Route("/users")
  */
  public function indexAction()
  {
    //Récupère en B.D, toutes les users présents dans l'ordre alphabetique.
    //J'utilise la méthode get du contrôleur, alias de la propriété $this->container, pour accéder aux services et récupérer ainsi mon bp_users.user_manager
    if (!$users = $this->get('bp_users.user_manager')->loadUsers()) {
      throw new NotFoundHttpException($this->get('translator')->trans('This users does not exist.'));
    }

    return $this->render('BiotopediaUsersBundle:User:index.html.twig', 
      array('users' => $users));
  }

  /**
  * @Route("/users/showUser/{username}")
  */
  public function showUserAction($username)
  {
    if (!$user = $this->get('bp_users.user_manager')->loadUser($username)) {
      throw new NotFoundHttpException($this->get('translator')->trans('This user does not exist.'));
    }
    
    return $this->render('BiotopediaUsersBundle:User:showUser.html.twig', 
      array('user' => $user));
  }

  /**
  * @Route("/users/editUser/{username}")
  */
  public function editUserAction($username, Request $request)
  {
    if (!$user = $this->get('bp_users.user_manager')->loadUser($username)) {
      throw new NotFoundHttpException($this->get('translator')->trans('This user does not exist.'));
    }
    // Si l'utilisateur est pas connecté
    if (!$this->get('security.context')->isGranted('ROLE_USER') OR ($this->getUser()->getUsername() !== $username)) {
      // Sinon je déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité au membre '.$username.' et aux administrateurs du site.');
    }

    //Création de l'objet formulaire dans $form, 'user' est un service déclaré
    $form = $this->createForm('userEditType', $user)
      ->add('Envoyer', 'submit');

    // handleRequest() détermine si le formulaire a été soumis ou pas. On fait le lien Requête <-> Formulaire
    // On fait le lien Requête <-> Formulaire
    // Now, $user contient les valeurs entrées dans le $form par le visiteur
    //isValid() retourne false si le formulaire n'a pas été soumis
    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Utilisateur bien modifié.');
      
      return $this->redirect($this->generateUrl('biotopedia_users_showUser',
        array('username' => $user->getUsername())));
    }

    return $this->render('BiotopediaUsersBundle:User:editUser.html.twig',
      array(
        'form' => $form->createView(),
        'user' => $user));// Je passe également le user à la vue si jamais je veut l'afficher
  }

  public function deleteUserAction(User $user, Request $request)
  {
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_ADMIN
    if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux administrateurs.');
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression de l'utilisateur contre cette faille
    $form = $this->createFormBuilder()->getForm();
    if ($form->handleRequest($request)->isValid()) {
      // Je récupère les services et Managers
      $em = $this->getDoctrine()->getManager();
      $um = $this->get('bp_users.user_manager');

      // Je persiste et flush le user via mon userManager
      $um->deleteUser($user);
      $request->getSession()->getFlashBag()->add('info', "L'utilisateur a bien été supprimé.");

      return $this->redirect($this->generateUrl('biotopedia_pisciotheque_homepage'));
    }
    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('BiotopediaUsersBundle:User:deleteUser.html.twig', array(
      'user' => $user,
      'form'   => $form->createView()
      ));
  }
  
  public function whoIsOnlineAction()
  {
    //J'utilise la méthode get du contrôleur, alias de la propriété $this->container, pour accéder aux services et récupérer ainsi mon bp_users.user_manager
    $users = $this->get('bp_users.user_manager')->loadUsersActive();
    
    return $this->render('BiotopediaUsersBundle:User:whoIsOnLine.html.twig',
      array('users' => $users));
  }
}