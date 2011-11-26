<?php

defined('_JEXEC') or die;

header('content-type:application/json', true, 200);
echo json_encode($this->data);