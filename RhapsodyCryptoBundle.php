<?php
namespace Rhapsody\CryptoBundle;

use Rhapsody\CryptoBundle\Mapping\DBAL\TypeInstaller as DBALTypeInstaller;
use Rhapsody\CryptoBundle\Mapping\MongoDB\TypeInstaller as MongoDBTypeInstaller;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Compiler\AddSecurityVotersPass;

/**
 * Bundle.
 *
 * @author Sean.Quinn
 * @since 1.0
 */
class RhapsodyCryptoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function boot() {
    	$enableDbalTypes = $this->container->getParameter('rhapsody.crypto.enable_dbal_types');
    	if ($enableDbalTypes === true) {
    		$installer = new DBALTypeInstaller($this->container);
    		$installer->installTypes();
    	}

    	$enableMongoDbTypes = $this->container->getParameter('rhapsody.crypto.enable_mongodb_types');
    	if ($enableMongoDbTypes === true) {
    		$installer = new MongoDBTypeInstaller($this->container);
    		$installer->installTypes();
    	}
    }
}
