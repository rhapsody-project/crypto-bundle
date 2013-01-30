<?php
namespace Rhapsody\CryptoBundle\Mapping\MongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Types\Type as MongoDBType;
use Rhapsody\CryptoBundle\Mapping\ITypeInstaller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TypeInstaller implements ITypeInstaller
{

	/**
	 *
	 * @property \Doctrine\ODM\MongoDB\DocumentManager
	 * @access protected
	 */
	protected $documentManager;

	/**
	 * The MongoDB types to be installed.
	 * @property array
	 * @access protected
	 */
	protected $types = array(
		'securehash' => 'Rhapsody\CryptoBundle\Mapping\MongoDB\Types\SecureHashType'
	);

	public function __construct(ContainerInterface $container = null)
	{
		// No-op.
	}

	public function installTypes()
	{
		foreach ($this->types as $type => $class) {
			if (!MongoDBType::hasType($type)) {
				MongoDBType::addType($type, $class);
			}
		}
	}
}