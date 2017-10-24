<?php
/**
 * 共通ライブラリ（受注管理）
 *
 * @author     
 * @version    v1.0
 */
class Order {
    
    // クラス定数宣言
    const ORDER_RESULT_NUM_MIN_PER_PAGE = 1;       // 1ページあたりの最小表示件数
    const ORDER_RESULT_NUM_MAX_PER_PAGE = 10;      // 1ページあたりの最大表示件数
    
    // メールテンプレート
    const ORDER_MAIL_TEMPLATE_SHIP = 4; // 出荷確定時配信メール
    
    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {

        // Library & Models
        $this->CommonTools = new CommonTools();
        
        $this->mdlBaseInfo = new Application_Model_BaseInfo();
        $this->mdlCity = new Application_Model_City();
        $this->mdlPayment = new Application_Model_Payment();
        $this->mdlPostage = new Application_Model_Postage();
        $this->mdlPostageTime = new Application_Model_PostageTime();
        $this->mdlPref = new Application_Model_Pref();
        $this->mdlProductClass = new Application_Model_ProductClass();
        $this->mdlZip = new Application_Model_Zip();
        
        // 受注情報
        $this->mdlOrder = new Application_Model_Order();
        $this->mdlOrderDelivery = new Application_Model_OrderDelivery();
        $this->mdlOrderDetail = new Application_Model_OrderDetail();
        $this->mdlOrderMng = new Application_Model_OrderMng();
        
        // 顧客情報
        $this->mdlCustomer = new Application_Model_Customer();
        
        // 基本情報
        $this->mdlMailHistory = new Application_Model_MailHistory();
        $this->mdlMailSetting = new Application_Model_MailSetting();

        $this->arrBaseInfo = $this->mdlBaseInfo->find(1);
        
        $this->arrPref = $this->CommonTools->changeDbArrayForFormTag(
                $this->mdlPref->fetchAll(array("m_pref_PrefCode", "m_pref_Name")));

        //Common
        $this->objCommon = new Common();
        
        //Session
        $this->objAdminSess = new Zend_Session_Namespace('Admin');
        $this->objFrontSess = new Zend_Session_Namespace('Front');
     }

