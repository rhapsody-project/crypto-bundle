<?php
namespace Rhapsody\CryptoBundle;

use Rhapsody\CryptoBundle\Context\ICryptoContext;
use Rhapsody\CryptoBundle\Hash\SecureHashProvider;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Crypto
 * @package   Rhapsody\Component\Crypto
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class CryptoContext implements ICryptoContext
{
	protected $provider;

	public function __construct($provider)
	{
		if (!($provider instanceof SecureHashProvider)) {
			throw new \Exception();
		}
	}

	public function check($data, $encrypted) {
		return $this->provider->check($data, $encrypted);
	}

	/**
	 * {@inheritDoc}
	 */
	public function encrypt($data, $salt = null, $encoding = null) {
		return $this->provider->encrypt($data, $salt, $encoding);
	}

	public function decrypt($encrypted, $encoding = null) {
		// TODO
	}
}