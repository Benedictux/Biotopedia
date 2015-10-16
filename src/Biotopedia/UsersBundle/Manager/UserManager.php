<?php
// src/Biotopedia/UsersBundle/Manager/UserManager.php

namespace Biotopedia\UsersBundle\Manager;

use Doctrine\ORM\EntityManager;

use Biotopedia\CoreBundle\Manager\BaseManager;
use Biotopedia\UsersBundle\Entity\User;

class UserManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadUsers() {
        return $this->getRepository()
            ->findAllOrderedByUsername();
    }

    public function loadUser($username) {
        return $this->getRepository()
            ->findOneBy(array('username' => $username));
    }

    public function loadUsersActive() {
        return $this->getRepository()
            ->findAllActive();
    }

    /**
    * Save User entity
    *
    * @param User $user 
    */
    public function saveUser(User $user)
    {
        $this->persistAndFlush($user);
    }

    /**
    * Update User entity
    *
    * @param User $user 
    */
    public function updateUser(User $user)
    {
        $this->flush();
    }

    /**
    * Delete User entity
    *
    * @param User $user 
    */
    public function deleteUser(User $user)
    {
        $this->removeAndFlush($user);
    }

    public function getRepository()
    {
        return $this->em->getRepository('BiotopediaUsersBundle:User');
    }

}