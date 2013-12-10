<?php
/* Copyright (c) 2013 Rhapsody Project
 *
 * Licensed under the MIT License (http://opensource.org/licenses/MIT)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
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