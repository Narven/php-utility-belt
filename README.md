#PHP Utility Belt


List of some PHP utility classes that i've put together

---

##UBArray

* ####extractFirstLevel( array $arr1 )
*Takes all 1-level deep elements and compiles a new array*

		$arr = array(
			array( 'foo0' => 'bar0' )
		);

		$extracted = UBArray::extractFirstLevel( $arr );

		var_dump( $extracted );

   result:

		array (size=1)
			0 => string 'bar0' (length=4)


* ####merge( array $arr1, array $arr2 = null )
*Takes all 1-level deep elements and compiles a new array*

		$arr1 = array(
			array( 'foo1' => 'bar1' )
		);

		$arr2 = array(
			array( 'foo2' => 'bar2' )
		);

		$merged = UBArray::merge($arr1, $arr2);

		var_dump($merged);
	
    result:

		array (size=1)
		  0 => 
    		array (size=2)
	    	  'foo1' => string 'bar1' (length=4)
	    	  'foo2' => string 'bar2' (length=4)


* ####squash( array $array, $useKey = null, $useVal = null )
*Takes 2 elements and compiles an associative array*

* ####find( $needle, array $haystack )
*Recusive alternative to array_key_exists*

* ####sort( $needle, array $haystack )
*Recusive alternative to ksort*

---

##UBDatabase

* ####copyTable( $source, $dest, $options )
*Copies a MySQL Table*

* ####uniqifyRecords( $data, $byFields, $overwrite = true )
*When you can't do a GROUP BY, Use this function to remove records with a number of identical fields.*

* ####bulkImport( &$data, $table, $method = 'transaction', $options = array() )
* Executes multiple queries in a 'bulk' to achieve better performance and integrity.

---

##UBDatetime

* ####calculateAge( $birthDate = false )
*Calculates the age based on the data of birth*

* ####datetimeDiff( $time1, $time2, $precision = 6 )
*Calculates time diference between 2 dates*

* ####convert2CustomDatetime( $datetime, $format = "Y-m-d G:i:s" )
*Converts a datetime format to another format*

---

##UBSocial

* ####gravatar( $email, $size = 50, $default = 'monsterid', $rating = 'x' )
*Gets gravatar image*

---

##UBIo

* ####fileExistsInPath( $file )
*file_exists does not check the include paths. This function does. It was not written by me, I don't know where it's from exactly.*

* ####isFileOk( $path )
*Check if file exists, using CURL*

* ####recursiveGlob( $pattern = '*', $flags = 0, $path = '' )
*Recursively goes through a folder and returns all files.*

* ####googleHTTPGeocoding($address = '', $city = '',$province = '', $country = '', $postalcode = '')
*Getting the Latitude and Longitude of an address using the Google Maps API*

* ####preserveUntil( $original = array(), $dynamic = array(), $splitLine = "# PLEASE DO NOT EDIT BELOW THIS LINE! #" )
*Returns new array and preserves until a specific line is found in the old one. Ideal for rewriting config files with dynamic content, but still allowing custom rules above that.

---

##UBFormat

* ####json2Jsonp( $data )
*Converts JSON to JSONP*

---

##UBMath

* ####getByPossibility( $elements, $n = 1 )
*Select of set of elements by some possibility, simple try this function.*

---

##UBSecurity

* ####hmac( $hashfunc, $key, $data )
*Calculate HMAC according to RFC 2104, for chosen algorithm.*

* ####hmacSha1( $key, $data )
*Calculate HMAC-SHA1 according to RFC 2104.*

* ####hmacMd5( $key, $data )
*Calculate HMAC-MD5 according to RFC 2104.*

* ####hex2b64( $str )
*Convert hex to base64.*

* ####encrypt( $plaintext, $salt )
*Encrypt password*

* ####decrypt( $crypttext, $salt )
*dEncrypt password*

---

##UBString

* ####camelCase( $str = false )
*Converts any group of words into CamelCase style*

* ####randomString( $length = 8 )
*Generates a random string*

* ####abbreviate( $str, $cutAt = 30, $suffix = '...' )
*Abbreviates a string. Chops it down to length specified in $cutAt.The string will never be longer than cutAt, even with the concatenated $suffix*

* ####between( $haystack, $left, $right, $include_needles = false, $case_sensitive = true )
*Finds a substring between two needles*

* ####shift( $delimiter, &$string )
*Takes first part of a string based on the delimiter. Returns that part, and mutates the original string to contain the reconcatenated remains*

* ####stripTags( $str )
*PHP's own strip_tags will destroy text after a < character, even if it's not a real tag. So this is the improved version of strip_tags that tries to match full tags, and only strips them.*

* ####isUtf8Encoded( $str )
*Checks to see if a string is utf8 encoded.*

* ####removeAccents( $string )
*Converts all accent characters to ASCII characters.*

* ####pre()
*Prints given arguments between pre tags using print_r*

* ####sanitize( $title )
*Sanitizes string*

* ####excerpt( $string, $minLength = 255, $chars = ' ', $extension = '...' )
*If you want to have a pretty looking excerpt of a longer string*

---

##UBUrl

* ####utf8UriEncode( $utf8_string, $length = 0 )
*Encode the Unicode values to be used in the URI.*