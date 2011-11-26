<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SearchifyViewForm extends JView
{
    function display($tpl = null)
    {
        $data = &$this->get('FormData');
        $this->assignRef('data', $data);
        parent::display($tpl);
    }
}