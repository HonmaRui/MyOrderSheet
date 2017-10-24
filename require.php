<?php
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';
require_once 'Zend/Layout.php';
require_once 'Zend/Db.php';
require_once 'Zend/Debug.php';
require_once 'Zend/Exception.php';
require_once 'Zend/Http/Client.php';
require_once 'Zend/Locale.php';
require_once 'Zend/Translate.php';
require_once 'Zend/Cache.php';
require_once 'Zend/Session.php';

// Config
require_once 'env.php';

//define('INI_FILE_PATH', APP_PATH);

// plugin
require_once APPLICATION_PATH . '/front/plugin/MaintenancePlugin.php';
//require_once APPLICATION_PATH . '/front/plugin/SessctrlPlugin.php';
