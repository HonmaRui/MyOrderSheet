<?php

class Index_IndexController extends Zend_Controller_Action {

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
            $this->mdlContentsNewinfo = new Application_Model_ContentsNewinfo();
            
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
     * トップページ表示アクション
     * 
     */
    public function indexAction() {

        try {
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * ログアウトアクション
     * 
     */
    public function logoutAction() {

        try {
            
            // セッション情報をクリアする
            if(isset($this->objFrontSess)){
                unset($this->objFrontSess);
                
                // クッキー削除
                if (isset($_COOKIE["ce"])) {
                    setcookie("ce", "", time() - 3600);
                }
                if (isset($_COOKIE["cp"])) {
                    setcookie("cp", "", time() - 3600);
                }
                // 最終的に、セッションを破壊する
                session_destroy();
            }
            
            return $this->_redirect(SSL_URL);

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
