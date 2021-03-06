<?php
// src/Biotopedia/MediathequeBundle/Entity/ArticleRepository.php
namespace Biotopedia\MediathequeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
	//Récupère la totalité des articles en B.D. et les classe par ordre alphabetique
	public function findAllOrderedByTitre()
	{
		//Récupère le QueryBuilder puis la Query corespondant à la selection et Result
		$qb = $this->createQueryBuilder('a')
			->orderBy('a.titre', 'ASC');

    	return $qb->getQuery()->getResult();
	}

	public function countArticles()
	{
		//getSingleScalarResult permet de ne retourner qu'une seule valeur
    	return $this
    	->createQueryBuilder('a')
    	->select('COUNT(a)')
    	->getQuery()->getSingleScalarResult();
	}
}
