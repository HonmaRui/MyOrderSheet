<?php

class My_Controller_Plugin_Maintenance extends Zend_Controller_Plugin_Abstract {
    
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $request->setActionName("index");
        $request->setControllerName("index");
        // モジュールを動作させているならば
        $request->setModuleName("maintenance"); // モジュール名を指定します。
    }
}