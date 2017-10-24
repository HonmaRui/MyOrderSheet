<?php

class Contents_StaticController extends Zend_Controller_Action {

    /***
     * 
     * 初期化処理
     * 
     */
    public function init() {

        try {
            // HTTP
            $objHttp = new Http();
            $objHttp->allowClientCacheCurrent();
            
            // Library & Models
            $this->objMypage = new Mypage();
            $this->mdlCustomer = new Application_Model_Customer();
            
            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();
            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->sidemenu_tpl = "sidemenu.tpl";
            $layout->footer_tpl = "footer.tpl";
            $this->view->assign("layout", $layout);
            
            // カレントモジュール、コントローラー、アクション名取得
            $this->objFormReq = $this->getRequest();
            $this->stModuleName = $this->objFormReq->getModuleName();
            $this->stControllerName = $this->objFormReq->getControllerName();
            $this->stActionName = $this->objFormReq->getActionName();
            $this->view->assign("stCurrentModule", $this->stModuleName);
            $this->view->assign("stCurrentController", $this->stControllerName);
            $this->view->assign("stCurrentAction", $this->stActionName);
            
            // セッション管理
            $this->objFrontSess = new Zend_Session_Namespace("Front");
            $this->objFrontSess->currentURL = $this->_request->getServer("REQUEST_URI");
            
            // 非ログイン時はクッキーによる自動ログイン判定
            if (isset($_COOKIE["ce"]) && isset($_COOKIE["cp"]) && !$this->objFrontSess->Login) {
                $arrCustomer = $this->mdlCustomer->findAll(array("d_customer_EmailAddress" => $_COOKIE["ce"]));
                if ($arrCustomer[0]["d_customer_Password"] == $_COOKIE["cp"]) {
                    $this->objMypage->startFrontSession($arrCustomer);
                }
            }
            
            // ログイン状況
            if ($this->objFrontSess->Login) {
                $this->bIsLogin = true;
                $this->view->assign("bIsLogin", $this->bIsLogin);
            }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * ページ表示アクション
     * 
     */
    public function indexAction() {

        try {
            
            $stDepth1 = $this->_getParam("depth1");
            $stDepth2 = $this->_getParam("depth2");
            $stDepth3 = $this->_getParam("depth3");
            $stDepth4 = $this->_getParam("depth4");
            $stDepth5 = $this->_getParam("depth5");
            
            if ($stDepth1 == "") {
                return $this->_redirect(SSL_URL);
            }
            
            $stRequestURI = $stDepth1;
            if ($stDepth2) {
                $stRequestURI .= "/" . $stDepth2;
            }
            if ($stDepth3) {
                $stRequestURI .= "/" . $stDepth3;
            }
            if ($stDepth4) {
                $stRequestURI .= "/" . $stDepth4;
            }
            if ($stDepth5) {
                $stRequestURI .= "/" . $stDepth5;
            }

            $this->render($stRequestURI);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
