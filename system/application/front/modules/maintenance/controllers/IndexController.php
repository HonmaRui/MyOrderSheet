<?php

class Maintenance_IndexController extends Zend_Controller_Action {

    /***
     * 
     * メンテナンスアクション
     * 
     */
    public function indexAction() {
        // メンテナンスモードじゃない場合はアクセスを拒否
        if (MAINTENANCE_MODE == 0) {
            return $this->_redirect(SSL_URL);
        }
    }

}
