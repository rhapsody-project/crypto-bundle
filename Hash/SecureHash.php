<?php
namespace Rhapsody\CryptoBundle\Hash;

use \Commons\Lang\Exception\IllegalArgumentException;

/**
 * <p>
 * An immutable, cryptographically secure hash value and salt pair based on
 * SHA-256. Instances can be generated from a plain text source value such as a
 * password or binary data, and can be converted to or from a Base64 encoded
 * string for easy storage and transfer over a network. Hash values are a
 * mixture of the source value and 192 bits of salt, one-third of which is
 * randomly generated at the time of hashing. Hash values are constructed as
 * follows:
 * <ol>
 * <li>64 bits of <b>random salt</b> plus the <b>source value bytes</b> are
 * hashed to produce <b>Result A</b>.</li>
 * <li><b>Result A</b> is cumulatively rehashed 1000 times to produce
 * <b>Result B</b>.</li>
 * <li><b>Result B</b> plus 64 bits of <b>application-specific salt</b> plus 64
 * bits of <b>static salt</b> hard-coded into this class are hashed to
 * produce <b>Result C</b>.</li>
 * <li><b>Result C</b> is cumulatively rehashed 9000 times to produce the
 * <b>final result</b>.</li>
 * </ol>
 * This object stores the final result and the random salt produced in the first
 * step. Both are needed to verify the hash value against other instances of the
 * same source value.
 * </p>
 * <p>
 * The purpose of using three salt values is to scatter the salt in different
 * locations so that if any one location is compromised, an attacker will still
 * lack sufficient information to mount a brute force attack. Such an attack
 * would require access to this class file, the installation-specific field
 * encryption key ("aw.kp") used to derive the installation-specific salt, and
 * the random salt and hash value, which are typically stored together. The
 * random salt ensures that the same source value produces a different hash
 * value each time it's hashed, which helps resist rainbow table attack on a
 * large collection of hash values such as passwords in a database table.
 * </p>
 * <p>
 * Factory methods are provided to construct <code>SecureHash</code> objects
 * from plain text values and Base64 encoded concatenations of the random salt
 * and hash value. See {@link #fromPlainText(String)} and
 * {@link #fromBase64(String)}. The object also contains a method that verifies
 * a hash value was derived from a source value, given the random salt. See
 * {@link #isDerivedFrom(String)}. Overloads of these are available to operate
 * on binary data instead of text.
 * </p>
 *
 * @author Sean.Quinn
 * @since 1.0
 */
final class SecureHash implements \Serializable
{

	/**
	 * Size of the buffer for salt values, in bits.
	 * @var int
	 */
	const /*int*/ SALT_SIZE_BITS = 16;

	/**
	 * The size of the salt value, in bytes.
	 * @var int
	 */
	const /*int*/ SALT_SIZE_BYTES = 8;

	/**
	 * Size of the hash value in bits.
	 * @var int
	 */
	const /*int*/ HASH_VALUE_SIZE_BITS = 64;

	/**
	 * The size of the hash value, in bytes.
	 * @var int
	 */
	const /*int*/ HASH_VALUE_SIZE_BYTES = 32;

	/**
	 * An 8-byte random salt value that will be used as part of the hashing
	 * algorithm. The usage of a byte string in PHP takes the place of the
	 * conventional <tt>byte[]</tt> in Java.
	 * @var string
	 * @access private
	 */
	private /*byte[]*/ $randomSalt;

	/**
	 * The hash value.
	 * @var string
	 * @access private
	 */
	private /*byte[]*/ $hashValue;

	/**
	 * The encoding to use for the <tt>Base64</tt> representation of the
	 * secure hash.
	 * @var string
	 * @access private
	 */
	private /*string*/ $encoding = 'US-ASCII';

	/**
	 * Create new instance.
	 * @param string $hashValue The hash value.
	 * @param string $randomSalt The random salt.
	 */
	public function __construct($hashValue, $randomSalt, $encoding = 'US-ASCII') {

		// ** Assert that both a hash value and salt value were passed...
		if (empty($hashValue) || empty($randomSalt)) {
			throw new IllegalArgumentException("Salt or hash value is null. Both values are required.");
		}

		// ** Assert length of the hash value, throwing an exception if it is invalid...
		if (strlen($hashValue) != SecureHash::HASH_VALUE_SIZE_BITS) {
			throw new IllegalArgumentException('Hash value has incorrect bit length: '
					. strlen($hashValue) . '; Expected: ' . SecureHash::HASH_VALUE_SIZE_BITS);
		}

		// ** Assert the length of the salt...
		if (strlen($randomSalt) != SecureHash::SALT_SIZE_BITS) {
			throw new IllegalArgumentException('Salt has incorrect bit length: '
					. strlen($randomSalt) . '; Expected: ' . SecureHash::SALT_SIZE_BITS);
		}
		$this->hashValue = $hashValue;
		$this->randomSalt = $randomSalt;
		$this->encoding = $encoding;
	}

