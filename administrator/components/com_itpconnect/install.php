<?php
/**
 * @package      ITPrism Components
 * @subpackage   ITPConnect
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPConnect is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.installer.installer');
	
// Load language file
$lang = &JFactory::getLanguage();
$lang->load('com_itpconnect');

$db = & JFactory::getDBO();
$status = new JObject();
$status->modules = array();
$status->plugins = array();
$src 		= $this->parent->getPath('source');

$modules = &$this->manifest->getElementByPath('modules');
$folder  = $modules->attributes('folder');

if (is_a($modules, 'JSimpleXMLElement') && count($modules->children())) {
	foreach ($modules->children() as $module) {
		$mname = $module->attributes('module');
		$client = $module->attributes('client');
		if(is_null($client)) $client = 'site';
		($client=='administrator')? $path=$src.DS.'administrator'.DS.'modules'.DS.$mname: $path = $src.DS.$folder.DS.'modules'.DS.$mname;
		$installer = new JInstaller;
		$result = $installer->install($path);
		$status->modules[] = array('name'=>$mname,'client'=>$client, 'result'=>$result);
	}
}

$plugins 	= &$this->manifest->getElementByPath('plugins');
$folder     = $plugins->attributes('folder');

if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())) {

	foreach ($plugins->children() as $plugin) {
		$pname = $plugin->attributes('plugin');
		$pgroup = $plugin->attributes('group');
		$path = $src.DS.$folder.DS.'plugins'.DS.$pgroup;
		$installer = new JInstaller;
		$result = $installer->install($path);
		$status->plugins[] = array('name'=>$pname,'group'=>$pgroup, 'result'=>$result);

		$query = "UPDATE #__plugins SET published=1 WHERE element=".$db->Quote($pname)." AND folder=".$db->Quote($pgroup);
		$db->setQuery($query);
		$db->query();
	}
}
?>

<?php $rows = 0;?>
<img src="components/com_itpconnect/assets/images/cpanel/itp_component_logo.png" width="170" height="30" alt="ITP Connect Component" align="right" />
<h2 style="float:left;"><?php echo JText::_('ITPConnect Installation Status'); ?></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th width="30">&nbsp;</th>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="4"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="icon"><img src="components/com_itpconnect/assets/images/component.png" width="24" height="24" alt="Component" /></td>
			<td class="key" colspan="2"><?php echo "ITPConnect"; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<?php if (count($status->modules)) : ?>
		<tr>
			<th>&nbsp;</th>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="icon"><img src="components/com_itpconnect/assets/images/module.png" width="24" height="24" alt="Module" /></td>
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo ($module['result'])?JText::_('Installed'):JText::_('Not installed'); ?></strong></td>
		</tr>
		<?php endforeach;?>
		<?php endif;?>
		<?php if (count($status->plugins)) : ?>
		<tr>
			<th>&nbsp;</th>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="icon"><img src="components/com_itpconnect/assets/images/plugin.png" width="24" height="24" alt="Plug-in" /></td>
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo ($plugin['result'])?JText::_('Installed'):JText::_('Not installed'); ?></strong></td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>