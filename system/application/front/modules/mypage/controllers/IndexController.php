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
     * カットサンプルご請求履歴ページ表示アクション
     * 
     */
    public function indexAction() {

        try {
//            
//            // ページ遷移設定
//            $iPageNumber = $this->_getParam("page");
//            if (!is_numeric($iPageNumber)) {
//                $iPageNumber = 1;
//            }
//            $iPageLimit = $this->_getParam("limit");
//            if (!is_numeric($iPageLimit)) {
//                $iPageLimit = 10;
//            }
//            
//            // 請求履歴データ取得
//            $this->mdlOrder->setPageLimit($iPageLimit);
//            $this->mdlOrder->setPageNumber($iPageNumber);
//            $arrColumn = array("d_order_OrderID", "d_order_CreatedTime");
//            $this->mdlOrder->setSearchConditionForMypage(array("d_order_CustomerID" => $this->objFrontSess->memberID), $arrColumn);
//            // 受注テーブルの検索実行
//            $arrData = $this->mdlOrder->search();
//            
//            // ページごとの表示数の定義を取得
//            $objPaginator = $this->objPaginator->getPaginator($this->mdlOrder->totalCount, $iPageLimit, $iPageNumber);
//           // ページ推移ボタンの情報を取得
//            $objPaginateInfo = $this->objPaginator->getPaginateInfo($objPaginator);
//            $this->view->assign("iPageLimit", $iPageLimit);
//            $this->view->assign("iPageNumber", $iPageNumber);
//            $this->view->assign("objPaginateInfo", $objPaginateInfo);
//            $this->view->assign("arrData", $arrData);
//            $this->view->assign("iOrderCount", $this->mdlOrder->totalCount);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * カットサンプルご請求履歴詳細ページ表示アクション
     * 
     */
    public function detailAction() {

        try {
//            
//            $iOrderID = $this->_getParam("orderID");
//            
//            // 請求履歴詳細データ取得
//            $arrColumn = array(
//                "d_order_OrderID",
//                "d_order_OrderDeliveryCompanyName",
//                "d_order_OrderDeliveryDepartmentName",
//                "d_order_OrderDeliveryName",
//                "d_order_OrderDeliveryNameKana",
//                "d_order_OrderDeliveryZip",
//                "d_order_OrderDeliveryPrefCode",
//                "d_order_OrderDeliveryAddress1",
//                "d_order_OrderDeliveryAddress2",
//                "d_order_OrderDeliveryTelNo",
//                "d_order_CreatedTime",
//                "od.d_order_detail_OrderDetailID",
//                "od.d_order_detail_ProductID",
//                "od.d_order_detail_ProductName",
//                "od.d_order_detail_Quantity"
//                );
//            $this->mdlOrder->setSearchConditionForMypage(array("d_order_OrderID" => $iOrderID), $arrColumn, array("od.d_order_detail_OrderDetailID"));
//            // 受注テーブルの検索実行
//            $arrOrder = $this->mdlOrder->search(false);
//            
//            
//            // 都道府県
//            $arrPref = $this->CommonTools->changeDbArrayForFormTag($this->mdlPref->fetchAll(array(
//                "m_pref_PrefCode", "m_pref_Name")), false);
//            $this->view->assign("arrPref", $arrPref);
//            $this->view->assign("arrOrder", $arrOrder);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
