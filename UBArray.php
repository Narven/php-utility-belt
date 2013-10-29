<?php

/**
 * Class UBArray
 *
 * Helpers for arrays related stuff
 *
 * @version 0.1
 */
class UBArray
{
	/**
	 * Takes all 1-level deep elements and compiles a new array
	 *
	 * @param array $array
	 *
	 * @author kvz
	 * @link   https://github.com/kvz/kvzlib/blob/master/php/functions/arrayExtract1.inc.php
	 * @return mixed boolean on failure or array on success
	 */
	public static function arrayExtract1( $array )
	{
		$newArray = array();
		if( !is_array( $array ) )
		{
			return false;
		}

		foreach( $array as $k => $array1 )
		{
			foreach( $array1 as $k1 => $v1 )
			{
				$newArray[ ] = $v1;
			}
		}

		return $newArray;
	}

	/**
	 * Taken from CakePHP!
	 * This function can be thought of as a hybrid between PHP's array_merge and array_merge_recursive. The difference
	 * to the two is that if an array key contains another array then the function behaves recursive (unlike array_merge)
	 * but does not do if for keys containing strings (unlike array_merge_recursive). See the unit test for more information.
	 *
	 * Note: This function will work with an unlimited amount of arguments and typecasts non-array parameters into arrays.
	 *
	 * @param array $arr1 Array to be merged
	 * @param array $arr2 Array to merge with
	 *
	 * @return array Merged array
	 */
	public static function arrayMerge( $arr1, $arr2 = null )
	{
		$args = func_get_args();

		if( !isset( $r ) )
		{
			$r = (array)current( $args );
		}

		while( ( $arg = next( $args ) ) !== false )
		{
			foreach( (array)$arg as $key => $val )
			{
				if( is_array( $val ) && isset( $r[ $key ] ) && is_array( $r[ $key ] ) )
				{
					$r[ $key ] = arrayMerge( $r[ $key ], $val );
				}
				elseif( is_int( $key ) )
				{
					$r[ ] = $val;
				}
				else
				{
					$r[ $key ] = $val;
				}
			}
		}

		return $r;
	}

	/**
	 * Takes 2 elements and compiles an associative array
	 *
	 * @param array $array
	 *
	 * @param null  $useKey
	 * @param null  $useVal
	 *
	 * @return mixed boolean on failure or array on success
	 */
	public static function arraySquash( $array, $useKey = null, $useVal = null )
	{
		if( !is_array( $array ) )
		{
			return false;
		}

		$newArray = array();
		$keys     = null;

		foreach( $array as $k => $array1 )
		{
			if( $keys === null )
			{
				$keys = array_keys( $array1 );
			}
			if( $useKey === null )
			{
				$useKey = array_shift( $keys );
			}
			if( $useVal === null )
			{
				$useVal = array_shift( $keys );
			}

			$newArray[ $array1[ $useKey ] ] = $array1[ $useVal ];
		}

		return $newArray;
	}

	/**
	 * Recusive alternative to array_key_exists
	 *
	 * Taken from http://nl3.php.net/manual/en/function.array-key-exists.php#82890
	 *
	 * The following code block can be utilized by PEAR's Testing_DocTest
	 * <code>
	 * // Input //
	 * $array = array(
	 *         'c' => array(
	 *                 'd' => 4,
	 *                 'a' => 1,
	 *                 'b' => 2,
	 *                 'c' => 3,
	 *                 'e' => 5,
	 *         ),
	 *         'a' => array(
	 *                 'd' => 4,
	 *                 'b' => 2,
	 *                 'a' => 1,
	 *                 'e' => 5,
	 *                 'c' => 3,
	 *         ),
	 *         'b' => array(
	 *                 'x' => 4,
	 *                 'y' => 2,
	 *                 'z' => 3,
	 *         )
	 * );
	 *
	 * // Execute //
	 * $output = array();
	 * $output[] = keyExistsTree('z', $array) ? 'true' : 'false';
	 * $output[] = keyExistsTree('a', $array) ? 'true' : 'false';
	 * $output[] = keyExistsTree('i', $array) ? 'true' : 'false';
	 * $output[] = keyExistsTree('c', $array) ? 'true' : 'false';
	 *
	 * // Show //
	 * print_r($output);
	 *
	 * // expects:
	 * // Array
	 * // (
	 * //         [0] => true
	 * //         [1] => true
	 * //         [2] => false
	 * //         [3] => true
	 * // )
	 * </code>
	 *
	 * @author        Kevin van Zonneveld <kevin@vanzonneveld.net>
	 * @copyright     2009 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	 * @license       http://www.opensource.org/licenses/bsd-license.php New BSD Licence
	 * @version       SVN: Release: $Id$
	 * @link          http://kevin.vanzonneveld.net/
	 *
	 * @param string $needle
	 * @param array  $haystack
	 *
	 * @return boolean
	 */
	public static function keyExistsTree( $needle, $haystack )
	{
		if( !is_array( $haystack ) )
		{
			return false;
		}

		$result = array_key_exists( $needle, $haystack );
		if( $result )
		{
			return $result;
		}

		if( is_array( $haystack ) )
		{
			foreach( $haystack as $v )
			{
				if( is_array( $v ) )
				{
					$result = keyExistsTree( $needle, $v );
				}
				if( $result )
				{
					return $result;
				}
			}
		}

		return $result;
	}

