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
}