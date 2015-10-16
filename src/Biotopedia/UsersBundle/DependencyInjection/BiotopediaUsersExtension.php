<?php

namespace Biotopedia\UsersBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BiotopediaUsersExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    #La méthode load() est automatiquement exécutée par Symfony2 lorsque le bundle est chargé.
    #Ce qui charge le fichier de configuration services.yml, et permet d'enregistrer la définition
    #des services qu'il contient dans le conteneur de services. 
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
