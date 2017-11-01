<?php

class Error_IndexController extends Zend_Controller_Action {

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
            
            $this->mdlCategory = new Application_Model_Category();
            
            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();
            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
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
            
            $this->objFrontSess = new Zend_Session_Namespace("Front");
            
            // ログイン情報取得(名前)
            if ($this->objFrontSess->Login) {
                $this->view->assign("bIsLogin", true);
                $this->view->assign("stCustomerName", $this->objFrontSess->Name);
            }
            
            // カテゴリ
            $this->arrCategory = CommonTools::changeDbArrayForFormTag($this->mdlCategory->fetchAll(array(
                "d_category_CategoryID", "d_category_CategoryName")));
            $this->view->assign("arrCategory", $this->arrCategory);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * エラーページ表示アクション
     * 
     */
    public function indexAction() {

        try {
        
            // エラー内容が空ならトップへ戻す
            if ($this->objFrontSess->ErrorMessage == "") {
                return $this->_redirect(SSL_URL);
            }
            // エラー内容をセッションから取得
            $stErrorMessage = $this->objFrontSess->ErrorMessage;
            unset($this->objFrontSess->ErrorMessage);
            $this->view->assign("stErrorMessage", $stErrorMessage);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
