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
namespace Rhapsody\CryptoBundle\Tests\Hash;

use Rhapsody\CryptoBundle\Hash\SecureHash;
use Rhapsody\CryptoBundle\Hash\SecureHashProvider;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Crypto
 * @package   Rhapsody\Component\Crypto\Tests\Hash
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class SecureHashProviderTest extends \PHPUnit_Framework_TestCase
{

	const PARAM_SECURE_HASH_ALGORITHM = 'sha256';
	const PARAM_SECURE_HASH_APP_KEY = 'e053025bc07f4458';
	const PARAM_SECURE_HASH_BUNDLE_KEY = '26a3b47be2a41886';
	const PARAM_SECURE_HASH_FIRST_PASS = 1000;
	const PARAM_SECURE_HASH_SECOND_PASS = 9000;

	/**
	 * @var SecureHashProvider
	 */
	private static $secureHashProvider;

	public static function setUpBeforeClass() {
		self::$secureHashProvider = new SecureHashProvider(
				SecureHashProviderTest::PARAM_SECURE_HASH_ALGORITHM,
				SecureHashProviderTest::PARAM_SECURE_HASH_APP_KEY,
				SecureHashProviderTest::PARAM_SECURE_HASH_BUNDLE_KEY,
				SecureHashProviderTest::PARAM_SECURE_HASH_FIRST_PASS,
				SecureHashProviderTest::PARAM_SECURE_HASH_SECOND_PASS
		);
	}

	public function testGenerateRandomSalt() {
		$salt = self::$secureHashProvider->generateRandomSalt();
		$this->assertEquals(SecureHash::SALT_SIZE_BITS, strlen($salt));
	}

	public function testEncrypt() {
		$secureHash = self::$secureHashProvider->encrypt('encryptedtext');
		$this->expectOutputString('');
	}

	public function testCheck() {
		$data = 'encryptedtext';
		$hash = '234e77d7c2700064cdd86cc4d509f8554688639604feac758a23582241daf626';
		$salt = '1478c0e7dc642964';

		$secureHash = new SecureHash($hash, $salt);
		$match = self::$secureHashProvider->check($data, $secureHash);
		$this->assertTrue($match);
	}
}