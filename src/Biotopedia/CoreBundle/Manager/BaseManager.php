<?php
// src/Biotopedia/CoreBundle/Manager/BaseManager.php
namespace Biotopedia\CoreBundle\Manager;

abstract class BaseManager
{
    protected function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
    protected function flush()
    {
      	$this->em->flush();
    }

    protected function removeAndFlush($entity)
    {
    	$this->em->remove($entity);
      	$this->em->flush();
    }
}