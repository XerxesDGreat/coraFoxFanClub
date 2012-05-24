<?php
/**
 * This file is the main canvas for the website; it's upon this page which all
 * the subtemplates get drafted and combined.
 */
defined('_JEXEC') or die('Restricted access'); // no direct access
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php';
$document = null;
if (isset($this)) {
	$document = & $this;
}
$baseUrl = $this->baseurl;
$templateUrl = $this->baseurl . '/templates/' . $this->template;
artxComponentWrapper($document, false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<jdoc:include type="head" />
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/system.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/general.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $templateUrl; ?>/css/template.css" />
		<!--[if IE 6]><link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.ie6.css" type="text/css" media="screen" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.ie7.css" type="text/css" media="screen" /><![endif]-->
		<script type="text/javascript" src="<?php echo $templateUrl; ?>/script.js"></script>
	</head>
	<body>
		<div id="art-main">
			<div class="art-Sheet">
				<div class="art-Sheet-cc"></div>
				<div class="art-Sheet-body">
					<jdoc:include type="modules" name="user3" />
					<div class="art-Header">
						<div class="art-Header-jpeg">
							<div class="art-HeaderNavbar">
								<jdoc:include type="modules" name="nav-menu" />
							</div>
							<div class="art-Logo">
								<img src="<?php echo $templateUrl; ?>/images/logo.png" />
							</div>
							<jdoc:include type="modules" name="slideshow1" />
						</div>
					</div>
					<jdoc:include type="modules" name="banner1" style="artstyle" artstyle="art-nostyle" />
<?php
echo artxPositions($document, array('top1', 'top2', 'top3'), 'art-block');
?>
					<div class="art-contentLayout">
<?php
if (artxCountModules($document, 'left')) {
?>
						<div class="art-sidebar1">
<?php
	echo artxModules($document, 'left', 'art-block');
?>
						</div>
<?php
}
?>
						<div class="art-<?php echo artxCountModules($document, 'left') ? 'content' : 'content-wide'; ?>">
<?php
echo artxModules($document, 'banner2', 'art-nostyle');
if (artxCountModules($document, 'breadcrumb')) {
	echo artxBreadcrumbs(artxModules($document, 'breadcrumb'));
}
echo artxPositions($document, array('user1', 'user2'), 'art-article');
echo artxModules($document, 'banner3', 'art-nostyle');
?>
<?php
if (artxHasMessages()) {
	echo artxPost(null, '<jdoc:include type="message" />');
}
?>
							<jdoc:include type="component" />
<?php
echo artxModules($document, 'banner4', 'art-nostyle');
echo artxPositions($document, array('user4', 'user5'), 'art-article');
echo artxModules($document, 'banner5', 'art-nostyle');
?>
						</div>
					</div>
					<div class="cleared"></div>
<?php
echo artxPositions($document, array('bottom1', 'bottom2', 'bottom3'), 'art-block');
?>
					<jdoc:include type="modules" name="banner6" style="artstyle" artstyle="art-nostyle" />
					<div class="cleared"></div>
				</div>
				<div class="cleared"></div>
				<div class="art-Footer">
					<div class="art-FooterText">
						<?php echo artxModules($document, 'copyright'); ?>
					</div><!-- end div.art-FooterText -->
				</div><!-- end div.art-Footer -->
				<div class="cleared"></div>
			</div>
		</div>
	</body>
</html>
