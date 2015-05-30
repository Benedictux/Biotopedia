<?php
// src/Biotopedia/UserBundle/Controller/UserAdminController.php
 
namespace Biotopedia\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Biotopedia\UserBundle\Entity\User;

class UserAdminController extends Controller
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

    return $this->render('BiotopediaUserBundle:Admin:index.html.twig', 
      array(
        'users' => $users
        ));
    }

    public function editUserAction(User $user, Request $request)
    {
        if (null === $user) {throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");}

        // Pour récupérer le service UserManager du bundle
        $userManager = $this->get('fos_user.user_manager');

        //Création de l'objet formulaire dans $form, 'user' est un service déclaré
        $form = $this->createForm('userEditAdminType', $user)
            ->add('Envoyer', 'submit');
        // handleRequest() détermine si le formulaire a été soumis ou pas.
        // On fait le lien Requête <-> Formulaire
        // Now, $user contient les valeurs entrées dans le $form par le visiteur
        //isValid() retourne false si le formulaire n'a pas été soumis
        if ($form->handleRequest($request)->isValid()) {
            // Pas besoin de persisté $article obtenu via le formulaire $form, il existe deja.
            $userManager->updateUser($user); // Pas besoin de faire un flush avec l'EntityManager, cette méthode le fait toute seule !

            $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien modifié.');
      
            return $this->redirect($this->generateUrl('biotopedia_userAdmin_homepage',
                array('id' => $user->getId())));
        }

        return $this->render('BiotopediaUserBundle:Admin:editUser.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $user));// Je passe également la user à la vue si jamais je veut l'afficher
    }

    public function deleteUserAction(User $user, Request $request)
    {
        if (null === $user) {throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");}

        // Pour récupérer le service UserManager du bundle
        $userManager = $this->get('fos_user.user_manager');

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression de poisson contre cette faille
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            // Pour supprimer un utilisateur
            $userManager->deleteUser($user);
            $request->getSession()->getFlashBag()->add('info', "L'utilisateur a bien été supprimée.");

            return $this->redirect($this->generateUrl('biotopedia_user_homepage'));
        }
    
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('BiotopediaUserBundle:User:deleteUser.html.twig', array(
            'user' => $user,
            'form'   => $form->createView()
        ));
    }
}