   /**
     * 受注管理フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheck($arrForm) {
        
        try {
            $objValidate = new Validate();
            
            // 顧客
            $stKey = "d_order_CustomerTelNo";
            $stColumnName = "電話番号";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            }

            $stKey = "d_order_CustomerZip";
            $stColumnName = "郵便番号";
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 7));
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $stKey = "d_order_CustomerPrefCode";
            $stColumnName = "都道府県";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $stKey = "d_order_CustomerAddress1";
            $stColumnName = "住所1";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));

            $stKey = "d_order_CustomerAddress2";
            $stColumnName = "住所2";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));
            }

            $stKey = "d_order_CompanyName";
            $stColumnName = "会社名";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }

            $stKey = "d_order_DepartmentName";
            $stColumnName = "部署名";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }

            $stKey = "d_order_CustomerName";
            $stColumnName = "顧客名";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));

            $stKey = "d_order_CustomerNameKana";
            $stColumnName = "顧客名カナ";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }

            $stKey = "d_order_CustomerEmailAddress";
            $stColumnName = "メールアドレス";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 100));
                $objValidate->Execute(array($stKey => $stColumnName), array("Email", $arrForm[$stKey]));
            }
            
            // 送り先
            $stKey = "d_order_OrderDeliveryTelNo";
            $stColumnName = "電話番号";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            }

            $stKey = "d_order_OrderDeliveryZip";
            $stColumnName = "郵便番号";
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 7));
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $stKey = "d_order_OrderDeliveryPrefCode";
            $stColumnName = "都道府県";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $stKey = "d_order_OrderDeliveryAddress1";
            $stColumnName = "住所1";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));

            $stKey = "d_order_OrderDeliveryAddress2";
            $stColumnName = "住所2";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));
            }

            $stKey = "d_order_OrderDeliveryCompanyName";
            $stColumnName = "会社名";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }

            $stKey = "d_order_OrderDeliveryDepartmentName";
            $stColumnName = "部署名";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }

            $stKey = "d_order_OrderDeliveryName";
            $stColumnName = "配送先名";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));

            $stKey = "d_order_OrderDeliveryNameKana";
            $stColumnName = "配送先名カナ";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }

            // 受注登録
            // 受注商品情報
            $stKey = "d_order_detail";
            $stColumnName = "受注商品情報";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $iOrderDetailCount = 1;
            foreach ($arrForm["d_order_detail"] as $key => $value) {
                if ($value["d_order_detail_DelFlg"] == "1") {
                    continue;
                }
                $stKey = "d_order_detail_Quantity_" . $iOrderDetailCount;
                $stColumnName = $iOrderDetailCount . "件目の受注商品にエラーがあります。数量";
                $objValidate->Execute(array($stKey => $stColumnName), array("GreaterThan", $value["d_order_detail_Quantity"], 1));
                $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $value["d_order_detail_Quantity"]));
                $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $value["d_order_detail_Quantity"]));
                $iOrderDetailCount++;
            }

            $stKey = "d_order_Status";
            $stColumnName = "対応状況";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $stKey = "d_order_Memo";
            $stColumnName = "メモ";
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 100));
            
            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 受注検索フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheckOrderSearch($arrForm) {
        
        try {
            
            $objValidate = new Validate();

            // 日付が設定されている場合は、その日付が完全か(年月日全てが入力されているか)チェックする。
            // 更新日
            if ($arrForm["update_from_Year"] || $arrForm["update_from_Month"] || $arrForm["update_from_Day"]) {
                $arrDate = array($arrForm["update_from_Year"], $arrForm["update_from_Month"], $arrForm["update_from_Day"]);
                $stFormKey = "d_order_UpdatedTimeStart";
                $stColumnName = "更新日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            if ($arrForm["update_to_Year"] || $arrForm["update_to_Month"] || $arrForm["update_to_Day"]) {
                $arrDate = array($arrForm["update_to_Year"], $arrForm["update_to_Month"], $arrForm["update_to_Day"]);
                $stFormKey = "d_order_UpdatedTimeEnd";
                $stColumnName = "更新日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            
            // 受注日
            if ($arrForm["order_from_Year"] || $arrForm["order_from_Month"] || $arrForm["order_from_Day"]) {
                $arrDate = array($arrForm["order_from_Year"], $arrForm["order_from_Month"], $arrForm["order_from_Day"]);
                $stFormKey = "d_order_OrderTimeStart";
                $stColumnName = "受注日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            if ($arrForm["order_to_Year"] || $arrForm["order_to_Month"] || $arrForm["order_to_Day"]) {
                $arrDate = array($arrForm["order_to_Year"], $arrForm["order_to_Month"], $arrForm["order_to_Day"]);
                $stFormKey = "d_order_OrderTimeEnd";
                $stColumnName = "受注日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            
            // 実在する日付かチェック
            // 更新日
            if ($arrForm["update_from_Year"] && $arrForm["update_from_Month"] && $arrForm["update_from_Day"]) {
                $stFromDate = $arrForm["update_from_Year"] . "-" . $arrForm["update_from_Month"] . "-" . $arrForm["update_from_Day"];
                $stFormKey = "d_order_UpdatedTimeStart";
                $stColumnName = "更新日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stFromDate));
            }
            if ($arrForm["update_to_Year"] && $arrForm["update_to_Month"] && $arrForm["update_to_Day"]) {
                $stToDate = $arrForm["update_to_Year"] . "-" . $arrForm["update_to_Month"] . "-" . $arrForm["update_to_Day"];
                $stFormKey = "d_order_UpdatedTimeEnd";
                $stColumnName = "更新日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stToDate));
            }
            
            // 受注日
            if ($arrForm["order_from_Year"] && $arrForm["order_from_Month"] && $arrForm["order_from_Day"]) {
                $stFromDate = $arrForm["order_from_Year"] . "-" . $arrForm["order_from_Month"] . "-" . $arrForm["order_from_Day"];
                $stFormKey = "d_order_OrderTimeStart";
                $stColumnName = "受注日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stFromDate));
            }
            if ($arrForm["order_to_Year"] && $arrForm["order_to_Month"] && $arrForm["order_to_Day"]) {
                $stToDate = $arrForm["order_to_Year"] . "-" . $arrForm["order_to_Month"] . "-" . $arrForm["order_to_Day"];
                $stFormKey = "d_order_OrderTimeEnd";
                $stColumnName = "受注日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stToDate));
            }

            // 日付の前後関係のチェックを行う
            // 更新日付の前後関係のチェックを行う
            if ($arrForm["update_from_Year"] && $arrForm["update_from_Month"] && $arrForm["update_from_Day"] &&
                $arrForm["update_to_Year"] && $arrForm["update_to_Month"] && $arrForm["update_to_Day"]) {

                $stFromDate = $arrForm["update_from_Year"] . "/" . $arrForm["update_from_Month"] . "/" . $arrForm["update_from_Day"];
                $stToDate = $arrForm["update_to_Year"] . "/" . $arrForm["update_to_Month"] . "/" . $arrForm["update_to_Day"];
                $stFormKey = "d_order_UpdatedTime";
                $stColumnName = "更新日";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("DateTimeCompare", array($stFromDate, $stToDate)));
            }
            
            // 受注日付の前後関係のチェックを行う
            if ($arrForm["order_from_Year"] && $arrForm["order_from_Month"] && $arrForm["order_from_Day"] &&
                $arrForm["order_to_Year"] && $arrForm["order_to_Month"] && $arrForm["order_to_Day"]) {

                $stFromDate = $arrForm["order_from_Year"] . "/" . $arrForm["order_from_Month"] . "/" . $arrForm["order_from_Day"];
                $stToDate = $arrForm["order_to_Year"] . "/" . $arrForm["order_to_Month"] . "/" . $arrForm["order_to_Day"];
                $stFormKey = "d_order_OrderTime";
                $stColumnName = "受注日";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("DateTimeCompare", array($stFromDate, $stToDate)));
            }

            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
     
   /**
     * 受注管理フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheckOrder($arrForm) {
        
        try {
            $objValidate = new Validate();


            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 受注入力（編集）フォーム 配送先入力データのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheckOrderDelivery($arrForm) {
        
        try {
            $objValidate = new Validate();

            // 配送先名
            $stKey = "d_order_delivery_FirstName";
            $stColumnName = "配送先名";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            // 配送先名（カナ）
            $stKey = "d_order_delivery_Kana";
            $stColumnName = "配送先名（カナ）";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("Kana", $arrForm[$stKey]));
                $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            }
            
            // 配送先電話番号
            $stKey = "d_order_delivery_TelNo";
            $stColumnName = "配送先電話番号";
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            // 配送先郵便番号
            $stKey = "d_order_delivery_Zip";
            $stColumnName = "配送先郵便番号";
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            // 配送先都道府県
            $stKey = "d_order_delivery_PrefCode";
            $stColumnName = "配送先都道府県";
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            // 配送先住所
            $stKey = "d_order_delivery_Address1";
            $stColumnName = "配送先住所";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $arrErrorMessage = $objValidate->getResult();
            
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     * 
     * 住所から、正しい市区町村コードを取得する
     * $arrCity        array  市区町村マスタ(該当する都道府県分のみ)
     * $stAddress      string 住所
     * return  $iCityCode 市区町村コード *** 0の場合は見つからなかった時
     */
    function checkAddress($arrCity, $stAddress) {
        
        $iCityCode = 0;
        foreach ($arrCity as $key => $value) {
            mb_regex_encoding("UTF-8");
            if (preg_match("/" . $value["m_city_Name"] . "/u", $stAddress)) {
                $iCityCode = $value["m_city_CityCode"];
                break;
            }
        }
        return $iCityCode;
    }
    
