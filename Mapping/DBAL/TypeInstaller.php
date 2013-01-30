<?php
namespace Rhapsody\CryptoBundle\Mapping\DBAL;

use Doctrine\DBAL\Types\Type as DBALType;
use Doctrine\ORM\EntityManager;
use Rhapsody\CryptoBundle\Mapping\ITypeInstaller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TypeInstaller implements ITypeInstaller
{

	/**
	 * @property Doctrine\ORM\EntityManager
	 * @access protected
	 */
	protected $entityManager;

	/**
	 * The MongoDB types to be installed.
	 * @property array
	 * @access protected
	 */
	protected $types = array(
		'securehash' => 'Rhapsody\CryptoBundle\Mapping\DBAL\Types\SecureHashType'
	);

	public function __construct(ContainerInterface $container = null)
	{
		$this->entityManager = $container->get('doctrine')->getEntityManager();
	}

	public function installTypes()
	{
		foreach ($this->types as $type => $class) {
			if (!DBALType::hasType($type)) {
				DBALType::addType($type, $class);
			}

			$connection = $this->entityManager->getConnection();
			$connection->getDatabasePlatform()->registerDoctrineTypeMapping($type, $type);
		}
	}
}