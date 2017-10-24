<?php

class Login_IndexController extends Zend_Controller_Action {

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
     * ログインページ表示アクション
     * 
     */
    public function indexAction() {

        try {
        
            // 既にログイン済みの場合はトップへ
            if (!empty($this->objFrontSess->memberID)) {
                return $this->_forward("index");
            }
            
            if (!$this->objFormReq->isPost()) {
                // Get request
            } else {
                // Post request
                $arrForm = $this->objFormReq->getPost();
                
                switch ($this->stMode) {
                    case "login":
                        $arrData = $this->objMypage->isJudgeLogin($arrForm["d_customer_EmailAddress"], $arrForm["d_customer_Password"]);
                        if ($arrData) {
                            // 「ログイン情報を記録する」にチェックが入っている場合はクッキーにmail,passを保持
                            if ($arrForm["login_memory"] == 1) {
                                setcookie("ce", $arrForm["d_customer_EmailAddress"], time() + COOKIE_EXPIRE_SECOND);
                                setcookie("cp", $this->objCommon->makePassword($arrForm["d_customer_Password"]), time() + COOKIE_EXPIRE_SECOND);
                            }
                            return $this->_redirect(SSL_URL);
                        } else {
                            // エラーページへ遷移
                            $this->objFrontSess->ErrorMessage = "メールアドレスもしくはパスワードが正しくありません。
                                <br>本登録がお済みでない場合は、仮登録メールに記載されている
                                <br>URLより本登録を行ってください。";
                            return $this->_redirect(SSL_URL . "/error");
                        }
                        break;
                    default:
                        break;
                }
            }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
