<?php

class UBDatetime
{

	/**
	 * Calculates the age based on the data of birth
	 *
	 * @param bool $birthDate
	 *
	 * @return bool|int
	 */
	public static function calculateAge( $birthDate = false )
	{
		if( !$birthDate )
		{
			return false;
		}

		//explode the date to get month, day and year
		$birthDate = explode( "-", $birthDate );

		if( count( $birthDate ) != 3 )
		{
			return false;
		}

		//get age from date or birthDate
		$age = ( date( "md", date( "U", mktime( 0, 0, 0, $birthDate[ 0 ], $birthDate[ 1 ], $birthDate[ 2 ] ) ) ) > date( "md" ) ? ( ( date( "Y" ) - $birthDate[ 0 ] ) - 1 ) : ( date( "Y" ) - $birthDate[ 0 ] ) );

		return (int)$age;
	}

	/**
	 * Calculates time diference between 2 dates
	 *
	 * @param     $time1
	 * @param     $time2
	 * @param int $precision
	 *
	 * @return string
	 */
	public static function datetimeDiff( $time1, $time2, $precision = 6 )
	{
		// If not numeric then convert texts to unix timestamps
		if( !is_int( $time1 ) )
		{
			$time1 = strtotime( $time1 );
		}
		if( !is_int( $time2 ) )
		{
			$time2 = strtotime( $time2 );
		}

		// If time1 is bigger than time2
		// Then swap time1 and time2
		if( $time1 > $time2 )
		{
			$ttime = $time1;
			$time1 = $time2;
			$time2 = $ttime;
		}

		// Set up intervals and diffs arrays
		$intervals = array( 'year', 'month', 'day', 'hour', 'minute', 'second' );
		$diffs     = array();

		// Loop thru all intervals
		foreach( $intervals as $interval )
		{
			// Set default diff to 0
			$diffs[ $interval ] = 0;
			// Create temp time from time1 and interval
			$ttime = strtotime( "+1 " . $interval, $time1 );
			// Loop until temp time is smaller than time2
			while( $time2 >= $ttime )
			{
				$time1 = $ttime;
				$diffs[ $interval ]++;
				// Create new temp time from time1 and interval
				$ttime = strtotime( "+1 " . $interval, $time1 );
			}
		}

		$count = 0;
		$times = array();
		// Loop thru all diffs
		foreach( $diffs as $interval => $value )
		{
			// Break if we have needed precission
			if( $count >= $precision )
			{
				break;
			}
			// Add value and interval
			// if value is bigger than 0
			if( $value > 0 )
			{
				// Add s if value is not 1
				if( $value != 1 )
				{
					$interval .= "s";
				}
				// Add value and interval to times array
				// $times[] = $value . " " . $interval;
				$times[ ] = $value;
				$count++;
			}
		}

		// Return string with times
		return implode( ", ", $times );
	}

	function convert2CustomDatetime( $datetime, $format = "Y-m-d G:i:s" )
	{
		if( !isset( $datetime ) || empty( $datetime ) )
		{
			return false;
		}

		// added failsafe for new/old php versions

		if( PHP_VERSION >= 5.3 )
		{
			//dump($datetime, 'first');

			try
			{
				$datetime = new DateTime( $datetime, new DateTimeZone( 'America/New_York' ) );
			}
			catch( Exception $e )
			{
				//echo $e->getMessage();
				exit( 1 );
			}

			//dump($datetime, 'second');

			// dump($datetime->format($format));

			//die;

			return $datetime->format( $format );
		}
		else
		{
			$old_date_timestamp = strtotime( $datetime );
			$new_date           = date( $format, $old_date_timestamp );

			return $new_date;
		}
	}

}