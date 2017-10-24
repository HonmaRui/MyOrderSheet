<?php

class Customer_IndexController extends Zend_Controller_Action {

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
            $this->objCommon = new Common();
            $this->objCustomer = new Customer();
            $this->objCustomerCSV = new CustomerCSV();
            $this->objFormat = new Format();
            $this->objPaginator = new Paginator();
            $this->mdlCustomer = new Application_Model_Customer();
            $this->mdlCustomerRank = new Application_Model_CustomerRank();
            $this->mdlJob = new Application_Model_Job();
            $this->mdlMember = new Application_Model_Member();
            $this->mdlOrder = new Application_Model_Order();
            $this->mdlPref = new Application_Model_Pref();
            
            // 権限確認
            $bAuthority = $this->objCommon->isAuthority($this->_request->getServer("REQUEST_URI"));
            if (!$bAuthority) {
                $this->_redirect(ADMIN_URL . "home");
            }
            
            // 初期データの読み込み
            // 都道府県マスタ
            $this->arrPref = CommonTools::changeDbArrayForFormTag(
                $this->mdlPref->fetchAll(array("m_pref_PrefCode", "m_pref_Name")));
            $this->view->assign("arrPref", $this->arrPref);
            
            // メンバー
            $this->arrMember = CommonTools::changeDbArrayForFormTag(
                $this->mdlMember->fetchAll(array("d_system_member_SystemMemberID", "d_system_member_Name")));
            $this->view->assign("arrMember", $this->arrMember);
            
            // カレントモジュール、コントローラー、アクション名取得
            $objFormReq = $this->getRequest();
            $this->stModuleName = $objFormReq->getModuleName();
            $this->stControllerName = $objFormReq->getControllerName();
            $this->stActionName = $objFormReq->getActionName();
            
            $this->stControllerName = $objFormReq->getControllerName();
            $this->view->assign("stCurrentModule", $this->stModuleName);
            $this->view->assign("stCurrentController", $this->stControllerName);
            $this->view->assign("stCurrentAction", $this->stActionName);
            
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
            
            // 顧客ランク
            $arrTemp = $this->mdlCustomerRank->fetchAll(array("d_customer_rank_CustomerRankID", "d_customer_rank_CustomerRankName", "d_customer_rank_OrderCount"));
            $arrRank = array();
            $this->arrRankDisp = array();
            foreach ($arrTemp as $key => $value) {
                $arrRank[$value["d_customer_rank_CustomerRankID"]] = $value["d_customer_rank_CustomerRankName"] . "(MAX:" . $value["d_customer_rank_OrderCount"] . ")";
                $this->arrRankDisp[$value["d_customer_rank_CustomerRankID"]] = $value["d_customer_rank_CustomerRankName"];
            }
            $this->view->assign("arrRank", $arrRank);
            $this->view->assign("arrRankDisp", $this->arrRankDisp);
            
            // 職業
            $this->arrJob = CommonTools::changeDbArrayForFormTag($this->mdlJob->fetchAll(array("m_job_JobID", "m_job_Name")));
            $this->view->assign("arrJob", $this->arrJob);
            
            $arrDel = array(1 => "退会者を含む");
            $this->view->assign("arrDel", $arrDel);
            
            $this->arrStatus = array(
                "" => "選択してください",
                Application_Model_Order::STATUS_NEW => "新規注文",
                Application_Model_Order::STATUS_PREPARING => "処理中",
                Application_Model_Order::STATUS_TRANSACTIONED => "完了",
                Application_Model_Order::STATUS_CANCEL => "キャンセル"
            );
            $this->view->assign("arrStatus", $this->arrStatus);
            
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

            $objFormReq = $this->getRequest();
            $stMode = $this->_getParam("mode");
            
            // ページ遷移設定
            $iPageNumber = $this->_getParam("page");
            if (!is_numeric($iPageNumber)) {
                $iPageNumber = Customer::RESULT_NUM_MIN_PER_PAGE;
            }

            $iPageLimit = $this->_getParam("limit");
            if (!is_numeric($iPageLimit)) {
                $iPageLimit = Customer::RESULT_NUM_MAX_PER_PAGE;
            }
            
            if ($objFormReq->isPost()) {
                $arrForm = $objFormReq->getPost();
            }
            
