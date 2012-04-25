<?php
/*
 * JPlayer for Joomla! 1.5
 * Author: MaxSVK
 * Version: 1.5.4
 * Last Update: 2011-05-29
 *
 * JW FLV Player
 * Author: Jeroen Wijering
 * ULR: http://www.longtailvideo.com/players/jw-flv-player/
 * Version: 4.3.132 OR 4.7.811
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgContentJplayer extends JPlugin
{

	function plgContentJplayer(&$subject, $params)
	{
		parent::__construct($subject, $params);
	}

	function onPrepareContent(&$article, &$params)
	{
		// Assign paths
		$sitePath	= JPATH_SITE;
		$siteUrl	= substr(JURI::root(), 0, -1);
		$user	= & JFactory::getUser();

		// Check if plugin is enabled
		if(JPluginHelper::isEnabled('content','jplayer')==false) return;

		// ------------------------------------ Prepare elements -------------------------------------
		// Includes
		require($sitePath.DS.'plugins'.DS.'content'.DS.'jplayer'.DS.'sources.php');
		// Simple performance check to determine whether plugin should process further
		$grabTags = str_replace("(","",str_replace(")","",implode(array_keys($tagReplace),"|")));
		if(preg_match("#{(".$grabTags.")}#s",$article->text)==false) return;

		// force load language file from administration
		//$language = & JFactory::getLanguage();
		//$language->load('plg_content_jplayer', JPATH_ADMINISTRATOR);

		// ---------------------------------- Get plugin parameters ----------------------------------
		$plugin = & JPluginHelper::getPlugin('content','jplayer');
		$pluginParams = new JParameter($plugin->params);

		/* Video */
		$videofolder		= $pluginParams->get('videofolder','media/video');
		$videowidth		= $pluginParams->get('videowidth',400);
		$videoheight		= $pluginParams->get('videoheight',300);
		/* Audio */
		$audiofolder		= $pluginParams->get('audiofolder','media/audio');
		$audiowidth		= $pluginParams->get('audiowidth',300);
		$audioheight		= $pluginParams->get('audioheight',20);
		/* Youtube */
		$youtuberelated	= ($pluginParams->get('youtuberelated',1)) ? true : false;
		$youtubeborder		= ($pluginParams->get('youtubeborder',0))  ? true : false;
		$youtubecolors		= $pluginParams->get('youtubecolors',0);
		$youtuberesolution	= $pluginParams->get('youtuberesolution',2);
		$youtubewidth		= $pluginParams->get('youtubewidth',480);
		$youtubeheight		= $pluginParams->get('youtubeheight',385);
		/* Playlist */
		$playlistfolder	= $pluginParams->get('playlistfolder','media/playlist');
		$playlistsize		= $pluginParams->get('playlistsize',350);
		$playlistposition	= $pluginParams->get('playlistposition','bottom');
		$shuffle			= ($pluginParams->get('shuffle',0)) ? true : false;
		/* Player */
		$playerversion		= $pluginParams->get('playerversion','4-7-811');
		$controlbar		= $pluginParams->get('controlbar','bottom');
		$transparency		= $pluginParams->get('transparency','transparent');
		$background		= $pluginParams->get('background','#010101');
		$backcolor		= $pluginParams->get('backcolor','');
		$frontcolor		= $pluginParams->get('frontcolor','');
		$lightcolor		= $pluginParams->get('lightcolor','');
		$screencolor		= $pluginParams->get('screencolor','');
		/* General */
		$fullscreen		= ($pluginParams->get('fullscreen',0)) ? 'true' : 'false';
		$autoplay			= ($pluginParams->get('autoplay',0))? 'true' : 'false';
		$repeat			= $pluginParams->get('repeat','none');
		$downloadLink		= $pluginParams->get('downloadLink',1);
		$skin			= $pluginParams->get('skin','');
		$logo			= $pluginParams->get('logo','');
		$jplayerLink		= $pluginParams->get('jplayerLink',0);
		$registeredonly	= $pluginParams->get('registeredonly',0);
		$registeredonlymsg	= $pluginParams->get('registeredonlymsg',0);
		/* Advanced */
		$debugMode		= ($pluginParams->get('debugMode',0)) ? true : false;

		// Turn off/on all error reporting
		if($debugMode)
			error_reporting(E_ALL);
		else
			error_reporting(0);

		// Show player?
		if($registeredonly && $user->guest)
			$showplayer = false;
		else
			$showplayer = true;

		if($showplayer)
		{
			// Prepare flashvars for JW Player
			$flashvars = "";

			if($playerversion=="default")
				$playerversion = "-4-7-811";
			else
				$playerversion = "-".$playerversion;
			if($repeat!="none")
				$flashvars .= "&repeat=".$repeat;
			if($logo!="")
				$flashvars .= "&logo=".$siteUrl."/".$logo;
			if($skin!="")
				$flashvars .= "&skin=".$siteUrl."/plugins/content/jplayer/mediaplayer/skins/".$skin;
			if($backcolor!="")
				$flashvars .= "&backcolor=".$backcolor;
			if($frontcolor!="")
				$flashvars .= "&frontcolor=".$frontcolor;
			if($lightcolor!="")
				$flashvars .= "&lightcolor=".$lightcolor;
			if($screencolor!="")
				$flashvars .= "&screencolor=".$screencolor;

			// Prepare playlist parameters
			$playlist = "&playlist=".$playlistposition."&playlistsize=".$playlistsize;
			if($shuffle)
				$playlist .= "&shuffle=true";

			// Prepare parameters for Youtube
			if($youtuberelated)
				$youtube_parameters = "";
			else
				$youtube_parameters = "&rel=0";

			switch($youtubecolors)
			{
				case 0:
					break;
				case 1:
					$youtube_parameters .= "&color1=0x3a3a3a&color2=0x999999";
					break;
				case 2:
					$youtube_parameters .= "&color1=0x2b405b&color2=0x6b8ab6";
					break;
				case 3:
					$youtube_parameters .= "&color1=0x006699&color2=0x54abd6";
					break;
				case 4:
					$youtube_parameters .= "&color1=0x234900&color2=0x4e9e00";
					break;
				case 5:
					$youtube_parameters .= "&color1=0xe1600f&color2=0xfebd01";
					break;
				case 6:
					$youtube_parameters .= "&color1=0xcc2550&color2=0xe87a9f";
					break;
				case 7:
					$youtube_parameters .= "&color1=0x402061&color2=0x9461ca";
					break;
				case 8:
					$youtube_parameters .= "&color1=0x5d1719&color2=0xcd311b";
					break;
			}

			switch($youtuberesolution)
			{
				case 1:
					$youtube_width  = 425;
					$youtube_height = 344;
					break;
				case 2:
					$youtube_width  = 480;
					$youtube_height = 385;
					break;
				case 3:
					$youtube_width  = 640;
					$youtube_height = 505;
					break;
				case 4:
					$youtube_width  = 960;
					$youtube_height = 745;
					break;
				case 5:
					$youtube_width  = $youtubewidth;
					$youtube_height = $youtubeheight;
					break;
			}
		}

		// ----------------------------------- Add CSS style sheet -----------------------------------
		$mainframe = & JFactory::getApplication();
		$document  = & JFactory::getDocument();
		if(file_exists($sitePath.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'plg_jplayer'.DS.'css'.DS.'style.css'))
			$document->addStyleSheet('templates/'.$mainframe->getTemplate().'/html/plg_jplayer/css/style.css');
		else
			$document->addStyleSheet('plugins/content/jplayer/tmpl/css/style.css');

		// ------------------------------------ Render the output ------------------------------------
		$final_width = "";
		$final_height = "";
		$final_autoplay = "";
		$final_folder = "";
		$final_controlbar = "";
		$final_youtube_width = "";
		$final_youtube_height = "";
		$final_youtube_parameters = "";

		foreach($tagReplace as $plg_tag => $value)
		{
			// expression to search for
			$regex = "#{".$plg_tag."}(.*?){/".$plg_tag."}#s";
			// process tags
			if(preg_match_all($regex, $article->text, $matches, PREG_PATTERN_ORDER) > 0)
			{
				// start the replace loop
				foreach($matches[0] as $key => $match)
				{
					$tagcontent = preg_replace("/{.+?}/", "", $match);
					$tagparams  = explode('|',$tagcontent);
					$tagsource  = trim(strip_tags($tagparams[0]));


					if($showplayer)
					{
						$plugins = "";

						// replacement elements
						if($plg_tag=="youtube")
						{
							$final_autoplay		= (@$tagparams[3]) ? $tagparams[3] : $autoplay;
							$final_youtube_width	= (@$tagparams[1]) ? $tagparams[1] : $youtube_width;
							$final_youtube_height	= (@$tagparams[2]) ? $tagparams[2] : $youtube_height;

							if($youtubeborder)
							{
								$final_youtube_width  += 20;
								$final_youtube_height += 20;
								$final_youtube_parameters = $youtube_parameters."&border=1";
							}
							else
								$final_youtube_parameters = $youtube_parameters;

							if($final_autoplay=="true" || $final_autoplay==1)
								$final_youtube_parameters = $final_youtube_parameters."&autoplay=1";
						}
						elseif(in_array($plg_tag, array("mp3","mp3remote","mp3playlist")))
						{
							$final_width		= (@$tagparams[1]) ? $tagparams[1] : $audiowidth;
							$final_height		= (@$tagparams[2]) ? $tagparams[2] : $audioheight;
							$final_autoplay	= (@$tagparams[3]) ? $tagparams[3] : $autoplay;
							$final_folder		= $audiofolder;

							$final_controlbar = "";

							if($plg_tag=="mp3playlist")
							{
								$final_height = $final_height+$playlistsize;
								$final_folder = $playlistfolder;
							}
						}
						else
						{
							$final_width		= (@$tagparams[1]) ? $tagparams[1] : $videowidth;
							$final_height		= (@$tagparams[2]) ? $tagparams[2] : $videoheight;
							$final_autoplay	= (@$tagparams[3]) ? $tagparams[3] : $autoplay;
							$final_folder		= $videofolder;

							if($controlbar!="bottom")
								$final_controlbar = "&controlbar=".$controlbar;

							if(@$tagparams[4])
							{
								if($plg_tag=="flv" || $plg_tag=="mp4")
								{
									$plugins = "&plugins=captions-1&captions.file=".$siteUrl."/".$final_folder."/".$tagsource.".srt";
								}
								elseif($plg_tag=="flvremote" || $plg_tag=="mp4remote")
								{
									$plugins = "&plugins=captions-1&captions.file=".$tagsource.".srt";
								}
								else
									$plugins = "&plugins=captions-1";
							}

							if($plg_tag=="videoplaylist" || $plg_tag=="remotevideoplaylist")
							{
								$final_height = $final_height+$playlistsize;
								$final_folder = $playlistfolder;
							}
						}

						// source elements
						$findAVparams = array(
							"{SITEURL}",
							"{PLAYERVERSION}",
							"{SOURCE}",
							"{FOLDER}",
							"{WIDTH}",
							"{HEIGHT}",
							"{TRANSPARENCY}",
							"{BACKGROUND}",
							"{AUTOPLAY}",
							"{FULLSCREEN}",
							"{FLASHVARS}",
							"{PLUGINS}",
							"{PLAYLIST}",
							"{YOUTUBECODE}",
							"{YOUTUBEWIDTH}",
							"{YOUTUBEHEIGHT}",
							"{YOUTUBEPARAMETERS}",
						);

						$replaceAVparams = array(
							$siteUrl,
							$playerversion,
							$tagsource,
							$final_folder,
							$final_width,
							$final_height,
							$transparency,
							$background,
							$final_autoplay,
							$fullscreen,
							$final_controlbar.$flashvars,
							$plugins,
							$playlist,
							$tagsource,
							$final_youtube_width,
							$final_youtube_height,
							$final_youtube_parameters,
						);

						// Prepare the HTML
						$output = new JObject;
						$output->player = JFilterOutput::ampReplace(str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]));

						if($debugMode)
						{
							$debugMode_file = '-';
							$debugMode_link = '-';
							$debugMode_fileexists = '-';
							$debugMode_previewexists = '-';
							$debugMode_subtitleexists = '-';
							$debugMode_subtitles = (@$tagparams[4]) ? 'true' : 'false';
							$debugMode_tag = str_replace("{", "[", $match);
							$debugMode_tag = str_replace("}", "]", $debugMode_tag);
							$debugMode_width  = $final_width;
							$debugMode_height = $final_height;

							if(in_array($plg_tag, array("flv","mp3","mp4")))
							{
								$debugMode_file = $sitePath.DS.$final_folder.DS.$tagsource.'.'.$plg_tag;
								$debugMode_file = str_replace('/', DS, $debugMode_file);
								$debugMode_link = $siteUrl.'/'.$final_folder.'/'.$tagsource.'.'.$plg_tag;
								$debugMode_fileexists = (file_exists($debugMode_file)) ? 'true' : 'false';
								$debugMode_previewexists = (file_exists($sitePath.DS.$final_folder.DS.$tagsource.'.png')) ? 'true' : 'false';
								$debugMode_subtitleexists = (file_exists($sitePath.DS.$final_folder.DS.$tagsource.'.srt')) ? 'true' : 'false';
							}
							elseif(in_array($plg_tag, array("flvremote","mp3remote","mp4remote")))
							{
								$debugMode_file = $tagsource;
								$debugMode_link = $tagsource;
								// file_exists dont work on remote files
								$debugMode_handle = @fopen($debugMode_file, 'r');
								if($debugMode_handle === false)
								{
									$debugMode_fileexists = "false";
								}
								else
								{
									$debugMode_fileexists = "true";
									fclose($debugMode_handle);
								}

								$debugMode_handle = @fopen($debugMode_file.'.png', 'r');
								if($debugMode_handle === false)
								{
									$debugMode_previewexists = "false";
								}
								else
								{
									$debugMode_previewexists = "true";
									fclose($debugMode_handle);
								}

								$debugMode_handle = @fopen($debugMode_file.'.srt', 'r');
								if($debugMode_handle === false)
								{
									$debugMode_subtitleexists = "false";
								}
								else
								{
									$debugMode_subtitleexists = "true";
									fclose($debugMode_handle);
								}

							}
							elseif(in_array($plg_tag, array("mp3playlist","videoplaylist")))
							{
								$debugMode_file = $sitePath.DS.$final_folder.DS.$tagsource.'.xml';
								$debugMode_file = str_replace('/', DS, $debugMode_file);
								$debugMode_link = $siteUrl.DS.$final_folder.DS.$tagsource.'.xml';
								$debugMode_link = str_replace('/', DS, $debugMode_link);
								$debugMode_fileexists = (file_exists($debugMode_file)) ? 'true' : 'false';
							}
							elseif($plg_tag=="youtube")
							{
								$debugMode_link = 'http://www.youtube.com/watch?v='.$tagsource;
								$debugMode_width = $final_youtube_width;
								$debugMode_height = $final_youtube_height;
								$debugMode_subtitles = '-';
							}

							$output->debugMode =
								'<hr /><strong>JPlayer debugmode</strong>'.
								'<br />Tag: '.$debugMode_tag.
								'<br />File: '.$debugMode_file.
								'<br />File exists: '.$debugMode_fileexists.
								'<br />File link: <a href="'.$debugMode_link.'">'.$debugMode_link.'</a>'.
								'<br />Preview file exists: '.$debugMode_previewexists.
								'<br />Width: '.$debugMode_width.
								'<br />Height: '.$debugMode_height.
								'<br />Autoplay: '.$final_autoplay.
								'<br />Subtitles: '.$debugMode_subtitles.
								'<br />Subtitles file exists: '.$debugMode_subtitleexists.
								'<hr />';
						}
						else
							$output->debugMode = '';

						if($downloadLink && (in_array($plg_tag, array("flv","mp3","mp4"))))
						{
							if(file_exists($sitePath.DS.$final_folder.DS.$tagsource.'.'.$plg_tag))
							{
								$filesize = filesize($sitePath.DS.$final_folder.DS.$tagsource.'.'.$plg_tag);
								$units = array('B', 'kB', 'MB', 'GB');
								$i = 0;
								while($filesize>=1024)
								{
									$filesize = $filesize/1024;
									$i++;
								}
								$filesize = round($filesize, 2).$units[$i];
								$output->downloadLink = '<a href="'.$siteUrl.'/'.$final_folder.'/'.$tagsource.'.'.$plg_tag.'" title="Download">Click to download</a> in '.strtoupper($plg_tag).' format ('.$filesize.')';
							}
							else
								$output->downloadLink = "File don't exists.";
						}
						else
							$output->downloadLink = '';

						if($jplayerLink)
						{
							$output->downloadLink .= '<span class="jplayer-poweredby"><a href="http://vault.futurama.sk/joomla/" alt="Powered by JPlayer for Joomla! 1.5">Powered by JPlayer</a></span>';
						}

						// loading template
						ob_start();
						if(file_exists($sitePath.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'plg_jplayer'.DS.'default.php'))
							include($sitePath.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'plg_jplayer'.DS.'default.php');
						else
							include($sitePath.DS.'plugins'.DS.'content'.DS.'jplayer'.DS.'tmpl'.DS.'default.php');
						$getTemplate = ob_get_contents();
						ob_end_clean();
						// end loading template

						// Do the replace
						$article->text = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#s", $getTemplate , $article->text);
					}
					else
					{
						// loading template
						ob_start();
						if(file_exists($sitePath.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'plg_jplayer'.DS.'guest.php'))
							include($sitePath.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'plg_jplayer'.DS.'guest.php');
						else
							include($sitePath.DS.'plugins'.DS.'content'.DS.'jplayer'.DS.'tmpl'.DS.'guest.php');
						$getTemplate = ob_get_contents();
						ob_end_clean();
						// end loading template
						$article->text = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#s", $getTemplate , $article->text);
					} // end guest check
				} // end foreach matches
			} // end if
		} //end foreach tags
	} // end onPrepareContent

}
?>
