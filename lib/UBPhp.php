<?php

class UBPhp
{
	public static function postToVar( $expected = array() )
	{
		foreach( $expected as $key )
		{
			if( !empty( $_POST[ $key ] ) )
			{
				${key} = $_POST[ $key ];
			}
			else
			{
				${key} = null;
			}
		}
	}

	/**
	 * How to clear incoming data without put in front
	 * each of them MYSQLREALESCAPE_STRING function.
	 *
	 * @param $_POST
	 */
	public static function mysqlRealEscapeString( $_POST )
	{
		foreach( $_POST as $key => $value )
		{
			$_POST[ $key ] = mysql_real_escape_string( $value );
		}
	}
}