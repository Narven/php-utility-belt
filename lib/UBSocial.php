<?php

/**
 * Class UBSocial
 *
 */
class UBSocial
{
	/**
	 * @param        email
	 * @param        int    size Size in pixels
	 * @param        string default Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]. You can also specify your custom default image over here by passing the complete link to the desired image.
	 * @param        string rating Maximum rating (inclusive) [ g | pg | r | x ]
	 *
	 * @link    http://www.akshitsethi.me/working-with-gravatar-images-in-php/
	 * @version 0.1
	 * @since   0.1
	 * @todo    NOT TESTED
	 * @return string
	 */
	public static function gravatar( $email, $size = 50, $default = 'monsterid', $rating = 'x' )
	{
		$email  = md5( strtolower( trim( $email ) ) );
		$imgUrl = 'http://www.gravatar.com/avatar/' . $email . '?s=' . $size . '&d=' . $default . '&r=' . $rating;

		return $imgUrl;
	}

	/**
	 * Get all tweets of a specific hashtag
	 *
	 * @param $hashTag
	 *
	 * @return bool
	 */
	public static function getTweets( $hashTag )
	{
		$url = 'http://search.twitter.com/search.atom?q=' . urlencode( $hashTag );

		echo "<p>Connecting to <strong>$url</strong> ...</p>";
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$xml = curl_exec( $ch );
		curl_close( $ch );

		//If you want to see the response from Twitter, uncomment this next part out:
		//echo "<p>Response:</p>";
		//echo "<pre>".htmlspecialchars($xml)."</pre>";

		$affected  = 0;
		$twelement = new SimpleXMLElement( $xml );
		foreach( $twelement->entry as $entry )
		{
			$text   = trim( $entry->title );
			$author = trim( $entry->author->name );
			$time   = strtotime( $entry->published );
			$id     = $entry->id;

			echo "<p>Tweet from " . $author . ": <strong>" . $text . "</strong>  <em>Posted " . date( 'n/j/y g:i a', $time ) . "</em></p>";
		}

		return true;
	}

	/**
	 * Retrieve the number of Facebook page followers
	 *
	 * @param $pageId
	 *
	 * @return mixed

	 * //getting skype status icon
		$ico = get_skype_status("ar2rsawseen", true, true);
		echo "<p>Skype icon:</p>";
		echo "<p><img src='".$ico."'/></p>";

		//getting skype status image
		$image = get_skype_status("ar2rsawseen", true);
		echo "<p>Skype image:</p>";
		echo "<p><img src='".$image."'/></p>";

		//getting skype status text
		$status = get_skype_status("ar2rsawseen");
		echo "<p>Skype status:</p>";
		echo "<p>".$status."</p>";
	 *
	 */
	public static function getFacebookFollowers( $pageId )
	{
		$json = file_get_contents( 'http://api.facebook.com/method/fql.query?format=json&query=select+fan_count+from+page+where+page_id%' . $pageId );

		$decode = json_decode( $json );

		return $decode[ 0 ]->fan_count;
	}

	/**
	 * Use Skype API to detect if a given user is online.
	 *
	 * This is how to use the API of mystatus.skype.com service
	 * to retrieve a given user status as an image or as a localized string.
	 *
	 * @param      $username
	 * @param bool $image
	 * @param bool $icon
	 *
	 * @return string
	 */
	public static function getSkypeStatus( $username, $image = false, $icon = false )
	{
		//creating url
		//if you need small icon
		if( $image && $icon )
		{
			/***************************************
			 * Possible types of images:
			 * balloon            - Balloon style
			 * bigclassic        - Big Classic Style
			 * smallclassic        - Small Classic Style
			 * smallicon        - Small Icon (transparent background)
			 * mediumicon        - Medium Icon
			 * dropdown-white    - Dropdown White Background
			 * dropdown-trans    - Dropdown Transparent Background
			 ****************************************/

			return "http://mystatus.skype.com/smallicon/" . $username;
		}
		//if you need image
		else if( $image )
		{
			return "http://mystatus.skype.com/" . $username;
		}
		//or just text
		else
		{
			/***************************************
			 * Possible status  values:
			 * NUM        TEXT                DESCRIPTION
			 * 0     UNKNOWN             Not opted in or no data available.
			 * 1     OFFLINE                 The user is Offline
			 * 2     ONLINE                  The user is Online
			 * 3     AWAY                    The user is Away
			 * 4     NOT AVAILABLE       The user is Not Available
			 * 5     DO NOT DISTURB  The user is Do Not Disturb (DND)
			 * 6     INVISIBLE               The user is Invisible or appears Offline
			 * 7     SKYPE ME                The user is in Skype Me mode
			 ****************************************/
			$url = "http://mystatus.skype.com/" . $username . ".xml";
			//getting contents
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			$data = curl_exec( $curl );
			curl_close( $curl );

			$pattern = '/xml:lang="en">(.*)</';
			preg_match( $pattern, $data, $match );

			return $match[ 1 ];
		}
	}
}