            switch ($stMode) {
                case "search":
                case "searchBack":
                    // バリデート処理実行
                    $arrErrorMessage = $this->objCustomer->errorCheckForCustomerSearch($arrForm);
                    if ($arrErrorMessage) {
                        $this->stOnload = "alert('検索条件にエラーがあります。');";
                        break;
                    }
                    
                    // 検索画面に戻ってきた場合は、以前の検索条件をセットする
                    if ($stMode == "searchBack") {
                        $arrForm = $_SESSION["customerSearchForm"];
                    }
                    
                    // 検索条件の設定取得
                    $this->mdlCustomer->setPageLimit($iPageLimit);
                    $this->mdlCustomer->setPageNumber($iPageNumber);
                    $this->mdlCustomer->setSearchCondition($arrForm, array(
                        "d_customer_CustomerID",
                        "d_customer_Name",
                        "d_customer_CompanyName",
                        "d_customer_DepartmentName",
                        "d_customer_EmailAddress",
                        "d_customer_TelNo",
                        "d_customer_NameKana",
                        "d_customer_SignedOut",
                        "d_customer_CustomerRankID"
                    ));

                    // 検索実行
                    $arrResult = $this->mdlCustomer->search();

                    // 検索条件フォーム値保存
                    $_SESSION["customerSearchForm"] = $arrForm;
                    
                    break;
                    
                case "download":
                    // ダウンロードボタン押下処理
                    // 処理制限時間を外す
                    set_time_limit(0);
                    ini_set('memory_limit' ,'1536M');
                    
                    // 一覧表・CSV出力設定の反映
                    $stOrder = "";
                    if ($arrForm["downloadConfig"] == Application_Model_Customer::CONFIG_CUSTOMER_ID) {
                        $stOrder = "d_customer_CustomerID ASC";
                    } else if ($arrForm["downloadConfig"] == Application_Model_Customer::CONFIG_ZIP) {
                        $stOrder = "d_customer_Zip ASC";
                    }
                    
                    // セレクトボックスの選択による処理分け
                    if ($arrForm["downloadSelect"] == Application_Model_Customer::DOWNLOAD_CUSTOMER_CSV_CHECK ||
                            $arrForm["downloadSelect"] == Application_Model_Customer::DOWNLOAD_CUSTOMER_CSV_ALL) {
                        // 顧客CSVデータの作成
                        list($stCustomerData, $arrCustomerCSVResult) = $this->objCustomerCSV->getCustomerCSVData($arrForm);
                        $stCustomerCSVFilename = "./csv/Customer_" . date(YmdHis) . ".csv";
                        file_put_contents($stCustomerCSVFilename, $stCustomerData);

                        // 一時ファイルの削除
                        unlink($stCustomerCSVFilename);
                        
                        // CSVファイルとしてダウンロード
                        $stCustomerCSVFilename = "Customer_" . date(YmdHis) . ".csv";
                        $this->objCustomerCSV->OutputCsv($stCustomerData, $stCustomerCSVFilename);
                        
                        break;
                    }
                    exit;
                default;
                    break;
            }
            
            // CSRF対策実装
            $stCsrfDelete = CommonTools::generateTokenData("delete");
            
            $stOnload = $_SESSION["stOnload"];
            if ($stOnload != "") {
                $this->stOnload = "alert('" . $stOnload . "');";
                unset($_SESSION["stOnload"]);
            }
            
            // 出力セレクトボックス
            $arrDownLoad = array(
                "" => "選択してください",
                Application_Model_Customer::DOWNLOAD_CUSTOMER_CSV_CHECK => "チェックされた顧客CSV",
                Application_Model_Customer::DOWNLOAD_CUSTOMER_CSV_ALL => "全ての顧客CSV"
                );
            $this->view->assign("arrDownLoad", $arrDownLoad);
            
            // 一覧表・CSV出力設定
            $arrDownLoadConfig = array(
            Application_Model_Customer::CONFIG_CUSTOMER_ID => "顧客ID順",
            Application_Model_Customer::CONFIG_ZIP => "郵便番号順",
            Application_Model_Customer::CONFIG_CUSTOMER_PREF => "都道府県順",
            );
            $this->view->assign("arrDownLoadConfig", $arrDownLoadConfig);

