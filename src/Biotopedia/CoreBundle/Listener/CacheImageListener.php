<?php
# src/Biotopedia/CoreBundle/Listener/CacheImageListener.php
namespace Biotopedia\CoreBundle\Listener;
 
use Doctrine\ORM\Event\LifecycleEventArgs;
use Biotopedia\CoreBundle\Entity\Image;
use Biotopedia\PisciothequeBundle\Entity\Famille;
  
class CacheImageListener
{
    protected $cacheManager;
  
    public function __construct($cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    // en cas la modification l'image d'origine
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // Si c'est une instance de Famille qui a été modifiée.
        if ($entity instanceof Famille) {
            // Si on avait un ancien dossier
            if (is_dir($entity->getTempdirectoryname()))
            {
                //si un file à upload existe alors je supprime l'ancien dossier
                if (null !== $entity->getImage()->getFile()) {
                    $objects = scandir($entity->getTempdirectoryname());
                    foreach ($objects as $object) {
                        if ($object != "." && $object != "..") {
                            if (filetype($entity->getTempdirectoryname()."/".$object) == "dir") rrmdir($entity->getTempdirectoryname()."/".$object); else unlink($entity->getTempdirectoryname()."/".$object);
                        }
                    }
                    reset($objects);
                    rmdir($entity->getTempdirectoryname());
                    // Puis je créer le nouveau
                    if (!is_dir($entity->getImageDirectory()))
                    {
                        mkdir($entity->getImageDirectory(), 0777, true);
                    }
                }
                // Sinon je le renome simplement
                else{rename($entity->getTempdirectoryname(), $entity->getImageDirectory());}
            }
        }

        if ($entity instanceof Famille OR $entity instanceof Image) {
            // Je vide le cache des vignettes
            $this->cacheManager->remove("uploads/img/Familles/");
        }
    }
 
    // en cas du supprission l'image d'origine
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
  
        if ($entity instanceof Famille) {
            // vider le cache des vignettes
            $this->cacheManager->remove("uploads/img/Familles/");
        }
    }
}