   /**
     * 受注データの作成・更新
     *
     * @param  array   $arrForm     フォームデータを格納した配列
    *  @param  bool    $bIsEdit     (true:編集, false:新規作成)
     * @return array
     */
    function createOrder($arrForm, $bIsEdit) {
        
        $arrOrder = array();
        $arrOrderMng = array();
        $arrOrderMng["d_order_mng_Date"] = date("Y.m.d H:i:s");

        // トランザクション開始
        $this->mdlOrderMng->begin();
        
        if ($bIsEdit == false) {
            // 注文管理データ新規作成
            $arrOrderMng["d_order_mng_CustomerID"] = $arrForm["d_order_CustomerID"];
            $arrOrderMng["d_order_mng_BuyDiv"] = 1;
            $arrOrderMng["d_order_mng_PostageID"] = 1;
            $arrOrderMng["d_order_mng_TaxRate"] = 8;
            $arrOrderMng["d_order_mng_BuyRouteID"] = 1;
            $arrOrderMng["d_order_mng_SalePlanID"] = 1;
            $arrOrderMng["d_order_mng_SaleDiv"] = 1;
            $arrOrderMng["d_order_mng_TaxDiv"] = 1;
            $arrOrderMng["d_order_mng_TaxFraction"] = 3;
            $arrOrderMng["d_order_mng_OrderMngID"] = $this->mdlOrderMng->insert($arrOrderMng);
        } else {
            $arrOrderMng = array();
            $arrTemp = $this->mdlOrder->find(array("d_order_OrderID" => $arrForm["d_order_OrderID"]));
            $arrOrderMng["d_order_mng_OrderMngID"] = $arrTemp["d_order_OrderMngID"];
            $arrOrderMng["d_order_mng_CustomerID"] = $arrTemp["d_order_CustomerID"];
            // 注文管理データ更新
            $this->mdlOrderMng->save($arrOrderMng);
        }
        
        // 受注データ
        $arrOrder["d_order_OrderMngID"] = $arrOrderMng["d_order_mng_OrderMngID"];
        foreach ($arrForm as $key => $value) {
            if (preg_match("/^d_order/", $key) && $key != "d_order_detail") {
                $arrOrder[$key] = $value;
            }
        }
        $arrOrder["d_order_ParentFlg"] = 1;
        
        // ステータスに応じて発送日の値変更
        switch ($arrOrder["d_order_Status"]) {
            case Application_Model_Order::STATUS_NEW:
            case Application_Model_Order::STATUS_PREPARING:
            case Application_Model_Order::STATUS_CANCEL:
                $arrOrder["d_order_ShippingDate"] = null;
                break;
            case Application_Model_Order::STATUS_TRANSACTIONED:
                $arrOrder["d_order_ShippingDate"] = date("Y.m.d H:i:s");
                break;
        }

        // 空を設定する
        if (empty($arrOrder["d_order_PostageTimeID"])) {
            $arrOrder["d_order_PostageTimeID"] = null;
        }
        if (empty($arrOrder["d_order_CustomerSenderID"])) {
            $arrOrder["d_order_CustomerSenderID"] = "";
        }
        if (empty($arrOrder["d_order_OrderDeliveryID"])) {
            $arrOrder["d_order_OrderDeliveryID"] = "";
        }

        if (empty($arrOrder["d_order_OrderID"])) {
            // 受注データ新規作成
            // 集計用データを編集する
            $stOrderMngDate = str_replace(".", "/", $arrOrderMng["d_order_mng_Date"]);
            $arrOrder["d_order_TotalDate"] = date("Y-m-d", strtotime($stOrderMngDate));
            $arrOrder["d_order_TotalMonth"] = date("Y-m", strtotime($stOrderMngDate));
            $arrOrder["d_order_TotalYear"] = intval(date("Y", strtotime($stOrderMngDate)));
            $arrOrder["d_order_TotalWeek"] = intval(date("w", strtotime($stOrderMngDate)));
            $arrOrder["d_order_TotalTime"] = intval(date("H", strtotime($stOrderMngDate)));

            $arrOrder["d_order_OrderID"] = $this->mdlOrder->insert($arrOrder);
        } else {
            // 受注データ更新
            $this->mdlOrder->save($arrOrder);
        }

        
        // 商品の重複を修正するループ
        $arrTemp = array();
        foreach ($arrForm["d_order_detail"] as $k => $v) {
            if ($v["d_order_detail_DelFlg"] == "0") {
                if ($arrTemp[$v["d_order_detail_ProductID"]] == "") {
                    $arrTemp[$v["d_order_detail_ProductID"]] = $v["d_order_detail_Quantity"];
                } else {
                    $arrTemp[$v["d_order_detail_ProductID"]] += $v["d_order_detail_Quantity"];
                    unset($arrForm["d_order_detail"][$k]);
                }
            }
        }
        
        foreach ($arrForm["d_order_detail"] as $k => $v) {
            $arrOrderDetail = $v;
            $arrOrderDetail["d_order_detail_OrderID"] = $arrOrder["d_order_OrderID"];
            $arrOrderDetail["d_order_detail_Quantity"] = $arrTemp[$v["d_order_detail_ProductID"]];
            if (empty($arrOrderDetail["d_order_detail_OrderDetailID"])) {
                // 受注明細データ新規作成
                if ($v["d_order_detail_DelFlg"] == "1") {
                    // 新規作成の場合は、削除データを登録する必要なし
                    continue;
                }
                $arrOrderDetail["d_order_detail_OrderDetailID"] = $this->mdlOrderDetail->insert($arrOrderDetail);
            } else {
                // 受注明細データ更新
                $this->mdlOrderDetail->save($arrOrderDetail);
            }
        }
            
        // トランザクション終了
        $this->mdlOrderMng->commit();
        
        return $arrOrder["d_order_OrderID"];
    }
    
