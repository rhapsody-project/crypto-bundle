<?php
namespace Rhapsody\CryptoBundle\Context;

interface ICryptoContext
{

	/**
	 *
	 * @param unknown_type $data
	 * @param unknown_type $encrypted
	 */
	function check($data, $encrypted);

	/**
	 *
	 * @param unknown_type $data
	 * @param unknown_type $salt
	 * @param unknown_type $encoding
	 */
	function encrypt($data, $salt = null, $encoding = null);

	/**
	 *
	 * @param unknown_type $encrypted
	 * @param unknown_type $encoding
	 */
	function decrypt($encrypted, $encoding = null);
}