<?php
// src/Biotopedia/UsersBundle/Controller/SecurityController.php

namespace Biotopedia\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Biotopedia\UsersBundle\Entity\User;

class SecurityController extends Controller
{
	public function loginAction(Request $request)
	{
		// Si le visiteur est déjà identifié, on le redirige vers l'accueil
    	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      		return $this->redirect($this->generateUrl('biotopedia_homepage'));
      	}

		$session = $request->getSession();

		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}

	    return $this->render('BiotopediaUsersBundle:Security:login.html.twig', array(
	        // last username entered by the user
	        'last_username' => $session->get(SecurityContext::LAST_USERNAME),
	        'error'         => $error,
	    ));
	}
	
	public function registerAction(Request $request)
  	{
  		$user = new User();

	    //Création de l'objet formulaire dans $form, 'user' est un service déclaré
	    $form = $this->createForm('userType', $user)
	      ->add('Envoyer', 'submit');

	    // handleRequest() détermine si le formulaire a été soumis ou pas.
	    $form->handleRequest($request);
	    //isValid() retourne false si le formulaire n'a pas été soumis
	    if ($form->isValid()) {
	    	// Je récupère les services et Managers
	     	$em = $this->getDoctrine()->getManager();
    		$um = $this->get('bp_users.user_manager');
			$factory = $this->get('security.encoder_factory');

			$encoder = $factory->getEncoder($user);
			$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
			$user->setPassword($password);

	      	// Je persiste et flush le user via mon userManager
	      	$um->saveUser($user);

	      	// Génération du lien d'activation
	      	$lien =  $this->get('router')->generate('activate', array('salt' => $user->getSalt()));
	      	// On envoie le mot de passe par mail
	      	$message = \Swift_Message::newInstance()
		        ->setSubject('Mail d\'enregistrement confirmé')
		        ->setFrom('send@example.com')
		        // normalement envoyer à utilisateur@example.com mais config_dev.yml me l'envoie a moi dipsetm12@hotmail.fr
		        ->setTo('utilisateur@example.com')
		        ->setBody($this->renderView('BiotopediaUsersBundle:Security:registerEmail.txt.twig', array('user' => $user->getUsername(), 'lien' => $lien)))
		    ;
		    $this->get('mailer')->send($message);

	      	$request->getSession()->getFlashBag()->add('info', 'Votre profile à bien été enregistré, un mail vous a été transmis pour activer votre compte : '.$lien.'.');
			return $this->redirect($this->generateUrl('biotopedia_homepage'));
	    }

	    return $this->render('BiotopediaUsersBundle:Security:register.html.twig',
	      array('form' => $form->createView(),));
	}

	public function activateAction(Request $request, $salt)
	{
	    $em = $this->container->get("doctrine.orm.default_entity_manager");
	    $user = $em->getRepository("BiotopediaUsersBundle:User")->findOneBySalt($salt);

	    if ($user != null) {
	        $user->isEnabled(true);

	        $em->persist($user);
	        $em->flush();

	        $request->getSession()->getFlashBag()->add('info', 'Votre compte à bien été activé, bienvenue '.$this->getUser()->getUsername().' !');
	    	return $this->redirect($this->generateUrl('biotopedia_homepage'));
	    }
		$request->getSession()->getFlashBag()->add('info-careful', 'Votre compte n\'à pas put étre activé');
	    return $this->redirect($this->generateUrl('biotopedia_homepage'));
	}

	public function resetAction(Request $request)
	{
		// Si le visiteur est déjà identifié je bloque l'accés
    	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      		// Je déclenche une exception « Accès interdit »
      		throw new AccessDeniedException('Accès limité aux membres ayant oubliés leurs mots de passe, vous êtes connecté, ce n\'est donc pas votre cas.');
      	}

  		$looser = new User();
	    //Création de l'objet formulaire dans $form, 'looser' est un service déclaré
	    $form = $this->createForm('resetType', $looser)
	      ->add('Envoyer', 'submit');

	    // handleRequest() détermine si le formulaire a été soumis ou pas.
	    // On fait le lien Requête <-> Formulaire
	    // Now, $looser contient les valeurs entrées dans le $form par le visiteur
	    $form->handleRequest($request);
	    //isValid() retourne false si le formulaire n'a pas été soumis
	    if ($form->isValid()) {
	     	// Inscrit en B.D.l'objet $user obtenu via le formulaire $form
	     	$em = $this->getDoctrine()->getManager();

	     	$user = $em->getRepository('BiotopediaUsersBundle:User')->findOneBy(array('username' => $looser->getUsername()));
     		if ($user == null) {
     			$request->getSession()->getFlashBag()->add('info-careful', 'Le nom d\'utilisateur fourni, n\'existe pas ou plus sur le site');
         		return $this->redirect($this->generateUrl('reset')); 
     		}

     		//Je génére un nouveau PW aléatoire
     		$newPassword = uniqid(null, true);
     		//Je l'encode
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($user);
			$password = $encoder->encodePassword($newPassword, $user->getSalt());
			//Je met à jour le PW encodé du user
			$user->setPassword($password);

	      	$em->persist($user);
	      	$em->flush();

	      	// On envoie le mot de passe par mail
	      	$message = \Swift_Message::newInstance()
		        ->setSubject('Mail de test resetPW')
		        ->setFrom('send@example.com')
		        // normalement envoyer à utilisateur@example.com mais config_dev.yml me l'envoie a moi dipsetm12@hotmail.fr
		        ->setTo('utilisateur@example.com')
		        ->setBody($this->renderView('BiotopediaUsersBundle:Security:resettingEmail.txt.twig', array('looser' => $looser->getUsername(), 'newPassword' => $newPassword)))
		    ;
		    $this->get('mailer')->send($message);

	      	$request->getSession()->getFlashBag()->add('info', 'Un mail vous a été transmis contenant le nouveau password généré : '.$newPassword.'.');

	      	return $this->redirect($this->generateUrl('biotopedia_homepage'));
	    }
	    return $this->render('BiotopediaUsersBundle:Security:reset.html.twig',
	      array('form' => $form->createView()));
	}


    public function changePasswordAction(User $user, Request $request)
    {
      	// Si le visiteur est pas connecté ou différent du user à éditer
	    if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED') OR ($this->getUser()->getId() !== $user->getId())) {
	      // Sinon on déclenche une exception « Accès interdit »
	      throw new AccessDeniedException('Accès limité au membre '.$this->getUser()->getUsername().' et aux administrateurs du site.');
	    }

  		$newUser = new User();
	    //Création de l'objet formulaire dans $form, 'userPWType' est un service déclaré
	    $form = $this->createForm('userPWType', $newUser)
	      ->add('Envoyer', 'submit');

	    // handleRequest() détermine si le formulaire a été soumis ou pas.
	    // On fait le lien Requête <-> Formulaire
	    // Now, $newUser contient les valeurs entrées dans le $form par le visiteur
	    $form->handleRequest($request);
	    //isValid() retourne false si le formulaire n'a pas été soumis
	    if ($form->isValid()) {
	     	// Inscrit en B.D.l'objet $user obtenu via le formulaire $form
	     	$em = $this->getDoctrine()->getManager();

     		//Je génére un nouveau PW aléatoire
     		$newPassword = $newUser->getPassword();
     		//Je l'encode
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($user);
			$password = $encoder->encodePassword($newPassword, $user->getSalt());
			//Je met à jour le PW encodé du user
			$user->setPassword($password);

	      	$em->persist($user);
	      	$em->flush();

	      	// On envoie le mot de passe par mail
	      	$message = \Swift_Message::newInstance()
		        ->setSubject('Changement de Password')
		        ->setFrom('send@example.com')
		        // normalement envoyer à utilisateur@example.com mais config_dev.yml me l'envoie a moi dipsetm12@hotmail.fr
		        ->setTo('utilisateur@example.com')
		        ->setBody($this->renderView('BiotopediaUsersBundle:Security:changedPasswordEmail.txt.twig', array('user' => $user->getUsername(), 'newPassword' => $newPassword)))
		    ;
		    $this->get('mailer')->send($message);

	      	$request->getSession()->getFlashBag()->add('notice', 'Mot de passe bien modifié !');

	      	return $this->redirect($this->generateUrl('biotopedia_homepage'));
	    }
	    return $this->render('BiotopediaUsersBundle:Security:changePassword.html.twig',
	      array(
	      	'form' => $form->createView(),
	      	'user' => $user
	      	));
	}
}