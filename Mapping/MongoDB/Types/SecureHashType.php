<?php
namespace Rhapsody\CryptoBundle\Mapping\MongoDB\Types;

use Rhapsody\CryptoBundle\Hash\SecureHash;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * <p>
 * The <tt>MongoDB</tt> implementation of the <tt>SecureHashType</tt> custom
 * type.
 * </p>
 *
 * @author Sean.Quinn
 * @since 1.0
 */
class SecureHashType extends Type
{
	const SECURE_HASH_TYPE = 'securehash';

	/**
	 * {@inheritDoc}
	 * @see Doctrine\ODM\MongoDB\Types\Type::closureToPHP()
	 */
	public function closureToPHP() {
		return '$return = $value === null ? null : \Rhapsody\CryptoBundle\Hash\SecureHash::fromBase64($value);';
	}

	/**
	 * {@inheritDoc}
	 * @see Doctrine\ODM\MongoDB\Types\Type::convertToPHPValue()
	 */
	public function convertToPHPValue($value) {
		return $value == null ? null : SecureHash::fromBase64($value);
	}

	/**
	 * {@inheritDoc}
	 * @see Doctrine\ODM\MongoDB\Types\Type::convertToDatabaseValue()
	 */
	public function convertToDatabaseValue($value)
	{
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