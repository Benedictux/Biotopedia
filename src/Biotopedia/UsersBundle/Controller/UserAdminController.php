<?php
// src/Biotopedia/UsersBundle/Controller/UserAdminController.php
 
namespace Biotopedia\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\UsersBundle\Entity\User;

class UserAdminController extends Controller
{
    public function indexAction()
    {
        //Récupère en B.D, toutes les users présents dans l'ordre alphabetique.
        //J'utilise la méthode get du contrôleur, alias de la propriété $this->container, pour accéder aux services et récupérer ainsi mon bp_users.user_manager
        if (!$users = $this->get('bp_users.user_manager')->loadUsers()) {
          throw new NotFoundHttpException($this->get('translator')->trans('This users does not exist.'));
        }

        return $this->render('BiotopediaUsersBundle:Admin:index.html.twig', 
          array('users' => $users));
    }

    public function editUserAction(User $user, Request $request)
    {
        if (null === $user) {throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");}

        // Je récupère les services et Managers
        $um = $this->get('bp_users.user_manager');

        //Création de l'objet formulaire dans $form, 'user' est un service déclaré
        $form = $this->createForm('userAdminType', $user)
            ->add('Envoyer', 'submit');

        //Traitement
        if ($form->handleRequest($request)->isValid()) {
            $um->updateUser($user); 

            $request->getSession()->getFlashBag()->add('info', 'Utilisateur bien modifié.');
      
            return $this->redirect($this->generateUrl('bp_users_admin_homepage'));
        }

        return $this->render('BiotopediaUsersBundle:Admin:editUser.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $user));// Je passe également la user à la vue si jamais je veut l'afficher
    }

    public function deleteUserAction(User $user, Request $request)
    {
        if (null === $user) {throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");}

        // Pour récupérer le service UserManager
        $um = $this->get('bp_users.user_manager');

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression de poisson contre cette faille
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            // Pour supprimer un utilisateur
            $um->deleteUser($user);
            $request->getSession()->getFlashBag()->add('info', "L'utilisateur a bien été supprimée.");

            return $this->redirect($this->generateUrl('bp_users_admin_homepage'));
        }
    
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('BiotopediaUsersBundle:Admin:deleteUser.html.twig', array(
            'user' => $user,
            'form'   => $form->createView()
        ));
    }
}