   /**
     * 受注データの作成
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function createOrderForFront($arrForm) {
        // 最終チェック
        // 1.受注商品数が制限を超えていたらエラーとする
        $iProductCount = 0;
        foreach ($arrForm["orderDetail"] as $key => $value) {
            $iProductCount += $value["d_order_detail_Quantity"];
        }
        if ($this->objFrontSess->SampleCartLimit < $iProductCount) {
            throw new Zend_Exception("受注商品数が制限を超過エラー");
        }
        // 2.顧客IDがない場合はエラーとする
        if ($arrForm["d_order_CustomerID"] == "" || !$arrForm["d_order_CustomerID"]) {
            throw new Zend_Exception("顧客が登録されていないか、ログインしていません");
        }
        
        $arrOrderMng = array();
        $arrOrderMng["d_order_mng_Date"] = date("Y.m.d H:i:s");
        $arrOrderMng["d_order_mng_CustomerID"] = $arrForm["d_order_CustomerID"];
        $arrOrderMng["d_order_mng_BuyDiv"] = 1;
        $arrOrderMng["d_order_mng_PostageID"] = 1;
        $arrOrderMng["d_order_mng_TaxRate"] = 8;
        $arrOrderMng["d_order_mng_BuyRouteID"] = 1;
        $arrOrderMng["d_order_mng_SalePlanID"] = 1;
        $arrOrderMng["d_order_mng_SaleDiv"] = 1;
        $arrOrderMng["d_order_mng_TaxDiv"] = 1;
        $arrOrderMng["d_order_mng_TaxFraction"] = 3;

        // トランザクション開始
        $this->mdlOrderMng->begin();
        // 注文管理データ新規作成
        $arrForm["d_order_OrderMngID"] = $this->mdlOrderMng->insert($arrOrderMng, false);
        
        $arrOrder = $arrForm;
        unset($arrOrder["orderDetail"]);
        // 集計用データを編集する
        $stOrderMngDate = str_replace(".", "/", $arrOrderMng["d_order_mng_Date"]);
        $arrOrder["d_order_TotalDate"] = date("Y-m-d", strtotime($stOrderMngDate));
        $arrOrder["d_order_TotalMonth"] = date("Y-m", strtotime($stOrderMngDate));
        $arrOrder["d_order_TotalYear"] = intval(date("Y", strtotime($stOrderMngDate)));
        $arrOrder["d_order_TotalWeek"] = intval(date("w", strtotime($stOrderMngDate)));
        $arrOrder["d_order_TotalTime"] = intval(date("H", strtotime($stOrderMngDate)));
        
        // 受注デフォルト値セット
        $arrOrder["d_order_ShippingTemp"] = 1;
        $arrOrder["d_order_Status"] = 1;
        $arrOrder["d_order_ParentFlg"] = 1;

        $iOrderID = $this->mdlOrder->insert($arrOrder, false);


        foreach ($arrForm["orderDetail"] as $key => $value) {
            $arrOrderDetail = $value;
            $arrProductClass = $this->mdlProductClass->findAll(array("d_product_class_ProductID" => $value["d_order_detail_ProductID"]));
            $arrOrderDetail["d_order_detail_ProductClassID"] = $arrProductClass[0]["d_product_class_ProductClassID"];
            $arrOrderDetail["d_order_detail_OrderID"] = $iOrderID;
            $arrOrderDetail["d_order_detail_Rank"] = $key + 1;
            // 受注明細データ新規作成
            $this->mdlOrderDetail->insert($arrOrderDetail, false);
        }
        
        // トランザクション終了
        $this->mdlOrderMng->commit();
                        
        // メール送信
        $arrData = $arrForm;
        $arrCustomer = $this->mdlCustomer->find($arrForm["d_order_CustomerID"]);
        $arrData["toAddress"] = $arrCustomer["d_customer_EmailAddress"];
        $arrData["name01"] = $arrCustomer["d_customer_Name"];
        $arrData["co01"] = $arrCustomer["d_customer_CompanyName"];
        $arrData["AllAddress"] = $this->arrPref[$arrForm["d_order_OrderDeliveryPrefCode"]] . $arrForm["d_order_OrderDeliveryAddress1"] . $arrForm["d_order_OrderDeliveryAddress2"];

        $arrHistoryData = array();
        $arrHistoryData["d_mail_history_CustomerName"] = $arrForm["d_order_CustomerName"];
        $arrHistoryData["d_mail_history_OrderID"] = $iOrderID;
        $arrHistoryData["d_mail_history_CustomerID"] = $arrForm["d_order_CustomerID"];
        $this->objCommon->sendMailAndSaveHistory(Application_Model_MailSetting::TEMPLATE_ID_ORDER_COMPLETE, $arrData, $arrHistoryData);
            
    }
    
    /**
     * 受注編集時、初期読込フォームを作成する
     * 
     * @param   string   $stOrderMngID      注文管理ID
     * @return  array
     */
    public function getEditOrderMngInfo($stOrderMngID) {
        $arrForm = array();
        
        // 注文管理テーブルのデータ取得
        $arrSearchCondition = array();
        $arrSearchCondition["d_order_mng_OrderMngID"] = $stOrderMngID;
        $arrColumn = array(
            "d_order_mng_BodyPrice",  "d_order_mng_BuyDiv",                "d_order_mng_BuyRouteID", "d_order_mng_CancelReason",     "d_order_mng_CloseDate",
            "d_order_mng_CustomerID", "d_order_mng_FeePrice",              "d_order_mng_OrderMngID", "d_order_mng_OrderMngTaxPrice", "d_order_mng_OrderTaxPrice",
            "d_order_mng_PaymentID",  "d_order_mng_PostageID",             "d_order_mng_SaleDiv",    "d_order_mng_SalePlanID",       "d_order_mng_ShippingPrice",
            "d_order_mng_Subtotal",   "d_order_mng_TaxableBodyPrice",      "d_order_mng_TaxDiv",     "d_order_mng_TaxFraction",      "d_order_mng_TaxRate",
            "d_order_mng_TotalPrice", "pay.d_payment_Name as paymentName", "d_order_mng_PaymentDate", "d_order_mng_BillingDate",     "d_order_mng_EstimateMngID"
        );
        $this->mdlOrderMng->setSearchCondition($arrSearchCondition, $arrColumn);
        $arrOrderMng = $this->mdlOrderMng->search();
        if (empty($arrOrderMng)) {
            return null;
        }
        
        // 日付の整形
        if ($arrOrderMng[0]["d_order_mng_PaymentDate"] != "") {
            $arrForm["payment_date_Year"] = date("Y", strtotime($arrOrderMng[0]["d_order_mng_PaymentDate"]));
            $arrForm["payment_date_Month"] = date("m", strtotime($arrOrderMng[0]["d_order_mng_PaymentDate"]));
            $arrForm["payment_date_Day"] = date("d", strtotime($arrOrderMng[0]["d_order_mng_PaymentDate"]));
        }
        
        $arrForm["d_order_mng"] = $arrOrderMng[0];
        // 内消費税分を計算
        $arrForm["d_order_mng"]["internalTax"] = abs($this->objCommon->calcTax($arrOrderMng[0]["d_order_mng_Subtotal"], null, $arrOrderMng[0]["d_order_mng_TaxRate"], $arrOrderMng[0]["d_order_mng_TaxFraction"]));
        // 配送伝票枚数 (初期化)
        $arrForm["d_order_mng"]["totalSlipCount"] = 0;
        
        // 顧客テーブルのデータ取得
        $arrSearchCondition = array();
        $arrSearchCondition["d_customer_CustomerID"] = $arrOrderMng[0]["d_order_mng_CustomerID"];
        $arrColumn = array(
            "d_customer_Address1",             "d_customer_Address2",      "d_customer_Address3",   "d_customer_Address4",       "d_customer_Balance",
            "d_customer_CityCode",             "d_customer_CustomerCode",  "d_customer_CustomerID", "d_customer_DeliverSlipFlg", "d_customer_EmailAddress",
            "d_customer_ExpectedPaymentMonth", "d_customer_Name",          "d_customer_NameKana",   "d_customer_PaymentDate",    "d_customer_PaymentID",
            "d_customer_PrefCode",             "d_customer_PrevDate",      "d_customer_Remarks",    "d_customer_SaleDiv",        "d_customer_SalePriceFlg",
            "d_customer_ShippingDiv",          "d_customer_TaxCalc",       "d_customer_TaxDiv",     "d_customer_TaxFraction",    "d_customer_TelBranchNo",
            "d_customer_TelNo",                "d_customer_UnitPriceRate", "d_customer_Zip",        "mp.m_pref_Name",            "pay.d_payment_Name",
        );
        $this->mdlCustomer->setSearchCondition($arrSearchCondition, $arrColumn);
        $arrCustomer = $this->mdlCustomer->search();
        $arrForm["d_customer"] = $arrCustomer[0];
        
        // 受注テーブルのデータ取得
        $arrSearchCondition = array();
        $arrSearchCondition["d_order_mng_OrderMngID"] = $arrOrderMng[0]["d_order_mng_OrderMngID"];
        $arrColumn = array(
            "d_order_BodyPrice",                   "d_order_BusinessPrintFlg",          "d_order_Column1",                  "d_order_Column10",              "d_order_Column2",
            "d_order_Column3",                     "d_order_Column4",                   "d_order_Column5",                  "d_order_Column6",               "d_order_Column7",
            "d_order_Column8",                     "d_order_Column9",                   "d_order_CustomerAddress1",         "d_order_CustomerAddress2",      "d_order_CustomerAddress3",
            "d_order_CustomerAddress4",            "d_order_CustomerCityCode",          "d_order_CustomerCode",             "d_order_CustomerID",            "d_order_CustomerName",
            "d_order_CustomerNameKana",            "d_order_CustomerPrefCode",          "d_order_CustomerSenderCode",       "d_order_CustomerSenderID",      "d_order_CustomerTelBranchNo",
            "d_order_CustomerTelNo",               "d_order_CustomerZip",               "d_order_DeliverSlipFlg",           "d_order_DetailTaxPrice",        "d_order_ExpectedShippingDate",
            "d_order_HopeDeliverDate",             "d_order_InvoicePrintFlg",           "d_order_Memo",                     "d_order_OrderDeliveryAddress1", "d_order_OrderDeliveryAddress2",
            "d_order_OrderDeliveryAddress3",       "d_order_OrderDeliveryCityCode",     "d_order_OrderDeliveryCode",        "d_order_OrderDeliveryID",       "d_order_OrderDeliveryName",
            "d_order_OrderDeliveryNameKana",       "d_order_OrderDeliveryPrefCode",     "d_order_OrderDeliveryTelBranchNo", "d_order_OrderDeliveryTelNo",    "d_order_OrderDeliveryZip",
            "d_order_OrderID",                     "d_order_OrderMngID",                "d_order_OrderTaxPrice",            "d_order_PostageTimeID",         "d_order_SlipRemarks",
            "d_order_SenderAddress1",              "d_order_SenderAddress2",            "d_order_SenderAddress3",           "d_order_SenderAddress4",        "d_order_SenderCityCode",
            "d_order_SenderName",                  "d_order_SenderNameKana",            "d_order_SenderPrefCode",           "d_order_SenderTelBranchNo",     "d_order_SenderTelNo",
            "d_order_SenderZip",                   "d_order_ShippingPrice",             "d_order_ShippingTemp",             "d_order_SlipCount",             "d_order_SlipHopeDeliverDatePrintFlg",
            "d_order_SlipPrintFlg",                "d_order_Status",                    "d_order_Summary",                  "d_order_TaxableBodyPrice",      "d_order_TotalPrice",
            "dmp.m_pref_Name as deliveryPrefName", "smp.m_pref_Name as senderPrefName", "d_order_ParentFlg",                "d_order_ShippingDate",          "d_order_SlipPackageStyle1",
            "d_order_SlipPackageStyle2"
        );
        $this->mdlOrder->setSearchCondition($arrSearchCondition, $arrColumn);
        $arrOrder = $this->mdlOrder->search(false);
        
        foreach ($arrOrder as $key => $value) {
            // 受注明細テーブルのデータ取得
            $arrSearchCondition = array();
            $arrSearchCondition["d_order_detail_OrderID"] = $value["d_order_OrderID"];
            $arrColumn = array(
                "d_order_detail_CostPrice",     "d_order_detail_DetailBodyPrice", "d_order_detail_DetailPrice",    "d_order_detail_DisplayProductName",    "d_order_detail_KgConversion",
                "d_order_detail_OrderDetailID", "d_order_detail_OrderID",         "d_order_detail_ProductClassID", "d_order_detail_ProductClassTypeName1", "d_order_detail_ProductClassTypeName2",
                "d_order_detail_ProductCode",   "d_order_detail_ProductID",       "d_order_detail_ProductName",    "d_order_detail_Quantity",              "d_order_detail_Rank",
                "d_order_detail_StandardPrice", "d_order_detail_TaxDiv",          "d_order_detail_TaxFraction",    "d_order_detail_TaxRate",               "d_order_detail_Unit",
                "d_order_detail_UnitPrice",     "d_order_detail_DetailTaxPrice",
            );
            $this->mdlOrderDetail->setSearchCondition($arrSearchCondition, $arrColumn);
            $arrOrder[$key]["d_order_detail"] = $this->mdlOrderDetail->search(false);
            foreach ($arrOrder[$key]["d_order_detail"] as $k => $v) {
                // 受注明細(商品)毎の小計
                $arrOrder[$key]["d_order_detail"][$k]["calcUnitPrice"] = $v["d_order_detail_UnitPrice"] * $v["d_order_detail_Quantity"];
                $arrOrder[$key]["d_order_detail"][$k]["calcStandardPrice"] = $v["d_order_detail_StandardPrice"] * $v["d_order_detail_Quantity"];
                // 受注単位での小計(税込)を保持する
                $arrOrder[$key]["subTotal"] += ($v["d_order_detail_UnitPrice"] * $v["d_order_detail_Quantity"]);
            }
            
            // 日付の整形
            if ($value["d_order_HopeDeliverDate"] != "") {
                $arrOrder[$key]["hope_delivery_Year"] = date("Y", strtotime($value["d_order_HopeDeliverDate"]));
                $arrOrder[$key]["hope_delivery_Month"] = date("m", strtotime($value["d_order_HopeDeliverDate"]));
                $arrOrder[$key]["hope_delivery_Day"] = date("d", strtotime($value["d_order_HopeDeliverDate"]));
            }
            if ($value["d_order_ExpectedShippingDate"] != "") {
                $arrOrder[$key]["expected_shipping_Year"] = date("Y", strtotime($value["d_order_ExpectedShippingDate"]));
                $arrOrder[$key]["expected_shipping_Month"] = date("m", strtotime($value["d_order_ExpectedShippingDate"]));
                $arrOrder[$key]["expected_shipping_Day"] = date("d", strtotime($value["d_order_ExpectedShippingDate"]));
            }
            if ($value["d_order_ShippingDate"] != "") {
                $arrOrder[$key]["shipping_date_Year"] = date("Y", strtotime($value["d_order_ShippingDate"]));
                $arrOrder[$key]["shipping_date_Month"] = date("m", strtotime($value["d_order_ShippingDate"]));
                $arrOrder[$key]["shipping_date_Day"] = date("d", strtotime($arrForm["edit"]["d_order"]["d_order_ShippingDate"]));
            }
            // 受注の送り先・送り主選択ポップアップ用データ
            if ($arrForm["stOrderForm"] != "") {
                $arrForm["stOrderForm"] .= "/";
            }
            $stOrderDeliveryID = empty($value["d_order_OrderDeliveryID"]) ? "" : $value["d_order_OrderDeliveryID"];
            $stCustomerSenderID = empty($value["d_order_CustomerSenderID"]) ? "" : $value["d_order_CustomerSenderID"];
            $arrForm["stOrderForm"] .= $stOrderDeliveryID . "," . $stCustomerSenderID . "," . $value["d_order_ShippingTemp"];
        }
        $arrForm["stOrderForm"] = rtrim($arrForm["stOrderForm"], "/");
        
        $arrForm["d_order"] = $arrOrder;
        
        return $arrForm;
    }    
    
