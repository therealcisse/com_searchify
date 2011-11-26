<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SearchifyViewResults extends JView
{
    function display($tpl = null)
    {
        $data = &$this->get('Data');
        $this->assignRef('data', $data);
        parent::display($tpl);
    }
}