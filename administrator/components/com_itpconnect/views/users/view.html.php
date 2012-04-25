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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class ViewUsers extends JView {
    
    public function display($tpl = null){
        
        $model = $this->getModel();
        $layout = $this->getLayout();
        
        global $mainframe, $option;
        
        // prepare list array
        $lists = array();
        
        // Filter Ordering
        $filter_order = $mainframe->getUserStateFromRequest($option . "." . $this->getName() . ".filter_order", 'filter_order', 'ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . "." . $this->getName() . ".filter_order_Dir", 'filter_order_Dir', '', 'word');
        
        // Filter Search
        $filter_search   = $mainframe->getUserStateFromRequest($option. "." . $this->getName() . ".quotes.filter_search",'filter_search');
        $lists['search'] = $filter_search;
                
        // The ordering of the table
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        
        // Gets data from the model
        $items      = $model->getData();
        $pagination = $model->getPagination();
        
        $this->assignRef('lists', $lists);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('items', $items);
        
        parent::display($tpl);
    }

}