    /***
     * 
     * 受注メール送信
     * $iTemplateID テンプレートID
     * $iOrderID    受注ID
     * $iCustomerID 顧客ID
     */
    function sendMailForOrder($iTemplateID, $iOrderID, $iCustomerID, $bTransaction = false) {
        
        $bTran = false;
        
        try {
            
            $stTitleString = "";
            $stMailString = "";
            $arrResult = $this->mdlMailSetting->find($iTemplateID);
            if ($arrResult) {
                $stTitleString = $arrResult["d_mail_setting_Title"];
                $stMailString = $arrResult["d_mail_setting_Content"];
            }

            $errorMsg = "";
            for ($i = 0; $i < 10; $i++) {
                $arrCustomer = $this->mdlCustomer->find($iCustomerID);
                if (!empty($arrCustomer)) {
                    break;
                }
                $errorMsg .= "customer find failed. (" . ($i + 1) . ") CustomerID = " . $iCustomerID . ": OrderID = " . $iOrderID . "\n";
                usleep(100000);
            }
            if (!empty($arrCustomer) && !empty($errorMsg)) {
                $fp = fopen("../log/errorMail" . date("YmdHis"), 'a+');
                fwrite($fp, $errorMsg);
                fclose($fp);
            }

            $arrOrder = $this->mdlOrder->find(array("d_order_OrderID" => $iOrderID), array("d_order_OrderMngID"));

            // 選択した受注データをもとに、メールテンプレートに当て込みを行う
            $stEmailAddress = $arrCustomer["d_customer_EmailAddress"];
            if (!empty($stEmailAddress) && MAIL_DISP == 1) {
                $stCustomerName = $arrCustomer["d_customer_Name"];
                $stTitleString = $this->getTemplateMailStringOrder($stTitleString, $iOrderID);
                $stMailString = $this->getTemplateMailStringOrder($stMailString, $iOrderID);

                // メール送信

                //言語設定、内部エンコーディングを指定する
                mb_language("japanese");
                mb_internal_encoding("UTF-8");

                $to = $stEmailAddress;
                $subject = $stTitleString;
                $body = $stMailString;
                $arrBaseInfo = $this->mdlBaseInfo->find(1);
                $header = "MIME-Version: 1.0\n"
                        . "Content-Transfer-Encoding: 7bit\n"
                        . "Content-Type: text/plain; charset=ISO-2022-JP\n"
                        . "Message-Id: <" . md5(uniqid(microtime())) . "@" . DOMAIN . ">\n"
                        . "From:" . mb_encode_mimeheader(mb_convert_encoding($arrBaseInfo["d_baseinfo_Name"],
                            "JIS", "UTF-8")) . "<" . $arrBaseInfo["d_baseinfo_EmailAddress"] . ">";
                $erraddress = $arrBaseInfo["d_baseinfo_EmailAddress"];

                $iRet = mb_send_mail($to, $subject, $body, $header, "-f " . $erraddress);
                if (!$iRet) {
                    $errorMsg = "sendOrderMail: mb_send_mail false: CustomerID = " . $iCustomerID . 
                        ": OrderID = " . $iOrderID . ": TO = " . $to . " : SUBJECT = " . $subject . ": BODY = " .
                        $body . ": HEADER = " . $header . " :";
                    $fp = fopen("../log/errorMail" . date("YmdHis"), 'w');
                    fwrite($fp, $errorMsg);
                    fclose($fp);
                }

                // メール送信履歴保存
                if ($bTransaction) {
                    $this->mdlMailHistory->begin();
                    $bTran = true;
                }
                $arrInsert = array();
                $arrInsert["d_mail_history_MailHistoryID"] = null;
                $arrInsert["d_mail_history_TemplateID"] = $iTemplateID;
                $arrInsert["d_mail_history_OrderMngID"] = $arrOrder["d_order_OrderMngID"];
                $arrInsert["d_mail_history_OrderID"] = $iOrderID;
                $arrInsert["d_mail_history_CustomerID"] = $iCustomerID;
                $arrInsert["d_mail_history_CustomerName"] = $stCustomerName;
                $arrInsert["d_mail_history_SendDate"] = date("Y-m-d H:i:s");
                $arrInsert["d_mail_history_Title"] = $subject;
                $arrInsert["d_mail_history_Content"] = $body;
                $arrInsert["d_mail_history_DelFlg"] = 0;
                $this->mdlMailHistory->insert($arrInsert, $bIsAdmin);
                if ($bTransaction) {
                    $this->mdlMailHistory->commit();
                    $bTran = false;
                }

                // ログ収集
                if ($bIsAdmin) {
                    $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrInsert, SET_LOG_LEVEL_LIMITED, true);
                } else {
                    $this->objCommon->writeLog($this->objFrontSess, $objFormReq, $arrInsert, SET_LOG_LEVEL_LIMITED, false);
                }
            } else {
                $errorMsg = "sendOrderMail: Email IS NULL: CustomerID = " . $iCustomerID . ": OrderID = " . $iOrderID;
                $fp = fopen("../log/errorMail" . date("YmdHis"), 'w');
                fwrite($fp, $errorMsg);
                fclose($fp);
            }
            
            return $errorMsg;
            
        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlOrder->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 受注検索からの受注メール送信(メール本文変換済みの前提)
     * $iTemplateID  テンプレートID(テンプレートを使用しないことも可能)
     * $stTitle      メール件名
     * $stContent    メール本文
     * $iOrderID     受注ID
     * $iOrderID     注文管理ID
     * $stCustomerID 顧客ID
     * $bTransaction トランザクション(初期値false　true:この関数内でトランザクション　false:呼び出し元でトランザクション)
     */
    function sendMailForOrderFromOrderSearch($iTemplateID, $stTitle, $stContent, $iOrderID, $iOrderMngID, $stCustomerID, $bTransaction = false) {

        $bTran = false;
        
        try {

            $errorMsg = "";
            for ($i = 0; $i < 10; $i++) {
                $arrCustomer = $this->mdlCustomer->find($stCustomerID);
                if (!empty($arrCustomer)) {
                    break;
                }
                $errorMsg .= "customer find failed. (" . ($i + 1) . ") CustomerID = " . $iCustomerID . ": OrderID = " . $iOrderID . "\n";
                usleep(100000);
            }
            if (!empty($arrCustomer) && !empty($errorMsg)) {
                $fp = fopen("../log/errorMail" . date("YmdHis"), 'a+');
                fwrite($fp, $errorMsg);
                fclose($fp);
            }

            // 選択した受注データをもとに、メールテンプレートに当て込みを行う
            $stEmailAddress = $arrCustomer["d_customer_EmailAddress"];
            if (!empty($stEmailAddress) && MAIL_DISP == 1) {
                $stCustomerName = $arrCustomer["d_customer_Name"];

                // メール送信

                //言語設定、内部エンコーディングを指定する
                mb_language("japanese");
                mb_internal_encoding("UTF-8");

                $to = $stEmailAddress;
                $subject = $stTitle;
                $body = $stContent;
                $arrBaseInfo = $this->mdlBaseInfo->find(1);
                $header = "MIME-Version: 1.0\n"
                        . "Content-Transfer-Encoding: 7bit\n"
                        . "Content-Type: text/plain; charset=ISO-2022-JP\n"
                        . "Message-Id: <" . md5(uniqid(microtime())) . "@" . DOMAIN . ">\n"
                        . "From:" . mb_encode_mimeheader(mb_convert_encoding($arrBaseInfo["d_baseinfo_Name"],
                            "JIS", "UTF-8")) . "<" . $arrBaseInfo["d_baseinfo_EmailAddress"] . ">";
                $erraddress = $arrBaseInfo["d_baseinfo_EmailAddress"];

                $iRet = mb_send_mail($to, $subject, $body, $header, "-f " . $erraddress);
                if (!$iRet) {
                    $errorMsg = "sendOrderMail: mb_send_mail false: CustomerID = " . $iCustomerID . 
                        ": OrderID = " . $iOrderID . ": TO = " . $to . " : SUBJECT = " . $subject . ": BODY = " .
                        $body . ": HEADER = " . $header . " :";
                    $fp = fopen("../log/errorMail" . date("YmdHis"), 'w');
                    fwrite($fp, $errorMsg);
                    fclose($fp);
                }

                // メール送信履歴保存
                if ($bTransaction) {
                    $this->mdlMailHistory->begin();
                    $bTran = true;
                }
                $arrInsert = array();
                $arrInsert["d_mail_history_MailHistoryID"] = null;
                if ($iTemplateID == "") {
                    $iTemplateID = null;
                }
                $arrInsert["d_mail_history_TemplateID"] = $iTemplateID;
                $arrInsert["d_mail_history_OrderMngID"] = $iOrderMngID;
                $arrInsert["d_mail_history_OrderID"] = $iOrderID;
                $arrInsert["d_mail_history_CustomerID"] = $stCustomerID;
                $arrInsert["d_mail_history_CustomerName"] = $stCustomerName;
                $arrInsert["d_mail_history_SendDate"] = date("Y-m-d H:i:s");
                $arrInsert["d_mail_history_Title"] = $subject;
                $arrInsert["d_mail_history_Content"] = $body;
                $arrInsert["d_mail_history_DelFlg"] = 0;
                $this->mdlMailHistory->insert($arrInsert, $bIsAdmin);
                if ($bTransaction) {
                    $this->mdlMailHistory->commit();
                    $bTran = false;
                }

                // ログ収集
                if ($bIsAdmin) {
                    $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrInsert, SET_LOG_LEVEL_LIMITED, true);
                } else {
                    $this->objCommon->writeLog($this->objFrontSess, $objFormReq, $arrInsert, SET_LOG_LEVEL_LIMITED, false);
                }
            } else {
                $errorMsg = "sendOrderMail: Email = " . $stEmailAddress . " CustomerID = " . $iCustomerID . ": OrderID = " . $iOrderID;
                $fp = fopen("../log/errorMail" . date("YmdHis"), 'w');
                fwrite($fp, $errorMsg);
                fclose($fp);
            }
            
            return $errorMsg;
            
        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlOrder->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }    
    }
    
