<?php
namespace Rhapsody\CryptoBundle\DependencyInjection;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * <p>
 * Adds the <tt>encrypt</tt> entry into the workflow of the Symfony
 * configuration system. Secure hash encryption can be customized (and in fact
 * is recommended to be overridden), in addition to identifying the encryption
 * routines to use when securing sensitive data in the database.
 * </p>
 *
 * @author Sean.Quinn
 * @since 1.0
 */
class Configuration implements ConfigurationInterface
{

	/**
	 * Generates the configuration tree builder.
	 *
	 * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('rhapsody_crypto');

		$rootNode
			->children()
				->scalarNode('enable_dbal_types')->defaultFalse()->end()
				->scalarNode('enable_mongodb_types')->defaultFalse()->end()
				->arrayNode('secure_hash')
					->addDefaultsIfNotSet()
					->children()
						->scalarNode('algorithm')->defaultValue('sha256')->end()
						->scalarNode('application_key')->cannotBeEmpty()->end()
						->scalarNode('bundle_key')->cannotBeEmpty()->end()
						->scalarNode('first_pass')->defaultValue(1000)->end()
						->scalarNode('second_pass')->defaultValue(9000)->end()
						->scalarNode('base64_encoding')->defaultValue('US-ASCII')->end()
					->end()
				->end()
				//TODO: Add encryption parameters for DAO entities, including "enable_encrypt"
			->end()
		;

		return $treeBuilder;
	}

}
