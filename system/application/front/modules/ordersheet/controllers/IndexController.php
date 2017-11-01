<?php

class Ordersheet_IndexController extends Zend_Controller_Action {

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
            
            // カテゴリ
            $arrCategory = CommonTools::changeDbArrayForFormTag($this->mdlCategory->fetchAll(array(
                "d_category_CategoryID", "d_category_CategoryName")));
            $this->view->assign("arrCategory", $arrCategory);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * オーダーシート検索ページ表示アクション
     * 
     */
    public function indexAction() {

        try {

            // ページ遷移設定
            $iPageNumber = $this->_getParam("page");
            if (!is_numeric($iPageNumber)) {
                $iPageNumber = 1;
            }
            $iPageLimit = $this->_getParam("limit");
            if (!is_numeric($iPageLimit)) {
                $iPageLimit = 10;
            }
            
            $arrForm = $this->objFormReq->getQuery();

            if (!$this->objFormReq->isPost()) {
                // Get request
                // ソート順
                $arrSort = array();
                $stSort = "";
                switch ($arrForm["search_sort"]) {
                    case 1: // 価格が安い順
                        $stSort = "d_product_SortPrice ASC";
                        break;
                    case 2: // 価格が高い順
                        $stSort = "d_product_SortPrice DESC";
                        break;
                    case 3: // 新しい順
                        $stSort = "d_product_CreatedTime DESC";
                        break;
                    case 4: // おすすめ順
                        $stSort = "sps.d_scrap_pdfdl_sum_TotalCount DESC";
                        break;
                }

                // 商品情報取得
                $this->mdlOrderSheet->setPageLimit($iPageLimit);
                $this->mdlOrderSheet->setPageNumber($iPageNumber);
                $this->mdlOrderSheet->setSearchCondition($arrForm, array("*"), $stSort);
                $arrResult = $this->mdlOrderSheet->search(true);

                if ($arrResult) {
                    $this->view->assign("arrResult", $arrResult);

                    // ページごとの表示数の定義を取得
                    $objPaginator = $this->objPaginator->getPaginator($this->mdlOrderSheet->totalCount, $iPageLimit, $iPageNumber);
                   // ページ推移ボタンの情報を取得
                    $objPaginateInfo = $this->objPaginator->getPaginateInfo($objPaginator);
                    $this->view->assign("iPageLimit", $iPageLimit);
                    $this->view->assign("iPageNumber", $iPageNumber);
                    $this->view->assign("objPaginateInfo", $objPaginateInfo);
                    $this->view->assign("iCount", $this->mdlOrderSheet->totalCount);
                }
            } else {
                // Post request
            }
            
            $this->view->assign("arrForm", $arrForm);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
