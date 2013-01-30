<?php
namespace Rhapsody\CryptoBundle\Tests\Hash;

/**
 * @author    Sean W. Quinn <sean.quinn@Rhapsody.org>
 * @category  Crypto
 * @package   Rhapsody\Component\Crypto\Tests\Hash
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
use Rhapsody\CryptoBundle\Hash\SecureHash;

class SecureHashTest extends \PHPUnit_Framework_TestCase
{
	const EXPECTED_HASH_VALUE = '234e77d7c2700064cdd86cc4d509f8554688639604feac758a23582241daf626';
	const EXPECTED_SALT_VALUE = '1478c0e7dc642964';
	const EXPECTED_BASE64_VALUE = 'MTQ3OGMwZTdkYzY0Mjk2NDIzNGU3N2Q3YzI3MDAwNjRjZGQ4NmNjNGQ1MDlmODU1NDY4ODYzOTYwNGZlYWM3NThhMjM1ODIyNDFkYWY2MjY=';

	private $secureHash;

	public function setUp() {
		$this->secureHash = new SecureHash(SecureHashTest::EXPECTED_HASH_VALUE, SecureHashTest::EXPECTED_SALT_VALUE);
	}

	public function testFromBase64() {
		$secureHash = SecureHash::fromBase64(SecureHashTest::EXPECTED_BASE64_VALUE, 'US-ASCII');

		$this->assertEquals(SecureHashTest::EXPECTED_HASH_VALUE, $secureHash->getHashValue());
		$this->assertEquals(SecureHashTest::EXPECTED_SALT_VALUE, $secureHash->getRandomSalt());
	}

	public function testToBase64() {
		$secureHash = new SecureHash(
				SecureHashTest::EXPECTED_HASH_VALUE,
				SecureHashTest::EXPECTED_SALT_VALUE,
				'US-ASCII'
		);
		$base64 = $this->secureHash->toBase64();

		$this->assertEquals(SecureHashTest::EXPECTED_BASE64_VALUE, $base64);
	}
}