	/**
	 * Recusive alternative to ksort
	 *
	 * The following code block can be utilized by PEAR's Testing_DocTest
	 * <code>
	 * // Input //
	 * $array = array(
	 *         "c" => array(
	 *                 "d" => 4,
	 *                 "a" => 1,
	 *                 "b" => 2,
	 *                 "c" => 3,
	 *                 "e" => 5
	 *         ),
	 *         "a" => array(
	 *                 "d" => 4,
	 *                 "b" => 2,
	 *                 "a" => 1,
	 *                 "e" => 5,
	 *                 "c" => 3
	 *         ),
	 *         "b" => array(
	 *                 "d" => 4,
	 *                 "b" => 2,
	 *                 "c" => 3,
	 *                 "a" => 1
	 *         )
	 * );
	 *
	 * // Execute //
	 * ksortTree($array);
	 *
	 * // Show //
	 * print_r($array);
	 *
	 * // expects:
	 * // Array
	 * // (
	 * //         [a] => Array
	 * //                 (
	 * //                         [a] => 1
	 * //                         [b] => 2
	 * //                         [c] => 3
	 * //                         [d] => 4
	 * //                         [e] => 5
	 * //                 )
	 * //
	 * //         [b] => Array
	 * //                 (
	 * //                         [a] => 1
	 * //                         [b] => 2
	 * //                         [c] => 3
	 * //                         [d] => 4
	 * //                 )
	 * //
	 * //         [c] => Array
	 * //                 (
	 * //                         [a] => 1
	 * //                         [b] => 2
	 * //                         [c] => 3
	 * //                         [d] => 4
	 * //                         [e] => 5
	 * //                 )
	 * //
	 * // )
	 * </code>
	 *
	 * @author        Kevin van Zonneveld <kevin@vanzonneveld.net>
	 * @copyright     2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	 * @license       http://www.opensource.org/licenses/bsd-license.php New BSD Licence
	 * @version       SVN: Release: $Id: ksortTree.inc.php 223 2009-01-25 13:35:12Z kevin $
	 * @link          http://kevin.vanzonneveld.net/
	 *
	 * @param array $array
	 *
	 * @return bool
	 */
	public static function sortTree( &$array )
	{
		if( !is_array( $array ) )
		{
			return false;
		}

		ksort( $array );
		foreach( $array as $k => $v )
		{
			ksortTree( $array[ $k ] );
		}

		return true;
	}

	/**
	 * Returns new array and preserves until a specific line is found in the old one.
	 * Ideal for rewriting config files with dynamic content, but still allowing
	 * custom rules above that.
	 *
	 * Tip, in combination with file(), consider using the FILE_IGNORE_NEW_LINES flag
	 *
	 * @param array  $original
	 * @param array  $dynamic
	 * @param string $splitLine
	 *
	 * @return array
	 */
	public static function preserveUntil( $original = array(), $dynamic = array(), $splitLine = "# PLEASE DO NOT EDIT BELOW THIS LINE! #" )
	{
		$new = array();

		$splitLineAt = false;
		foreach( $original as $n => $line )
		{
			if( trim( $line ) == trim( $splitLine ) )
			{
				$splitLineAt = $n;
				break;
			}
		}

		if( is_numeric( $splitLineAt ) )
		{
			$new = array_slice( $original, 0, ( $splitLineAt ) );
		}
		else
		{
			// Failsafe. No splitLine found. Preserve entire original.
			$new = $original;
		}

		$new[ ] = $splitLine;
		$new    = array_merge( $new, $dynamic );

		return $new;
	}

	/**
	 * Select of set of elements by some possibility, simple try this function.
	 *
	 * @param     $elements
	 * @param int $n
	 *
	 * @link https://coderwall.com/p/hji52q?&p=5&q=
	 *
	 * @return array
	 */
	public static function getByPossibility( $elements, $n = 1 )
	{

		$selected = array();

		if( !empty( $elements ) )
		{

			$possibility = 0;

			foreach( $elements as $element )
			{
				$possibility += $element->possibility;
			}

			while( count( $selected ) < $n )
			{

				$sum  = 0;
				$rand = rand() / getrandmax();

				foreach( $elements as $element )
				{

					$sum += $element->possibility / $possibility;

					if( $sum > $rand )
					{

						$selected[ ] = $element;
						break;

					}

				}

			}

		}

		return $selected;

	}

	/**
	 * Removs duplicated keys from array
	 *
	 * @param array $arr
	 *
	 * @return array
	 *
	 * @deprecated Use array_unique() http://pt2.php.net/manual/en/function.array-unique.php
	 */
	public static function removeDuplicateKeys( array $arr )
	{
		return array_flip( array_flip( $arr ) );
	}
}