<?php

namespace Rhapsody\CryptoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RhapsodyCryptoExtension extends Extension
{
	// TODO: http://symfony.com/doc/current/components/dependency_injection/tags.html

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    	if (!array_filter($configs)) {
    		return;
    	}

    	$loader = new Loader\XmlFileLoader(
    			$container,
    			new FileLocator(__DIR__.'/../Resources/config')
    	);
    	$loader->load('crypto.xml');
    	$cryptoConfig = new Configuration();
    	$config = $this->processConfiguration($cryptoConfig, $configs);

    	// ** Set parameters for the types that are enabled...
    	$container->setParameter('rhapsody.crypto.enable_dbal_types', $config['enable_dbal_types']);
    	$container->setParameter('rhapsody.crypto.enable_mongodb_types', $config['enable_mongodb_types']);

    	$secureHashConfig = $config['secure_hash'];
    	$container->setParameter('rhapsody.crypto.secure_hash.algorithm', $secureHashConfig['algorithm']);
    	$container->setParameter('rhapsody.crypto.secure_hash.application_key', $secureHashConfig['application_key']);
    	$container->setParameter('rhapsody.crypto.secure_hash.bundle_key', $secureHashConfig['bundle_key']);
    	$container->setParameter('rhapsody.crypto.secure_hash.first_pass', $secureHashConfig['first_pass']);
    	$container->setParameter('rhapsody.crypto.secure_hash.second_pass', $secureHashConfig['second_pass']);
    	$container->setParameter('rhapsody.crypto.secure_hash.base64_encoding', $secureHashConfig['base64_encoding']);
    }
}
