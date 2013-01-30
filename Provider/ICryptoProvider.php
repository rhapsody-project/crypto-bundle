<?php
namespace Rhapsody\CryptoBundle\Provider;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Crypto
 * @package   Rhapsody\Component\Crypto
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
interface ICryptoProvider
{
	/**
	 *
	 * @param unknown_type $data
	 * @param unknown_type $encrypted
	 */
	function check($data, $encrypted);

	/**
	 *
	 * @param unknown_type $encrypted
	 * @param unknown_type $salt
	 */
	function decrypt($encrypted, $salt = null);

	/**
	 *
	 * @param unknown_type $data
	 * @param unknown_type $salt
	 * @param unknown_type $encoding
	 */
	function encrypt($data, $salt = null, $encoding = null);
}