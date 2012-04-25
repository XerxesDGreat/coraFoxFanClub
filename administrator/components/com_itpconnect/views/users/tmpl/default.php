<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php 
	// Set toolbar items for the page
	JToolBarHelper::title(JText::_('Users'), 'itp-users');
	JToolBarHelper::deleteList();
?>
<form action="index.php" method="post" name="adminForm">
<table>
    <tr>
        <td align="left" width="100%">
           <?php echo JText::_('Filter'); ?>:
           <input type="text" name="filter_search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
           <button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
           <button onclick="document.adminForm.filter_search.value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
        </td>
        <td nowrap="nowrap">
        </td>
    </tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="15">
				#
			</th>
			<th width="15">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th class="title" >
				<?php echo JHTML::_('grid.sort', JText::_('Name'), 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="100" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',  JText::_('Facebook ID'), 'fbuser_id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <!-- 
            <th width="100" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',  JText::_('Twitter ID'), 'twuser_id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
             -->
            <th width="80">
                <?php echo JHTML::_('grid.sort',  JText::_('User ID'), 'users_id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 1;
	$n = count( $this->items );
	
	for ($i=0;$i < $n; $i++ )
	{
		
		$row = &$this->items[$i];
		$row->checked_out = false;
		
		$checked 	= JHTML::_('grid.checkedout', $row, $i, "id" );
		settype($row->id,"integer");
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td >
				<?php echo $k ?>
			</td>
			<td >
				<?php echo $checked; ?>
			</td>
			<td >
				<a href="index.php?option=com_users&amp;view=user&amp;task=edit&amp;cid[]=<?php echo $row->users_id;?>" ><?php echo $row->name; ?></a>
			</td>
			<td align="center">
			 <?php echo $row->fbuser_id;?>
            </td>
            <!-- 
            <td align="center">
				<?php echo $row->twuser_id;?>
            </td>
             -->
            <td align="center">
                <?php echo $row->users_id;?>
            </td>
		</tr>
		<?php
		$k++;
	}
	?>
	</tbody>
	</table>
</div>

<input type="hidden" name="option" value="com_itpconnect" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="users" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="users" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>

</form>