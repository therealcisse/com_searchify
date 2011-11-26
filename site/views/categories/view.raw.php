<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SearchifyViewCategories extends JView
{
    function display($tpl = null)
    {
        $data = &$this->get('CategoryData');
        $this->assignRef('categories', $data);
        parent::display($tpl);
    }
}