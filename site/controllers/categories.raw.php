<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/*
 *  @description Handles json search categores
 *
 * */
class SearchifyControllerCategories extends JController
{

    public function display($cachable = false, $urlparams = false)
    {
        $this->default_view = 'categories'; //Just to make it clear
        parent::display($cachable, $urlparams);
    }
}