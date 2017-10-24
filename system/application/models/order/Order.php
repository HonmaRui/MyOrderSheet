<?php

/**
 * 受注テーブル用モデル
 *
 * @author     M-PIC鈴木
 * @version    v1.0
 */
class Application_Model_Order extends Application_Model_Abstract {

    /**
     * テーブル名
     * @var
     */
    protected $_stTableName = "d_order";

    // クラス定数宣言

    // 対応状況
    const STATUS_NEW = 1;                       // 1:新規注文
    const STATUS_PREPARING = 2;                 // 2:処理中
    const STATUS_TRANSACTIONED = 3;             // 3:完了
    const STATUS_CANCEL = 4;                    // 4:キャンセル
  
    // 配送時間帯
    const TIME_MORNING = 1;                     // 1:午前中
    const TIME_FROM12TO14 = 2;                  // 2:12時から14時
    const TIME_FORM14TO16 = 3;                  // 3:14時から16時
    const TIME_FORM16TO18 = 4;                  // 4:16時から18時
    const TIME_FORM18TO20 = 5;                  // 5:18時から20時
    const TIME_FORM20TO21 = 6;                  // 6:20時から21時    
    
    // 配達温度帯
    const SHIPPING_TEMP_NORMAL = 1;             // 1:通常
    const SHIPPING_TEMP_CHILLED = 2;            // 2:冷蔵
    const SHIPPING_TEMP_FROZEN = 3;             // 3:冷凍
    
    // 支払方法(DB/env.phpと合わせる事)
    const PAYMENT_CASH_ID = 0;                  // 0:現金
    const PAYMENT_POST_ID = 1;                  // 1:郵便振替
    const PAYMENT_BANK_ID = 2;                  // 2:銀行振込
    const PAYMENT_CHECK_ID = 3;                 // 3:小切手
    const PAYMENT_DRAFT_ID = 4;                 // 4:手形
    const PAYMENT_OTHER_ID = 5;                 // 5:その他
    const PAYMENT_CVS_ID = 6;                   // 6:コンビニ
    const PAYMENT_COD_ID = 7;                   // 7:代金引換

    // 購入経路        
    const BUYROUTE_PC = 1;                        // 1:PCから購入
    const BUYROUTE_SP = 2;                        // 2:モバイルから購入
    const BUYROUTE_REGULAR = 3;                   // 3:定期から購入
    const BUYROUTE_ADMIN = 4;                     // 4:管理者が購入
    
    // 購入区分
    const BUYDIV_NORMAL = 1;                      // 1:通常
    const BUYDIV_REGULAR = 2;                     // 2:定期
    
    // 売上区分
    const SALEDIV_CASH = 1;                 // 現金売
    const SALEDIV_CHARGE = 2;               // 掛売
    const SALEDIV_RETURN_CASH = 3;          // 現金売返品
    const SALEDIV_RETURN_CHARGE = 4;        // 掛売返品
    const SALEDIV_ETC = 5;                  // その他
    
    // 受注種別
    const ORDERCLASS_ORDER = 1;                      // 1:受注
    const ORDERCLASS_ESTIMATE = 2;                   // 2:見積
    
    // 親受注
    const ORDER_PARENT = 1;                       // 1=親受注
    
    // 納品書印刷フラグ
    const ORDER_INVOICE_PRINT_NONE = 0;                // 0=未印刷
    const ORDER_INVOICE_PRINTED = 1;                   // 1=印刷済
    
    // 配送伝票フラグ
    const ORDER_DELIVERY_SLIP_NECESSARY = 1;           // 1=必要
    const ORDER_DELIVERY_SLIP_UNNECESSARY = 2;         // 2=不要
    
    // 配送伝票印刷フラグ
    const ORDER_DELIVERY_SLIP_PRINT_NONE = 0;          // 0=未印刷
    const ORDER_DELIVERY_SLIP_PRINTED = 1;             // 1=印刷済
    const ORDER_DELIVERY_SLIP_PRINT_UNNECESSARY = 2;   // 2=印刷不要
    
    // 事業者印刷フラグ
    const ORDER_SLIP_BUSINESS_PRINT_NONE = 0;          // 0=しない
    const ORDER_SLIP_BUSINESS_PRINT = 1;               // 1=する
    
    // ダウンロード用セレクトボックス
    const ORDER_CSV_CHECK = 1;         // 1=チェックされた受注CSV
    const ORDER_CSV_ALL = 2;           // 2=全ての受注CSV
    const ORDER_XLS_CHECK = 3;         // 3=チェックされた受注PDF
    const ORDER_XLS_ALL = 4;           // 4=全ての受注PDF
    
    /**
     * コンストラクタ
     *
     * @return  void
     */
    public function __construct() {
        // 更新系
        $this->objMasterDb = Zend_Registry::get("MASTER_DATABASE");
        // 参照系
        $this->objSlaveDb = Zend_Registry::get("SLAVE_DATABASE");
        // Session 定義（ログイン情報）
        $this->objAdminSess = new Zend_Session_Namespace("Admin");
        $this->objFrontSess = new Zend_Session_Namespace("Front");
        // 総件数
        $this->totalCount = 0;

        // Library & Models
        $this->objCommon = new Common();
        $this->mdlCategory = new Application_Model_Category();
    }

    /**
     * Zend_Db_Table::beginTransaction()
     *
     * @return  self
     */
    public function begin() {
        $this->objMasterDb->beginTransaction();
        return $this;
    }

    /**
     * Zend_Db_Table::commit()
     *
     * @return  self
     */
    public function commit() {
        $this->objMasterDb->commit();
        return $this;
    }

    /**
     * Zend_Db_Table::rollBack()
     *
     * @return  self
     */
    public function rollBack() {
        $this->objMasterDb->rollBack();
        return $this;
    }

