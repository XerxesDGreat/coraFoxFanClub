<?php
/*
 * JPlayer for Joomla! 1.5
 * Author: MaxSVK
 * Version: 1.5.4
 * Last Update: 2011-05-29
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$tagReplace = array(

"flv" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="{FULLSCREEN}" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SITEURL}/{FOLDER}/{SOURCE}.flv&image={SITEURL}/{FOLDER}/{SOURCE}.png&autostart={AUTOPLAY}&fullscreen={FULLSCREEN}{FLASHVARS}{PLUGINS}" />
</object>
',

"flvremote" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="{FULLSCREEN}" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SOURCE}&image={SOURCE}.png&autostart={AUTOPLAY}&fullscreen={FULLSCREEN}{FLASHVARS}{PLUGINS}" />
</object>
',

"mp3" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="false" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SITEURL}/{FOLDER}/{SOURCE}.mp3&autostart={AUTOPLAY}{FLASHVARS}{PLUGINS}" />
</object>
',

"mp3playlist" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="false" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SITEURL}/{FOLDER}/{SOURCE}.xml&autostart={AUTOPLAY}{FLASHVARS}{PLAYLIST}" />
</object>
',

"mp3remote" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="false" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SOURCE}&autostart={AUTOPLAY}{FLASHVARS}" />
</object>
',

"mp4" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="{FULLSCREEN}" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SITEURL}/{FOLDER}/{SOURCE}.mp4&image={SITEURL}/{FOLDER}/{SOURCE}.png&autostart={AUTOPLAY}&fullscreen={FULLSCREEN}{FLASHVARS}{PLUGINS}" />
</object>
',

"mp4remote" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="{FULLSCREEN}" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SOURCE}&image={SOURCE}.png&autostart={AUTOPLAY}&fullscreen={FULLSCREEN}{FLASHVARS}{PLUGINS}" />
</object>
',

"videoplaylist" => '
<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf">
	<param name="movie" value="{SITEURL}/plugins/content/jplayer/mediaplayer/player{PLAYERVERSION}.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="{TRANSPARENCY}" />
	<param name="bgcolor" value="{BACKGROUND}" />
	<param name="autoplay" value="{AUTOPLAY}" />
	<param name="allowfullscreen" value="{FULLSCREEN}" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars" value="file={SITEURL}/{FOLDER}/{SOURCE}.xml&autostart={AUTOPLAY}&fullscreen={FULLSCREEN}{PLAYLIST}{FLASHVARS}" />
</object>
',

"youtube" => '
<object width="{YOUTUBEWIDTH}" height="{YOUTUBEHEIGHT}">
	<param name="movie" value="http://www.youtube.com/v/{YOUTUBECODE}&hl=en_US&fs=1&{YOUTUBEPARAMETERS}"></param>
	<param name="allowFullScreen" value="true"></param>
	<param name="allowscriptaccess" value="always"></param>
	<embed
		src="http://www.youtube.com/v/{YOUTUBECODE}&hl=en_US&fs=1&{YOUTUBEPARAMETERS}"
		type="application/x-shockwave-flash"
		allowscriptaccess="always"
		allowfullscreen="true"
		width="{YOUTUBEWIDTH}"
		height="{YOUTUBEHEIGHT}">
	</embed>
</object>
',

);
?>
