<?php

class Basis_MailHistoryController extends Zend_Controller_Action {

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
            $this->objPaginator = new Paginator();
            $this->objCommon = new Common();
            $this->objBasis = new Basis();
            $this->objFormat = new Format();
            $this->mdlMailSetting = new Application_Model_MailSetting();
            $this->mdlMailHistory = new Application_Model_MailHistory();

            // メールテンプレート
            $this->arrTemplate = CommonTools::changeDbArrayForFormTag(
                $this->mdlMailSetting->fetchAll(array("d_mail_setting_TemplateID", "d_mail_setting_Name")));

            //権限確認
            $bAuthority = $this->objCommon->isAuthority($_SERVER['REQUEST_URI']);
            if (!$bAuthority) {
                $this->_redirect(ADMIN_URL.'home');
            }

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 検索画面表示アクション
     * 
     */
    public function indexAction() {

        try {

            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();
            
            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->nav_tpl = "nav.tpl";
            $layout->footer_tpl = "footer.tpl";
            $this->view->assign("layout", $layout);
            
            $this->objAdminSess = new Zend_Session_Namespace("Admin");
            $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);
            $this->view->assign("arrGlobalNavPos", $this->objCommon->getGlobalNavCurrentPos($this->objAdminSess->arrMenu));

            // 共通テンプレへの変数渡し
            $view = $layout->getView();
            $view->assign("arrTemplate",  $this->arrTemplate);

            $objFormReq = $this->getRequest();
            $stMode = $this->_getParam("mode");

            // ページ遷移設定
            $iPageNumber = $this->_getParam("page");
            if (!is_numeric($iPageNumber)) {
                $iPageNumber = Basis::BASIS_RESULT_NUM_MIN_PER_PAGE;
            }

            $iPageLimit = $this->_getParam("limit");
            if (!is_numeric($iPageLimit)) {
                $iPageLimit = Basis::BASIS_RESULT_NUM_MAX_PER_PAGE;
            }

            if ($objFormReq->isPost()) {
                $arrForm = $objFormReq->getPost();
            }

            switch ($stMode) {
                case "search":
                    $arrErrorMessage = $this->objBasis->errorCheckMailHistory($arrForm);
                    if ($arrErrorMessage) {
                        $arrForm["mode"] = "";
                        break;
                    }

                    // 検索条件の設定取得
                    $arrForm["mode"] = "search";
                    $this->mdlMailHistory->setPageLimit($iPageLimit);
                    $this->mdlMailHistory->setPageNumber($iPageNumber);
                    $arrColumn = array("*");
                    
                    $this->mdlMailHistory->setSearchCondition($arrForm, $arrColumn);
                    
                    set_time_limit(0);
                    
                    // メール履歴テーブルの検索実行
                    $arrResult = $this->mdlMailHistory->search();

                    break;
                default;
                    break;
            }
 
            // 画面タイトル
            $stPageTitle = "メール履歴";
            
            // 配信日の設定
            if ($arrForm["post_from_Year"] && $arrForm["post_from_Month"] && $arrForm["post_from_Day"]) {
                $this->view->assign("postFromDate", $arrForm["post_from_Year"] . "-" . $arrForm["post_from_Month"] . "-" . $arrForm["post_from_Day"]);
                $this->view->assign("post_from_datepicker", $arrForm["post_from_Year"] . "/" . $arrForm["post_from_Month"] . "/" . $arrForm["post_from_Day"]);
            }
            if ($arrForm["post_to_Year"] && $arrForm["post_to_Month"] && $arrForm["post_to_Day"]) {
                $this->view->assign("postToDate", $arrForm["post_to_Year"] . "-" . $arrForm["post_to_Month"] . "-" . $arrForm["post_to_Day"]);
                $this->view->assign("post_to_datepicker", $arrForm["post_to_Year"] . "/" . $arrForm["post_to_Month"] . "/" . $arrForm["post_to_Day"]);
            }

            if (!$arrErrorMessage) {
                 // ページごとの表示数の定義を取得
                // Paginatorオブジェクトを生成
                $objPaginator = $this->objPaginator->getPaginator($this->mdlMailHistory->totalCount, $iPageLimit, $iPageNumber);
                // ページ推移ボタンの情報を取得
                $objPaginateInfo = $this->objPaginator->getPaginateInfo($objPaginator);

                // ----------------------------- tplファイルへの変数渡し処理
                // 件数
                $this->view->assign("arrResult", $this->objFormat->Escape($arrResult));
                $this->view->assign("iCount", $this->mdlMailHistory->totalCount);
                $this->view->assign("iPageNumber", $iPageNumber);
                $this->view->assign("objPaginateInfo", $objPaginateInfo);
                $this->view->assign("stMode", $stMode);
            }

            $this->view->assign("iPageLimit", $iPageLimit);
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("stOnload", $this->stOnload);
            $this->view->assign("stPageTitle", $stPageTitle);

            // 検索結果表示件数
            $arrPageLimit = array(10 => "10", 20 => "20", 30 => "30", 40 => "40", 50 => "50", 60 => "60", 70 => "70", 80 => "80", 90 => "90", 100 => "100");
            $this->view->assign("arrPageLimit", $arrPageLimit);

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    /***
     * 
     * 確認ポップアップアクション
     * 
     */
    public function confirmPopupAction() {

        try {

            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();
            
            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->footer_tpl = "footer.tpl";
            $this->view->assign("layout", $layout);

            $this->objAdminSess = new Zend_Session_Namespace("Admin");
            $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);

            // 共通テンプレへの変数渡し
            $view = $layout->getView();
            $view->assign("arrTemplate",  $this->arrTemplate);

            $objFormReq = $this->getRequest();
            $iMailHistoryID = $this->_getParam("mailhistoryid");

            if (!$objFormReq->isPost()) {
                // Get request
            } else {
                // Post request
                if ($iMailHistoryID) {
                    $arrResult = $this->mdlMailHistory->find($iMailHistoryID);
                    
                    // HTMLエンコードする
                    $arrResult["d_mail_history_CustomerName"] =  $this->objFormat->Escape($arrResult["d_mail_history_CustomerName"]);
                    $arrResult["d_mail_history_SendDate"] =  $this->objFormat->Escape($arrResult["d_mail_history_SendDate"]);
                    $arrResult["d_mail_history_Content"] =  $this->objFormat->Escape($arrResult["d_mail_history_Content"]);
                }
            }

            // ----------------------------- tplへの変数作成
            // 改行文字を<br />にする
            $arrResult["d_mail_history_Content"] = nl2br($arrResult["d_mail_history_Content"]);
            
            $this->view->assign("arrResult", $arrResult);
            $this->view->assign("stOnload", $this->stOnload);

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}