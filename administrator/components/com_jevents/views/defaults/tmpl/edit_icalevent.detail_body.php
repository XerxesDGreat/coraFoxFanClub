<?php 
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: edit.php 1438 2009-05-02 09:25:42Z geraint $
 * @package     JEvents
 * @copyright   Copyright (C)  2008-2009 GWE Systems Ltd
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.jevents.net
 */

defined('_JEXEC') or die('Restricted access');
?>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><?php echo JText::_("JEV_PLUGIN_INSTRUCTIONS",true);?></td>
		<td><select id="jevdefaults" onchange="defaultsEditorPlugin.insert('value','jevdefaults' )" ></select></td>
	</tr>
</table>

<script type="text/javascript">
defaultsEditorPlugin.node($('jevdefaults'),"<?php echo JText::_("JEV_PLUGIN_SELECT",true);?>","");
// built in group
var optgroup = defaultsEditorPlugin.optgroup($('jevdefaults') , "<?php echo JText::_("JEV_CORE_DATA",true);?>");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD TITLE",true);?>", "TITLE");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD ICALBUTTON",true);?>", "ICALBUTTON");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD ICALDIALOG",true);?>", "ICALDIALOG");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD EDITBUTTON",true);?>", "EDITBUTTON");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD EDITDIALOG",true);?>", "EDITDIALOG");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD REPEATSUMMARY",true);?>", "REPEATSUMMARY");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD PREVIOUSNEXT",true);?>", "PREVIOUSNEXT");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD CREATOR LABEL",true);?>", "CREATOR_LABEL");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD CREATOR",true);?>", "CREATOR");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD HITS",true);?>", "HITS");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD DESCRIPTION",true);?>", "DESCRIPTION");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD URL",true);?>", "URL");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD LOCATION_LABEL",true);?>", "LOCATION_LABEL");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD LOCATION",true);?>", "LOCATION");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD CONTACT_LABEL",true);?>", "CONTACT_LABEL");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD CONTACT",true);?>", "CONTACT");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD EXTRAINFO",true);?>", "EXTRAINFO");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD CATEGORY",true);?>", "CATEGORY");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV FIELD CREATIONDATE",true);?>", "CREATED");
defaultsEditorPlugin.node(optgroup , "<?php echo JText::_("JEV ADMIN PANEL",true);?>", "MANAGEMENT");

<?php
// get list of enabled plugins
$jevplugins = JPluginHelper::getPlugin("jevents");
foreach ($jevplugins as $jevplugin){
	if (JPluginHelper::importPlugin("jevents", $jevplugin->name)){
		$classname = "plgJevents".ucfirst($jevplugin->name);
		if (is_callable(array($classname,"fieldNameArray"))){
			$lang = JFactory::getLanguage();
			$lang->load("plg_jevents_".$jevplugin->name,JPATH_ADMINISTRATOR);
			$fieldNameArray = call_user_func(array($classname,"fieldNameArray"));
			if (!isset($fieldNameArray['labels'])) continue;
			?>
			optgroup = defaultsEditorPlugin.optgroup($('jevdefaults') , '<?php echo $fieldNameArray["group"];?>');
			<?php
			for ($i=0;$i<count($fieldNameArray['labels']);$i++) {
				?>
				defaultsEditorPlugin.node(optgroup , "<?php echo $fieldNameArray['labels'][$i];?>", "<?php echo $fieldNameArray['values'][$i];?>");
				<?php
			}
		}
	}
}
?>
</script>
