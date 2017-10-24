<?php

class Mypage_RefusalController extends Zend_Controller_Action {

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
     * 退会手続きトップページ表示アクション
     * 
     */
    public function indexAction() {

        try {
            
//            $stPostCsrf = $this->_getParam("csrf", null);
//            $stSalt = "refusal";
//            
//            if (!$this->objFrontSess->Login) {
//                return $this->_redirect(SSL_URL);
//            }
//            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//            } else {
//                // Post request
//                $this->CommonTools->checkTokenData($stSalt, $stPostCsrf);
//                return $this->_forward("confirm");
//            }
//            
//            $stCsrf = $this->CommonTools->generateTokenData($stSalt);
//            $this->view->assign("stCsrf", $stCsrf);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 退会手続き確認ページ表示アクション
     * 
     */
    public function confirmAction() {

        try {
            
//            $stPostCsrf = $this->_getParam("csrf", null);
//            $stSalt = "refusal-confirm";
//            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//                return $this->_redirect(SSL_URL);
//            } else {
//                // Post request
//                switch ($this->stMode) {
//                    case "confirm":
//                        $this->CommonTools->checkTokenData($stSalt, $stPostCsrf);
//                        
//                        // 退会処理
//                        $arrUpdate = array();
//                        $arrUpdate["d_customer_CustomerID"] = $this->objFrontSess->memberID;
//                        $arrUpdate["d_customer_SignedOut"] = "1";
//                        $this->mdlCustomer->begin();
//                        $this->mdlCustomer->save($arrUpdate);
//                        $this->mdlCustomer->commit();
//                         
//                        // 完了画面へ
//                        return $this->_forward("complete");
//
//                        break;
//                    default:
//                        break;
//                }
//            }
//            
//            $stCsrf = $this->CommonTools->generateTokenData($stSalt);
//            $this->view->assign("stCsrf", $stCsrf);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 退会手続き完了ページ表示アクション
     * 
     */
    public function completeAction() {

        try {
            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//                return $this->_redirect(SSL_URL);
//            } else {
//                // セッション情報をクリアする
//                if(isset($this->objFrontSess)){
//                    unset($this->objFrontSess);
//
//                    // クッキー削除
//                    if (isset($_COOKIE["ce"])) {
//                        setcookie("ce", "", time() - 3600);
//                    }
//                    if (isset($_COOKIE["cp"])) {
//                        setcookie("cp", "", time() - 3600);
//                    }
//                    // 最終的に、セッションを破壊する
//                    session_destroy();
//                }
//                
//                $arrBaseInfo = $this->mdlBaseInfo->find(1);
//                $this->view->assign("arrBaseInfo", $arrBaseInfo);
//                $this->bIsLogin = false;
//                $this->view->assign("bIsLogin", $this->bIsLogin);
//            }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
