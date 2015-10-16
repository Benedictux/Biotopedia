<?php
// src/Biotopedia/UsersBundle/Listener/LoginListener.php

namespace Biotopedia\UsersBundle\Listener;
 
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\HttpKernel;

use Biotopedia\UsersBundle\Entity\User;
 
class LoginListener
{
    protected $em;
 
    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
    }
 
    /**
    * Update the user "lastlogin" on each login
    * @param InteractiveLoginEvent $event
    */
    public function onSecurityController(InteractiveLoginEvent $event)
    {
        // Nous vérifions qu'un token d'autentification est bien présent avant d'essayer manipuler l'utilisateur courant.
        if ($event->getAuthenticationToken()) {
            $user = $event->getAuthenticationToken()->getUser();
 
            // Nous vérifions que l'utilisateur est bien du bon type pour ne pas appeler getLastActivity() sur un objet autre objet User
            if ($user instanceof User) {
                $user->setLastlogin(new \Datetime());
                $this->em->flush($user);
                $event->getRequest()->getSession()->getFlashBag()->add('info', 'Salut '.$user->getUsername().', content de vous revoir !' );
            }
        }
    }
}