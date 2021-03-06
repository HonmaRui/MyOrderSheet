<?php

class Mypage_IndexController extends Zend_Controller_Action {

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
            $this->objCustomer = new Customer();
            $this->objFormat = new Format();
            $this->objMypage = new Mypage();
            $this->objPaginator = new Paginator();
            $this->mdlCategory = new Application_Model_Category();
            $this->mdlCustomer = new Application_Model_Customer();
            $this->mdlOrderSheet = new Application_Model_OrderSheet();
            
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
                $this->view->assign("stCustomerName", $this->objFrontSess->Name);
            }
            
            if ($this->objFrontSess->memberID == "") {
                return $this->_redirect(URL);
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
     * マイページ表示アクション
     * 
     */
    public function indexAction() {

        try {
            
            $stPostCsrf = $this->_getParam("csrf", null);
            $stMode = $this->_getParam("mode", null);
            $stSalt = "fYglTXMP";
            
            if (!$this->objFormReq->isPost()) {
                // Get request
                if ($_SESSION["changeData"] === true) {
                    $this->view->assign("bCompleted", true);
                    $_SESSION["changeData"] = false;
                }
            } else {
                // Post request
                $arrForm = $this->objFormReq->getPost();

                switch ($stMode) {
                    case "back":
                        CommonTools::checkTokenData($stSalt, $stPostCsrf);
                        $stMode = "entry";
                        break;
                    case "entry":
                        CommonTools::checkTokenData($stSalt, $stPostCsrf);
                        
                        $arrErrorMessage = array();
                        $arrErrorMessage = $this->objMypage->errorCheck($arrForm);
                        
                        // メールアドレス重複チェック
                        if ($arrForm["d_customer_EmailAddress"] != "" && $arrErrorMessage["d_customer_EmailAddress"] == "") {
                            $arrTemp = $this->mdlCustomer->findAll(array(
                                "d_customer_EmailAddress" => $arrForm["d_customer_EmailAddress"],
                                "d_customer_SignedOut" => 0,
                                "d_customer_DelFlg" => 0,
                            ), array("d_customer_CustomerID"));
                            if (count($arrTemp) > 1) {
                                $arrErrorMessage["d_customer_EmailAddress"] = "このメールアドレスは使用できません。別のメールアドレスでご登録ください。";
                            }
                        }
                        
                        // エラーがなければ確認画面へ
                        if (empty($arrErrorMessage)) {
                            $this->objFrontSess->pass = $arrForm["d_customer_Password"];
                            $stMode = "confirm";
                        }

                        break;
                    case "confirm":
                        CommonTools::checkTokenData($stSalt, $stPostCsrf);
                        
                        // 会員データ作成
                        $arrForm["d_customer_Password"] = $this->objFrontSess->pass;
                        $this->objCustomer->createCustomer($this->objFormat->Escape($arrForm), $this->objFrontSess->memberID);
                        
                        // 完了画面へ
                        $_SESSION["changeData"] = true;
                        return $this->_redirect(URL . "/mypage");

                        break;
                    case "refusal":
                        CommonTools::checkTokenData($stSalt, $stPostCsrf);
                        
                        // 退会処理
                        $arrUpdate = array();
                        $arrUpdate["d_customer_CustomerID"] = $this->objFrontSess->memberID;
                        $arrUpdate["d_customer_SignedOut"] = "1";
                        $this->mdlCustomer->begin();
                        $this->mdlCustomer->save($arrUpdate);
                        $this->mdlCustomer->commit();
                        
                        // セッション情報をクリアする
                        if(isset($this->objFrontSess)){
                            unset($this->objFrontSess);
                            session_destroy();
                        }
                         
                        // 完了
                        return $this->_redirect(URL);

                        break;
                    default:
                        break;
                }
            }
            
            $stCsrf = CommonTools::generateTokenData($stSalt);
            $this->view->assign("stCsrf", $stCsrf);
            $this->view->assign("stMode", $stMode);
            
            $this->view->assign("arrForm", $arrForm);
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            
            // ページ遷移設定
            $iPageNumber = $this->_getParam("page");
            if (!is_numeric($iPageNumber)) {
                $iPageNumber = 1;
            }
            $iPageLimit = $this->_getParam("limit");
            if (!is_numeric($iPageLimit)) {
                $iPageLimit = 10;
            }
            
            // データ取得
            $this->mdlOrderSheet->setPageLimit($iPageLimit);
            $this->mdlOrderSheet->setPageNumber($iPageNumber);
            $this->mdlOrderSheet->setSearchConditionForMypage(array("d_order_sheet_CustomerID" => $this->objFrontSess->memberID), array("*"));
            // 受注テーブルの検索実行
            $arrOrderSheet = $this->mdlOrderSheet->search();
            
            if ($arrOrderSheet) {
                // ページごとの表示数の定義を取得
                $objPaginator = $this->objPaginator->getPaginator($this->mdlOrderSheet->totalCount, $iPageLimit, $iPageNumber);
               // ページ推移ボタンの情報を取得
                $objPaginateInfo = $this->objPaginator->getPaginateInfo($objPaginator);
                $this->view->assign("iPageLimit", $iPageLimit);
                $this->view->assign("iPageNumber", $iPageNumber);
                $this->view->assign("objPaginateInfo", $objPaginateInfo);
                $this->view->assign("arrOrderSheet", $arrOrderSheet);
                $this->view->assign("iOrderCount", $this->mdlOrderSheet->totalCount);
            }
            
            // 会員情報取得
            $arrCustomer = $this->objCustomer->getCustomerInfoForFront($this->objFrontSess->memberID);
            $this->view->assign("arrCustomer", $arrCustomer);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
