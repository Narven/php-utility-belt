<?php

/**
 * Class UBMath
 *
 * Helpers for math related stuff
 *
 * @version 0.1
 */
class UBMath
{
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
	 * Calculate distance between two points
	 *
	 * Want to be able to calculate the distance between two points?
	 * The function below use the latitude and longitude of two locations,
	 * and calculate the distance between them in both miles and metric units.
	 *
	 * @param $latitude1
	 * @param $longitude1
	 * @param $latitude2
	 * @param $longitude2
	 *
	 * @return array
	 */
	public static function calculateDistanceBetweenPoints( $latitude1, $longitude1, $latitude2, $longitude2 )
	{
		$theta      = $longitude1 - $longitude2;
		$miles      = ( sin( deg2rad( $latitude1 ) ) * sin( deg2rad( $latitude2 ) ) ) + ( cos( deg2rad( $latitude1 ) ) * cos( deg2rad( $latitude2 ) ) * cos( deg2rad( $theta ) ) );
		$miles      = acos( $miles );
		$miles      = rad2deg( $miles );
		$miles      = $miles * 60 * 1.1515;
		$feet       = $miles * 5280;
		$yards      = $feet / 3;
		$kilometers = $miles * 1.609344;
		$meters     = $kilometers * 1000;

		return compact( 'miles', 'feet', 'yards', 'kilometers', 'meters' );
	}
}