    /**
     * 第一引数の配列から主キーを抽出し、
     * 主キーに紐づくデータ第一引数の値で更新する。
     * 
     * @param   array   $arrData    更新対象のカラム名と値の連想配列
     * @param   bool    $bIsAdmin   管理画面からの登録
     * @return  self
     */
    public function save($arrData, $bIsAdmin = true) {
        
        try {
            if (count($arrData) == 0) {
                throw new Zend_Exception('$arrDataが空です。');
            }

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams["d_order_UpdatedTime"] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams["d_order_UpdatedByID"] = $this->objAdminSess->MemberID;
            } else {
                $arrParams["d_order_UpdatedByID"] = 0;
            }
            $stWhere = $this->objMasterDb->quoteInto("d_order_OrderID = ? ", $arrData["d_order_OrderID"]);
            $this->objMasterDb->update($stTable, $arrParams, $stWhere);

            return $this;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 第一引数の配列から主キーを抽出し、
     * 主キーに紐づくデータ第一引数の値で更新する。
     * 
     * @param   string  $stCurrentCustomerID    更新対象の顧客ID
     * @param   array   $arrData                更新対象のカラム名と値の連想配列
     * @param   bool    $bIsAdmin               管理画面からの登録
     * @return  self
     */
    public function saveByCustomerID($stCurrentCustomerID, $arrData, $bIsAdmin = true) {
        
        try {
            if (count($arrData) == 0) {
                throw new Zend_Exception('$arrDataが空です。');
            }

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams["d_order_UpdatedTime"] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams["d_order_UpdatedByID"] = $this->objAdminSess->MemberID;
            } else {
                $arrParams["d_order_UpdatedByID"] = 0;
            }
            $stWhere = $this->objMasterDb->quoteInto("d_order_CustomerID = ? ", $stCurrentCustomerID);
            $this->objMasterDb->update($stTable, $arrParams, $stWhere);

            return $this;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }    

    /**
     * 第一引数に紐づくデータを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrWhere   検索条件のキーと値の連想配列
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @return  array   $arrResult  検索結果
     */
    public function find($arrWhere, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_DelFlg = ? ", 0);
            if ($arrWhere) {
                foreach ($arrWhere as $key => $value) {
                    $objSelect->where($key . " = ? ", $value);
                }
            }

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 第一引数に紐づくデータを更新系から取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrWhere   検索条件のキーと値の連想配列
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @return  array   $arrResult  検索結果
     */
    public function findFromMaster($arrWhere, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objMasterDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_DelFlg = ? ", 0);
            foreach ($arrWhere as $key => $value) {
                $objSelect->where($key . " = ? ", $value);
            }

            // select文を実行する
            $objSql = $this->objMasterDb->query($objSelect);
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数に検索条件を指定する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 第三引数が指定されている場合、その配列を並び順とする。
     * 
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrOrder     ソートカラム名を格納した配列
     * @return  array   $arrResult    検索結果
     */
    public function findAll($arrWhere, $arrColumn = "", $arrOrder = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_DelFlg = ? ", 0);
            
            if ($arrWhere) {
                foreach ($arrWhere as $key => $value) {
                    $objSelect->where($key . " = ? ", $value);
                }
            }
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_order_OrderID ASC");
            }
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 第一引数に検索条件を指定する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 第三引数が指定されている場合、その配列を並び順とする。
     * 紐づくデータを更新系から取得する
     * 
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrOrder     ソートカラム名を格納した配列
     * @return  array   $arrResult    検索結果
     */
    public function findAllFromMaster($arrWhere, $arrColumn = "", $arrOrder = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objMasterDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_DelFlg = ? ", 0);
            
            foreach ($arrWhere as $key => $value) {
                $objSelect->where($key . " = ? ", $value);
            }
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_order_OrderID ASC");
            }
            
            // select文を実行する
            $objSql = $this->objMasterDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 削除フラグが0の行を全て取得する。
     * 第一引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @param   array   $arrOrder   ソートカラム名配列
     * @return  array   $arrResult  検索結果
     */
    public function fetchAll($arrColumn = "", $arrOrder = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_DelFlg = ? ", 0);
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_order_OrderID ASC");
            }

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数の配列から主キーを抽出し、
     * データ第一引数の値で登録する。
     * 
     * @param   array   $arrData    登録対象のカラム名と値の連想配列
     * @param   bool    $bIsAdmin   管理画面からの登録 
     * @return  integer             最後に自動生成されたID
     */
    public function insert($arrData, $bIsAdmin = true) {
        
        try {
            if (count($arrData) == 0) {
                throw new Zend_Exception('$arrDataが空です。');
            }

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams["d_order_CreatedTime"] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams["d_order_CreatedByID"] = $this->objAdminSess->MemberID;
                $arrParams["d_order_UpdatedByID"] = $this->objAdminSess->MemberID;
            } else {
                $arrParams["d_order_CreatedByID"] = 0;
                $arrParams["d_order_UpdatedByID"] = 0;
            }    
            $arrParams["d_order_DelFlg"] = "0";
            $this->objMasterDb->insert($stTable, $arrParams);

            return $this->objMasterDb->lastInsertId();
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数に紐づくデータに対して削除フラグを立てる。
     * 
     * @param   integer $iID ID(PK)
     * @param   bool    $bIsAdmin   管理画面からの登録
     * @return  self
     */
    public function delete($iID, $bIsAdmin = true) {

        try {
            if ($iID == "" || $iID < 0) {
                throw new Zend_Exception('$IDを指定してください。');
            }

            $stTable = $this->getTableName();
            // 更新内容
            $arrParams["d_order_DelFlg"] = "1";
            $arrParams["d_order_UpdatedTime"] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams["d_order_UpdatedByID"] = $this->objAdminSess->MemberID;
            } else {
                $arrParams["d_order_UpdatedByID"] = 0;
            }
            // 更新条件
            $arrWhere["d_order_OrderID = ?"] = $iID;
            $arrWhere["d_order_DelFlg = ?"]  = "0";
            $this->objMasterDb->update($stTable, $arrParams, $arrWhere);
           
            return $this;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * テーブル内のカラムを連想配列（値はnull）で取得する。
     * 第一引数が指定されている場合、その配列を取得対象カラムから除外する。
     * 
     * @param   array   $arrUnsetColumns    取得対象除外のカラム名を格納した配列
     * @return  array   $arrResult          取得対象のカラム名を格納した配列
     */
    public function getColumns($arrUnsetColumns = null) {
        
        try {
            // メンバ変数初期化
            $arrResult = array();
            $arrTemp = array();

            // select文を実行する
            $objDescribe = "DESCRIBE " . $this->getTableName();
            $objSql = $this->objSlaveDb->query($objDescribe);
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            // 取得した要素から「Field」のみを取得
            foreach ($arrResult as $key => $val) {
                foreach ($val as $k => $v) {
                    if ($k == "Field")
                        $arrTemp[ $v ] = null;
                }
            }
            // 整形した配列から除外カラムを取り除く
            if (is_array($arrUnsetColumns)) {
                foreach ($arrTemp as $key => $val) {
                    foreach ($arrUnsetColumns as $k => $v) {
                        if ($key == $v)
                            unset($arrTemp[ $key ]);
                    }
                }
            }
            $arrResult = $arrTemp;
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * setSearchCondition()でセットされた検索条件を元に、
     * 検索処理を実行して結果を返す。
     * 検索結果はsetSearchResult()によりメンバ変数に格納される。
     * 
     * @param   boolean $bLimit     1ページあたりの表示件数の適用有無 (true:適用する, false:適用しない)
     * @return  array               検索結果
     */
    public function search($bLimit = true) {
        
        try {
            if (is_object($this->getSearchCondition())) {
                $objSelect = $this->getSearchCondition();
            } else {
                throw new Zend_Exception("検索条件を指定してください。");
            }

            if ($bLimit) {
                $objSelect = &$objSelect->limitPage($this->getPageNumber(), $this->getPageLimit());
            }
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            $arrResult = $objSql->fetchAll();

            // 検索結果をメンバ変数に保持する
            $this->setSearchResult($arrResult);
            
            // 合計件数取得用にSQL文を再構築する
            $arrAlias = $objSelect->getPart(Zend_Db_Select::FROM);
            foreach ($arrAlias as $key =>$value) {
                $stAlias = $key;
                break;
            }
            $objSelect->columns('COUNT(DISTINCT ' .  "`$stAlias`" . '.`d_order_OrderID`) AS cnt');
            $objSelect->reset(Zend_Db_Select::ORDER);
            $objSelect->reset(Zend_Db_Select::GROUP);
            $objSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
            $objSelect->reset(Zend_Db_Select::LIMIT_COUNT);
            $objSelect->reset(Zend_Db_Select::HAVING);
            $objSqlCount = $this->objSlaveDb->query($objSelect);
            $arrCountTemp = $objSqlCount->fetch();
            $this->totalCount = intval($arrCountTemp["cnt"]);
            
            return $this->getSearchResult();
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * search()でセットされた検索結果に対しcount()を実行し、
     * 検索結果の件数を返す。
     * 事前にsearch()を実行していない場合、例外を返す。
     * 
     * @return  integer 検索結果件数
     */
    public function searchCount() {
        
        try {
            if (is_array($this->getSearchResult())) {
                $iCount = count($this->getSearchResult());
                return $iCount;
            } else {
                throw new Zend_Exception("検索処理を先に実行してください。");
            }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrGroup           GROUP BY 配列
     * @return  self
     */
    public function setSearchCondition($arrSearchCondition, $arrColumn = array("*"), $arrGroup = array()) {

        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        // 注文管理テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("mng" => "d_order_mng"),
                "o.d_order_OrderMngID = mng.d_order_mng_OrderMngID", array());
        // 注文管理テーブルと支払方法テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pay" => "d_payment"),
                "mng.d_order_mng_PaymentID = pay.d_payment_PaymentID", array());
        // 注文管理テーブルと購入経路マスタとの結合
        $objSelect = &$objSelect->joinLeft(array("br" => "m_buy_route"),
                "mng.d_order_mng_BuyRouteID = br.m_buy_route_BuyRoureID", array());
        // 受注明細テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("odp" => "d_order_detail"),
                "o.d_order_OrderID = odp.d_order_detail_OrderID and odp.d_order_detail_DelFlg = '0'", array());
        // 受注明細テーブルと商品テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("p" => "d_product"),
                "odp.d_order_detail_ProductID = p.d_product_ProductID", array());
        // 受注明細テーブルと商品規格テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("pcl" => "d_product_class"),
                "odp.d_order_detail_ProductClassID = pcl.d_product_class_ProductClassID", array());
        // 商品規格テーブルと商品規格マスタテーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pcm1" => "d_product_class_master"),
                "pcl.d_product_class_ProductClassMasterID1 = pcm1.d_product_class_master_ProductClassMasterID", array());
        $objSelect = &$objSelect->joinLeft(array("pcm2" => "d_product_class_master"),
                "pcl.d_product_class_ProductClassMasterID2 = pcm2.d_product_class_master_ProductClassMasterID", array());
        // 顧客テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("c" => "d_customer"),
                "o.d_order_CustomerID = c.d_customer_CustomerID", array());
        // 送り先テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("od" => "d_order_delivery"),
                "o.d_order_OrderDeliveryID = od.d_order_delivery_OrderDeliveryID", array());
        // 時間指定テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pt" => "d_postage_time"),
                "o.d_order_PostageTimeID = pt.d_postage_time_PostageTimeID", array());
        // 都道府県マスタとの結合 (送り先)
        $objSelect = &$objSelect->joinLeft(array("dmp" => "m_pref"),
                "o.d_order_OrderDeliveryPrefCode = dmp.m_pref_PrefCode", array());
        // 都道府県マスタとの結合 (送り主)
        $objSelect = &$objSelect->joinLeft(array("smp" => "m_pref"),
                "o.d_order_SenderPrefCode = smp.m_pref_PrefCode", array());
        
        // Where句セット
        // 顧客ID
        $stFormKey = "d_order_CustomerID";
        $stDBKey = "d_order_CustomerID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        // 請求先ID
        $stFormKey = "d_order_CustomerBillID";
        $stDBKey = "d_order_CustomerBillID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        // 送り先ID
        $stFormKey = "d_order_OrderDeliveryID";
        $stDBKey = "d_order_OrderDeliveryID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 注文管理番号(ID)
        $stFormKey = "d_order_mng_OrderMngID";
        $stDBKey = "d_order_mng_OrderMngID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("mng.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("mng.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("mng.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("mng.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        // 注文番号(ID)
        $stFormKey = "d_order_OrderID";
        $stDBKey = "d_order_OrderID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 請求管理番号(ID)
        $stFormKey = "d_claim_mng_ClaimMngID";
        $stDBKey = "d_claim_mng_ClaimMngID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("clm.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("clm.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("clm.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("clm.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        // 請求番号(ID)
        $stFormKey = "d_claim_ClaimID";
        $stDBKey = "d_claim_ClaimID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("cl.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("cl.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("cl.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("cl.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        // 入金番号(ID)
        $stFormKey = "d_deposit_history_DepositHistoryID";
        $stDBKey = "d_deposit_history_DepositHistoryID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("dh.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("dh.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("dh.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("dh.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 受注種別
        $stFormKey = "d_order_mng_OrderClass";
        $stDBKey = "d_order_mng_OrderClass";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("mng.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        
        // 購入区分
        $stFormKey = "d_order_mng_BuyDiv";
        $stDBKey = "d_order_mng_BuyDiv";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(mng.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("mng.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("mng.$stDBKey = ? )", $value);
                }
            }
        }
        
        // 対応状況
        $stFormKey = "d_order_Status";
        $stDBKey = "d_order_Status";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 配送温度帯
        $stFormKey = "d_order_ShippingTemp";
        $stDBKey = "d_order_ShippingTemp";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(o.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("o.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("o.$stDBKey = ? )", $value);
                }
            }
        }
        
        // 受注日
        $stDBKey = "d_order_mng_Date";
        if ($arrSearchCondition["order_from_Year"] != "" && $arrSearchCondition["order_from_Month"] != "" && 
            $arrSearchCondition["order_from_Day"] != "") {
            $objSelect->where("mng.$stDBKey >= ?", $arrSearchCondition["order_from_Year"] . "-" . 
                $arrSearchCondition["order_from_Month"] . "-" . $arrSearchCondition["order_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["order_to_Year"] != "" && $arrSearchCondition["order_to_Month"] != "" && 
            $arrSearchCondition["order_to_Day"] != "") {
            $objSelect->where("mng.$stDBKey <= ?", $arrSearchCondition["order_to_Year"] . "-" . 
                $arrSearchCondition["order_to_Month"] . "-" . $arrSearchCondition["order_to_Day"] . " 23:59:59");
        }

        // 出荷予定日
        $stDBKey = "d_order_ExpectedShippingDate";
        if ($arrSearchCondition["esd_from_Year"] != "" && $arrSearchCondition["esd_from_Month"] != "" && 
            $arrSearchCondition["esd_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["esd_from_Year"] . "-" . 
                $arrSearchCondition["esd_from_Month"] . "-" . $arrSearchCondition["esd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["esd_to_Year"] != "" && $arrSearchCondition["esd_to_Month"] != "" && 
            $arrSearchCondition["esd_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["esd_to_Year"] . "-" . 
                $arrSearchCondition["esd_to_Month"] . "-" . $arrSearchCondition["esd_to_Day"] . " 23:59:59");
        }

        // 出荷日
        $stDBKey = "d_order_ShippingDate";
        if ($arrSearchCondition["sd_from_Year"] != "" && $arrSearchCondition["sd_from_Month"] != "" && 
            $arrSearchCondition["sd_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["sd_from_Year"] . "-" . 
                $arrSearchCondition["sd_from_Month"] . "-" . $arrSearchCondition["sd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["sd_to_Year"] != "" && $arrSearchCondition["sd_to_Month"] != "" && 
            $arrSearchCondition["sd_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["sd_to_Year"] . "-" . 
                $arrSearchCondition["sd_to_Month"] . "-" . $arrSearchCondition["sd_to_Day"] . " 23:59:59");
        }
        
        // 入金日
        $stDBKey = "d_deposit_history_DepositDate";
        if ($arrSearchCondition["dhd_from_Year"] != "" && $arrSearchCondition["dhd_from_Month"] != "" && 
            $arrSearchCondition["dhd_from_Day"] != "") {
            $objSelect->where("dh.$stDBKey >= ?", $arrSearchCondition["dhd_from_Year"] . "-" . 
                $arrSearchCondition["dhd_from_Month"] . "-" . $arrSearchCondition["dhd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["dhd_to_Year"] != "" && $arrSearchCondition["dhd_to_Month"] != "" && 
            $arrSearchCondition["dhd_to_Day"] != "") {
            $objSelect->where("dh.$stDBKey <= ?", $arrSearchCondition["dhd_to_Year"] . "-" . 
                $arrSearchCondition["dhd_to_Month"] . "-" . $arrSearchCondition["dhd_to_Day"] . " 23:59:59");
        }
        
        // 請求日
        $stDBKey = "d_order_mng_BillingDate";
        if ($arrSearchCondition["bd_from_Year"] != "" && $arrSearchCondition["bd_from_Month"] != "" && 
            $arrSearchCondition["bd_from_Day"] != "") {
            $objSelect->where("mng.$stDBKey >= ?", $arrSearchCondition["bd_from_Year"] . "-" . 
                $arrSearchCondition["bd_from_Month"] . "-" . $arrSearchCondition["bd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["bd_to_Year"] != "" && $arrSearchCondition["bd_to_Month"] != "" && 
            $arrSearchCondition["bd_to_Day"] != "") {
            $objSelect->where("mng.$stDBKey <= ?", $arrSearchCondition["bd_to_Year"] . "-" . 
                $arrSearchCondition["bd_to_Month"] . "-" . $arrSearchCondition["bd_to_Day"] . " 23:59:59");
        }
        
        // 購入金額
        $stFormKey = "d_order_TotalPriceFrom";
        $stDBKey = "d_order_TotalPrice";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey >= ? ", $arrSearchCondition[$stFormKey]);
        }
        $stFormKey = "d_order_TotalPriceTo";
        $stDBKey = "d_order_TotalPrice";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey <= ? ", $arrSearchCondition[$stFormKey]);
        }

        // 支払方法
        $stFormKey = "d_order_mng_PaymentID";
        $stDBKey = "d_order_mng_PaymentID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(mng.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("mng.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("mng.$stDBKey = ? )", $value);
                }
            }
        }

        // 顧客名
        $stFormKey = "d_order_CustomerName";
        $stDBKey = "d_order_CustomerName";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 顧客名カナ
        $stFormKey = "d_order_CustomerNameKana";
        $stDBKey = "d_order_CustomerNameKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 受注顧客・顧客分類1
        $stFormKey = "d_customer_TypeMaster1ID";
        $stDBKey = "d_customer_TypeMaster1ID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("c.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        // 受注顧客・顧客分類2
        $stFormKey = "d_customer_TypeMaster2ID";
        $stDBKey = "d_customer_TypeMaster2ID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("c.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        // TEL
        $stFormKey = "d_order_CustomerTelNo";
        $stDBKey = "d_order_CustomerTelNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 受注送り先名
        $stFormKey = "d_order_OrderDeliveryName";
        $stDBKey = "d_order_OrderDeliveryName";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 受注送り先名カナ
        $stFormKey = "d_order_OrderDeliveryNameKana";
        $stDBKey = "d_order_OrderDeliveryNameKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 受注送り先・顧客分類1
        $stFormKey = "d_order_delivery_TypeMaster1ID";
        $stDBKey = "d_order_delivery_TypeMaster1ID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("od.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        // 受注送り先・顧客分類2
        $stFormKey = "d_order_delivery_TypeMaster2ID";
        $stDBKey = "d_order_delivery_TypeMaster2ID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("od.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        // 受注送り先TEL
        $stFormKey = "d_order_OrderDeliveryTelNo";
        $stDBKey = "d_order_OrderDeliveryTelNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 商品名
        $stFormKey = "d_product_Name";
        $stDBKey = "d_product_Name";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("p.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 商品規格
        $stFormKey = "d_product_class_master_Name";
        if ($arrSearchCondition[$stFormKey] != "") {
            $stDBKey = "pcm1.d_product_class_master_Name";
            $objSelect->where("($stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
            $stDBKey = "pcm2.d_product_class_master_Name";
            $objSelect->orwhere("$stDBKey LIKE ? )", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 商品ID
        $stFormKey = "d_product_ProductID";
        $stDBKey = "d_order_detail_ProductID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("odp.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        // 商品区分1
        $stFormKey = "d_product_class_ProductDiv1MasterID";
        $stDBKey = "d_product_class_ProductDiv1MasterID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("pcl.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        // 商品区分2
        $stFormKey = "d_product_class_ProductDiv2MasterID";
        $stDBKey = "d_product_class_ProductDiv2MasterID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("pcl.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("mng.d_order_mng_DelFlg = ? ", 0);
        $objSelect->where("o.d_order_DelFlg = ? ", 0);

        // OrderIDでGROUP BY
        if (empty($arrGroup)) {
            $objSelect->group("o.d_order_OrderID");
        } else {
            foreach ($arrGroup as $key => $value) {
                $objSelect->group($value);
            }
        }

        // 表示順
        $objSelect->order("o.d_order_OrderID DESC");

        $this->_objSelect = $objSelect;
        return $this;
    }
    
    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 出荷処理用受注検索に使用するために作成
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrGroup           GROUP BY 配列
     * @param   array   $arrOrder           表示順
     * @return  self
     */
    public function setSearchConditionForShipSearch($arrSearchCondition, $arrColumn = array("*"), $arrGroup = array(), $arrOrder = "") {

        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        // 注文管理テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("mng" => "d_order_mng"),
                "o.d_order_OrderMngID = mng.d_order_mng_OrderMngID", array());
        // 顧客テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("c" => "d_customer"),
                "o.d_order_CustomerID = c.d_customer_CustomerID", array());
        // 顧客テーブルと顧客カテゴリセットテーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("cc" => "d_customer_category_set"),
                "c.d_customer_CustomerID = cc.d_customer_category_set_CustomerID", array());
        // 顧客カテゴリーセットテーブルと顧客カテゴリテーブルの結合
        $objSelect = &$objSelect->joinLeft(array("dcc" => "d_customer_category"),
            "cc.d_customer_category_set_CustomerCategoryID = dcc.d_customer_category_CustomerCategoryID", array(""));
        // 送り先テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("od" => "d_order_delivery"),
                "o.d_order_OrderDeliveryID = od.d_order_delivery_OrderDeliveryID", array());
        
        // 配送伝票番号テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("os" => "d_order_slip"),
                "o.d_order_OrderID = os.d_order_slip_OrderID", array());
        
        if ($arrSearchCondition["d_order_CustomerTag"] != "") {
            // 顧客テーブルとタグテーブルの結合
            $objSelect = &$objSelect->joinLeft(array("ct" => "d_tag"),
                    "c.d_customer_CustomerID = ct.d_tag_TargetID", array());

            // タグテーブルとタグ管理テーブルの結合
            $objSelect = &$objSelect->joinLeft(array("ctm" => "d_tag_mng"),
                    "ct.d_tag_TagMngID = ctm.d_tag_mng_TagMngID", array());
        }
        if ($arrSearchCondition["d_order_delivery_Tag"] != "") {
            // 送り先テーブルとタグテーブルの結合
            $objSelect = &$objSelect->joinLeft(array("dt" => "d_tag"),
                    "od.d_order_delivery_OrderDeliveryID = dt.d_tag_TargetID", array());

            // タグテーブルとタグ管理テーブルの結合
            $objSelect = &$objSelect->joinLeft(array("dtm" => "d_tag_mng"),
                    "dt.d_tag_TagMngID = dtm.d_tag_mng_TagMngID", array());
        }
        
        // Where句セット
        // 注文管理ID
        $stFormKey = "d_order_OrderMngID";
        $stDBKey = "d_order_OrderMngID";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        
        // 受注ID
        $stFormKey = "d_order_OrderID";
        $stDBKey = "d_order_OrderID";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        
        // 受注区分(購入区分)
        $stFormKey = "d_order_mng_BuyDiv";
        $stDBKey = "d_order_mng_BuyDiv";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(mng.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("mng.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("mng.$stDBKey = ? )", $value);
                }
            }
        }
        
        // 納品書出力
        $stFormKey = "d_order_InvoicePrintFlg";
        $stDBKey = "d_order_InvoicePrintFlg";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(o.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("o.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("o.$stDBKey = ? )", $value);
                }
            }
        }
        
        // 配送伝票必要不要
        $stFormKey = "d_order_DeliverSlipFlg";
        $stDBKey = "d_order_DeliverSlipFlg";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(o.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("o.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("o.$stDBKey = ? )", $value);
                }
            }
        }
        
        // 配送伝票出力
        $stFormKey = "d_order_SlipPrintFlg";
        $stDBKey = "d_order_SlipPrintFlg";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(o.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("o.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("o.$stDBKey = ? )", $value);
                }
            }
        }
        
        // 対応状況
        $stFormKey = "d_order_Status";
        $stDBKey = "d_order_Status";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 配送温度帯
        $stFormKey = "d_order_ShippingTemp";
        $stDBKey = "d_order_ShippingTemp";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("(o.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("o.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("o.$stDBKey = ? )", $value);
                }
            }
        }
        
        // 配送業者
        $stFormKey = "d_order_mng_PostageID";
        $stDBKey = "d_order_mng_PostageID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("mng.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        
        // 配送伝票番号
        $stFormKey = "d_order_slip_OrderSlipNo";
        $stDBKey = "os.d_order_slip_OrderSlipNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrNo = array();
                $arrNo = explode(",", $arrSearchCondition[$stFormKey]);
                $stNo = "(";
                foreach ($arrNo as $key => $value) {
                     $stNo .= "'" . $value . "',";
                }
                $stNo = rtrim($stNo, ",");
                $stNo .= ")";
                $objSelect->where("$stDBKey IN " . $stNo);
            } else if (strpos($arrSearchCondition[$stFormKey], "-")) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                $objSelect->where("$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 受注日
        $stDBKey = "d_order_mng_Date";
        if ($arrSearchCondition["order_from_Year"] != "" && $arrSearchCondition["order_from_Month"] != "" && 
            $arrSearchCondition["order_from_Day"] != "") {
            $objSelect->where("mng.$stDBKey >= ?", $arrSearchCondition["order_from_Year"] . "-" . 
                $arrSearchCondition["order_from_Month"] . "-" . $arrSearchCondition["order_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["order_to_Year"] != "" && $arrSearchCondition["order_to_Month"] != "" && 
            $arrSearchCondition["order_to_Day"] != "") {
            $objSelect->where("mng.$stDBKey <= ?", $arrSearchCondition["order_to_Year"] . "-" . 
                $arrSearchCondition["order_to_Month"] . "-" . $arrSearchCondition["order_to_Day"] . " 23:59:59");
        }

        // 出荷予定日
        $stDBKey = "d_order_ExpectedShippingDate";
        if ($arrSearchCondition["shipplan_from_Year"] != "" && $arrSearchCondition["shipplan_from_Month"] != "" && 
            $arrSearchCondition["shipplan_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["shipplan_from_Year"] . "-" . 
                $arrSearchCondition["shipplan_from_Month"] . "-" . $arrSearchCondition["shipplan_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["shipplan_to_Year"] != "" && $arrSearchCondition["shipplan_to_Month"] != "" && 
            $arrSearchCondition["shipplan_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["shipplan_to_Year"] . "-" . 
                $arrSearchCondition["shipplan_to_Month"] . "-" . $arrSearchCondition["shipplan_to_Day"] . " 23:59:59");
        }

        // 出荷日
        $stDBKey = "d_order_ShippingDate";
        if ($arrSearchCondition["ship_from_Year"] != "" && $arrSearchCondition["ship_from_Month"] != "" && 
            $arrSearchCondition["ship_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["ship_from_Year"] . "-" . 
                $arrSearchCondition["ship_from_Month"] . "-" . $arrSearchCondition["ship_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["ship_to_Year"] != "" && $arrSearchCondition["ship_to_Month"] != "" && 
            $arrSearchCondition["ship_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["ship_to_Year"] . "-" . 
                $arrSearchCondition["ship_to_Month"] . "-" . $arrSearchCondition["ship_to_Day"] . " 23:59:59");
        }
        
        // お届け希望日
        $stDBKey = "d_order_HopeDeliverDate";
        if ($arrSearchCondition["delivery_from_Year"] != "" && $arrSearchCondition["delivery_from_Month"] != "" && 
            $arrSearchCondition["delivery_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["delivery_from_Year"] . "-" . 
                $arrSearchCondition["delivery_from_Month"] . "-" . $arrSearchCondition["delivery_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["delivery_to_Year"] != "" && $arrSearchCondition["delivery_to_Month"] != "" && 
            $arrSearchCondition["delivery_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["delivery_to_Year"] . "-" . 
                $arrSearchCondition["delivery_to_Month"] . "-" . $arrSearchCondition["delivery_to_Day"] . " 23:59:59");
        }
        
        // 売上日(注文管理テーブル)
        $stDBKey = "d_order_mng_SaleDate";
        if ($arrSearchCondition["sale_from_Year"] != "" && $arrSearchCondition["sale_from_Month"] != "" && 
            $arrSearchCondition["sale_from_Day"] != "") {
            $objSelect->where("mng.$stDBKey >= ?", $arrSearchCondition["sale_from_Year"] . "-" . 
                $arrSearchCondition["sale_from_Month"] . "-" . $arrSearchCondition["sale_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["sale_to_Year"] != "" && $arrSearchCondition["sale_to_Month"] != "" && 
            $arrSearchCondition["sale_to_Day"] != "") {
            $objSelect->where("mng.$stDBKey <= ?", $arrSearchCondition["sale_to_Year"] . "-" . 
                $arrSearchCondition["sale_to_Month"] . "-" . $arrSearchCondition["sale_to_Day"] . " 23:59:59");
        }
        
        // 受注顧客コード
        $stFormKey = "d_order_CustomerCode";
        $stDBKey = "d_order_CustomerCode";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        // 受注顧客ID
        $stFormKey = "d_order_CustomerID";
        $stDBKey = "d_order_CustomerID";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        // 受注顧客名
        $stFormKey = "d_order_CustomerName";
        $stDBKey = "d_order_CustomerName";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 受注顧客名カナ
        $stFormKey = "d_order_CustomerNameKana";
        $stDBKey = "d_order_CustomerNameKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 売上判定日
        $stFormKey = "d_customer_SaleDiv";
        $stDBKey = "d_customer_SaleDiv";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("c.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            $objSelect->where("c.d_customer_DelFlg = ? ", 0);
        }
        
        // 受注送り先コード
        $stFormKey = "d_order_OrderDeliveryCode";
        $stDBKey = "d_order_OrderDeliveryCode";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        // 受注送り先ID
        $stFormKey = "d_order_OrderDeliveryID";
        $stDBKey = "d_order_OrderDeliveryID";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        // 受注送り先名
        $stFormKey = "d_order_OrderDeliveryName";
        $stDBKey = "d_order_OrderDeliveryName";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 受注送り先名カナ
        $stFormKey = "d_order_OrderDeliveryNameKana";
        $stDBKey = "d_order_OrderDeliveryNameKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        // 受注送り先タグ
        $stFormKey = "d_order_delivery_Tag";
        if ($arrSearchCondition[$stFormKey] != "") {
            $stTag = $arrSearchCondition[$stFormKey];
            $arrTag = explode(",", $stTag);
            
            $objSelect->where("(dt.d_tag_TagDiv = ? ", Application_Model_Tag::TAG_DIV_DELIVERY);
            $objSelect->where("dt.d_tag_DelFlg = ? ", 0);
            $iCount = 0;
            $iLength = count($arrTag) - 1;
            foreach ($arrTag as $key => $value) {
                if ($iCount >= $iLength) {
                    // 最終ループのみ右括弧をつける
                    $objSelect->where("dtm.d_tag_mng_TagName = ?)", $value);
                } else {
                    $objSelect->where("dtm.d_tag_mng_TagName = ? ", $value);
                }
                $iCount++;
            }
        }

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("mng.d_order_mng_DelFlg = ? ", 0);
        $objSelect->where("o.d_order_DelFlg = ? ", 0);

        // OrderIDでGROUP BY
        if (empty($arrGroup)) {
            $objSelect->group("o.d_order_OrderID");
        } else {
            foreach ($arrGroup as $key => $value) {
                $objSelect->group($value);
            }
        }

        // 表示順
        if ($arrOrder) {
            foreach ($arrOrder as $key => $value) {
                $objSelect->order($key . " = ? ", $value);
            }
        } else {
            $objSelect->order("o.d_order_OrderID DESC");
        }

        $this->_objSelect = $objSelect;
        return $this;
    }
    
    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 出荷処理用受注検索に使用するために作成
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrGroup           GROUP BY 配列
     * @param   array   $arrOrder           表示順
     * @return  self
     */
    public function setSearchConditionForOrderSearch($arrSearchCondition, $arrColumn = array("*"), $arrGroup = array(), $arrOrder = "") {

        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        
        // Where句セット
        // 受注ID
        $stFormKey = "d_order_OrderID";
        $stDBKey = "d_order_OrderID";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        
        // 対応状況
        $stFormKey = "d_order_Status";
        $stDBKey = "d_order_Status";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 受注日
        $stDBKey = "d_order_CreatedTime";
        if ($arrSearchCondition["order_from_Year"] != "" && $arrSearchCondition["order_from_Month"] != "" && 
            $arrSearchCondition["order_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["order_from_Year"] . "-" . 
                $arrSearchCondition["order_from_Month"] . "-" . $arrSearchCondition["order_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["order_to_Year"] != "" && $arrSearchCondition["order_to_Month"] != "" && 
            $arrSearchCondition["order_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["order_to_Year"] . "-" . 
                $arrSearchCondition["order_to_Month"] . "-" . $arrSearchCondition["order_to_Day"] . " 23:59:59");
        }
        
        // 更新日
        $stDBKey = "d_order_UpdatedTime";
        if ($arrSearchCondition["update_from_Year"] != "" && $arrSearchCondition["update_from_Month"] != "" && 
            $arrSearchCondition["update_from_Day"] != "") {
            $objSelect->where("$stDBKey >= ?", $arrSearchCondition["update_from_Year"] . "-" . 
                $arrSearchCondition["update_from_Month"] . "-" . $arrSearchCondition["update_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["update_to_Year"] != "" && $arrSearchCondition["update_to_Month"] != "" && 
            $arrSearchCondition["update_to_Day"] != "") {
            $objSelect->where("$stDBKey <= ?", $arrSearchCondition["update_to_Year"] . "-" . 
                $arrSearchCondition["update_to_Month"] . "-" . $arrSearchCondition["update_to_Day"] . " 23:59:59");
        }

        // 受注顧客ID
        $stFormKey = "d_order_CustomerID";
        $stDBKey = "d_order_CustomerID";
        $stDBAlias = "o";
        if ($arrSearchCondition[$stFormKey] != "") {
            // OR検索及び範囲検索への対応
            $this->objCommon->addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, $objSelect);
        }
        
        // 受注顧客名
        $stFormKey = "d_order_CustomerName";
        $stDBKey = "d_order_CustomerName";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 受注顧客名カナ
        $stFormKey = "d_order_CustomerNameKana";
        $stDBKey = "d_order_CustomerNameKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 受注顧客会社名
        $stFormKey = "d_order_CompanyName";
        $stDBKey = "d_order_CompanyName";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 受注顧客電話番号
        $stFormKey = "d_order_CustomerTelNo";
        $stDBKey = "d_order_CustomerTelNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 受注顧客メールアドレス
        $stFormKey = "d_order_CustomerEmailAddress";
        $stDBKey = "d_order_CustomerEmailAddress";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_DelFlg = ? ", 0);

        // OrderIDでGROUP BY
        if (empty($arrGroup)) {
            $objSelect->group("o.d_order_OrderID");
        } else {
            foreach ($arrGroup as $key => $value) {
                $objSelect->group($value);
            }
        }

        // ソート順
        if (empty($arrOrder)) {
            $objSelect->order("o.d_order_OrderID DESC");
        } else {
            if (!is_array($arrOrder)) {
                $arrOrder = array($arrOrder);
            }
            foreach ($arrOrder as $value) {
                $objSelect->order($value);
            }
        }
        $this->_objSelect = $objSelect;
        return $this;
    }
    
    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrGroup           GROUP BY 配列
     * @return  self
     */
    public function setSearchConditionForCustomerOrderHistory($arrSearchCondition, $arrColumn = array("*"), $arrGroup = array()) {

       $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        
        // 注文管理テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("mng" => "d_order_mng"),
                "o.d_order_OrderMngID = mng.d_order_mng_OrderMngID", array());
        
        // 配送先テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("od" => "d_order_delivery"),
                "o.d_order_OrderDeliveryID = od.d_order_delivery_OrderDeliveryID", array());
        
        // 支払方法テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pay" => "d_payment"),
                "mng.d_order_mng_PaymentID = pay.d_payment_PaymentID", array("d_payment_Name"));

        // 顧客テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("c" => "d_customer"),
                "o.d_order_CustomerID = c.d_customer_CustomerID", array());
        
        // 受注商品テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("op" => "d_order_products"),
                "o.d_order_OrderID = op.d_order_products_OrderID", array());
        
        // 受注商品テーブルと商品テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("p" => "d_product"),
                "op.d_order_products_ProductID = p.d_product_ProductID", array());
        
        // 受注商品テーブルと受注商品詳細テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("opd" => "d_order_products_detail"),
                "op.d_order_products_OrderProductsID = opd.d_order_products_detail_OrderProductsID", array());
        
        // 定期テーブルとの結合
        
        // Where句セット
        // 顧客ID
        $stFormKey = "d_order_CustomerID";
        $stDBKey = "d_order_CustomerID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_DelFlg = ? ", 0);

        // OrderIDでGROUP BY
        if (empty($arrGroup)) {
            $objSelect->group("o.d_order_OrderID");
        } else {
            foreach ($arrGroup as $key => $value) {
                $objSelect->group($value);
            }
        }

        // 表示順
        $objSelect->order("o.d_order_OrderID DESC");
        
        $this->_objSelect = $objSelect;
        return $this;
    }
    
    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrGroup           GROUP BY 配列
     * @return  self
     */
    public function setSearchConditionForMypage ($arrSearchCondition, $arrColumn = array("*"), $arrGroup = array()) {

       $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
       
        // 受注明細テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("od" => "d_order_detail"),
                "o.d_order_OrderID = od.d_order_detail_OrderID", array());
        
        // Where句セット
        // 顧客ID
        $stFormKey = "d_order_CustomerID";
        $stDBKey = "d_order_CustomerID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        
        // 受注ID
        $stFormKey = "d_order_OrderID";
        $stDBKey = "d_order_OrderID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_DelFlg = ? ", 0);
        
        // OrderIDでGROUP BY
        if (empty($arrGroup)) {
            $objSelect->group("o.d_order_OrderID");
        } else {
            foreach ($arrGroup as $key => $value) {
                $objSelect->group($value);
            }
        }

        // 表示順
        $objSelect->order("o.d_order_OrderID DESC");
        
        $this->_objSelect = $objSelect;
        return $this;
    }

    /**
     * メンバ変数に格納された検索条件格納オブジェクトを返す。
     * 
     * @return  object
     */
    public function getSearchCondition() {
        return $this->_objSelect;
    }

    /**
     * 第一引数で指定された検索結果をメンバ変数に格納する。
     * 
     * @return  self
     */
    public function setSearchResult($arrSearchResult) {
        $this->_arrSearchResult = $arrSearchResult;
        return $this;
    }

    /**
     * setSearchResult()でメンバ変数に格納された検索結果を返す。
     * 
     * @return  object
     */
    public function getSearchResult() {
        return $this->_arrSearchResult;
    }

    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @return  self
     */
    public function setSearchConditionOfOrderCSV($arrSearchCondition, $arrColumn = array("*")) {

        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        // 注文管理テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("mng" => "d_order_mng"),
                "o.d_order_OrderMngID = mng.d_order_mng_OrderMngID", array());
        // 注文管理テーブルと注文入金履歴テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("mh" => "d_order_mng_history"),
                "mng.d_order_mng_OrderMngID = mh.d_order_mng_history_OrderMngID AND " . 
                "mng.d_order_mng_LatestOrderMngHistoryID = mh.d_order_mng_history_OrderMngHistoryID", array());
        // 受注挿入メールテーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("oi" => "d_order_insertmail"),
                "o.d_order_OrderID = oi.d_order_insertmail_OrderID", array());
        // 顧客テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("c" => "d_customer"),
                "o.d_order_CustomerID = c.d_customer_CustomerID", array());
        // 配送先テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("od" => "d_order_delivery"),
                "o.d_order_OrderDeliveryID = od.d_order_delivery_OrderDeliveryID", array());
        // 都道府県マスタとの結合
        $objSelect = &$objSelect->joinLeft(array("mp" => "m_pref"),
                "o.d_order_SenderPrefCode = mp.m_pref_PrefCode", array());
        // 市区町村マスタとの結合
        $objSelect = &$objSelect->joinLeft(array("mc" => "m_city"),
                "o.d_order_SenderCityCode = mc.m_city_CityCode", array());
        // のしテーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("n" => "d_noshi"),
                "o.d_order_NoshiID = n.d_noshi_NoshiID", array());
        // 時間指定テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pt" => "d_postage_time"),
                "o.d_order_PostageTimeID = pt.d_postage_time_PostageTimeID", array());
