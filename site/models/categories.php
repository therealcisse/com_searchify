<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once dirname(__FILE__) . '/form.php';

class SearchifyModelCategories extends SearchifyModelForm
{
    protected $_categoryData;

    public function getCategoryData()
    {
        if (!isset($this->_categoryData))
            $this->_categoryData = $this->get_child_categories(JRequest::getInt('parent_id', 0));

        return $this->_categoryData;
    }
}