            // 登録日の設定
            if ($arrForm["create_from_Year"] && $arrForm["create_from_Month"] && $arrForm["create_from_Day"]) {
                $this->view->assign("createFromDate", $arrForm["create_from_Year"] . "-" . $arrForm["create_from_Month"] . "-" . $arrForm["create_from_Day"]);
                $this->view->assign("create_from_datepicker", $arrForm["create_from_Year"] . "/" . $arrForm["create_from_Month"] . "/" . $arrForm["create_from_Day"]);
            }
            if ($arrForm["create_to_Year"] && $arrForm["create_to_Month"] && $arrForm["create_to_Day"]) {
                $this->view->assign("createToDate", $arrForm["create_to_Year"] . "-" . $arrForm["create_to_Month"] . "-" . $arrForm["create_to_Day"]);
                $this->view->assign("create_to_datepicker", $arrForm["create_to_Year"] . "/" . $arrForm["create_to_Month"] . "/" . $arrForm["create_to_Day"]);
            }
            // 更新日の設定
            if ($arrForm["update_from_Year"] && $arrForm["update_from_Month"] && $arrForm["update_from_Day"]) {
                $this->view->assign("updateFromDate", $arrForm["update_from_Year"] . "-" . $arrForm["update_from_Month"] . "-" . $arrForm["update_from_Day"]);
                $this->view->assign("update_from_datepicker", $arrForm["update_from_Year"] . "/" . $arrForm["update_from_Month"] . "/" . $arrForm["update_from_Day"]);
            }
            if ($arrForm["update_to_Year"] && $arrForm["update_to_Month"] && $arrForm["update_to_Day"]) {
                $this->view->assign("updateToDate", $arrForm["update_to_Year"] . "-" . $arrForm["update_to_Month"] . "-" . $arrForm["update_to_Day"]);
                $this->view->assign("update_to_datepicker", $arrForm["update_to_Year"] . "/" . $arrForm["update_to_Month"] . "/" . $arrForm["update_to_Day"]);
            }
            
            // 検索結果表示件数
            $arrPageLimitTemp = array();
            $arrPageLimit = array();
            $arrPageLimitTemp = explode(',', SEARCH_RESULT_NUMBER);
            foreach ($arrPageLimitTemp as $value) {
                $arrPageLimit[$value] = $value;
            }
            $this->view->assign("arrPageLimit", $arrPageLimit);

