<?php

/**
 * システム管理者用モデル
 *
 * @author     M-PIC鈴木
 * @version    v1.0
 */
class Application_Model_BaseInfo extends Application_Model_Abstract 
{
    /**
     * テーブル名
     * @var
     */
    protected $_stTableName = "d_baseinfo";

    // クラス定数宣言
    // 売上区分
    const SALEDIV_CASH = 1;                 // 現金売
    const SALEDIV_CHARGE = 2;               // 掛売
    
    // 税計算区分
    const TAX_CALC_CLAIM = 1;               // 請求単位
    const TAX_CALC_ORDER_MNG = 2;           // 注文単位
    const TAX_CALC_ORDER = 3;               // 受注単位
    const TAX_CALC_PRODUCT = 4;             // 商品単位
    
    // 消費税端数区分
    const TAX_FRACTION_CEIL = 1;            // 切り上げ
    const TAX_FRACTION_ROUND = 2;           // 四捨五入
    const TAX_FRACTION_FLOOR = 3;           // 切捨て

    // キャンセル有効フラグ
    const CANCEL_IMPOSSIBLE = 0;            // ステータスキャンセル選択不可
    const CANCEL_POSSIBLE = 1;              // キャンセル可
    
    // 随時請求書出力区分
    const BILLDIV_DELIVERY_INVOICE = 1;         // 納品書兼請求書
    const BILLDIV_DELIVERY_INVOICE_POSTAL = 2;  // 納品書兼請求書＋郵便振替
    
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
        
        // セッション
        $this->objAdminSess = new Zend_Session_Namespace("Admin");
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
     * @return  self
     */
    public function save($arrData) {
        
        try {
            if (count($arrData) == 0)
                throw new Zend_Exception('$arrDataが空です。');

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams[ "d_baseinfo_UpdatedTime" ] = date("Y-m-d H:i:s");            
            $arrParams[ "d_baseinfo_UpdatedByID" ] = $this->objAdminSess->MemberID;
            $stWhere = $this->objMasterDb->quoteInto("d_baseinfo_BaseinfoID = ? ", $arrData[ "d_baseinfo_BaseinfoID" ]);
            $this->objMasterDb->update($stTable, $arrParams, $stWhere);

            return $this;
            
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数に紐づくデータを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   integer $iID ID(PK)
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @return  array   $arrResult  検索結果
     */
    public function find($iID, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn))
                $arrColumn = array("*");
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_baseinfo_BaseinfoID = ? ", $iID);

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

  /**
     * 第一引数に検索条件カラムを指定する。
     * 第二引数に検索条件の値を指定する。 
     * 第三引数が指定されている場合、その配列を取得対象カラムとする。
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
                $objSelect->order("d_baseinfo_BaseinfoID ASC");
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
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_baseinfo_BaseinfoID ASC");
            }

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数の配列から主キーを抽出し、
     * データ第一引数の値で登録する。
     * 
     * @param   array   $arrData    登録対象のカラム名と値の連想配列
     * @return  integer             最後に自動生成されたID
     */
    public function insert($arrData) {
        
        try {
            if (count($arrData) == 0)
                throw new Zend_Exception('$arrDataが空です。');

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams[ "d_baseinfo_CreatedTime" ] = date("Y-m-d H:i:s");
            $arrParams[ "d_baseinfo_UpdatedTime" ] = date("Y-m-d H:i:s");
            $arrParams[ "d_baseinfo_CreatedByID" ] = $this->objAdminSess->MemberID;
            $arrParams[ "d_baseinfo_UpdatedByID" ] = $this->objAdminSess->MemberID;
            $this->objMasterDb->insert($stTable, $arrParams);

            return $this->objMasterDb->lastInsertId();
            
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数に紐づくデータに対して削除フラグを立てる。
     * 
     * @param   integer $iID ID(PK)
     * @return  self
     */
    public function delete($iID) {

        try {
            if ($iID == "" || $iID < 0)
                throw new Zend_Exception('IDを指定してください。');

            $stTable = $this->getTableName();
            // 更新内容
            $arrParams[ "d_baseinfo_UpdatedTime" ] = date("Y-m-d H:i:s");
            $arrParams[ "d_baseinfo_UpdatedByID" ] = $this->objAdminSess->MemberID;
            // 更新条件
            $arrWhere[ "d_baseinfo_BaseinfoID = ?" ] = $iID;
            $this->objMasterDb->update($stTable, $arrParams, $arrWhere);
           
            return $this;
            
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
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
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * setSearchCondition()でセットされた検索条件を元に、
     * 検索処理を実行して結果を返す。
     * 検索結果はsetSearchResult()によりメンバ変数に格納される。
     * 
     * @return  array               検索結果
     */
    public function search() {
        
        try {
            if (is_object($this->getSearchCondition())) {
                $objSelect = $this->getSearchCondition();
            } else {
                throw new Zend_Exception("検索条件を指定してください。");
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
            $objSelect->columns('COUNT(DISTINCT ' .  "`$stAlias`" . '.`d_baseinfo_BaseinfoID`) AS cnt');
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
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
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
            } else
                throw new Zend_Exception("検索処理を先に実行してください。");
            
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @return  self
     */
    public function setSearchCondition($arrSearchCondition) {

        // 初期設定
        $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), array("*"));

        // ID
        if ($arrSearchCondition[ "d_baseinfo_BaseinfoID" ]) {
            $objSelect->where('d_baseinfo_BaseinfoID = ?', $arrSearchCondition[ "d_baseinfo_BaseinfoID" ]);
        }

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
     * mySQLのバージョンを取得する
     * 
     * @return  string
     */
    public function getMySQLVersion() {
        
        try {
            
            $arrConfig = $this->objMasterDb->getConfig();
            $mysqli = new mysqli($arrConfig["host"], $arrConfig["username"], $arrConfig["password"]);
            if (mysqli_connect_errno()) {
                throw new Zend_Exception("Connect failed: %s\n", mysqli_connect_error());
            }
            
            $stServerInfo = $mysqli->server_info;
            $mysqli->close();        
            
            return $stServerInfo;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
}