	/**
	 * <p>
	 * Returns a <tt>Base64</tt> encoded concatenation of the random salt and
	 * hash value.
	 * </p>
	 *
	 * @return string a <tt>Base64</tt> encoded concatenation of the random salt
	 * 			and hash value.
	 */
	public function __toString() {
		return toBase64();
	}

	/**
	 * @return string the encoding.
	 */
	public function /*string*/ getEncoding() {
		return $this->encoding;
	}

	/**
	 * @return string the hash value. Callers should not modify it.
	 */
	public function /*byte[]*/ getHashValue() {
		return $this->hashValue;
	}

	/**
	 * @return string the 8-byte random salt value. Callers should not modify it.
	 */
	public function /*byte[]*/ getRandomSalt() {
		return $this->randomSalt;
	}

	/**
	 * <p>
	 * Returns <tt>true</tt> if the object has the same hash value and salt as
	 * this one.
	 * </p>
	 *
	 * @param obj The other object.
	 * @return <tt>true</tt> if the objects are equal; otherwise <tt>false</tt>.
	 */
	public function /*bool*/ equals(/*Object*/ $obj) {
		if ($obj instanceof SecureHash) {
			$hashEquals = strcmp($this->hashValue, $obj->hashValue);
			$saltEquals = strcmp($this->randomSalt, $obj->randomSalt);
			return $hashEquals == 0 && $saltEquals == 0;
		}
		return false;
	}

	/**
	 * @return The hash code of this secure hash.
	 */
	public function /*int*/ hashCode() {
		return new HashCodeBuilder(78543919, 483927)
				.append(hashValue)
				.append(randomSalt)
				.toHashCode();
	}

	public function serialize () {
		return $this->toBase64();
	}

	/**
	 * @param serialized
	 */
	public function unserialize ($serialized) {
		$secureHash = SecureHash::fromBase64($serialized, 'US-ASCII');
		$this->hashValue = $secureHash->getHashValue();
		$this->randomSalt = $secureHash->getRandomSalt();
	}

	/**
	 * <p>
	 * Returns the combined random salt and hash value as a Base64 encoded
	 * string, which can be be converted back into a {@link SecureHash} later
	 * via {@link #fromBase64(String)}.
	 * </p>
	 * @return string random salt and hash value concatenated together and
	 * 			encoded in Base64.
	 */
	public function /*string*/ toBase64() {
		$randomSalt = $this->getRandomSalt();
		$hashValue = $this->getHashValue();

		$buffer =  $randomSalt . $hashValue;
		assert(strlen($buffer) == (SecureHash::SALT_SIZE_BITS + SecureHash::HASH_VALUE_SIZE_BITS));
		assert(strlen($buffer) == (strlen($randomSalt) + strlen($hashValue)));

		$encoded = base64_encode($buffer);
		return mb_convert_encoding($encoded, $this->encoding);
	}

	/**
	 * <p>
	 * Create a new {@link SecureHash} instance from a combined salt and hash
	 * value encoded in Base64. This method is the reverse of
	 * {@link #toBase64()}.
	 * </p>
	 *
	 * @param encodedSecureHash Salt and hash value concatenated together and encoded in Base64. The encoded value
	 *   must correspond to a SHA-256 hash value with 64-bit salt. Otherwise, an exception is thrown.
	 * @return A secure hash value.
	 */
	public static function /*SecureHash*/ fromBase64($encodedSecureHash, $encoding = 'US-ASCII') {
		if (empty($encodedSecureHash)) {
			throw new IllegalArgumentException('Object cannot be null.');
		}

		$buffer = base64_decode(mb_convert_encoding($encodedSecureHash, $encoding));
		if (strlen($buffer) != SecureHash::SALT_SIZE_BITS + SecureHash::HASH_VALUE_SIZE_BITS) {
			throw new IllegalArgumentException('Encoded secure hash has incorrect length: ' . strlen($buffer));
		}

		$randomSalt = substr($buffer, 0, SecureHash::SALT_SIZE_BITS);
		$hashValue = substr($buffer, strlen($randomSalt), SecureHash::HASH_VALUE_SIZE_BITS);
		return new SecureHash($hashValue, $randomSalt, $encoding);
	}
}