//        // メール設定テーブルとの結合
//        $objSelect = &$objSelect->joinLeft(array("ms" => "d_mail_setting"),
//                "o.d_order_InsertMailTemplateID = ms.d_mail_setting_TemplateID", array());
        // 受注商品テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("op" => "d_order_products"),
                "o.d_order_OrderID = op.d_order_products_OrderID", array());
        // 受注商品テーブルと受注商品詳細テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("opd" => "d_order_products_detail"),
                "op.d_order_products_OrderProductsID = opd.d_order_products_detail_OrderProductsID", array());
        // 受注商品テーブルと商品テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("p" => "d_product"),
                "op.d_order_products_ProductID = p.d_product_ProductID", array());
        // 受注商品テーブルと商品規格テーブルの結合
        $objSelect = &$objSelect->joinLeft(array("pcl" => "d_product_class"),
                "op.d_order_products_ProductClassID = pcl.d_product_class_ProductClassID", array());
        
        // 商品規格テーブルと商品規格プレゼントテーブルの結合
        // 2015/08/06 mpic 削除フラグ対応
        //$objSelect = &$objSelect->joinLeft(array("pcp" => "d_product_class_present"),
        //        "pcl.d_product_class_ProductClassID = pcp.d_product_class_present_ProductClassID", array());
        $objSelect = &$objSelect->joinLeft(array("pcp" => "d_product_class_present"),
                "pcl.d_product_class_ProductClassID = pcp.d_product_class_present_ProductClassID and pcp.d_product_class_present_DelFlg = '0'", array());
        
        // 商品規格マスタテーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pcm1" => "d_product_class_master"),
                "pcl.d_product_class_ProductClassMasterID1 = pcm1.d_product_class_master_ProductClassMasterID", array("d_product_class_master_Name"));
        $objSelect = &$objSelect->joinLeft(array("pcm2" => "d_product_class_master"),
                "pcl.d_product_class_ProductClassMasterID2 = pcm2.d_product_class_master_ProductClassMasterID", array("d_product_class_master_Name"));
        
        // 商品分類マスタテーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pctm1" => "d_product_class_type_master"),
                "pcl.d_product_class_ProductClassTypeMasterID1 = pctm1.d_product_class_type_master_ProductClassTypeMasterID", array("d_product_class_type_master_Name"));
        $objSelect = &$objSelect->joinLeft(array("pctm2" => "d_product_class_type_master"),
                "pcl.d_product_class_ProductClassTypeMasterID2 = pctm2.d_product_class_type_master_ProductClassTypeMasterID", array("d_product_class_type_master_Name"));
        
        // 商品カテゴリーセットテーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pc" => "d_product_category"),
                "p.d_product_ProductID = pc.d_product_category_ProductID", array("d_product_category_CategoryID"));
        // 支払方法テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("pay" => "d_payment"),
                "mng.d_order_mng_PaymentID = pay.d_payment_PaymentID", array());
        // 都道府県マスタとの結合(配送先)
        $objSelect = &$objSelect->joinLeft(array("odmp" => "m_pref"),
                "od.d_order_delivery_PrefCode = odmp.m_pref_PrefCode", array());
        
        // 都道府県マスタとの結合(受注配送先)
        $objSelect = &$objSelect->joinLeft(array("odmp2" => "m_pref"),
                "o.d_order_DeliveryPrefCode = odmp2.m_pref_PrefCode", array());
        
        // 都道府県マスタとの結合(顧客)
        $objSelect = &$objSelect->joinLeft(array("cmp" => "m_pref"),
                "c.d_customer_PrefCode = cmp.m_pref_PrefCode", array());
        // 都道府県マスタとの結合(送り主)
        $objSelect = &$objSelect->joinLeft(array("osmp" => "m_pref"),
                "o.d_order_SenderPrefCode = osmp.m_pref_PrefCode", array());
        // 購入経路マスタとの結合
        $objSelect = &$objSelect->joinLeft(array("br" => "m_buy_route"),
                "mng.d_order_mng_BuyRouteID = br.m_buy_route_BuyRoureID", array());
        
        // Where句セット
        // 注文管理番号(ID)
        $stFormKey = "d_order_mng_OrderMngID";
        $stDBKey = "d_order_mng_OrderMngID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("mng.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("mng.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("mng.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("mng.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }

        // 注文番号(ID)
        $stFormKey = "d_order_OrderID";
        $stDBKey = "d_order_OrderID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }

        // 対応状況
        $stFormKey = "d_order_Status";
        $stDBKey = "d_order_Status";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }

        // 受注日
        $stDBKey = "d_order_mng_Date";
        if ($arrSearchCondition["order_from_Year"] != "" && $arrSearchCondition["order_from_Month"] != "" && 
            $arrSearchCondition["order_from_Day"] != "") {
            $objSelect->where("mng.$stDBKey >= ?", $arrSearchCondition["order_from_Year"] . "-" . 
                $arrSearchCondition["order_from_Month"] . "-" . $arrSearchCondition["order_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["order_to_Year"] != "" && $arrSearchCondition["order_to_Month"] != "" && 
            $arrSearchCondition["order_to_Day"] != "") {
            $objSelect->where("mng.$stDBKey <= ?", $arrSearchCondition["order_to_Year"] . "-" . 
                $arrSearchCondition["order_to_Month"] . "-" . $arrSearchCondition["order_to_Day"] . " 23:59:59");
        }

        // 発注予定日
        $stDBKey = "d_order_products_ScheduledSupplyOrderDate";
        if ($arrSearchCondition["ssod_from_Year"] != "" && $arrSearchCondition["ssod_from_Month"] != "" && 
            $arrSearchCondition["ssod_from_Day"] != "") {
            $objSelect->where("op.$stDBKey >= ?", $arrSearchCondition["ssod_from_Year"] . "-" . 
                $arrSearchCondition["ssod_from_Month"] . "-" . $arrSearchCondition["ssod_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["ssod_to_Year"] != "" && $arrSearchCondition["ssod_to_Month"] != "" && 
            $arrSearchCondition["ssod_to_Day"] != "") {
            $objSelect->where("op.$stDBKey <= ?", $arrSearchCondition["ssod_to_Year"] . "-" . 
                $arrSearchCondition["ssod_to_Month"] . "-" . $arrSearchCondition["ssod_to_Day"] . " 23:59:59");
        }
        
        // ステータス管理の発注予定日
        $stDBKey = "d_order_products_ScheduledSupplyOrderDate";
        if ($arrSearchCondition["scheduled_supply_from_Year"] != "" && $arrSearchCondition["scheduled_supply_from_Month"] != "" && 
            $arrSearchCondition["scheduled_supply_from_Day"] != "") {
            $objSelect->where("op.$stDBKey >= ?", $arrSearchCondition["scheduled_supply_from_Year"] . "-" . 
                $arrSearchCondition["scheduled_supply_from_Month"] . "-" . $arrSearchCondition["scheduled_supply_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["scheduled_supply_to_Year"] != "" && $arrSearchCondition["scheduled_supply_to_Month"] != "" && 
            $arrSearchCondition["scheduled_supply_to_Day"] != "") {
            $objSelect->where("op.$stDBKey <= ?", $arrSearchCondition["scheduled_supply_to_Year"] . "-" . 
                $arrSearchCondition["scheduled_supply_to_Month"] . "-" . $arrSearchCondition["scheduled_supply_to_Day"] . " 23:59:59");
        }
        
        // 発注日
        $stDBKey = "d_order_SupplyOrderDate";
        if ($arrSearchCondition["sod_from_Year"] != "" && $arrSearchCondition["sod_from_Month"] != "" && 
            $arrSearchCondition["sod_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["sod_from_Year"] . "-" . 
                $arrSearchCondition["sod_from_Month"] . "-" . $arrSearchCondition["sod_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["sod_to_Year"] != "" && $arrSearchCondition["sod_to_Month"] != "" && 
            $arrSearchCondition["sod_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["sod_to_Year"] . "-" . 
                $arrSearchCondition["sod_to_Month"] . "-" . $arrSearchCondition["sod_to_Day"] . " 23:59:59");
        }
        // ステータス管理の発注日
        $stDBKey = "d_order_SupplyOrderDate";
        if ($arrSearchCondition["supply_from_Year"] != "" && $arrSearchCondition["supply_from_Month"] != "" && 
            $arrSearchCondition["supply_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["supply_from_Year"] . "-" . 
                $arrSearchCondition["supply_from_Month"] . "-" . $arrSearchCondition["supply_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["supply_to_Year"] != "" && $arrSearchCondition["supply_to_Month"] != "" && 
            $arrSearchCondition["supply_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["supply_to_Year"] . "-" . 
                $arrSearchCondition["supply_to_Month"] . "-" . $arrSearchCondition["supply_to_Day"] . " 23:59:59");
        }
        
        // 発送予定日
        $stDBKey = "d_order_ExpectedShippingDate";
        if ($arrSearchCondition["esd_from_Year"] != "" && $arrSearchCondition["esd_from_Month"] != "" && 
            $arrSearchCondition["esd_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["esd_from_Year"] . "-" . 
                $arrSearchCondition["esd_from_Month"] . "-" . $arrSearchCondition["esd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["esd_to_Year"] != "" && $arrSearchCondition["esd_to_Month"] != "" && 
            $arrSearchCondition["esd_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["esd_to_Year"] . "-" . 
                $arrSearchCondition["esd_to_Month"] . "-" . $arrSearchCondition["esd_to_Day"] . " 23:59:59");
        }

        // 発送日
        $stDBKey = "d_order_ShippingDate";
        if ($arrSearchCondition["sd_from_Year"] != "" && $arrSearchCondition["sd_from_Month"] != "" && 
            $arrSearchCondition["sd_from_Day"] != "") {
            $objSelect->where("o.$stDBKey >= ?", $arrSearchCondition["sd_from_Year"] . "-" . 
                $arrSearchCondition["sd_from_Month"] . "-" . $arrSearchCondition["sd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["sd_to_Year"] != "" && $arrSearchCondition["sd_to_Month"] != "" && 
            $arrSearchCondition["sd_to_Day"] != "") {
            $objSelect->where("o.$stDBKey <= ?", $arrSearchCondition["sd_to_Year"] . "-" . 
                $arrSearchCondition["sd_to_Month"] . "-" . $arrSearchCondition["sd_to_Day"] . " 23:59:59");
        }
        
        // 入金確認日
        $stDBKey = "d_order_mng_history_PaymentDate";
        if ($arrSearchCondition["mhpd_from_Year"] != "" && $arrSearchCondition["mhpd_from_Month"] != "" && 
            $arrSearchCondition["mhpd_from_Day"] != "") {
            $objSelect->where("mh.$stDBKey >= ?", $arrSearchCondition["mhpd_from_Year"] . "-" . 
                $arrSearchCondition["mhpd_from_Month"] . "-" . $arrSearchCondition["mhpd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["mhpd_to_Year"] != "" && $arrSearchCondition["mhpd_to_Month"] != "" && 
            $arrSearchCondition["mhpd_to_Day"] != "") {
            $objSelect->where("mh.$stDBKey <= ?", $arrSearchCondition["mhpd_to_Year"] . "-" . 
                $arrSearchCondition["mhpd_to_Month"] . "-" . $arrSearchCondition["mhpd_to_Day"] . " 23:59:59");
        }
        // ステータス管理の入金確認日
        $stDBKey = "d_order_mng_history_PaymentDate";
        if ($arrSearchCondition["payment_from_Year"] != "" && $arrSearchCondition["payment_from_Month"] != "" && 
            $arrSearchCondition["payment_from_Day"] != "") {
            $objSelect->where("mh.$stDBKey >= ?", $arrSearchCondition["payment_from_Year"] . "-" . 
                $arrSearchCondition["payment_from_Month"] . "-" . $arrSearchCondition["payment_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["payment_to_Year"] != "" && $arrSearchCondition["payment_to_Month"] != "" && 
            $arrSearchCondition["payment_to_Day"] != "") {
            $objSelect->where("mh.$stDBKey <= ?", $arrSearchCondition["payment_to_Year"] . "-" . 
                $arrSearchCondition["payment_to_Month"] . "-" . $arrSearchCondition["payment_to_Day"] . " 23:59:59");
        }
        
        // 購入金額
        $stFormKey = "d_order_TotalPriceFrom";
        $stDBKey = "d_order_TotalPrice";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey >= ", $arrSearchCondition[$stFormKey]);
        }
        $stFormKey = "d_order_TotalPriceTo";
        $stDBKey = "d_order_TotalPrice";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey <= ? ", $arrSearchCondition[$stFormKey]);
        }

        // 支払方法
//        $stFormKey = "d_order_mng_PaymentID_Search"; // チェック分カンマ区切りで連結
        $stFormKey = "d_order_mng_PaymentID";
        $stDBKey = "d_order_mng_PaymentID";
        if ($arrSearchCondition[$stFormKey] != "") {
//            $objSelect->where("mng.$stDBKey IN (?) ", $arrSearchCondition[$stFormKey]);
            $objSelect->where("(mng.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("mng.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("mng.$stDBKey = ? )", $value);
                }
            }
        }

        // 購入経路
//        $stFormKey = "d_order_mng_BuyRouteID_Search"; // チェック分カンマ区切りで連結
        $stFormKey = "d_order_mng_BuyRouteID";
        $stDBKey = "d_order_mng_BuyRouteID";
        if ($arrSearchCondition[$stFormKey] != "") {
//            $objSelect->where("mng.$stDBKey IN (?) ", $arrSearchCondition[$stFormKey]);
            $objSelect->where("(mng.$stDBKey is null");
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("mng.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("mng.$stDBKey = ? )", $value);
                }
            }
        }

        // 定期・頒布会

        // 顧客名
        //  d_customer_NameはFirstNameとLastNameの結合(CONCAT)
        $stFormKey = "d_customer_Name";
        $stDBKey = "CONCAT(IFNULL(d_customer_FirstName,''),IFNULL(d_customer_LastName,''))";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 顧客名カナ
        $stFormKey = "d_customer_Kana";
        $stDBKey = "d_customer_Kana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("c.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // メールアドレス
        $stFormKey = "d_customer_EmailAddress";
        $stDBKey = "d_customer_EmailAddress";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("c.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // TEL
        $stFormKey = "d_customer_TelNo";
        $stDBKey = "d_customer_TelNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("c.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 性別
//        $stFormKey = "d_customer_SexID_Search"; // チェック分カンマ区切りで連結
        $stFormKey = "d_customer_SexID";
        $stDBKey = "d_customer_SexID";
        if ($arrSearchCondition[$stFormKey] != "") {
//            $objSelect->where("c.$stDBKey IN (?) ", $arrSearchCondition[$stFormKey]);
            $objSelect->where("(c.$stDBKey = ?" , 999);
            $iMaxKey = max(array_keys($arrSearchCondition[$stFormKey]));
            foreach ($arrSearchCondition[$stFormKey] as $key => $value) {
                if ($iMaxKey != $key) {
                    $objSelect->orwhere("c.$stDBKey = ? ", $value);
                } else {
                    $objSelect->orwhere("c.$stDBKey = ? )", $value);
                }
            }
        }

        // 職業
        $stFormKey = "d_customer_JobID";
        $stDBKey = "d_customer_JobID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("c.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }

        // 生年月日
        $stDBKey = "d_customer_BirthDate";
        if ($arrSearchCondition["birthd_from_Year"] != "" && $arrSearchCondition["birthd_from_Month"] != "" && 
            $arrSearchCondition["birthd_from_Day"] != "") {
            $objSelect->where("c.$stDBKey >= ?", $arrSearchCondition["birthd_from_Year"] . "-" . 
                $arrSearchCondition["birthd_from_Month"] . "-" . $arrSearchCondition["birthd_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["birthd_to_Year"] != "" && $arrSearchCondition["birthd_to_Month"] != "" && 
            $arrSearchCondition["birthd_to_Day"] != "") {
            $objSelect->where("c.$stDBKey <= ?", $arrSearchCondition["birthd_to_Year"] . "-" . 
                $arrSearchCondition["birthd_to_Month"] . "-" . $arrSearchCondition["birthd_to_Day"] . " 23:59:59");
        }

        // 受注送り主名
        //  d_order_SenderNameはFirstNameとLastNameの結合(CONCAT)
        $stFormKey = "d_order_SenderName";
        $stDBKey = "CONCAT(IFNULL(d_order_SenderFirstName,''),IFNULL(d_order_SenderLastName,''))";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 受注送り主カナ
        $stFormKey = "d_order_SenderKana";
        $stDBKey = "d_order_SenderKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 受注送り主TEL
        $stFormKey = "d_order_SenderTelNo";
        $stDBKey = "d_order_SenderTelNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 2014/11/17 届け先参照変更
        // 受注届け先名
        //  d_order_DeliveryNameはFirstNameとLastNameの結合(CONCAT)
        $stFormKey = "d_order_DeliveryName";
        $stDBKey = "CONCAT(IFNULL(d_order_DeliveryFirstName,''),IFNULL(d_order_DeliveryLastName,''))";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 受注届け先カナ
        $stFormKey = "d_order_DeliveryKana";
        $stDBKey = "d_order_DeliveryKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 受注届け先TEL
        $stFormKey = "d_order_DeliveryTelNo";
        $stDBKey = "d_order_DeliveryTelNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
//        // 受注届け先名
//        //  d_order_delivery_NameはFirstNameとLastNameの結合(CONCAT)
//        $stFormKey = "d_order_delivery_Name";
//        $stDBKey = "CONCAT(IFNULL(d_order_delivery_FirstName,''),IFNULL(d_order_delivery_LastName,''))";
//        if ($arrSearchCondition[$stFormKey] != "") {
//            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
//        }
//
//        // 受注届け先カナ
//        $stFormKey = "d_order_delivery_Kana";
//        $stDBKey = "d_order_delivery_Kana";
//        if ($arrSearchCondition[$stFormKey] != "") {
//            $objSelect->where("od.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
//        }
//
//        // 受注届け先TEL
//        $stFormKey = "d_order_delivery_TelNo";
//        $stDBKey = "d_order_delivery_TelNo";
//        if ($arrSearchCondition[$stFormKey] != "") {
//            $objSelect->where("od.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
//        }

        // 商品名
        $stFormKey = "d_product_Name";
        $stDBKey = "d_product_Name";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("p.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 商品規格
        $stFormKey = "d_product_class_master_Name";
        if ($arrSearchCondition[$stFormKey] != "") {
            $stDBKey = "pcm1.d_product_class_master_Name";
            $objSelect->where("($stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
            $stDBKey = "pcm2.d_product_class_master_Name";
            $objSelect->orwhere("$stDBKey LIKE ? )", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 商品コード
        $stFormKey = "d_product_class_ProductCode";
        $stDBKey = "d_product_class_ProductCode";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("pcl.$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // カテゴリ
        $stFormKey = "d_product_category_CategoryID";
        $stDBKey = "d_product_category_CategoryID";
        if ($arrSearchCondition[$stFormKey] > 0) {
            // このカテゴリIDの子カテゴリ配下も検索対象にする
            $stIds = $this->mdlCategory->getChildCategoryIDs($arrSearchCondition[$stFormKey]);
            $stIds = rtrim($stIds, ",");
            $arrIds = explode(",", $stIds);
            $objSelect->where("pc.$stDBKey IN (?) ", $arrIds);
        }

        // ステータス管理 支払状況
        $stFormKey = "d_order_products_PayStatus";
        $stDBKey = "d_order_products_PayStatus";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("op.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("mng.d_order_mng_DelFlg = ? ", 0);
        $objSelect->where("o.d_order_DelFlg = ? ", 0);
        $objSelect->where("op.d_order_products_DelFlg = ? ", 0);
        $objSelect->where("opd.d_order_products_detail_DelFlg = ? ", 0);

        // 2015/08/06 mpic 削除フラグ対応
        //$objSelect->where("(pcp.d_product_class_present_DelFlg = ? ", 0);
        //$objSelect->orwhere("pcp.d_product_class_present_DelFlg IS NULL)");
        
        // 受注ステータスがキャンセルを除く
        $objSelect->where("o.d_order_Status != ? ", Application_Model_Order::STATUS_CANCEL);
        
        // OrderProductsDetailIDでGROUP BY
        $objSelect->group("op.d_order_products_OrderProductsID");
        $objSelect->group("opd.d_order_products_detail_OrderProductsID");
        
        // 表示順
        $objSelect->order("o.d_order_OrderID DESC");
        $objSelect->order("pcl.d_product_class_Rank ASC");
        
        $this->_objSelect = $objSelect;
        return $this;
    }
    
    /**
     * 売上高と売上件数を取得する
     * 
     * @param   array   $arrSearchCondition     検索条件
     * @param   string  $stSumColumn            最大値を取得するカラム
     * @param   array   $arrColumn              取得対象のカラム名を格納した配列
     * @return  array   $arrResult              検索結果
     */
    public function getSalesAmountAndNumberOfSales($arrSearchCondition, $stSumColumn, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("count(*) as saleCount,sum(" . $stSumColumn . ") as " . $stSumColumn)));

            // 対応状況
            $stFormKey = "d_order_Status";
            $stDBKey = "d_order_Status";
            if ($arrSearchCondition[$stFormKey] != "") {
                $objSelect->where("$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
            
            // 更新日
            $stDBKey = "d_order_UpdatedTime";
            if ($arrSearchCondition["update_from_Year"] != "" && $arrSearchCondition["update_from_Month"] != "" && 
                $arrSearchCondition["update_from_Day"] != "") {
                $objSelect->where("$stDBKey >= ?", $arrSearchCondition["update_from_Year"] . "-" . 
                    $arrSearchCondition["update_from_Month"] . "-" . $arrSearchCondition["update_from_Day"] . " 00:00:00");
            }
            if ($arrSearchCondition["update_to_Year"] != "" && $arrSearchCondition["update_to_Month"] != "" && 
                $arrSearchCondition["update_to_Day"] != "") {
                $objSelect->where("$stDBKey <= ?", $arrSearchCondition["update_to_Year"] . "-" . 
                    $arrSearchCondition["update_to_Month"] . "-" . $arrSearchCondition["update_to_Day"] . " 23:59:59");
            }
            $objSelect->where("d_order_DelFlg = ? ", 0);
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    /**
     * 第一引数に紐づくデータに対して削除フラグを立てる。
     * 
     * @param   integer $iID ID(PK)
     * @param   bool    $bIsAdmin   管理画面からの登録
     * @return  self
     */
    public function deleteByOrderMngID($iID, $bIsAdmin = true) {

        try {
            if ($iID == "" || $iID < 0) {
                throw new Zend_Exception('$iIDを指定してください。');
            }

            $stTable = $this->getTableName();
            // 更新内容
            $arrParams["d_order_DelFlg"] = 1;
            $arrParams["d_order_UpdatedTime"] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams["d_order_UpdatedByID"] = $this->objAdminSess->MemberID;
            } else {
                $arrParams["d_order_UpdatedByID"] = 0;
            }
            // 更新条件
            $arrWhere["d_order_OrderMngID = ?"] = $iID;
            $arrWhere["d_order_DelFlg = ?"]  = 0;
            $this->objMasterDb->update($stTable, $arrParams, $arrWhere);
           
            return $this;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 注文管理番号毎の受注数を取得する
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @return  self
     */
    public function getOrderMngCount($arrSearchCondition) {

        $arrColumn = array("d_order_OrderMngID", "COUNT(d_order_OrderID) AS d_order_Count");
        
        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
 
        // Where句セット

        // 注文管理番号(ID)
        $stFormKey = "d_order_OrderMngID";
        $stDBKey = "d_order_OrderMngID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_DelFlg = ? ", 0);
        
        // OrderIDでGROUP BY
        $objSelect->group("o.d_order_OrderMngID"); 
        
        // 表示順
        $objSelect->order("o.d_order_OrderMngID ASC");

        // select文を実行する
        $objSql = $this->objSlaveDb->query($objSelect);
        // 検索結果を $arrResultに格納
        $arrResult = $objSql->fetchAll();

        return $arrResult;
    }
    
    /**
     * 注文管理番号毎の一覧表示ステータスを取得する
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @return  self
     */
    public function getOrderMngStatusList($arrSearchCondition) {

        $arrColumn = array("d_order_OrderMngID", "d_order_OrderID", "d_order_Status");
        
        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
 
        // Where句セット

        // 注文管理番号(ID)
        $stFormKey = "d_order_OrderMngID";
        $stDBKey = "d_order_OrderMngID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_DelFlg = ? ", 0);
        
        // 表示順
        $objSelect->order("o.d_order_OrderMngID ASC");
        $objSelect->order("o.d_order_OrderID ASC");
        
        // select文を実行する
        $objSql = $this->objSlaveDb->query($objSelect);
        // 検索結果を $arrResultに格納
        $arrWork = $objSql->fetchAll();

        $arrResult = array();
        // 受注ステータス表示順
        $arrSortStatus = array(0 => Application_Model_Order::STATUS_NEW, 1 => Application_Model_Order::STATUS_PREPARING,
            2 => Application_Model_Order::STATUS_SHIPPING_STOCK_DECISION, 3 => Application_Model_Order::STATUS_SHIPPING_SCHEDULE,
            4 => Application_Model_Order::STATUS_SHIPPED, 5 => Application_Model_Order::STATUS_CANCEL, 
            6 => Application_Model_Order::STATUS_PRODUCING);

        if ($arrWork) {
            $iOrderMngSave = $arrWork[0]["d_order_OrderMngID"];
            $iStatus = 99;
            $iKeySave = 99;
            foreach ($arrWork as $key => $value) {
                if ($iOrderMngSave != $value["d_order_OrderMngID"]) {
                    $arrResult[$iOrderMngSave] = $iStatus;
                    $iOrderMngSave = $value["d_order_OrderMngID"];
                    $iStatus = 99;
                    $iKeySave = 99;
                }
                foreach ($arrSortStatus as $key2 => $value2) {
                    if ($value2 == $value["d_order_Status"]) {
                        if ($iKeySave >= $key2) {
                            $iStatus = $value["d_order_Status"];
                            $iKeySave = $key2;
                            break;
                        }
                    }
                }
            }
            // 最後のループ
            $arrResult[$iOrderMngSave] = $iStatus;
        }
        
        return $arrResult;
    }
    
    /**
     * 受注データから、対応状況に該当する最小受注日を取得する
     * 
     * @param   string  $stOrderStatus      対応状況
     * @return  string
     */
    public function getMinOrderMngDate($stOrderStatus = 0) {

        // ** select句, from句セット **
        $arrColumn = array("MIN(mng.d_order_mng_Date) AS min_order_mng_Date");
        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);

        // ** 結合句セット **
        // 注文管理テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("mng" => "d_order_mng"),
                "o.d_order_OrderMngID = mng.d_order_mng_OrderMngID", array());

        // ** Where句セット **
        // 対応状況（出荷済みのみ）
        $objSelect->where("o.d_order_Status = ? ", $stOrderStatus);
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("mng.d_order_mng_DelFlg = ? ", 0);
        $objSelect->where("o.d_order_DelFlg = ? ", 0);

        // select文を実行する
        $objSql = $this->objSlaveDb->query($objSelect);
        // 検索結果を $arrResultに格納
        $arrResult = $objSql->fetch();
        //
        $stResultDate = '';
        if (isset($arrResult['min_order_mng_Date'])) {
            $stResultDate = $arrResult['min_order_mng_Date'];
        }

        return $stResultDate;
    }

    /**
     * 第一引数に紐づく最大値データを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $stOrderMngID   検索条件
     * @return  array   $arrResult      検索結果
     */
    public function getOrderCount($stOrderMngID) {
        
        try {
            
            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("count(d_order_OrderID) AS orderCount")));
            $objSelect->where("d_order_OrderMngID = ? ", $stOrderMngID);
            $objSelect->where("d_order_DelFlg = ? ", 0);
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    /**
     * 第一引数で指定された商品IDを持つ受注(対応状況:キャンセル,出荷済み　を除く)を全て取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param           $iProductID     商品ID
     * @return  array   $arrResult      検索結果
     */
    public function getOrderFromProductID($iProductID) {
        
        try {
            $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), array("d_order_OrderID"));
            // 受注明細テーブルとの結合
             $objSelect = &$objSelect->joinLeft(array("odp" => "d_order_detail"),
                "o.d_order_OrderID = odp.d_order_detail_OrderID", array());
            // 受注明細テーブルと商品テーブルの結合
            $objSelect = &$objSelect->joinLeft(array("p" => "d_product"),
                "odp.d_order_detail_ProductID = p.d_product_ProductID", array());
            // 受注明細テーブルと商品規格テーブルの結合
            $objSelect = &$objSelect->joinLeft(array("pcl" => "d_product_class"),
                "odp.d_order_detail_ProductClassID = pcl.d_product_class_ProductClassID", array());
            // 商品規格テーブルと商品規格マスタテーブルとの結合
            $objSelect = &$objSelect->joinLeft(array("pcm1" => "d_product_class_master"),
                "pcl.d_product_class_ProductClassMasterID1 = pcm1.d_product_class_master_ProductClassMasterID", array());
            $objSelect = &$objSelect->joinLeft(array("pcm2" => "d_product_class_master"),
                "pcl.d_product_class_ProductClassMasterID2 = pcm2.d_product_class_master_ProductClassMasterID", array());

            // Where句セット
            $objSelect->where("odp.d_order_detail_ProductID = ? ", $iProductID);
            $objSelect->where("o.d_order_Status != ? ", Application_Model_Order::STATUS_CANCEL);
            $objSelect->where("o.d_order_Status != ? ", Application_Model_Order::STATUS_SHIPPED);
            $objSelect->where("d_order_DelFlg = ? ", 0);
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 対象顧客に未出荷・未キャンセルの受注があるかチェック
     * 
     * @param   array   $iCustomerID 顧客ID
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @return  $iRet   true = 対象受注あり  false = 対象受注なし
     */
    public function checkCustomerShipCancel($iCustomerID) {

        $arrColumn = array("DISTINCT COUNT(*) AS CustomerCount");
        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        
        // Where句セット
        $stDBKey = "d_order_CustomerID";
        $objSelect->where("o.$stDBKey = ? ", $iCustomerID);
        $stDBKey = "d_order_Status";
        $objSelect->where("o.$stDBKey != ? ", Application_Model_Order::STATUS_SHIPPED);
        $objSelect->where("o.$stDBKey != ? ", Application_Model_Order::STATUS_CANCEL);

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_DelFlg = ? ", 0);
        $objSql = $this->objSlaveDb->query($objSelect);
        // 検索結果を $arrResultに格納
        $arrResult = $objSql->fetch();        

        if ($arrResult["CustomerCount"] > 0) {
            $iRet = true;
        } else {
            $iRet = false;
        }
        return $iRet;
    }

    /**
     * 受注データのお届け日一覧を取得する（未出荷限定）
     * 
     * @param   string  $stOrderMngID 注文管理ID
     */
    public function getMostRecentDeliveryDate($arrSearchCondition, $arrColumn) {
        
        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        
        // 注文管理テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("mng" => "d_order_mng"),
                "o.d_order_OrderMngID = mng.d_order_mng_OrderMngID", array());
        
        // 頒布会管理テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("ocm" => "d_order_club_mng"),
                "mng.d_order_mng_OrderClubMngID = ocm.d_order_club_mng_OrderClubMngID", array());
        
        // Where句セット
        // 注文管理番号(ID)
        $stFormKey = "d_order_mng_OrderMngID";
        $stDBKey = "mng.d_order_mng_OrderMngID";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where("$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("mng.d_order_mng_DelFlg = ? ", 0);
        $objSelect->where("o.d_order_DelFlg = ? ", 0);
        
        // 受注ステータスがキャンセル、出荷済みを除く
//        $objSelect->where("o.d_order_Status != ? ", Application_Model_Order::STATUS_CANCEL);
//        $objSelect->where("o.d_order_Status != ? ", Application_Model_Order::STATUS_SHIPPED);
        
        // GROUP BY
//        $objSelect->group("o.d_order_OrderID");
        
        // 表示順
        $objSelect->order("o.d_order_OrderID ASC");

        $this->_objSelect = $objSelect;
        
        $arrResult = $this->search(false);

        return $arrResult;
    }
    

}