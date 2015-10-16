<?php
// src/Biotopedia/MediaBundle/Manager/ArticleManager.php

namespace Biotopedia\MediaBundle\Manager;

use Doctrine\ORM\EntityManager;

use Biotopedia\CoreBundle\Manager\BaseManager;
use Biotopedia\MediaBundle\Entity\Article;

class ArticleManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadArticle($articleId) {
        return $this->getRepository()
            ->findOneBy(array('id' => $articleId));
    }

    /**
    * Save Article entity
    *
    * @param Article $article 
    */
    public function saveArticle(Article $article)
    {
        $this->persistAndFlush($article);
    }

    public function getPreviousArticle($articleId) {
        return $this->getRepository()
                ->getAdjacentDesk($articleId, false)
                ->getQuery()
                ->setMaxResults(1)
                ->getOneOrNullResult();
    }

    public function getNextArticle($articleId) {
        return $this->getRepository()
                ->getAdjacentDesk($articleId, true)
                ->getQuery()
                ->setMaxResults(1)
                ->getOneOrNullResult();
    }

    public function isAuthorized(Article $article, $memberId)
    {
        return ($article->getAuteur()->getId() == $memberId) ?
                true:
                false;
    }

    public function getPreviousAndNextArticle($article)
    {
        return array(
            'prev' => $this->getPreviousArticle($article->getId()),
            'article' => $article,
            'next' => $this->getNextArticle($article->getId()),
            'voted' => false
        );
    }

    public function getRepository()
    {
        return $this->em->getRepository('BiotopediaMediaBundle:Article');
    }

}