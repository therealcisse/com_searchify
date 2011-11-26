<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

$controller = JController::getInstance('Searchify');
$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();