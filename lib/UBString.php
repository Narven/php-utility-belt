<?php

/**
 * Class UBString
 *
 * Helpers for strings related stuff
 *
 * ex: CamelCasing strings, Generating random strings, utf8 checking
 *
 * @version 0.1
 */
class UBString
{

	/**
	 * CamelCase - Converts any group of words into CamelCase style
	 *
	 * ex: We All Live In America
	 *
	 * @param bool $str
	 *
	 * @return bool|string
	 */
	public static function camelCase( $str = false )
	{
		if( !$str )
		{
			return false;
		}

		$words    = explode( ' ', $str );
		$fullname = '';

		for( $i = 0; $i < count( $words ); $i++ ) // loop each word
		{
			$fullname .= ucfirst( strtolower( $words[ $i ] ) ) . " "; // uppercase first letter
		}

		return trim( $fullname );
	}

	/**
	 * Generates a random string
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	public static function randomString( $length = 8 )
	{
		// start with a blank password
		$password = "";

		// define possible characters - any character in this string can be
		// picked for use in the password, so if you want to put vowels back in
		// or add special characters such as exclamation marks, this is where
		// you should do it
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

		// we refer to the length of $possible a few times, so let's grab it now
		$maxlength = strlen( $possible );

		// check for length overflow and truncate if necessary
		if( $length > $maxlength )
		{
			$length = $maxlength;
		}

		// set up a counter for how many characters are in the password so far
		$i = 0;

		// add random characters to $password until $length is reached
		while( $i < $length )
		{

			// pick a random character from the possible ones
			$char = substr( $possible, mt_rand( 0, $maxlength - 1 ), 1 );

			// have we already used this character in $password?
			if( !strstr( $password, $char ) )
			{
				// no, so it's OK to add it onto the end of whatever we've already got...
				$password .= $char;
				// ... and increase the counter by one
				$i++;
			}

		}

		return $password;
	}

	/**
	 * Abbreviates a string. Chops it down to length specified in $cutAt
	 * The string will never be longer than cutAt, even with the
	 * concatenated $suffix
	 *
	 *
	 * The following code block can be utilized by PEAR's Testing_DocTest
	 * <code>
	 * // Input //
	 * $input = "Kevin and Max go for walk in the park.";
	 *
	 * // Execute //
	 * $output = array();
	 * $output[] = abbreviate($input, 20);
	 * $output[] = abbreviate($input, 10);
	 * $output[] = abbreviate($input, 30, ' [more >>]');
	 *
	 * // Show //
	 * print_r($output);
	 *
	 * // expects:
	 * // Array
	 * // (
	 * //         [0] => Kevin and Max go ...
	 * //         [1] => Kevin a...
	 * //         [2] => Kevin and Max go for [more >>]
	 * // )
	 * </code>
	 *
	 * @param string  $str
	 * @param integer $cutAt
	 * @param string  $suffix
	 *
	 * @author kvz
	 * @link   https://github.com/kvz/kvzlib/blob/master/php/functions/abbreviate.inc.php
	 * @return mixed boolean or string
	 */
	public static function abbreviate( $str, $cutAt = 30, $suffix = '...' )
	{
		if( strlen( $str ) <= $cutAt )
		{
			return $str;
		}

		$canBe = $cutAt - strlen( $suffix );

		return substr( $str, 0, $canBe ) . $suffix;
	}

	/**
	 * Finds a substring between two needles
	 *
	 * The following code block can be utilized by PEAR's Testing_DocTest
	 * <code>
	 * // Input //
	 * $input = "Kevin and Max go for walk in the park.";
	 *
	 * // Execute //
	 * $output = array();
	 * $output[] = strBetween($input, "and ", " go");
	 * $output[] = strBetween($input, "and ", " GO", true, true);
	 *
	 * // Show //
	 * print_r($output);
	 *
	 * // expects:
	 * // Array
	 * // (
	 * //         [0] => Max
	 * //         [1] => and Max go
	 * // )
	 * </code>
	 *
	 * @param string  $haystack
	 * @param string  $left
	 * @param string  $right
	 * @param boolean $include_needles
	 * @param boolean $case_sensitive
	 *
	 * @return mixed boolean or string
	 */
	public static function between( $haystack, $left, $right, $include_needles = false, $case_sensitive = true )
	{
		// Set parameters
		$left      = preg_quote( $left );
		$right     = preg_quote( $right );
		$modifiers = "s";

		// Case insensitive modifier
		if( $case_sensitive )
		{
			$modifiers .= "i";
		}

		// Match
		$pattern = '/(' . $left . ')(.+?)(' . $right . ')/s' . $modifiers;
		if( !preg_match( $pattern, $haystack, $r ) )
		{
			// Not found
			return false;
		}

		// Include needles?
		$return = $r[ 2 ];
		if( $include_needles )
		{
			$return = $r[ 1 ] . $return . $r[ 3 ];
		}

		// Return
		return $return;
	}

