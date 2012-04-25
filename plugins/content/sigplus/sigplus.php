<?php
/**
* @file
* @brief    sigplus Image Gallery Plus plug-in for Joomla
* @author   Levente Hunyadi
* @version  1.3.4
* @remarks  Copyright (C) 2009-2011 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/sigplus
*/

/*
* sigplus Image Gallery Plus plug-in for Joomla
* Copyright 2009-2010 Levente Hunyadi
*
* sigplus is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* sigplus is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('SIGPLUS_VERSION')) {
	define('SIGPLUS_VERSION', '1.3.4');
}

if (!defined('SIGPLUS_DEBUG')) {
	// Triggers debug mode. Debug uses uncompressed version of scripts rather than the bandwidth-saving minified versions.
	define('SIGPLUS_DEBUG', false);
}
if (!defined('SIGPLUS_LOGGING')) {
	// Triggers logging mode. Verbose status messages are printed to the output.
	define('SIGPLUS_LOGGING', false);
}

// import library dependencies
jimport('joomla.event.plugin');

require_once dirname(__FILE__).DS.'core.php';

/**
* sigplus Image Gallery Plus plug-in.
*/
class plgContentSIGPlus extends JPlugin {
	/** Activation tag used to invoke the plug-in. */
	private $activationtag = 'gallery';
	/** sigplus core service object. */
	private $core;
	/** sigplus configuration. */
	private $configuration;

	function __construct(&$subject, $config) {
		parent::__construct($subject, $config);

		$activationtag = $this->params->get('activationtag', $this->activationtag);
		if (is_string($activationtag) && ctype_alpha($activationtag)) {
			$this->activationtag = $activationtag;
		}

		// create configuration parameter objects
		$this->configuration = new SIGPlusConfiguration();
		$this->configuration->setParameters($this->params);
	}

	/**
	* Joomla 1.5 compatibility method.
	*/
	function onAfterDisplayTitle(&$article, &$params) {
		$this->onContentAfterTitle(null, $article, $params, 0);
	}

	/**
	* Fired before article contents are to be processed by the plug-in.
	* @param $article The article that is being rendered by the view.
	* @param $params An associative array of relevant parameters.
	* @param $limitstart An integer that determines the "page" of the content that is to be generated.
	* @param
	*/
	function onContentAfterTitle($context, &$article, &$params, $limitstart) {

	}

	/**
	* Joomla 1.5 compatibility method.
	*/
	function onPrepareContent(&$row, &$params) {
		$this->onContentPrepare(false, $row, $params, 0);
	}

	/**
	* Fired when contents are to be processed by the plug-in.
	* Recommended usage syntax:
	* a) POSIX fully portable file names
	*    Folder name characters are in [A-Za-z0-9._-])
	*    Regular expression: [/\w.-]+
	*    Example: {gallery rows=1 cols=1}  /sigplus/birds/  {/gallery}
	* b) URL-encoded absolute URLs
	*    Regular expression: (?:[0-9A-Za-z!"$&\'()*+,.:;=@_-]|%[0-9A-Za-z]{2})+
	*    Example: {gallery} http://example.com/image.jpg {/gallery}
	*/
	function onContentPrepare($context, &$article, &$params, $limitstart) {
		if (strpos($article->text, '{'.$this->activationtag) === false) {
			return;  /* short-circuit plugin activation */
		}

		if (SIGPLUS_LOGGING) {
			$logging = SIGPlusLogging::instance();
			$logging->append('<strong>sigplus is currently running in logging mode</strong>. This should be turned off in a production environment by setting the constant SIGPLUS_LOGGING in <kbd>sigplus.php</kbd> to <kbd>false</kbd>, in which case this message will also disappear.');
		}

		// load language file for internationalized labels and error messages
		$lang = JFactory::getLanguage();
		$lang->load('plg_content_sigplus', JPATH_ADMINISTRATOR);

		try {
			// on-demand instantiation
			if (!isset($this->core)) {
				$this->core = new SIGPlusCore($this->configuration);
			}

			// find gallery tags and emit code
			$activationtag = preg_quote($this->activationtag, '#');
			$article->text = preg_replace_callback('#[{]'.$activationtag.'([^{}]*)(?<!/)[}]\s*((?:[^{]+|[{](?!/'.$activationtag.'))+)\s*[{]/'.$activationtag.'[}]#', array($this, 'getGalleryRegexReplacement'), $article->text, -1);
			$count = 0;
			$article->text = preg_replace('#[{]'.$activationtag.'\s*/[}]#', '', $article->text, -1, $count);
			$this->core->addGalleryEngines($count > 0);
		} catch (Exception $e) {
			$app = JFactory::getApplication();
			$app->enqueueMessage( $e->getMessage(), 'error' );
			$article->text = $e->getMessage() . $article->text;
		}

		if (SIGPLUS_LOGGING) {
			$article->text = $logging->fetch().$article->text;
		}
	}

	/**
	* Generates image thumbnails with alternate text, title and lightbox pop-up activation on mouse click.
	* This method is to be called as a regular expression replace callback.
	* Any error messages are printed to screen.
	* @param $match A regular expression match.
	*/
	public function getGalleryRegexReplacement($match) {
		$imagereference = $match[2];
		if (is_remote_path($imagereference)) {
			$imagereference = safeurlencode($imagereference);
		}
		return $this->core->getGalleryHtml($imagereference, $match[1]);
	}

	/*
	public function onExtensionAfterInstall($installer, $eid) { }

	public function onExtensionAfterUninstall($installer, $eid, $result) {
		self::removeCacheFolder($this->params->get('thumb_folder', 'thumbs'));
		self::removeCacheFolder($this->params->get('preview_folder', 'preview'));
		self::removeCacheFolder($this->params->get('script_folder', 'sigplus'));
	}

	public function onExtensionAfterUpdate($installer, $eid) {
		self::removeCacheFolder($this->params->get('script_folder', 'sigplus'), false);
	}
	*/
}
