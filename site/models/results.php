<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class SearchifyModelResults extends JModel
{
    protected $_data;

    public function getData()
    {
        if (!isset($this->_data)) {
            $this->_data = $this->my_search_articles();
        }
        return $this->_data;
    }

    private function my_search_articles()
    {
        $startTime = time();

        try {

            $reqData = json_decode(file_get_contents('php://input'));

            $search = @urldecode($reqData->search);
            $language = @urldecode($reqData->language);
            $start_date = @urldecode($reqData->start_date);
            $end_date = @urldecode($reqData->end_date);
            $category = @urldecode($reqData->category);
            //$states = $reqData->states;

            if (empty($end_date))
                $end_date = date('Y-m-d');

            $db = &$this->getDbo();
            if (!$db->connected()) {
                throw new Exception("In my_search_articles(); can't connect to db: " . $db->getErrorMsg(), $db->getErrorNum());
            }

            /*$categories = array();
            foreach ($reqData->categories as $category)
                $categories[] = $db->quote(intval($category));

            $authors = array();
            foreach ($reqData->authors as $author)
                $authors[] = $db->quote(intval($author));*/


            $query = $db->getQuery(true);
            $query->select(
                array(
                     $db->quoteName('content.id AS content_id'),
                     $db->quoteName('content.alias AS content_alias'),
                     $db->quoteName('content.title AS content_title'),
                     $db->quoteName('content.created AS content_created'),
                     $db->quoteName('user.id AS user_id'),
                     $db->quoteName('user.name AS user_name'),
                     $db->quoteName('user.username AS user_username'),
                     $db->quoteName('category.id AS category_id'),
                     $db->quoteName('category.alias AS category_alias'),
                     $db->quoteName('category.title AS category_title')));

            $query->from($db->quoteName('#__content') . ' AS content')
                  ->from($db->quoteName('#__users') . ' AS user')
                  ->from($db->quoteName('#__categories') . ' AS category');

            //Joins
            $query->where($db->quoteName('user.id') . ' = ' . $db->quoteName('content.created_by'))
                  ->where($db->quoteName('category.id') . ' = ' . $db->quoteName('content.catid'));

            //State
            $query->where($db->quoteName('user.block') . ' = ' . $db->quote(0))
                  ->where($db->quoteName('category.published') . ' = ' . $db->quote(1))
                  ->where($db->quoteName('content.state') . ' = ' . $db->quote(1))
                  ->where($db->quoteName('category.extension') . ' = ' . $db->quote('com_content'));

            //$query->where($db->quoteName('content.state') . ' = ' . $db->quote($states->published or $states->all ? 1 : 0));
            //$query->where($db->quoteName('content.featured') . ' = ' . $db->quote($states->featured or $states->all ? 1 : 0));

            //Start date
            if (!empty($start_date)) {
                $query->where($db->quoteName('content.created') . ' >= ' . $db->quote(date('Y-m-d', strtotime($start_date))));
            }

            //End date
            $query->where($db->quoteName('content.created') . ' <= ' . $db->quote(date('Y-m-d', strtotime($end_date))));

            //Category
            if(is_numeric($category) and intval($category) !== 0) //0 means all categories
                $query->where($db->quoteName('content.catid') . ' <= ' . $db->quote($category));

                //Language
            if($language and $language !== '*') //0 means all categories
                $query->where($db->quoteName('content.language') . ' IN (' . $db->quote($language) . ',' . $db->quote('*') .  ')');

            //Title
            if (!empty($search)) {

                $sSQL  = '(';
                $sSQL .= ' ucase(' . $db->quoteName('content.title') . ') LIKE ' . $db->quote('%' . strtolower($search) . '%');
                $sSQL .= ' OR ';
                $sSQL .= ' ucase(' . $db->quoteName('content.introtext') . ') LIKE ' . $db->quote('%' . strtolower($search) . '%');
                $sSQL .= ' OR ';
                $sSQL .= ' ucase(' . $db->quoteName('content.`fulltext`') . ') LIKE ' . $db->quote('%' . strtolower($search) . '%');
                $sSQL .= ')';
                $query->where($sSQL);
            }

            //Categories and authors (they're already quoted)

/*            unset($sSQL);
            if (!empty($categories)) {
                $sSQL = (empty($authors) ? ''
                        : '(') . $db->quoteName('category.id') . ' IN (' . implode(', ', $categories) . ')';
            }

            if (!empty($authors)) {
                if (isset ($sSQL))
                    $sSQL .= ' OR ';
                $sSQL .= $db->quoteName('user.id') . ' IN (' . implode(', ', $authors) . ')' . (empty($categories) ? ''
                        : ')');
            }

            if (isset ($sSQL))
                $query->where($sSQL);*/

            //Group by categories?
            //$query->group($db->quoteName('category.id'));

            $query->order($db->quoteName('category.id') . ' DESC');
            $query->order($db->quoteName('content.created') . ' DESC');

            $db->setQuery($query);

            $ret = array();
            foreach ($db->loadObjectList() as $article) {
                $ret[] = array(
                    "id" => $article->content_id,
                    'title' => $article->content_title,
                    'alias' => $article->content_alias,
                    'category_id' => $article->category_id,
                    'category_title' => $article->category_title,
                    'category_alias' => $article->category_alias,
                    "user_id" => $article->user_id,
                    "username" => empty($article->user_name) ? $article->user_name : $article->user_username,
                    'created' => date("c", strtotime($article->content_created))
                );
            }


            //echo JText::sprintf('Lang=%s', print_r($language, true)) . PHP_EOL;
            //echo JText::sprintf('res=%s', print_r($res, true)) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'start date:', $start_date) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'end date:', $end_date) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'title:', $title) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'body:', $body) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'categories:', print_r($categories,true)) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'authors:', print_r($authors, true)) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'Ret:', print_r($ret, true)) . PHP_EOL;
            //echo JText::sprintf("%s=%s", 'States:', print_r($reqData->states, true)) . PHP_EOL;
            //echo JText::sprintf("sql=%s", str_replace('#__', $db->getPrefix(), $query)) . PHP_EOL;

            return array(
                'results' => $ret,
                'totalrows' => $db->getAffectedRows(),
                'duration' => time() - $startTime
            );
        }

        catch (Exception $e) {
            header('', 500);
            exit;
        }
    }
}