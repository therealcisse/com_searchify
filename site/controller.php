<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/*
 *  @description Handles the form
 *
 * */
class SearchifyController extends JController
{
    public function __construct($config = array())
    {
        $this->_loadStyles();
        $this->_loadScripts();
        parent::__construct($config);
    }

    public function display($cachable = false, $urlparams = false)
    {
        $this->default_view = 'form';
        parent::display($cachable, $urlparams);
    }

    private function _loadScripts()
    {
        $doc = &JFactory::getDocument();

        $doc->addScriptDeclaration("var baseurl='" . JURI::base(true) . "/index.php';");

        $lang = explode('-', JFactory::getLanguage()->getTag());
        $doc->addScriptDeclaration("var lang='" .  $lang[0] . "';");

        //Add scripts

        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/jquery.min.js');
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/jquery.hotkeys.js');

        //Vakata's jstree
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jquery.jstree.js');
        /*
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/vakata.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.core.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.ui.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.themes.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.hotkeys.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.html.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.checkbox.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.json.js');
     $doc->addScript( JURI::base(true) . '/components/com_searchify/assets/javascript/vakata/jstree.unique.js');
        */

        //Dates
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/is_date.js');
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/date_format.js');
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/date.js');

        //WIJMO
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/jquery-ui-1.8.16.custom.min.js');
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/jquery.mousewheel.min.js');

        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/wijmo/jquery.wijmo.wijutil.min.js');
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/wijmo/jquery.wijmo.wijsuperpanel.min.js');
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/wijmo/jquery.wijmo.wijlist.min.js');

        //jQuery.collapsible.js
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/jQuery.collapsible.js');

        //jQuery.relativedate
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/jQuery.relativedate.js');

        //Main
        $doc->addScript(JURI::base(true) . '/components/com_searchify/assets/javascript/main.js');
    }

    private function _loadStyles()
    {
        $doc = &JFactory::getDocument();

        //Add styles

        //WIJMO
        $doc->addStyleSheet(JURI::base(true) . '/components/com_searchify/assets/css/wijmo/themes/rocket/jquery-wijmo.css');
        $doc->addStyleSheet(JURI::base(true) . '/components/com_searchify/assets/css/wijmo//jquery.wijmo.wijsuperpanel.css');
        $doc->addStyleSheet(JURI::base(true) . '/components/com_searchify/assets/css/wijmo/jquery.wijmo.wijlist.css');
        $doc->addStyleSheet(JURI::base(true) . '/components/com_searchify/assets/css/jquery.ui.datepicker.css');

        //jQuery.collapsible.css
        $doc->addStyleSheet(JURI::base(true) . '/components/com_searchify/assets/css/jQuery.collapsible.css');

        //Main
        $doc->addStyleSheet(JURI::base(true) . '/components/com_searchify/assets/css/style.css');
    }
}