<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    /**
     * Start session 
     */
    public function _initCoreSession() {

        Zend_Session::start();

    }
    
    protected function _initView()
    {
        try {

            $options = new Zend_Config($this->getOptions());
            // 更新系
            $dbConfig = $options->db;
            $dbConect = Zend_Db::factory($dbConfig->adapter, $dbConfig->params);
            Zend_Registry::set('MASTER_DATABASE', $dbConect);
            Zend_Db_Table::setDefaultAdapter($dbConect);

            // 参照系
            $dbConfig = $options->slavedb;
            $dbConect = Zend_Db::factory($dbConfig->adapter, $dbConfig->params);
            Zend_Registry::set('SLAVE_DATABASE', $dbConect);

        } catch (Exception $e) {
            echo '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" />';
            echo '<title>エラー</title></head><body>';
            if ('production' == APPLICATION_ENV ){
                echo '<h1>エラーです。</h1>';
            } else{
                echo '<h1>データベースに接続できません。</h1>';
                echo '<h3>Message</h3>';
                echo $e->getMessage();
                echo '<h3>File</h3>';
                echo $e->getFile();
                echo '<h3>Line</h3>';
                echo $e->getLine();
                echo '<h3>Trace</h3>';
                echo '<pre>';
                echo $e->getTraceAsString();
                echo '</pre>';
            }
            echo '</body></html>';
            exit;
        }

        $view_config = $options->view->toArray();
        $smarty_config = $options->view->smarty->toArray();

        require_once 'Smarty/libs/Smarty.class.php';
        require_once 'Zend_View_Smarty.class.php';

        $view = new Zend_View_Smarty($view_config['scriptPath'], $smarty_config);
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper("ViewRenderer");
        $viewRenderer->setView($view)
                     ->setViewBasePathSpec($options->view->scriptPath)
                     ->setViewScriptPathSpec(APPLICATION_PATH . "/front/views/scripts/:module/:controller/:action.:suffix")
                     ->setViewScriptPathNoControllerSpec(':action.:suffix')
                     ->setViewSuffix('tpl');
        // Message
        $objMessage = new Message();
        Zend_Registry::set('objMessage', $objMessage);
        
        // PreperDate
        $objPreperDate = new PreperDate();
        Zend_Registry::set('objPreperDate', $objPreperDate);
        
        $bIsLogin = false;
        
    }

    // メンテナンスモードを実行するプラグインを登録します。
    protected function _initControlerPluginMaintenanceMod() {
        
        try {
            // メンテナンスモード時
            if (MAINTENANCE_MODE) {
                // 特定のIPアドレスからのアクセスは、メンテナンスモードとしない
                if ($_SERVER["HTTP_CLIENT_IP"] != null) {
                    $stClientIP = $_SERVER["HTTP_CLIENT_IP"];
                } else if ($_SERVER["HTTP_X_FORWARDED_FOR"] != null) {
                    $stClientIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $stClientIP = $_SERVER["REMOTE_ADDR"];
                }

                // 管理者用IPアドレスの取得
                $arrAdminClientIP = array();
                for($i = 1; $i < ADMIN_CLIENT_IP_COUNT; $i++) {
                    $arrAdminClientIP[] = constant("ADMIN_CLIENT_IPADDR{$i}");
                }
                if (array_search($stClientIP, $arrAdminClientIP) === false) {
                    $this->bootstrap("frontController");
                    $front = $this->getResource("frontController");
                    $front->registerPlugin(new My_Controller_Plugin_Maintenance());
                }
            }
            
            // セッション管理
            $this->bootstrap("frontController");
            $front = $this->getResource("frontController");
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }    
}
