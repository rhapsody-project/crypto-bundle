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
namespace Rhapsody\CryptoBundle\Mapping\DBAL\Types;

use Rhapsody\CryptoBundle\Hash\SecureHash;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * <p>
 * The <tt>DBAL</tt> implementation of the <tt>SecureHashType</tt>.
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody CryptoBundle
 * @package   Rhapsody\CryptoBundle\Mapping\DBAL\Types
 * @copyright Copyright (c) 2012 Extesla Digital Entertainment, Ltd.
 * @version   $Id$
 * @since     1.0
 */
class SecureHashType extends Type
{
	const SECURE_HASH_TYPE = 'securehash';

	/**
	 * {@inheritDoc}
	 * @see Doctrine\DBAL\Types\Type::getSqlDeclaration()
	 */
	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
	}

	/**
	 * {@inheritDoc}
	 * @see Doctrine\DBAL\Types\Type::convertToPHPValue()
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform) {
		return $value == null ? null : SecureHash::fromBase64($value);
	}

	/**
	 * {@inheritDoc}
	 * @see Doctrine\DBAL\Types\Type::convertToDatabaseValue()
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if (!empty($value) && $value instanceof SecureHash) {
			$value = $value->toBase64();
		}
		return $value == null ? null : $value;
	}

	/**
	 * {@inheritDoc}
	 * @see Doctrine\DBAL\Types\Type::getName()
	 */
	public function getName()
	{
		return strtolower(self::SECURE_HASH_TYPE);
	}
}