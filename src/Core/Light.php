<?php
namespace Api\Core;

/**
 * Purpose is to turn on the lights after verification
 * This is just a demoo version
 */
trait Light{
	protected static function verifyRequestOrigin(){
		return true;
	}

	protected static function verifyRequestKey(){
		return true;
	}

	protected static function checkLights(){
		if((self::verifyRequestKey() === true) && (self::verifyRequestOrigin() === true)){
			return true;
		}
	}
}