	/**
	 * Takes first part of a string based on the delimiter.
	 * Returns that part, and mutates the original string to contain
	 * the reconcatenated remains
	 *
	 * The following code block can be utilized by PEAR's Testing_DocTest
	 * <code>
	 * // Input //
	 * $input = "Kevin and Max go for walk in the park.";
	 *
	 * // Execute //
	 * $output = array();
	 * $output[] = strShift(" ", $input)." - ".$input;
	 * $output[] = strShift(" ", $input)." - ".$input;
	 *
	 * // Show //
	 * print_r($output);
	 *
	 * // expects:
	 * // Array
	 * // (
	 * //         [0] => Kevin - and Max go for walk in the park.
	 * //         [1] => and - Max go for walk in the park.
	 * // )
	 * </code>
	 *
	 * @param string $delimiter
	 * @param string &$string
	 *
	 * @return string
	 */
	public static function shift( $delimiter, &$string )
	{
		// Explode into parts
		$parts = explode( $delimiter, $string );

		// Shift first
		$first = array_shift( $parts );

		// Glue back together, overwrite string by reference
		$string = implode( $delimiter, $parts );

		// Return first part
		return $first;
	}

	/**
	 * PHP's own strip_tags will destroy text after a < character, even if
	 * it's not a real tag. So this is the improved version of strip_tags that
	 * tries to match full tags, and only strips them.
	 *
	 * The following code block can be utilized by PEAR's Testing_DocTest
	 * <code>
	 * // Input //
	 * $input = "Kevin and <b>Max</b> go for walk in the <i>park</i>.";
	 *
	 * // Execute //
	 * $output = array();
	 * $output[] = stripTags($input);
	 *
	 * // Show //
	 * print_r($output);
	 *
	 * // expects:
	 * // Array
	 * // (
	 * //         [0] => Kevin and Max go for walk in the park.
	 * // )
	 * </code>
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	public static function stripTags( $str )
	{
		return preg_replace( '@<\/?([^>]+)\/?>@s', '', $str );
	}

	/**
	 * Checks to see if a string is utf8 encoded.
	 *
	 * NOTE: This function checks for 5-Byte sequences, UTF8
	 *       has Bytes Sequences with a maximum length of 4.
	 *
	 * @author bmorel at ssi dot fr (modified)
	 * @since  1.2.1
	 *
	 * @param string $str The string to be checked
	 *
	 * @return bool True if $str fits a UTF-8 model, false otherwise.
	 */
	public static function isUtf8Encoded( $str )
	{
		$length = strlen( $str );
		for( $i = 0; $i < $length; $i++ )
		{
			$c = ord( $str[ $i ] );
			if( $c < 0x80 )
			{
				$n = 0;
			} # 0bbbbbbb
			elseif( ( $c & 0xE0 ) == 0xC0 )
			{
				$n = 1;
			} # 110bbbbb
			elseif( ( $c & 0xF0 ) == 0xE0 )
			{
				$n = 2;
			} # 1110bbbb
			elseif( ( $c & 0xF8 ) == 0xF0 )
			{
				$n = 3;
			} # 11110bbb
			elseif( ( $c & 0xFC ) == 0xF8 )
			{
				$n = 4;
			} # 111110bb
			elseif( ( $c & 0xFE ) == 0xFC )
			{
				$n = 5;
			} # 1111110b
			else
			{
				return false;
			} # Does not match any model
			for( $j = 0; $j < $n; $j++ )
			{ # n bytes matching 10bbbbbb follow ?
				if( ( ++$i == $length ) || ( ( ord( $str[ $i ] ) & 0xC0 ) != 0x80 ) )
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Converts all accent characters to ASCII characters.
	 *
	 * If there are no accent characters, then the string given is just returned.
	 *
	 * @param string $string Text that might have accent characters
	 *
	 * @return string Filtered string with replaced "nice" characters.
	 */
	public static function removeAccents( $string )
	{
		if( !preg_match( '/[\x80-\xff]/', $string ) )
		{
			return $string;
		}

		if( self::seemsUtf8( $string ) )
		{
			$chars = array(
				// Decompositions for Latin-1 Supplement
				chr( 194 ) . chr( 170 )              => 'a', chr( 194 ) . chr( 186 ) => 'o',
				chr( 195 ) . chr( 128 )              => 'A', chr( 195 ) . chr( 129 ) => 'A',
				chr( 195 ) . chr( 130 )              => 'A', chr( 195 ) . chr( 131 ) => 'A',
				chr( 195 ) . chr( 132 )              => 'A', chr( 195 ) . chr( 133 ) => 'A',
				chr( 195 ) . chr( 134 )              => 'AE', chr( 195 ) . chr( 135 ) => 'C',
				chr( 195 ) . chr( 136 )              => 'E', chr( 195 ) . chr( 137 ) => 'E',
				chr( 195 ) . chr( 138 )              => 'E', chr( 195 ) . chr( 139 ) => 'E',
				chr( 195 ) . chr( 140 )              => 'I', chr( 195 ) . chr( 141 ) => 'I',
				chr( 195 ) . chr( 142 )              => 'I', chr( 195 ) . chr( 143 ) => 'I',
				chr( 195 ) . chr( 144 )              => 'D', chr( 195 ) . chr( 145 ) => 'N',
				chr( 195 ) . chr( 146 )              => 'O', chr( 195 ) . chr( 147 ) => 'O',
				chr( 195 ) . chr( 148 )              => 'O', chr( 195 ) . chr( 149 ) => 'O',
				chr( 195 ) . chr( 150 )              => 'O', chr( 195 ) . chr( 153 ) => 'U',
				chr( 195 ) . chr( 154 )              => 'U', chr( 195 ) . chr( 155 ) => 'U',
				chr( 195 ) . chr( 156 )              => 'U', chr( 195 ) . chr( 157 ) => 'Y',
				chr( 195 ) . chr( 158 )              => 'TH', chr( 195 ) . chr( 159 ) => 's',
				chr( 195 ) . chr( 160 )              => 'a', chr( 195 ) . chr( 161 ) => 'a',
				chr( 195 ) . chr( 162 )              => 'a', chr( 195 ) . chr( 163 ) => 'a',
				chr( 195 ) . chr( 164 )              => 'a', chr( 195 ) . chr( 165 ) => 'a',
				chr( 195 ) . chr( 166 )              => 'ae', chr( 195 ) . chr( 167 ) => 'c',
				chr( 195 ) . chr( 168 )              => 'e', chr( 195 ) . chr( 169 ) => 'e',
				chr( 195 ) . chr( 170 )              => 'e', chr( 195 ) . chr( 171 ) => 'e',
				chr( 195 ) . chr( 172 )              => 'i', chr( 195 ) . chr( 173 ) => 'i',
				chr( 195 ) . chr( 174 )              => 'i', chr( 195 ) . chr( 175 ) => 'i',
				chr( 195 ) . chr( 176 )              => 'd', chr( 195 ) . chr( 177 ) => 'n',
				chr( 195 ) . chr( 178 )              => 'o', chr( 195 ) . chr( 179 ) => 'o',
				chr( 195 ) . chr( 180 )              => 'o', chr( 195 ) . chr( 181 ) => 'o',
				chr( 195 ) . chr( 182 )              => 'o', chr( 195 ) . chr( 184 ) => 'o',
				chr( 195 ) . chr( 185 )              => 'u', chr( 195 ) . chr( 186 ) => 'u',
				chr( 195 ) . chr( 187 )              => 'u', chr( 195 ) . chr( 188 ) => 'u',
				chr( 195 ) . chr( 189 )              => 'y', chr( 195 ) . chr( 190 ) => 'th',
				chr( 195 ) . chr( 191 )              => 'y', chr( 195 ) . chr( 152 ) => 'O',
				// Decompositions for Latin Extended-A
				chr( 196 ) . chr( 128 )              => 'A', chr( 196 ) . chr( 129 ) => 'a',
				chr( 196 ) . chr( 130 )              => 'A', chr( 196 ) . chr( 131 ) => 'a',
				chr( 196 ) . chr( 132 )              => 'A', chr( 196 ) . chr( 133 ) => 'a',
				chr( 196 ) . chr( 134 )              => 'C', chr( 196 ) . chr( 135 ) => 'c',
				chr( 196 ) . chr( 136 )              => 'C', chr( 196 ) . chr( 137 ) => 'c',
				chr( 196 ) . chr( 138 )              => 'C', chr( 196 ) . chr( 139 ) => 'c',
				chr( 196 ) . chr( 140 )              => 'C', chr( 196 ) . chr( 141 ) => 'c',
				chr( 196 ) . chr( 142 )              => 'D', chr( 196 ) . chr( 143 ) => 'd',
				chr( 196 ) . chr( 144 )              => 'D', chr( 196 ) . chr( 145 ) => 'd',
				chr( 196 ) . chr( 146 )              => 'E', chr( 196 ) . chr( 147 ) => 'e',
				chr( 196 ) . chr( 148 )              => 'E', chr( 196 ) . chr( 149 ) => 'e',
				chr( 196 ) . chr( 150 )              => 'E', chr( 196 ) . chr( 151 ) => 'e',
				chr( 196 ) . chr( 152 )              => 'E', chr( 196 ) . chr( 153 ) => 'e',
				chr( 196 ) . chr( 154 )              => 'E', chr( 196 ) . chr( 155 ) => 'e',
				chr( 196 ) . chr( 156 )              => 'G', chr( 196 ) . chr( 157 ) => 'g',
				chr( 196 ) . chr( 158 )              => 'G', chr( 196 ) . chr( 159 ) => 'g',
				chr( 196 ) . chr( 160 )              => 'G', chr( 196 ) . chr( 161 ) => 'g',
				chr( 196 ) . chr( 162 )              => 'G', chr( 196 ) . chr( 163 ) => 'g',
				chr( 196 ) . chr( 164 )              => 'H', chr( 196 ) . chr( 165 ) => 'h',
				chr( 196 ) . chr( 166 )              => 'H', chr( 196 ) . chr( 167 ) => 'h',
				chr( 196 ) . chr( 168 )              => 'I', chr( 196 ) . chr( 169 ) => 'i',
				chr( 196 ) . chr( 170 )              => 'I', chr( 196 ) . chr( 171 ) => 'i',
				chr( 196 ) . chr( 172 )              => 'I', chr( 196 ) . chr( 173 ) => 'i',
				chr( 196 ) . chr( 174 )              => 'I', chr( 196 ) . chr( 175 ) => 'i',
				chr( 196 ) . chr( 176 )              => 'I', chr( 196 ) . chr( 177 ) => 'i',
				chr( 196 ) . chr( 178 )              => 'IJ', chr( 196 ) . chr( 179 ) => 'ij',
				chr( 196 ) . chr( 180 )              => 'J', chr( 196 ) . chr( 181 ) => 'j',
				chr( 196 ) . chr( 182 )              => 'K', chr( 196 ) . chr( 183 ) => 'k',
				chr( 196 ) . chr( 184 )              => 'k', chr( 196 ) . chr( 185 ) => 'L',
				chr( 196 ) . chr( 186 )              => 'l', chr( 196 ) . chr( 187 ) => 'L',
				chr( 196 ) . chr( 188 )              => 'l', chr( 196 ) . chr( 189 ) => 'L',
				chr( 196 ) . chr( 190 )              => 'l', chr( 196 ) . chr( 191 ) => 'L',
				chr( 197 ) . chr( 128 )              => 'l', chr( 197 ) . chr( 129 ) => 'L',
				chr( 197 ) . chr( 130 )              => 'l', chr( 197 ) . chr( 131 ) => 'N',
				chr( 197 ) . chr( 132 )              => 'n', chr( 197 ) . chr( 133 ) => 'N',
				chr( 197 ) . chr( 134 )              => 'n', chr( 197 ) . chr( 135 ) => 'N',
				chr( 197 ) . chr( 136 )              => 'n', chr( 197 ) . chr( 137 ) => 'N',
				chr( 197 ) . chr( 138 )              => 'n', chr( 197 ) . chr( 139 ) => 'N',
				chr( 197 ) . chr( 140 )              => 'O', chr( 197 ) . chr( 141 ) => 'o',
				chr( 197 ) . chr( 142 )              => 'O', chr( 197 ) . chr( 143 ) => 'o',
				chr( 197 ) . chr( 144 )              => 'O', chr( 197 ) . chr( 145 ) => 'o',
				chr( 197 ) . chr( 146 )              => 'OE', chr( 197 ) . chr( 147 ) => 'oe',
				chr( 197 ) . chr( 148 )              => 'R', chr( 197 ) . chr( 149 ) => 'r',
				chr( 197 ) . chr( 150 )              => 'R', chr( 197 ) . chr( 151 ) => 'r',
				chr( 197 ) . chr( 152 )              => 'R', chr( 197 ) . chr( 153 ) => 'r',
				chr( 197 ) . chr( 154 )              => 'S', chr( 197 ) . chr( 155 ) => 's',
				chr( 197 ) . chr( 156 )              => 'S', chr( 197 ) . chr( 157 ) => 's',
				chr( 197 ) . chr( 158 )              => 'S', chr( 197 ) . chr( 159 ) => 's',
				chr( 197 ) . chr( 160 )              => 'S', chr( 197 ) . chr( 161 ) => 's',
				chr( 197 ) . chr( 162 )              => 'T', chr( 197 ) . chr( 163 ) => 't',
				chr( 197 ) . chr( 164 )              => 'T', chr( 197 ) . chr( 165 ) => 't',
				chr( 197 ) . chr( 166 )              => 'T', chr( 197 ) . chr( 167 ) => 't',
				chr( 197 ) . chr( 168 )              => 'U', chr( 197 ) . chr( 169 ) => 'u',
				chr( 197 ) . chr( 170 )              => 'U', chr( 197 ) . chr( 171 ) => 'u',
				chr( 197 ) . chr( 172 )              => 'U', chr( 197 ) . chr( 173 ) => 'u',
				chr( 197 ) . chr( 174 )              => 'U', chr( 197 ) . chr( 175 ) => 'u',
				chr( 197 ) . chr( 176 )              => 'U', chr( 197 ) . chr( 177 ) => 'u',
				chr( 197 ) . chr( 178 )              => 'U', chr( 197 ) . chr( 179 ) => 'u',
				chr( 197 ) . chr( 180 )              => 'W', chr( 197 ) . chr( 181 ) => 'w',
				chr( 197 ) . chr( 182 )              => 'Y', chr( 197 ) . chr( 183 ) => 'y',
				chr( 197 ) . chr( 184 )              => 'Y', chr( 197 ) . chr( 185 ) => 'Z',
				chr( 197 ) . chr( 186 )              => 'z', chr( 197 ) . chr( 187 ) => 'Z',
				chr( 197 ) . chr( 188 )              => 'z', chr( 197 ) . chr( 189 ) => 'Z',
				chr( 197 ) . chr( 190 )              => 'z', chr( 197 ) . chr( 191 ) => 's',
				// Decompositions for Latin Extended-B
				chr( 200 ) . chr( 152 )              => 'S', chr( 200 ) . chr( 153 ) => 's',
				chr( 200 ) . chr( 154 )              => 'T', chr( 200 ) . chr( 155 ) => 't',
				// Euro Sign
				chr( 226 ) . chr( 130 ) . chr( 172 ) => 'E',
				// GBP (Pound) Sign
				chr( 194 ) . chr( 163 )              => '',
				// Vowels with diacritic (Vietnamese)
				// unmarked
				chr( 198 ) . chr( 160 )              => 'O', chr( 198 ) . chr( 161 ) => 'o',
				chr( 198 ) . chr( 175 )              => 'U', chr( 198 ) . chr( 176 ) => 'u',
				// grave accent
				chr( 225 ) . chr( 186 ) . chr( 166 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 167 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 176 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 177 ) => 'a',
				chr( 225 ) . chr( 187 ) . chr( 128 ) => 'E', chr( 225 ) . chr( 187 ) . chr( 129 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 146 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 147 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 156 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 157 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 170 ) => 'U', chr( 225 ) . chr( 187 ) . chr( 171 ) => 'u',
				chr( 225 ) . chr( 187 ) . chr( 178 ) => 'Y', chr( 225 ) . chr( 187 ) . chr( 179 ) => 'y',
				// hook
				chr( 225 ) . chr( 186 ) . chr( 162 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 163 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 168 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 169 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 178 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 179 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 186 ) => 'E', chr( 225 ) . chr( 186 ) . chr( 187 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 130 ) => 'E', chr( 225 ) . chr( 187 ) . chr( 131 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 136 ) => 'I', chr( 225 ) . chr( 187 ) . chr( 137 ) => 'i',
				chr( 225 ) . chr( 187 ) . chr( 142 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 143 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 148 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 149 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 158 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 159 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 166 ) => 'U', chr( 225 ) . chr( 187 ) . chr( 167 ) => 'u',
				chr( 225 ) . chr( 187 ) . chr( 172 ) => 'U', chr( 225 ) . chr( 187 ) . chr( 173 ) => 'u',
				chr( 225 ) . chr( 187 ) . chr( 182 ) => 'Y', chr( 225 ) . chr( 187 ) . chr( 183 ) => 'y',
				// tilde
				chr( 225 ) . chr( 186 ) . chr( 170 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 171 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 180 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 181 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 188 ) => 'E', chr( 225 ) . chr( 186 ) . chr( 189 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 132 ) => 'E', chr( 225 ) . chr( 187 ) . chr( 133 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 150 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 151 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 160 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 161 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 174 ) => 'U', chr( 225 ) . chr( 187 ) . chr( 175 ) => 'u',
				chr( 225 ) . chr( 187 ) . chr( 184 ) => 'Y', chr( 225 ) . chr( 187 ) . chr( 185 ) => 'y',
				// acute accent
				chr( 225 ) . chr( 186 ) . chr( 164 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 165 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 174 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 175 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 190 ) => 'E', chr( 225 ) . chr( 186 ) . chr( 191 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 144 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 145 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 154 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 155 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 168 ) => 'U', chr( 225 ) . chr( 187 ) . chr( 169 ) => 'u',
				// dot below
				chr( 225 ) . chr( 186 ) . chr( 160 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 161 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 172 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 173 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 182 ) => 'A', chr( 225 ) . chr( 186 ) . chr( 183 ) => 'a',
				chr( 225 ) . chr( 186 ) . chr( 184 ) => 'E', chr( 225 ) . chr( 186 ) . chr( 185 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 134 ) => 'E', chr( 225 ) . chr( 187 ) . chr( 135 ) => 'e',
				chr( 225 ) . chr( 187 ) . chr( 138 ) => 'I', chr( 225 ) . chr( 187 ) . chr( 139 ) => 'i',
				chr( 225 ) . chr( 187 ) . chr( 140 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 141 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 152 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 153 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 162 ) => 'O', chr( 225 ) . chr( 187 ) . chr( 163 ) => 'o',
				chr( 225 ) . chr( 187 ) . chr( 164 ) => 'U', chr( 225 ) . chr( 187 ) . chr( 165 ) => 'u',
				chr( 225 ) . chr( 187 ) . chr( 176 ) => 'U', chr( 225 ) . chr( 187 ) . chr( 177 ) => 'u',
				chr( 225 ) . chr( 187 ) . chr( 180 ) => 'Y', chr( 225 ) . chr( 187 ) . chr( 181 ) => 'y',
			);

			$string = strtr( $string, $chars );
		}
		else
		{
			// Assume ISO-8859-1 if not UTF-8
			$chars[ 'in' ] = chr( 128 ) . chr( 131 ) . chr( 138 ) . chr( 142 ) . chr( 154 ) . chr( 158 )
				. chr( 159 ) . chr( 162 ) . chr( 165 ) . chr( 181 ) . chr( 192 ) . chr( 193 ) . chr( 194 )
				. chr( 195 ) . chr( 196 ) . chr( 197 ) . chr( 199 ) . chr( 200 ) . chr( 201 ) . chr( 202 )
				. chr( 203 ) . chr( 204 ) . chr( 205 ) . chr( 206 ) . chr( 207 ) . chr( 209 ) . chr( 210 )
				. chr( 211 ) . chr( 212 ) . chr( 213 ) . chr( 214 ) . chr( 216 ) . chr( 217 ) . chr( 218 )
				. chr( 219 ) . chr( 220 ) . chr( 221 ) . chr( 224 ) . chr( 225 ) . chr( 226 ) . chr( 227 )
				. chr( 228 ) . chr( 229 ) . chr( 231 ) . chr( 232 ) . chr( 233 ) . chr( 234 ) . chr( 235 )
				. chr( 236 ) . chr( 237 ) . chr( 238 ) . chr( 239 ) . chr( 241 ) . chr( 242 ) . chr( 243 )
				. chr( 244 ) . chr( 245 ) . chr( 246 ) . chr( 248 ) . chr( 249 ) . chr( 250 ) . chr( 251 )
				. chr( 252 ) . chr( 253 ) . chr( 255 );

			$chars[ 'out' ] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

			$string                = strtr( $string, $chars[ 'in' ], $chars[ 'out' ] );
			$double_chars[ 'in' ]  = array( chr( 140 ), chr( 156 ), chr( 198 ), chr( 208 ), chr( 222 ), chr( 223 ), chr( 230 ), chr( 240 ), chr( 254 ) );
			$double_chars[ 'out' ] = array( 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' );
			$string                = str_replace( $double_chars[ 'in' ], $double_chars[ 'out' ], $string );
		}

		return $string;
	}

	/**
	 * Prints given arguments between pre tags using print_r
	 *
	 * @internal param mixed $arguments Multiple mixed arguments to print
	 */
	public static function pre()
	{
		$count = func_num_args();

		$args = func_get_args();

		for( $i = 0; $i < $count; $i++ )
		{
			echo '<pre>';
			print_r( $args[ $i ] );
			echo '</pre>';
		}
	}

	/**
	 * Sanitizes title
	 */
	public static function sanitize( $title )
	{
		$title = self::remove_accents( $title );
		$title = strip_tags( $title );
		// Preserve escaped octets.
		$title = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title );
		// Remove percent signs that are not part of an octet.
		$title = str_replace( '%', '', $title );
		// Restore octets.
		$title = preg_replace( '|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title );

		if( self::seems_utf8( $title ) )
		{
			if( function_exists( 'mb_strtolower' ) )
			{
				$title = mb_strtolower( $title, 'UTF-8' );
			}
			$title = self::utf8_uri_encode( $title, 200 );
		}

		$title = strtolower( $title );
		$title = preg_replace( '/&.+?;/', '', $title ); // kill entities
		$title = str_replace( '.', '-', $title );

		$title = preg_replace( '/[^%a-z0-9 _-]/', '', $title );
		$title = preg_replace( '/\s+/', '-', $title );
		$title = preg_replace( '|-+|', '-', $title );
		$title = trim( $title, '-' );

		return $title;
	}

	/**
	 * Excerpt
	 *
	 * If you want to have a pretty looking excerpt of a longer string
	 *
	 * @param        $string
	 * @param int    $minLength
	 * @param string $chars
	 * @param string $extension
	 *
	 * @link https://coderwall.com/p/73ndcw?&p=5&q=
	 *
	 * @return string
	 */
	public static function excerpt( $string, $minLength = 255, $chars = ' ', $extension = '...' )
	{

		$length = strlen( $string );

		if( $length <= $minLength )
		{
			return $string;
		}

		$excerptLength = ( $length < $minLength ) ? 0 : $minLength;

		$pos = null;

		foreach( (array)$chars as $char )
		{

			$tmp = strpos( $string, $char, $excerptLength );

			if( ( is_null( $pos ) || $tmp < $pos ) && $tmp )
			{
				$pos = $tmp + strlen( $char );
			}

		}

		$excerpt = is_null( $pos ) ? substr( $string, 0, $excerptLength ) : substr( $string, 0, $pos );

		return $excerpt . $extension;

	}

	/**
	 * Extract keywords from a webpage
	 *
	 * @param url|null $url
	 * @param string   $what
	 *
	 * @return array
	 * @todo not tested
	 * @link http://www.catswhocode.com/blog/useful-snippets-for-php-developers
	 */
	public static function extractMetaFromUrl( $url = null, $what = 'keywords' )
	{
		$meta = get_meta_tags( $url );

		$keywords = $meta[ $what ];

		// Split keywords
		$keywords = explode( ',', $keywords );

		// Trim them
		$keywords = array_map( 'trim', $keywords );

		// Remove empty values
		$keywords = array_filter( $keywords );

		return $keywords;
	}

	/**
	 * Find All Links on a Page
	 *
	 * @param null $url
	 *
	 * @return void
	 * @link http://www.catswhocode.com/blog/useful-snippets-for-php-developers
	 */
	public static function getAllLinksInPage( $url = null )
	{
		$html = file_get_contents( $url );

		$dom = new DOMDocument();
		@$dom->loadHTML( $html );

		// grab all the on the page
		$xpath = new DOMXPath( $dom );
		$hrefs = $xpath->evaluate( "/html/body//a" );

		for( $i = 0; $i < $hrefs->length; $i++ )
		{
			$href = $hrefs->item( $i );
			$url  = $href->getAttribute( 'href' );
			echo $url . '<br />';
		}
	}

	/**
	 * Add (th, st, nd, rd, th) to the end of a number
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public static function ordinal( $value )
	{
		$test_c = abs( $value ) % 10;
		$ext    = ( ( abs( $value ) % 100 < 21 && abs( $value ) % 100 > 4 ) ? 'th'
			: ( ( $test_c < 4 ) ? ( $test_c < 3 ) ? ( $test_c < 2 ) ? ( $test_c < 1 )
				? 'th' : 'st' : 'nd' : 'rd' : 'th' ) );

		return $value . $ext;
	}

	/**
	 * Automatic mailto links
	 *
	 * The following snippet looks for an email address in a string,
	 * and replace it by a mailto link. Pretty useful on private
	 * applications, but for obvious spamming reason I do not
	 * recommend using this on a website, blog or forum.
	 *
	 * @param string $stringa
	 *
	 * @return string
	 */
	public static function stringToMailTo( $stringa = '' )
	{
		// $stringa = "This should format my email address example@domain.com";

		$pattern = "/([a-z0-9][_a-z0-9.-]+@([0-9a-z][_0-9a-z-]+\.)+[a-z]{2,6})/i";
		$replace = "\\1";
		$text    = preg_replace( $pattern, $replace, $stringa );

		return htmlspecialchars( $text );
	}

	/**
	 * Simple random quote generator.
	 *
	 * @param $quote
	 */
	public static function randomQuote( $quote )
	{
		/*
		 $quote = array(
			1 => 'Quote 1',
			2 => 'Quote 2',
			3 => 'Quote 3',
			4 => 'Quote 4',
			5 => 'Quote 5',
		);
		 */
		srand( (double)microtime() * 1000000 );
		$randnum = rand( 1, count( $quote ) );

		echo $quote[ $randnum ];
	}

	/**
	 * Cleaning a string
	 *
	 * @param $string
	 *
	 * @link http://www.emoticode.net/php/cleaning-a-string.html
	 *
	 * @return mixed|string
	 */
	public static function clean( $string )
	{
		//$string = strtolower($string);

		// Fix german special chars
		$string = preg_replace( '/[Ã¤Ã„]/', 'ae', $string );
		$string = preg_replace( '/[Ã¼Ãœ]/', 'ue', $string );
		$string = preg_replace( '/[Ã¶Ã–]/', 'oe', $string );
		$string = preg_replace( '/[ÃŸ]/', 'ss', $string );

		// Replace other special chars
		$specialCharacters = array(
			'#'   => 'sharp',
			'$'   => 'dollar',
			'%'   => 'prozent', //'percent',
			'&'   => 'und', //'and',
			'@'   => 'at',
			'.'   => 'punkt', //'dot',
			'â‚¬' => 'euro',
			'+'   => 'plus',
			'='   => 'gleich', //'equals',
			'Â§'  => 'paragraph',
		);

		while( list( $character, $replacement ) = each( $specialCharacters ) )
		{
			$string = str_replace( $character, '-' . $replacement . '-', $string );
		}

		$string = strtr( $string,
			"Ã€ÃÃ‚ÃƒÃ„Ã…Ã Ã¡Ã¢Ã£Ã¤Ã¥Ã’Ã“Ã”Ã•Ã–Ã˜Ã²Ã³Ã´ÃµÃ¶Ã¸ÃˆÃ‰ÃŠÃ‹Ã¨Ã©ÃªÃ«Ã‡Ã§ÃŒÃÃŽÃÃ¬Ã­Ã®Ã¯Ã™ÃšÃ›ÃœÃ¹ÃºÃ»Ã¼Ã¿Ã‘Ã±",
			"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
		);

		// Remove all remaining other unknown characters
		$string = preg_replace( '/[^a-zA-Z0-9\-]/', '-', $string );
		$string = preg_replace( '/^[\-]+/', '', $string );
		$string = preg_replace( '/[\-]+$/', '', $string );
		$string = preg_replace( '/[\-]{2,}/', '-', $string );

		return $string;
	}

	/**
	 * Truncate String
	 *
	 * @param $string
	 * @param $maxlength
	 * @param $extension
	 *
	 * @return array|string
	 * @link http://www.emoticode.net/php/truncate-string-2.html
	 */
	public static function truncate( $string, $maxlength, $extension )
	{

		// Set the replacement for the "string break" in the wordwrap function
		$cutmarker = "**cut_here**";

		// Checking if the given string is longer than $maxlength
		if( strlen( $string ) > $maxlength )
		{

			// Using wordwrap() to set the cutmarker
			// NOTE: wordwrap (PHP 4 >= 4.0.2, PHP 5)
			$string = wordwrap( $string, $maxlength, $cutmarker );

			// Exploding the string at the cutmarker, set by wordwrap()
			$string = explode( $cutmarker, $string );

			// Adding $extension to the first value of the array $string, returned by explode()
			$string = $string[ 0 ] . $extension;
		}

		// returning $string
		return $string;
	}

	/**
	 * Increments a given string by given interval.
	 * An optional array with forbidden return values may be passed.
	 *
	 * @param string $string    String to Increment
	 * @param int    $increment Increment value, 1 by default
	 * @param array  $forbidden Array with strings the function must not return.
	 *
	 * @return string Incremented string
	 * @uses    String_Increment()
	 * @author  Carsten Witt <carsten.witt@gmail.com>
	 * @version 20060706-2230
	 * @link    http://www.emoticode.net/php/increment-string.html
	 *
	 * @return string
	 */
	public static function increment( $string, $increment = 1, $forbidden = array() )
	{
		$regex = "(_?)([0-9]+)$";
		ereg( $regex, $string, $regs );
		$z      = empty( $regs ) ? '' : $regs[ 2 ];
		$neu    = ( (int)$z ) + $increment;
		$string = ereg_replace( ( ( $z == '' ) ? "$" : $regs[ 0 ] . "$" ), ( (string)$regs[ 1 ] ) . ( (string)$neu ), $string );
		if( in_array( $string, $forbidden ) )
		{
			$string = String_Increment( $string, $increment, $array );
		}

		return $string;
	}

	/**
	 * Make a "SEO Friendly URL" string.
	 *
	 * @param $string
	 *
	 * @return string
	 * @link http://www.emoticode.net/php/make-a-seo-friendly-url-string.html
	 */
	public static function niceUrl( $string )
	{
		$string = preg_replace( "`\[.*\]`U", "", $string );
		$string = preg_replace( '`&(amp;)?#?[a-z0-9]+;`i', '-', $string );
		$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string );
		$string = preg_replace( array( "`[^a-z0-9]`i", "`[-]+`" ), "-", $string );
		$string = htmlentities( $string, ENT_COMPAT, 'utf-8' );

		return strtolower( trim( $string, '-' ) );
	}

	/**
	 * Convert string to underscore_name
	 *
	 * Converts "My House" to "my_house".
	 * Converts " Peter's nice car " to "peters_nice_car".
	 * Converts "_88" to "88"
	 *
	 * @param $string
	 *
	 * @return mixed|string
	 * @link http://www.emoticode.net/php/convert-string-to-underscore_name.html
	 */
	public static function toUnderscore( $string )
	{
		$string = preg_replace( '/[\'"]/', '', $string );
		$string = preg_replace( '/[^a-zA-Z0-9]+/', '_', $string );
		$string = trim( $string, '_' );
		$string = strtolower( $string );

		return $string;
	}
}