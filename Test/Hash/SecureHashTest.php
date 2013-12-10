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