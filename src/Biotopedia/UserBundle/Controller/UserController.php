<?php
// src/Biotopedia/MediathequeBundle/Controller/UserController.php

namespace Biotopedia\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\UserBundle\Entity\User;

class UserController extends Controller
{
  public function indexAction()
  {
    // Pour récupérer le service UserManager du bundle
    $userManager = $this->get('fos_user.user_manager');

    // Pour charger un utilisateur
    //$user = $userManager->findUserBy(array('username' => 'winzou'));

    // Pour modifier un utilisateur
    //$user->setEmail('cetemail@nexiste.pas');
    //$userManager->updateUser($user); // Pas besoin de faire un flush avec l'EntityManager, cette méthode le fait toute seule !

    // Pour récupérer la liste de tous les utilisateurs
    $users = $userManager->findUsers();

    return $this->render('BiotopediaUserBundle:User:index.html.twig', 
      array(
        'users' => $users
        ));
  }

  public function showUserAction(User $user)
  {
    //Pas besoin de récupèrer en B.D, les data de la user selon son ID. Le ParamConverter "Famille $famille"
    //s'en occupe deja et gére egalement la 404 error sir l'id en roouting n'existe pas.

    return $this->render('BiotopediaUserBundleBundle:User:showUser.html.twig', 
      array(
        'user' => $user
        ));
  }
}