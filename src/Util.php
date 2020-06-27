<?php
	
	
	namespace MarwaDB;
	
	class Util {
		
		/**
		 * @param string $string
		 * @param string $startString
		 * @return bool
		 */
		public static function startsWith( $string, $startString )
		{
			$len = strlen($startString);
			
			return ( substr($string, 0, $len) === $startString );
		}
		
		/**
		 * @param array $a
		 * @return bool
		 */
		public static function is_multi( $a )
		{
			if ( count($a) == count($a, COUNT_RECURSIVE) )
			{
				return false;
			}
			
			return true;
		}
	}
