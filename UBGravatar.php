<?php

/**
 * Class UBGravatar
 *
 * Allows for handliing Gravatar stuff
 *
 * @link http://en.gravatar.com/
 * @version 0.1
 */
class UBGravatar
{

	/**
	 * @param        email
	 * @param int    size Size in pixels
	 * @param string default Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]. You can also specify your custom default image over here by passing the complete link to the desired image.
	 * @param string rating Maximum rating (inclusive) [ g | pg | r | x ]
	 *
	 * @link http://www.akshitsethi.me/working-with-gravatar-images-in-php/
	 * @version 0.1
	 * @since 0.1
	 * @todo NOT TESTED
	 * @return string
	 */
	public static function getGravatar( $email, $size = 50, $default = 'monsterid', $rating = 'x' )
	{
		$email   = md5( strtolower( trim( $email ) ) );
		$imgUrl = 'http://www.gravatar.com/avatar/' . $email . '?s=' . $size . '&d=' . $default . '&r=' . $rating;

		return $imgUrl;
	}
}