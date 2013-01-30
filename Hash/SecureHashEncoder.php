<?php
namespace Rhapsody\CryptoBundle\Hash;

use Rhapsody\CryptoBundle\ICryptoProvider;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class SecureHashEncoder implements PasswordEncoderInterface
{

	/**
	 *
	 * @property \Rhapsody\CryptoBundle\ICryptoProvider
	 */
	protected $cryptoProvider;

	public function __construct($cryptoProvider) {
		$this->cryptoProvider = $cryptoProvider;
	}

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface::encodePassword()
	 */
	public function encodePassword($raw, $salt) {
		if ($this->cryptoProvider === null) {
			throw new \Exception('NullPointerException');
		}
		return $this->cryptoProvider->encrypt($raw, $salt);
	}

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface::isPasswordValid()
	 */
	public function isPasswordValid(/*SecureHash*/ $encoded, /*string*/ $raw, $salt) {
		if ($this->cryptoProvider === null) {
			throw new \Exception('NullPointerException');
		}
		return $this->cryptoProvider->check($raw, $encoded);
	}
}