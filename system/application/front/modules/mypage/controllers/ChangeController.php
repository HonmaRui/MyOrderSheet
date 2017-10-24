<?php

class Mypage_ChangeController extends Zend_Controller_Action {

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
     * 会員情報変更ページ表示アクション
     * 
     */
    public function indexAction() {

        try {
//            
//            $stPostCsrf = $this->_getParam("csrf", null);
//            $stMode = $this->_getParam("mode", null);
//            $stSalt = "change";
//            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//                if ($this->bIsLogin) {
//                    // 顧客情報の取得
//                    $arrForm = $this->mdlCustomer->find($this->objFrontSess->memberID);
//                    $arrForm["d_customer_EmailAddress-confirm"] = $arrForm["d_customer_EmailAddress"];
//                    $arrForm["d_customer_Password"] = substr($arrForm["d_customer_Password"], 0, 8); // パスワード(暗号化済)は先頭8文字だけ出す
//                    $arrForm["d_customer_Password-confirm"] = substr($arrForm["d_customer_Password"], 0, 8);
//                } else {
//                    return $this->_redirect(SSL_URL);
//                }
//            } else {
//                // Post request
//                $arrForm = $this->objFormReq->getPost();
//
//                switch ($stMode) {
//                    case "change":
//                        $this->CommonTools->checkTokenData($stSalt, $stPostCsrf);
//                        
//                        $arrErrorMessage = array();
//                        $arrErrorMessage = $this->objMypage->errorCheck($arrForm);
//                        
//                        // メールアドレス重複チェック
//                        if ($arrForm["d_customer_EmailAddress-original"] != $arrForm["d_customer_EmailAddress"] &&
//                                $arrForm["d_customer_EmailAddress"] != "" && $arrErrorMessage["d_customer_EmailAddress"] == "") {
//                            $arrTemp = $this->mdlCustomer->findAll(array(
//                                "d_customer_EmailAddress" => $arrForm["d_customer_EmailAddress"],
//                                "d_customer_SignedOut" => 0,
//                                "d_customer_DelFlg" => 0,
//                            ), array("d_customer_CustomerID"));
//                            if (count($arrTemp) > 0) {
//                                $arrErrorMessage["d_customer_EmailAddress"] = "このメールアドレスは使用できません。別のメールアドレスでご登録ください。";
//                            }
//                        }
//                        // メールアドレス確認チェック
//                        if ($arrForm["d_customer_EmailAddress"] != $arrForm["d_customer_EmailAddress-confirm"] && $arrErrorMessage["d_customer_EmailAddress-confirm"] == "") {
//                            $arrErrorMessage["d_customer_EmailAddress-confirm"] = "メールアドレスとメールアドレス(確認)が一致しません。";
//                        }
//                        // パスワードが変更された場合のみ、パスワード確認チェックを行う
//                        if ($arrForm["d_customer_Password-original"] != substr($this->objCommon->makePassword($arrForm["d_customer_Password"]), 0, 8)) {
//                            if ($arrForm["d_customer_Password"] != $arrForm["d_customer_Password-confirm"] && $arrErrorMessage["d_customer_Password-confirm"] == "") {
//                                $arrErrorMessage["d_customer_Password-confirm"] = "パスワードとパスワード(確認)が一致しません。";
//                            }
//                        }
//
//                        // エラーがなければ確認画面へ
//                        if (empty($arrErrorMessage)) {
//                            $this->objFrontSess->pass = $arrForm["d_customer_Password"];
//                            return $this->_forward("confirm");
//                        }
//
//                        break;
//                    default:
//                        break;
//                }
//            }
//            
//            $stCsrf = $this->CommonTools->generateTokenData($stSalt);
//            $this->view->assign("stCsrf", $stCsrf);
//            
//            $this->view->assign("arrForm", $arrForm);
//            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
   
    /***
     * 
     * 入力内容確認アクション
     * 
     */
    public function confirmAction() {

        try {
//            
//            $stPostCsrf = $this->_getParam("csrf", null);
//            $stMode = $this->_getParam("mode", null);
//            $stSalt = "confirm";
//            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//                if (!$this->bIsLogin) {
//                    return $this->_redirect(SSL_URL);
//                }
//            } else {
//                // Post request
//                $arrForm = $this->objFormReq->getPost();
//                
//                $arrForm["d_customer_Password"] = $this->objFrontSess->pass;
//                
//                // パスワードが変更されていなければunset
//                if ($arrForm["d_customer_Password-original"] == substr($this->objCommon->makePassword($arrForm["d_customer_Password"]), 0, 8)) {
//                    unset($arrForm["d_customer_Password"]); // 変更しないのでunset
//                    unset($arrForm["d_customer_Password-confirm"]);
//                    unset($arrForm["d_customer_Password-original"]);
//                }
//
//                switch ($stMode) {
//                    case "confirm":
//                        $this->CommonTools->checkTokenData($stSalt, $stPostCsrf);
//                        
//                        // 保存
//                        unset($arrForm["d_customer_EmailAddress-confirm"]);
//                        unset($arrForm["d_customer_Password-confirm"]);
//                        $this->objCustomer->createCustomer($this->objFormat->Escape($arrForm), $this->objFrontSess->memberID);
//                        
//                        // ログ収集
//                        $this->objCommon->writeLog($this->objFrontSess, $this->objFormReq, $arrForm, SET_LOG_LEVEL_ALL, false);
//                        
//                        // 完了画面へ
//                        return $this->_redirect(SSL_URL . "/mypage/change/complete");
//
//                        break;
//                    default:
//                        break;
//                }
//            }
//            
//            unset($arrForm["csrf"]);
//            $stCsrf = $this->CommonTools->generateTokenData($stSalt);
//            $this->view->assign("stCsrf", $stCsrf);
//            
//            $this->view->assign("arrForm", $arrForm);

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 完了画面表示アクション
     * 
     */
    public function completeAction() {

        try {
            
            if (!$this->bIsLogin) {
                return $this->_redirect(SSL_URL);
            }

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
