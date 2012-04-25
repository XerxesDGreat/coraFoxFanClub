<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
    // Sets toolbar items for this page
    JToolBarHelper::title(  JText::_("COM_ITPCONNECT_CPANEL_TITLE") , 'itp-properties' );
?>
<div id="itp-cpanel">
    <div class="itp-cpitem">
        <a href="index.php?option=com_itpconnect&amp;controller=settings&amp;view=settings" >
            <?php echo JHTML::_('image.site',  "settings.png", '/components/com_itpconnect/assets/images/cpanel/', NULL, NULL, JText::_("Settings") ); ?>
            <span><?php echo JText::_("Settings")?></span> 
        </a>
    </div>
    <div class="itp-cpitem">
        <a href="index.php?option=com_itpconnect&amp;controller=users&amp;view=users" >
            <?php echo JHTML::_('image.site',  "users.png", '/components/com_itpconnect/assets/images/cpanel/', NULL, NULL, JText::_("Users") ); ?>
            <span><?php echo JText::_("Users")?></span> 
        </a>
    </div>
</div>
<div id="itp-itprism">
<a href="http://itprism.com/free-joomla-extensions/social-connection-authentication" title="<?php echo JText::_("COM_ITPCONNECT");?>" target="_blank" >
<img src="<?php echo JURI::base() . "components/com_itpconnect/assets/images/cpanel/itp_component_logo.jpg"; ?>" alt="<?php echo JText::_("COM_ITPCONNECT");?>" />
</a>
<a href="http://itprism.com" title="A Product of ITPrism.com"><?php echo JHTML::_('image.site',  "product_of_itprism.png", '/components/com_itpconnect/assets/images/cpanel/', NULL, NULL, "ITPrism.com" ); ?></a>
<p id="itp-vote-link" ><?php echo JText::_("COM_ITPCONNECT_YOUR_VOTE"); ?></p>
<p id="itp-vote-link" ><?php echo JText::_("COM_ITPCONNECT_SPONSORSHIP"); ?></p>
</div>