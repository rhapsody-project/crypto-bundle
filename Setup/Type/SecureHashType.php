<?php
namespace Rhapsody\CryptoBundle\Setup\Type;

use Rhapsody\CryptoBundle\Hash\SecureHash;
use Rhapsody\CryptoBundle\Hash\SecureHashProvider;
use Rhapsody\SetupBundle\Type\IType;

class SecureHashType implements IType
{

	/**
	 * <p>
	 * The crypto provider for a <tt>SecureHash</tt> value.
	 * </p>
	 *
	 * @property SecureHashProvider
	 * @access protected
	 */
	protected $provider;

	/**
	 *
	 * @param SecureHashProvider $provider the provider
	 */
	public function __construct(SecureHashProvider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 *
	 * @param unknown $input
	 * @return Ambigous <boolean, string>|boolean
	 * @see Rhapsody\PopulatorBundle\Type\IType
	 */
	public function convert($input, array $attributes = array())
	{
		if (!is_string($input)) {
			throw new \InvalidArgumentException('Unable to convert input of type: '.gettype($input).' to SecureHash. String required.');
		}
		$input = strtolower(trim($input));
		return $this->provider->encrypt($input);
	}
}