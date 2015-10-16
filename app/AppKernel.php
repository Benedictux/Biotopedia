<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),


            new FOS\UserBundle\FOSUserBundle(),//Déclare le Bundle FOS/UserBundle
            new Ornicar\GravatarBundle\OrnicarGravatarBundle(),//Déclare le Bundle GravatarBundle
            new Liip\ImagineBundle\LiipImagineBundle(),//Déclare le Bundle LiipImagineBundle pour manipuler des images
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),//Déclare le Bundle ivory intégrant ckeditor (WYSIWYG)

            //Biotopedia
            new Biotopedia\UserBundle\BiotopediaUserBundle(),
            new Biotopedia\PisciothequeBundle\BiotopediaPisciothequeBundle(),
            new Biotopedia\MediathequeBundle\BiotopediaMediathequeBundle(),
            new Biotopedia\CoreBundle\BiotopediaCoreBundle(),
            new Biotopedia\UsersBundle\BiotopediaUsersBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
