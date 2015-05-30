<?php
// src/Biotopedia/UserBundle/Entity/UserRepository.php
namespace Biotopedia\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	//Récupère la totalité des users en B.D. et les classe par ordre alphabetique
	public function findAllOrderedByUsername()
	{
		//Récupère le QueryBuilder puis la Query corespondant à la selection et Result
		$qb = $this
		->createQueryBuilder('u')
		->orderBy('u.username', 'ASC');

    	return $qb->getQuery()->getResult();
	}

	public function countUsers()
	{
		//getSingleScalarResult permet de ne retourner qu'une seule valeur
    	return $this
    	->createQueryBuilder('u')
    	->select('COUNT(u)')
    	->getQuery()->getSingleScalarResult();
	}
}