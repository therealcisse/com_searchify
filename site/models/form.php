<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class SearchifyModelForm extends JModel
{
    protected $_formData;

    public function getFormData()
    {
        if (!isset($this->_formData)) {

            $this->_formData = array(
                'current_date' => date('Y-m-d'),
                'pcategories' => $this->get_categories_by_level_options(1),
                'ccategories' => $this->get_categories_by_level_options(2),
                'languages' => $this->get_languages_options()
            );
        }
        return $this->_formData;
    }

    protected function create_category_tree_html($start_id = 1)
    {

        function buildone($category)
        {
            return '<li data-key="' . $category['attr']['data-key'] . '"><a href="#">' . $category['data'] . '</a>' . (isset($category['children'])
                    ? buildmany($category['children']) : '') . '</li>' . PHP_EOL;
        }

        function buildmany($categories)
        {

            $ret = '<ul>' . PHP_EOL;
            foreach ($categories as $category) {
                $ret .= buildone($category);
            }
            $ret .= '</ul>' . PHP_EOL;

            return $ret;
        }

        return buildmany($this->get_category_tree($start_id));
    }

    private function to_html_option($key, $label, $selected='')
    {
        return "<option $selected value=$key>$label</option>";
    }


    protected function get_languages_options()
    {
        $html = '';
        foreach (JLanguageHelper::getLanguages() as $lang) {
            $html .= $this->to_html_option($lang->lang_code, isset($lang->title_native) ? $lang->title_native
                                                                   : $lang->title, $lang->lang_code === JFactory::getLanguage()->getTag() ? 'selected="selected"' : '') . PHP_EOL;
        }

        return $html;
    }

    protected function get_categories_by_level_options($level)
    {
        $html = '';
        foreach ($this->get_categories_by_level($level) as $category) {
            $html .= $this->to_html_option($category['id'], $category['title']) . PHP_EOL;
        }

        return $html;
    }

    protected function get_categories_by_level($level = 0)
    {
        $db = &$this->getDbo();
        $query = $db->getQuery(true);
        $query->select(array($db->quoteName('id'), $db->quoteName('title'), $db->quoteName('parent_id')))
                ->from($db->quoteName('#__categories'))
                ->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'))
                ->where($db->quoteName('published') . ' = ' . $db->quote(1)); //make sure the category is published

        if ($level !== 0) {
            $query->where($db->quoteName('level') . ' = ' . $db->quote($level));
            $query->order($db->quote('level'));
        }

        $db->setQuery($query);

        $ret = array();
        foreach ($db->loadObjectList() as $category) {

            $ret[] = array(
                "id" => $category->id,
                "title" => $category->title
            );
        }

        return $ret;
    }

    protected function get_child_categories($parent_id = 0)
    {
        $db = &$this->getDbo();
        $query = $db->getQuery(true);
        $query->select(array($db->quoteName('id'), $db->quoteName('title'), $db->quoteName('parent_id')))
                ->from($db->quoteName('#__categories'))
                ->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'))
                ->where($db->quoteName('published') . ' = ' . $db->quote(1)); //make sure the category is published

        if ($parent_id !== 0) {
            $query->where($db->quoteName('parent_id') . ' = ' . $db->quote($parent_id));
            $query->order($db->quote('parent_id'));
        }

        else {
            $query->where($db->quoteName('level') . ' = ' . $db->quote(2));
            $query->order($db->quote('level'));
        }

        $db->setQuery($query);

        $ret = array();
        foreach ($db->loadObjectList() as $category) {

            $ret[] = array(
                "id" => $category->id,
                "title" => $category->title
            );
        }

        return $ret;
    }

    protected function get_category_tree($start_id = 1)
    {

        $db = &$this->getDbo();
        if (!$db->connected()) {
            throw new Exception("In category_tree(); can't connect to db: " . $db->getErrorMsg(), $db->getErrorNum());
        }

        function fetch_categories($parent_id, &$db)
        {

            // Get the data from the database table.
            $query = $db->getQuery(true);
            $query->select(array($db->quoteName('id'), $db->quoteName('title'), $db->quoteName('parent_id')))
                    ->from($db->quoteName('#__categories'))
                    ->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'))
                    ->where($db->quoteName('parent_id') . ' = ' . $db->quote($parent_id))
                    ->where($db->quoteName('published') . ' = ' . $db->quote(1)); //make sure the category is published

            $db->setQuery($query);

            $ret = array();
            foreach ($db->loadObjectList() as $category) {

                $cat = array(
                    "data" => $category->title,
                    "attr" => array("data-key" => $category->id)
                );

                $children = fetch_categories($category->id, &$db);
                if (!empty($children)) {
                    $cat["state"] = "open";
                    $cat["children"] = $children;
                } else {
                    $cat["state"] = "closed";
                }

                $ret[] = $cat;
            }

            return $ret;
        }


        return fetch_categories($start_id, &$db);
    }

    protected function get_authors()
    {

        $db = &$this->getDbo();
        if (!$db->connected()) {
            throw new Exception("In authors(); can't connect to db: " . $db->getErrorMsg(), $db->getErrorNum());
        }

        $query = $db->getQuery(true);
        $query->select(array($db->quoteName('id'), $db->quoteName('name'), $db->quoteName('username')))
                ->from($db->quoteName('#__users'))
                ->where($db->quoteName('block') . ' = ' . $db->quote(0)); //make sure the user is not blocked

        $db->setQuery($query);

        $ret = array();
        foreach ($db->loadObjectList() as $user) {
            $ret[] = array(
                "label" => empty($user->name) ? $user->username : $user->name,
                "value" => $user->id
            );
        }

        return $ret;
    }
}