            if (!$arrErrorMessage) {
                 // ページごとの表示数の定義を取得
                $objPaginator = $this->objPaginator->getPaginator($this->mdlCustomer->totalCount, $iPageLimit, $iPageNumber);
                // ページ推移ボタンの情報を取得
                $objPaginateInfo = $this->objPaginator->getPaginateInfo($objPaginator);

                $this->view->assign("arrResult", $this->objFormat->Escape($arrResult));
                $this->view->assign("iPageLimit", $iPageLimit);
                $this->view->assign("iPageNumber", $iPageNumber);
                $this->view->assign("objPaginateInfo", $objPaginateInfo);
                $this->view->assign("stMode", $stMode);
            }
            
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            $this->view->assign("iCount", $this->mdlCustomer->totalCount);
            $this->view->assign("stOnload", $this->stOnload);
            $this->view->assign("stCsrfDelete", $stCsrfDelete);
            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("stPageTitle", "顧客検索");

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 登録処理アクション
     * 
     */
    public function addAction() {
        
        $bTran = false;
        $bIsNotExist = false;
        try {
            // 値取得
            $objFormReq = $this->getRequest();
            $stPostCsrf = $this->_getParam("csrf", null);
            $bIsEdit = $this->_getParam("isEdit");
            $stMode = $this->_getParam("mode");
            $iCustomerID = $this->_getParam("customerID");
            $bIsDeleteConfirm = false;
            
            // GET or POST の判定
            if (!$objFormReq->isPost()) {
                // Get request
                if ($bIsEdit) {
                    // 顧客IDがfindできなければ存在しないページとして404表示
                    $arrForm = $this->mdlCustomer->find($iCustomerID);
                    
                    if (!$arrForm) {
                        $bIsNotExist = true;
                        throw new Zend_Exception();
                    }
                }
            } else {
                 // Post request
                CommonTools::checkTokenData("customer", $stPostCsrf);
                $arrFormTemp = $objFormReq->getPost();

                // 入力データの反映
                if ($arrForm != "") {
                    $arrForm = array_merge($arrForm, $arrFormTemp);
                } else {
                    $arrForm = $arrFormTemp;
                }
                
                // ひらがなはカナに変換する
                $arrForm["d_customer_NameKana"] = mb_convert_kana($arrForm["d_customer_NameKana"], "C", "UTF-8");
                // 全角数字は半角数字に変換する
                $arrForm["d_customer_TelNo"] = mb_convert_kana($arrForm["d_customer_TelNo"], "n", "UTF-8");
                $arrForm["d_customer_Zip"] = mb_convert_kana($arrForm["d_customer_Zip"], "n", "UTF-8");
                $arrForm["d_customer_EmailAddress"] = mb_convert_kana($arrForm["d_customer_EmailAddress"], "r", "UTF-8");
                
                switch ($stMode) {
                    case "add":
                        // 顧客登録
                        $arrErrorMessage = $this->objCustomer->errorCheckForCustomer($arrForm, $iCustomerID); // 編集保存時は顧客IDが入る
                        if ($arrErrorMessage == "") {
                            // 共通関数を使用して登録
                            $iCustomerID = $this->objCustomer->createCustomer($arrForm, $iCustomerID); // 編集保存時は顧客IDが入る
                            // ログ収集
                            $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                        } else {
                            $this->stOnload = "alert('顧客情報にエラーがあります。');";
                        }
                        
                        if ($iCustomerID != "" && $arrErrorMessage == "") {
                            // 登録完了後は編集画面へリダイレクト
                            $_SESSION["stOnload"] = "顧客ID[" . $iCustomerID . "]のデータ保存が完了しました";
                            $this->_redirect(ADMIN_URL . "customer/edit/" . $iCustomerID);
                        }
                        break;
                    default:
                        break;
                }
            }
            
            unset($arrForm["csrf"]);
            
            $stOnload = $_SESSION["stOnload"];
            if ($stOnload != "") {
                $this->stOnload = "alert('" . $stOnload . "');";
                unset($_SESSION["stOnload"]);
            }
            
            // 購入履歴取得
            if ($iCustomerID) {
                $arrColumn = array(
                    "d_order_OrderID",
                    "d_order_OrderMngID",
                    "d_order_Status",
                    "d_order_CustomerID",
                    "d_order_CustomerName",
                    "d_order_CreatedTime"
                );
                $arrOrder = $this->mdlOrder->findAll(array("d_order_CustomerID" => $iCustomerID), $arrColumn, array("d_order_OrderID DESC"));
                $this->view->assign("arrOrder", $arrOrder);
            }
            
            // CSRF対策実装
            $stCsrf = CommonTools::generateTokenData("customer");
            $stCsrfDelete = CommonTools::generateTokenData("delete");
            
            // 削除メニュー用配列
            $arrDeleteMenu = array(0 => "選択してください",1 => "この顧客データを削除する");
            $this->view->assign("arrDeleteMenu", $arrDeleteMenu);
            
            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            $this->view->assign("stOnload", $this->stOnload);
            $this->view->assign("stCsrf", $stCsrf);
            $this->view->assign("stMode", $stMode);
            $this->view->assign("stCsrfDelete", $stCsrfDelete);
            $this->view->assign("bIsEdit", $bIsEdit);
            $this->view->assign("bIsDeleteConfirm", $bIsDeleteConfirm);
            $this->view->assign("iCustomerID", $iCustomerID);
            if ($bIsEdit == true) {
                $stPageTitle = "顧客編集";
            } else {
                $stPageTitle = "顧客登録";
            }
            $this->view->assign("stPageTitle", $stPageTitle);
                
        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlCustomer->rollBack();
            }
            if ($bIsNotExist) {
                throw new Zend_Controller_Action_Exception("This page does not exist", 404);
            } else {
                throw new Zend_Exception($e->getMessage());
            }
        }
    }    
    
    /***
     * 
     * 削除処理アクション
     * 
     */
    public function deleteAction() {
        
        $bTran = false;
       
        try {
            // 値取得
            $objFormReq = $this->getRequest();
            $iCustomerID = $this->_getParam("delID");
            $stPostCsrf = $this->_getParam("csrfdelete", null);
            
            // GET or POST の判定
            if (!$objFormReq->isPost()) {
                 // Get request
                 throw new Zend_Exception("リクエストメソッドが不正です。");
            } else {
                 // Post request
                 // CSRF対策チェック
                 CommonTools::checkTokenData("delete", $stPostCsrf);

                 $arrForm = $objFormReq->getPost();
                 
                 // IDチェック
                 if (empty($iCustomerID)) {
                     throw new Zend_Exception("顧客ID取得に失敗しました。");
                 }
            }

            // begin
            $this->mdlCustomer->begin();
            $bTran = true;
            
            // 顧客関連データの削除

            // 顧客データの削除
            $this->mdlCustomer->delete($iCustomerID);
            
            // commit
            $this->mdlCustomer->commit();
            
            if ($objFormReq->isPost()) {
                // ログ収集
                $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);                
            }
            
            $_SESSION["stOnload"] = "顧客ID[" . $iCustomerID . "]の削除が完了しました";
            // redirect
            $this->_redirect(ADMIN_URL . "customer");
                
        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlCustomer->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
       }
    }    
    
}
