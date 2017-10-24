<?php

class Entry_IndexController extends Zend_Controller_Action {

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
     * 規約ページ表示アクション
     * 
     */
    public function indexAction() {

        try {
            
//            // 既にログイン済みの場合はフラグ作成
//            if (!empty($this->objFrontSess->memberID)) {
//                $bIsLogin = true;
//                $this->view->assign("bIsLogin", $bIsLogin);
//            }
//            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//            } else {
//                // Post request
//                return $this->_forward("regist");
//            }
//        
//            // 会員規約取得
//            $arrMembership = $this->mdlMembership->fetchAll("", array("d_membership_Rank ASC"));
//            $stMemberShip = "";
//            foreach ($arrMembership as $value) {
//                $stMemberShip .= $value["d_membership_Title"] . "\n\n" . $value["d_membership_Text"] . "\n\n";
//            }
//            $this->view->assign("stMemberShip", $stMemberShip);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 情報入力アクション
     * 
     */
    public function registAction() {

        try {
            
//            $stPostCsrf = $this->_getParam("csrf", null);
//            $stMode = $this->_getParam("mode", null);
//            $stSalt = "regist";
//            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//                return $this->_forward("index");
//            } else {
//                // Post request
//                $arrForm = $this->objFormReq->getPost();
//
//                switch ($stMode) {
//                    case "regist":
//                        $this->CommonTools->checkTokenData($stSalt, $stPostCsrf);
//                        
//                        $arrErrorMessage = array();
//                        $arrErrorMessage = $this->objMypage->errorCheck($arrForm);
//                        
//                        // メールアドレス重複チェック
//                        if ($arrForm["d_customer_EmailAddress"] != "" && $arrErrorMessage["d_customer_EmailAddress"] == "") {
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
//                        // パスワード確認チェック
//                        if ($arrForm["d_customer_Password"] != $arrForm["d_customer_Password-confirm"] && $arrErrorMessage["d_customer_Password-confirm"] == "") {
//                            $arrErrorMessage["d_customer_Password-confirm"] = "パスワードとパスワード(確認)が一致しません。";
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
            
//            $stPostCsrf = $this->_getParam("csrf", null);
//            $stMode = $this->_getParam("mode", null);
//            $stSalt = "confirm";
//            
//            // ブラウザバック対策
//            if ($this->bIsLogin) {
//                return $this->_redirect(SSL_URL);
//            }
//            
//            if (!$this->objFormReq->isPost()) {
//                // Get request
//                return $this->_forward("index");
//            } else {
//                // Post request
//                $arrForm = $this->objFormReq->getPost();
//
//                switch ($stMode) {
//                    case "confirm":
//                        $this->CommonTools->checkTokenData($stSalt, $stPostCsrf);
//                        
//                        // 会員データ作成
//                        unset($arrForm["d_customer_EmailAddress-confirm"]);
//                        unset($arrForm["d_customer_Password-confirm"]);
//                        $arrForm["d_customer_Password"] = $this->objFrontSess->pass;
//                        $iCustomerID = $this->objCustomer->createCustomer($this->objFormat->Escape($arrForm), "");
//                        $this->objFrontSess->entry_complete_mail = $arrForm["d_customer_EmailAddress"];
//                        $this->objFrontSess->entry_complete_pass = $arrForm["d_customer_Password"];
//                        // ログイン済み扱いにする
//                        $this->bIsLogin = true;
//                        $this->view->assign("stCustomerName", $arrForm["d_customer_Name"]);
//                        
//                        // 登録完了メール送信
//                        $arrData = array();
//                        $arrData["toAddress"] = $arrForm["d_customer_EmailAddress"];
//                        $arrData["name01"] = $arrForm["d_customer_Name"];
//                        $arrData["co01"] = $arrForm["d_customer_CompanyName"];
//
//                        $arrHistoryData = array();
//                        $arrHistoryData["d_mail_history_CustomerName"] = $arrForm["d_customer_Name"];
//                        $arrHistoryData["d_mail_history_CustomerID"] = $iCustomerID;
//                        $this->objCommon->sendMailAndSaveHistory(Application_Model_MailSetting::TEMPLATE_ID_MEMBER_REGIST, $arrData, $arrHistoryData);
//                        
//                        // ログ収集
//                        $this->objCommon->writeLog($this->objFrontSess, $this->objFormReq, $arrForm, SET_LOG_LEVEL_ALL, false);
//                        
//                        // 完了画面へ
//                        return $this->_redirect(SSL_URL . "/entry/complete");
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
            
//            if ($this->objFrontSess->entry_complete_mail != "") {
//                // ログイン状態にする
//                $this->objMypage->isJudgeLogin($this->objFrontSess->entry_complete_mail, $this->objFrontSess->entry_complete_pass);
//                unset($this->objFrontSess->entry_complete_mail);
//                unset($this->objFrontSess->entry_complete_pass);
//                if ($this->objFrontSess->Login) {
//                    $this->bIsLogin = true;
//                    $this->view->assign("bIsLogin", $this->bIsLogin);
//                    // サイド領域表示用
//                    if ($this->stCustomerName == "") {
//                        $arrCustomer = $this->objCustomer->getCustomerInfoForFront($this->objFrontSess->memberID);
//                        $this->objFrontSess->SampleCartLimit = $arrCustomer["SampleCartLimit"];
//                        $this->stCustomerName = $arrCustomer["d_customer_Name"];
//                        $this->view->assign("stCustomerName", $this->stCustomerName);
//                    }
//                }
//            } else {
//                return $this->_redirect(SSL_URL);
//            }

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
