<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
    // Sets toolbar items for this page
    JToolBarHelper::title(  $this->title , 'itp-settings' );
    JToolBarHelper::save();
    JToolBarHelper::cancel();
    
?>
<form action="index.php" method="post" name="adminForm" id="itp-settings-form" >
<?php
$pane =& JPane::getInstance('Tabs');
echo $pane->startPane('ITPConnectPane');

{
?>

<?php echo $pane->startPanel('Facebook', 'facebook'); ?>

<?php echo $this->params->render("facebook", "facebook"); ?>

<?php echo $pane->endPanel(); ?>
<!--
<?php echo $pane->startPanel('Twitter', 'twitter'); ?>

<?php echo $this->params->render("twitter", "twitter"); ?>

<?php echo $pane->endPanel(); ?>
-->
<?php 
}
echo $pane->endPane();

?>
<div>
  <input type="hidden" name="option" value="com_itpconnect" />
  <input type="hidden" name="controller" value="settings" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="view" value="settings" />
  <?php echo JHTML::_( 'form.token' ); ?>
</div>
</form>