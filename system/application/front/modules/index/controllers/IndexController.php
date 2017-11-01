<?php

class Index_IndexController extends Zend_Controller_Action {

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
            $this->objFormat = new Format();
            $this->objMypage = new Mypage();
            $this->objImage = new Image();
            $this->mdlCategory = new Application_Model_Category();
            $this->mdlCustomer = new Application_Model_Customer();
            $this->mdlContentsNewinfo = new Application_Model_ContentsNewinfo();
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
            $this->arrCategory = CommonTools::changeDbArrayForFormTag($this->mdlCategory->fetchAll(array(
                "d_category_CategoryID", "d_category_CategoryName")));
            $this->view->assign("arrCategory", $this->arrCategory);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * トップページ表示アクション
     * 
     */
    public function indexAction() {

        try {
            
            $bTran = false;
            $stPostCsrf = $this->_getParam("csrf", null);
            $stMode = $this->_getParam("mode", null);
            $stSalt = "fOuXhEA0";
            
            if (!$this->objFormReq->isPost()) {
                // Get request
                if ($_SESSION["NewOrderSheet"] != "") {
                    $arrNewOrderSheet = $_SESSION["NewOrderSheet"];
                    unset($_SESSION["NewOrderSheet"]);
                }
            } else {
                // Post request
//                CommonTools::checkTokenData($stSalt, $stPostCsrf);
                $arrForm = $this->objFormReq->getPost();

                switch ($stMode) {
                    case "add": // オーダーシート新規投稿
                        
                        // 画像アップロード
                        list($arrForm, $arrErrorMessage) = $this->objImage->imageUpload($arrForm, "d_order_sheet_ImageFileName1", "画像");

                        // オーダーシート作成
                        $arrFormOrderSheet = CommonTools::getExtractTableData(array("d_order_sheet"), $arrForm);
                        
                        // カテゴリマスタデータ取得
                        $arrCategoryData = $this->mdlCategory->find($arrForm["d_order_sheet_CategoryID"]);
                        $arrFormOrderSheet["d_order_sheet_CategoryName"] = $arrCategoryData["d_category_CategoryName"];
                        $arrFormOrderSheet["d_order_sheet_CategoryColorClass"] = $arrCategoryData["d_category_ColorClass"];
                        
                        // 会員データ
                        if ($this->bIsLogin) {
                            $arrFormOrderSheet["d_order_sheet_CustomerID"] = $this->objFrontSess->memberID;
                            $arrFormOrderSheet["d_order_sheet_CustomerName"] = $this->objFrontSess->Name;
                        } else {
                            $arrFormOrderSheet["d_order_sheet_CustomerID"] = "";
                            $arrFormOrderSheet["d_order_sheet_CustomerName"] = "";
                        }
                        
                        // エスケープと改行変換
                        $arrFormOrderSheet["d_order_sheet_Contents"] = $this->objFormat->Escape($arrFormOrderSheet["d_order_sheet_Contents"]);
                        $arrFormOrderSheet["d_order_sheet_Contents"] = str_replace("\n","<br>",$arrFormOrderSheet["d_order_sheet_Contents"]);
                        $arrFormOrderSheet["d_order_sheet_Title"] = $this->objFormat->Escape($arrFormOrderSheet["d_order_sheet_Title"]);
                        
                        // 検索用キーワード
                        $stKeyword = $arrFormOrderSheet["d_order_sheet_Title"] . "$" . $arrFormOrderSheet["d_order_sheet_Contents"] . "$" . $arrFormOrderSheet["d_order_sheet_CategoryName"];
                        $arrFormOrderSheet["d_order_sheet_Keyword"] = $stKeyword;
                        
                        $this->mdlOrderSheet->begin();
                        $bTran = true;
                        
                        $iNewOrderSheetID = $this->mdlOrderSheet->insert($arrFormOrderSheet);
                        $arrFormOrderSheet["d_order_sheet_CreatedTime"] = date("Y-m-d H:i:s");
                        
                        $this->mdlOrderSheet->commit();
                        $bTran = false;
                        
                        unset($arrForm);
                        
                        $arrFormOrderSheet["d_order_sheet_OrderSheetID"] = $iNewOrderSheetID;
                        $_SESSION["NewOrderSheet"] = $arrFormOrderSheet;
                        
                        return $this->_redirect(URL);
                        
                        break;
                    default:
                        break;
                }
            }
            
            // 新着オーダーシート取得
            $arrNewOrder = $this->mdlOrderSheet->fetchLimit("", "", 10);
            
            // カテゴリ別オーダー一覧取得
            foreach ($this->arrCategory as $key => $value) {
                $stVariableName = "category" . $key;
                $arrTemp = $this->mdlOrderSheet->findLimit(array("d_order_sheet_CategoryID" => $key), "", "", 10);
                $arrCategoryOrder[$stVariableName] = $arrTemp;
            }

            $stCsrf = CommonTools::generateTokenData($stSalt);
            $this->view->assign("stCsrf", $stCsrf);
            $this->view->assign("arrNewOrderSheet", $arrNewOrderSheet);
            $this->view->assign("arrNewOrder", $arrNewOrder);
            $this->view->assign("arrCategoryOrder", $arrCategoryOrder);
            $this->view->assign("arrForm", $arrForm);
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            
        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlOrderSheet->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * シート詳細取得アクション
     * 
     */
    public function getSheetInfoAction() {
        
        // オーダーシートID
        $stOrderSheetID = json_decode($this->_getParam("ordersheetID"));
        $bExist = true;
        
        // セッション情報が不正、または、有効期限の場合は、エラーを返す
        if ($this->objFrontSess->invalid == true || empty($stOrderSheetID)) {
            header("HTTP/1.1 503 Service Unavailable");        
            echo "invalid";
            exit;
        } else if ($this->objFrontSess->expired == true) {
            header("HTTP/1.1 503 Service Unavailable");        
            echo "expired";
            exit;
        }
        
        if ($stOrderSheetID) {
            $arrReturn = array();
            $arrResult = $this->mdlOrderSheet->find($stOrderSheetID);
            if (empty($arrResult)) {
                // 取得できない場合は、エラーを返す
                $bExist = false;
            }
            
            $arrResult["d_order_sheet_CreatedTime"] = date("Y年m月d日 H:i", strtotime($arrResult["d_order_sheet_CreatedTime"]));
        } else {
            $bExist = false;
        }
        
        if (!$bExist) {
            header("HTTP/1.1 503 Service Unavailable");
            echo "notExist";
            exit;
        }

        $sJson = json_encode($arrResult);
        echo $sJson;
        
        exit;
    }
    
    /***
     * 
     * ログアウトアクション
     * 
     */
    public function logoutAction() {

        try {
            
            // セッション情報をクリアする
            if(isset($this->objFrontSess)){
                unset($this->objFrontSess);
                
                // クッキー削除
                if (isset($_COOKIE["ce"])) {
                    setcookie("ce", "", time() - 3600);
                }
                if (isset($_COOKIE["cp"])) {
                    setcookie("cp", "", time() - 3600);
                }
                // 最終的に、セッションを破壊する
                session_destroy();
            }
            
            return $this->_redirect(URL);

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