   /**
    * メールテンプレート文字列の出力（受注用）
    * $stData string 変換文字列
    * $iOrderID int  受注ID
    * return  $stString メール変換後文字列
    */
    function getTemplateMailStringOrder($stData, $iOrderID) {
        
        try {
            
            // 都道府県マスタ
            $arrPref = $this->CommonTools->changeDbArrayForFormTag(
                $this->mdlPref->fetchAll(array("m_pref_PrefCode", "m_pref_Name")));
            // 市区町村マスタ
            $arrCity = $this->CommonTools->changeDbArrayForFormTag(
                $this->mdlCity->fetchAll(array("m_city_CityCode", "m_city_Name")));
            // 配送時間テーブル
            $arrPostageTime = $this->CommonTools->changeDbArrayForFormTag(
                $this->mdlPostageTime->fetchAll(array("d_postage_time_PostageTimeID", "d_postage_time_Name")));

            $stString = "";
            // ①対象文字列内のコメント(<!--,-->)を外す
            $arrReplace = array();
            $arrReplace[] = "/<!--/";
            $arrReplace[] = "/-->/";
            $stExchange = preg_replace($arrReplace, "", $stData);

            // ②受注データ作成
            $arrConf = array();
            $arrBaseInfo = $this->mdlBaseInfo->find(1);
            $arrConf["shop_name"] = $arrBaseInfo["d_baseinfo_Name"];
            $arrData = array();
            $arrOrder = $this->mdlOrder->findAll(array("d_order_OrderID" =>$iOrderID));
            $arrOrderMng = $this->mdlOrderMng->findAll(array("d_order_mng_OrderMngID" => $arrOrder[0]["d_order_OrderMngID"]));
            if ($arrOrder[0]["d_order_OrderDeliveryID"]) {
               $arrOrderDelivery = $this->mdlOrderDelivery->findAll(array("d_order_delivery_OrderDeliveryID" => $arrOrder[0]["d_order_OrderDeliveryID"]));
            } else {
                $arrOrderDelivery = array();
            }
            $arrOrderDetail = $this->mdlOrderDetail->findAll(array("d_order_detail_OrderID" => $iOrderID));

            // 親受注フラグ
            $arrData["parent_flg"] = $arrOrder[0]["d_order_ParentFlg"];
            
            $arrData["orderNo"] = $iOrderID;
            $arrData["name01"] = $arrOrder[0]["d_order_CustomerName"];
            $arrData["order_kana01"] = $arrOrder[0]["d_order_CustomerNameKana"];
            $arrData["co01"] = $arrOrder[0]["d_order_CompanyName"];
            $arrData["co02"] = $arrOrder[0]["d_order_DepartmentName"];
            $arrData["order_zip01"] = $arrOrder[0]["d_order_CustomerZip"];
            $arrData["order_pref"] = $arrPref[$arrOrder[0]["d_order_CustomerPrefCode"]];
            $arrData["order_addr01"] = trim($arrOrder[0]["d_order_CustomerAddress1"] . " " . $arrOrder[0]["d_order_CustomerAddress2"]
                     . " " . $arrOrder[0]["d_order_CustomerAddress3"] . " " . $arrOrder[0]["d_order_CustomerAddress4"]);
            $arrData["tel01"] = $arrOrder[0]["d_order_CustomerTelNo"];
            $arrData["email"] = $arrOrder[0]["d_order_CustomerEmailAddress"];
            
            $arrData["create_date"] = date("Y年m月d日 H:i:s", strtotime($arrOrderMng[0]["d_order_mng_Date"]));
            
            // お届け先
            $arrData["d_order_OrderDeliveryName"] = $arrOrder[0]["d_order_OrderDeliveryName"];
            $arrData["d_order_OrderDeliveryZip"] = $arrOrder[0]["d_order_OrderDeliveryZip"];
            $arrData["d_order_OrderDeliveryCompanyName"] = $arrOrder[0]["d_order_OrderDeliveryCompanyName"];
            $arrData["d_order_OrderDeliveryDepartmentName"] = $arrOrder[0]["d_order_OrderDeliveryDepartmentName"];
            $arrData["deliv_pref"] = $arrPref[$arrOrder[0]["d_order_OrderDeliveryPrefCode"]];
            $arrData["AllAddress"] = trim($arrPref[$arrOrder[0]["d_order_OrderDeliveryPrefCode"]] . $arrOrder[0]["d_order_OrderDeliveryAddress1"] . $arrOrder[0]["d_order_OrderDeliveryAddress2"]
                     . " " . $arrOrder[0]["d_order_OrderDeliveryAddress3"] . " " . $arrOrder[0]["d_order_OrderDeliveryDept1"] . " " . $arrOrder[0]["d_order_OrderDeliveryDept2"]);
            $arrData["d_order_OrderDeliveryTelNo"] = $arrOrder[0]["d_order_OrderDeliveryTelNo"];
            
            $arrData["deliv_date"] = date("Y年m月d日", strtotime($arrOrder[0]["d_order_HopeDeliverDate"]));
            $arrData["deliv_time"] = $arrPostageTime[$arrOrder[0]["d_order_PostageTimeID"]];
            $arrData["d_order_Memo"] = $arrOrder[0]["d_order_Memo"];
            $arrData["genre"] = "注文時のお問い合わせ";
            $arrData["contents"] = $arrOrder[0]["d_order_Memo"];

            $arrData["detail"] = array();

            $iTax = 0;
            
            if ($arrOrderDetail) {
                foreach ($arrOrderDetail as $key => $value) {
                    $arrData["orderDetail"][$key]["product_code"] = $value["d_order_detail_ProductID"];
                    $arrData["orderDetail"][$key]["d_order_detail_ProductName"] = $value["d_order_detail_ProductName"];
                    $arrData["orderDetail"][$key]["price02"] = $value["d_order_detail_UnitPrice"];
                    $arrData["orderDetail"][$key]["d_order_detail_Quantity"] = $value["d_order_detail_Quantity"];
                }
            }
            $arrData["subtotal"] = $arrOrder[0]["d_order_TotalPrice"] - $arrOrder[0]["d_order_ShippingPrice"];

            $arrData["tax"] = $arrOrder[0]["d_order_DetailTaxPrice"];
            $arrData["deliv_fee"] = $arrOrder[0]["d_order_ShippingPrice"]; // 受注毎の送料に変更
            $arrData["charge"] = $arrOrderMng[0]["d_order_mng_FeePrice"];
            if ($arrOrder[0]["d_order_ParentFlg"] == 1) {
                // 親受注
                $arrData["total"] = intval($arrOrder[0]["d_order_TotalPrice"]) + intval($arrOrderMng[0]["d_order_mng_FeePrice"]);  //受注内の総額 + 手数料
            } else {
                $arrData["total"] = $arrOrder[0]["d_order_TotalPrice"];  //受注内の総額
            }
            $arrData["payment_total"] = $arrOrderMng[0]["d_order_mng_TotalPayment"];

            $arrOrderAll = $this->mdlOrder->findAll(array("d_order_OrderMngID" => $arrOrder[0]["d_order_OrderMngID"]));
            
            $arrData["order_num"] = count($arrOrderAll);
            $arrData["order"] = array();
            if ($arrOrderAll) {
                foreach ($arrOrderAll as $key => $value) {
                    $arrData["order"][$key]["order_id"] = $value["d_order_OrderID"];
                    $arrData["order"][$key]["total"] = $value["d_order_TotalPrice"];
                }
            }

            $arrData["sum_payment_total"] = $arrOrderMng[0]["d_order_mng_TotalPayment"];
            $arrData["payment_id"] = $arrOrderMng[0]["d_order_mng_PaymentID"];

            // 出荷予告
            $arrData["commit_date"] = date("Y年m月d日", strtotime($arrOrder[0]["d_order_ExpectedShippingDate"]));
            
            // ③smartyで変換
            $smarty = new Smarty;
            $smarty->assign("arrConf", $arrConf);
            $smarty->assign("arrData", $arrData);
            $stString = $smarty->fetch('string:'.$stExchange);

            return $stString;

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 
     * 配送先を追加する
     *  
     * @param   array   $arrForm            入力フォームデータ
     * @return  int     $stOrderDeliveryID    送り先ID
     */
    function createOrderDelivery($arrForm, $bIsAdmin = true) {

        try {
            // 送り先テーブル
            $arrOrderDelivery = array();
            foreach ($arrForm as $key => $value) {
                if (preg_match("/^d_order_delivery_[A-Z]+/", $key)) {
                    $arrOrderDelivery[$key] = $value;
                }
            }
            
            $this->mdlOrderDelivery->begin();
            
            if (empty($arrOrderDelivery["d_order_delivery_OrderDeliveryID"])) {
                // 新規登録
                $stOrderDeliveryID = $this->mdlOrderDelivery->insert($arrOrderDelivery, $bIsAdmin);
            } else {
                // 登録済みデータの更新
                $this->mdlOrderDelivery->save($arrOrderDelivery, $bIsAdmin);
                $stOrderDeliveryID = $arrOrderDelivery["d_order_delivery_OrderDeliveryID"];
            }
        
            $this->mdlOrderDelivery->commit();
            return $stOrderDeliveryID;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
