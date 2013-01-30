<?php
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