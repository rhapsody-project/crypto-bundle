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
namespace Rhapsody\CryptoBundle\Hash;

use \Rhapsody\CryptoBundle\Provider\ICryptoProvider;
use \Rhapsody\CryptoBundle\Hash\SecureHash;

/**
 *
 * @author    Sean W. Quinn <sean.quinn@Rhapsody.org>
 * @category  Crypto
 * @package   Rhapsody\CryptoBundle\Hash
 * @copyright Copyright (c) 2013 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
final class SecureHashProvider implements ICryptoProvider
{

	/**
	 *
	 * @var string
	 */
	protected $algorithm = 'sha256';

	/**
     * Application encryption key. This is one of three encryption keys that get
     * fed into the secure hash. A change to the application encryption key will
     * result in the invalidation of all encrypted values that relied on the old
     * encryption key.
     * @var string
     * @access protected
	 */
	protected $applicationKey;

	/**
	 * The bundle encryption key. This is one of the three encryption keys that
	 * gets used during the encryption of a secure hash value. Changing this
	 * will result in the invalidation of all previously encrypted values.
	 * @var string
	 * @access protected
	 */
	protected $bundleKey;

	/**
	 * The encoding that should be used when converting an encyrpted value to
	 * a <tt>Base64</tt> string. Default value is <tt>US-ASCII</tt>.
	 * @var string
	 * @access protected
	 */
	protected $encoding = 'US-ASCII';

	/**
	 * The number of times the source data plus random salt is hash is rehashed
	 * when encrypting a value passed to the provider. Default is 1000.
	 * @var number
	 * @access protected
	 */
	protected $firstPass = 1000;

	/**
	 * The number of additional times the hash value is rehashed after mixing in
	 * application and static salt. Default is 9000.
	 * @var number
	 * @access protected
	 */
	protected $secondPass = 9000;

	/**
	 * Constructor.
	 *
	 * @param string $algorithm
	 * @param string $applicationKey
	 * @param string $bundleKey
	 * @param int $firstPass
	 * @param int $secondPass
	 * @param string $encoding
	 */
	public function __construct($algorithm, $applicationKey, $bundleKey, $firstPass = 1000, $secondPass = 9000, $encoding = 'US-ASCII') {
		$this->algorithm = $algorithm;
		$this->applicationKey = $applicationKey;
		$this->bundleKey = $bundleKey;
		$this->firstPass = $firstPass;
		$this->secondPass = $secondPass;
		$this->encoding = $encoding;
	}

	/**
	 * Returns true of this secure hash may have been derived from the given clear data.
	 * @param string $data The clear data bytes. May not be null.
	 * @param SecureHash $secureHash The secure hash object we are checking to
	 * 			see if there is a match.
	 * @return True if this hash may have been derived from the clear data. False, otherwise.
	 */
 	public function /*bool*/ check($data, $secureHash)
	{
 		$candidateHashValue = $this->computeHash($data, $secureHash->getRandomSalt());
 		return $secureHash->getHashValue() === $candidateHashValue;
 	}

 	/**
	 * <p>
	 * Generates a random salt value to be used to compute a hash.
	 * </p>
	 * @return string the generated random salt for hashing.
	 */
	public function generateRandomSalt() {
		if (@function_exists('openssl_random_pseudo_bytes')) {
			$salt = openssl_random_pseudo_bytes(SecureHash::SALT_SIZE_BYTES * 2, $strong);
			if ($strong !== true) {
				throw new \Exception('OpenSSL failed to generate a strong salt.');
			}
			return $salt;
		}

		$salt = '';
		$sha = '';

		//$bitPairs = ...
		for ($i = 0; $i < SecureHash::SALT_SIZE_BYTES; $i++) {
			$sha = hash('sha256', $sha . mt_rand());
			$char = mt_rand(0,62);
			$salt .= $sha[$char].$sha[$char+1];
		}
		return $salt;
	}

	public function decrypt($encrypted, $salt = null) {
		throw new \Exception('SecureHashProvider does not support decryption of one-way hash.');
	}

	/**
	 * Generate new secure hash value based on the supplied salt and clear data.
	 * If no salt is supplied, salt will be generated.
	 * @param array clearData The clear data bytes.
	 * @param array randomSalt The salt, which must be 64 bits.
	 * @return SecureHash a new <tt>SecureHash</tt> object.
	 */
	public function /*SecureHash*/ encrypt($data, $randomSalt = null, $encoding = null) {
		if (empty($randomSalt)) {
			$randomSalt = $this->generateRandomSalt();
		}
		$hash = $this->computeHash($data, $randomSalt);
		return new SecureHash($hash, $randomSalt, $this->encoding);
	}

	/**
	 * <p>
	 * Compute SHA-256 hash value for the given salt and clear data. This will
	 * create a 64-bit hash that includes the randomized salt, clear data, as
	 * well as application and bundle keys.
	 * </p>
	 *
	 * @param string $clearData The clear data bytes. Must not be null.
	 * @param string $randomSalt The salt to use. Must be 64-bits.
	 * @return The hash value.
	 */
	private function /*byte[]*/ computeHash($clearData, $randomSalt) {

		// ** Verify parameters are not null; if they are throw exception(s)...
		if (empty($randomSalt) || empty($clearData)) {
			throw new \InvalidArgumentException("Object cannot be null.");
		}

		// ** Confirm the random salt's size.
		if (strlen($randomSalt) != SecureHash::SALT_SIZE_BITS) {
			throw new \InvalidArgumentException('Invalid salt length: '
					. strlen($randomSalt) . '; Salt length is too short: Expected number of bits: ' . SecureHash::SALT_SIZE_BITS);
		}

		// Algorithm adapted from http://www.owasp.org/index.php/Hashing_Java
		$digest = hash_init($this->algorithm);
		hash_update($digest, $clearData);
		hash_update($digest, $randomSalt);
		$result = hash_final($digest);

		// Multiple iterations for added security.
		for ($i = 0; $i < $this->firstPass; $i++) {
			$digest = hash_init($this->algorithm);
			hash_update($digest, $result);
			$result = hash_final($digest);
		}

		// Mix in static and installation-specific salt.
		$digest = hash_init($this->algorithm);
		hash_update($digest, $result);
		hash_update($digest, $this->applicationKey);
		hash_update($digest, $this->bundleKey);
		$result = hash_final($digest);

		// Multiple iterations for added security.
		for ($i = 0; $i < $this->secondPass; $i++) {
			$digest = hash_init($this->algorithm);
			hash_update($digest, $result);
			$result = hash_final($digest);
		}
		assert(strlen($result) == SecureHash::HASH_VALUE_SIZE_BITS);
		return $result;
	}
}
