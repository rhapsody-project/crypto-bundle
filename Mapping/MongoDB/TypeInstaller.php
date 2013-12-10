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
namespace Rhapsody\CryptoBundle\Mapping\MongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Types\Type as MongoDBType;
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