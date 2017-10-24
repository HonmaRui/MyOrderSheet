<?php

/**
 * メール履歴テーブル用モデル
 *
 * @author     M-PIC鈴木
 * @version    v1.0
 */
class Application_Model_MailHistory extends Application_Model_Abstract 
{
    /**
     * テーブル名
     * @var
     */
    protected $_stTableName = "d_mail_history";

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
        // 総件数
        $this->totalCount = 0;
        
        // セッション
        $this->objAdminSess = new Zend_Session_Namespace("Admin");
        $this->objFrontSess = new Zend_Session_Namespace("Front");
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
            if (count($arrData) == 0)
                throw new Zend_Exception('$arrDataが空です。');

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams[ "d_mail_history_UpdatedTime" ] = date("Y-m-d H:i:s");            
            if ($bIsAdmin) {
                $arrParams[ "d_mail_history_UpdatedByID" ] = $this->objAdminSess->MemberID;
            } else {
                $arrParams[ "d_mail_history_UpdatedByID" ] = 0;
            }
            $stWhere = $this->objMasterDb->quoteInto("d_mail_history_MailHistoryID = ? ", $arrData[ "d_mail_history_MailHistoryID" ]);
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
     * @param   integer $iMailHistoryID メール履歴ID(PK)
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @return  array   $arrResult  検索結果
     */
    public function find($iMailHistoryID, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn))
                $arrColumn = array("*");
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_mail_history_DelFlg = ? ", 0);
            $objSelect->where("d_mail_history_MailHistoryID = ? ", $iMailHistoryID);

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
    public function findAll($arrWhere, $arrColumn = "", $arrOrder = "", $iLimit = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_mail_history_DelFlg = ? ", 0);
            if ($iLimit != "") {
                $objSelect->limit($iLimit);
            }
            
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
                $objSelect->order("d_mail_history_MailHistoryID DESC");
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
            $objSelect->where("d_mail_history_DelFlg = ? ", 0);
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_mail_history_SendDate DESC");
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
     * @param   bool    $bIsAdmin   管理画面からの呼び出しかどうか
     * @return  integer             最後に自動生成されたID
     */
    public function insert($arrData, $bIsAdmin = true) {
        
        try {
            if (count($arrData) == 0)
                throw new Zend_Exception('$arrDataが空です。');

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams[ "d_mail_history_CreatedTime" ] = date("Y-m-d H:i:s");
            $arrParams[ "d_mail_history_UpdatedTime" ] = date("Y-m-d H:i:s");
            
            if ($bIsAdmin) {
                $arrParams[ "d_mail_history_CreatedByID" ] = $this->objAdminSess->MemberID;
                $arrParams[ "d_mail_history_UpdatedByID" ] = $this->objAdminSess->MemberID;
            } else {
                $arrParams[ "d_mail_history_CreatedByID" ] = 0;
                $arrParams[ "d_mail_history_UpdatedByID" ] = 0;
            }
            $arrParams[ "d_mail_history_DelFlg" ] = "0";
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
     * @param   integer $iMailHistoryID メール履歴ID(PK)
     * @param   bool    $bIsAdmin   管理画面からの登録
     * @return  self
     */
    public function delete($iMailHistoryID, $bIsAdmin = true) {

        try {
            if ($iMailHistoryID == "" || $iMailHistoryID < 0)
                throw new Zend_Exception('$iMailHistoryIDを指定してください。');

            $stTable = $this->getTableName();
            // 更新内容
            $arrParams[ "d_mail_history_DelFlg" ] = "1";
            $arrParams[ "d_mail_history_UpdatedTime" ] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams[ "d_mail_history_UpdatedByID" ] = $this->objAdminSess->MemberID;
            } else {
                $arrParams[ "d_mail_history_UpdatedByID" ] = 0;
            }
            // 更新条件
            $arrWhere[ "d_mail_history_MailHistoryID = ?" ] = $iMailHistoryID;
            $arrWhere[ "d_mail_history_DelFlg = ?" ]  = "0";
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
            $objSelect->columns('COUNT(DISTINCT ' .  "`$stAlias`" . '.`d_mail_history_MailHistoryID`) AS cnt');
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
     * setSearchCondition()でセットされた検索条件を元に、
     * 検索処理を実行して件数を返す。
     * 
     * @return  array               検索結果
     */
    public function getSearchTotalCount() {
        
        try {
            if (is_object($this->getSearchCondition())) {
                $objSelect = $this->getSearchCondition();
            } else {
                throw new Zend_Exception("検索条件を指定してください。");
            }
            // 合計件数
//            $this->totalCount = count($this->objSlaveDb->query($objSelect)->fetchAll());
//            $objSelect = &$this->objSlaveDb->select()->from(
//                array($this->getTableName()), array(new Zend_Db_Expr("count(*) AS OCount")));
            
            $arrTotalCount = $this->objSlaveDb->query($objSelect)->fetch();

            if (count($arrTotalCount) == 1) {
                $this->totalCount = intval($arrTotalCount["OCount"]);
            } else {
                $this->totalCount = count($arrTotalCount);
            }
            return;
            
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
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列 
     * @return  self
     */
    public function setSearchCondition($arrSearchCondition, $arrColumn = array("*")) {

        // 初期設定
        $objSelect = &$this->objSlaveDb->select()->from(array("mh" => $this->getTableName()), $arrColumn);
        // 顧客テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("c" => "d_customer"),
                "mh.d_mail_history_CustomerID = c.d_customer_CustomerID", array());

        // メール履歴ID
        if ($arrSearchCondition[ "d_mail_history_MailHistoryID" ]) {
            $objSelect->where('mh.d_mail_history_MailHistoryID = ?', $arrSearchCondition[ "d_mail_history_MailHistoryID" ]);
        }
        // メールテンプレートID
        if ($arrSearchCondition[ "d_mail_history_TemplateID" ]) {
            $objSelect->where('mh.d_mail_history_TemplateID = ?', $arrSearchCondition[ "d_mail_history_TemplateID" ]);
        }

        // 注文管理ID
        if ($arrSearchCondition[ "d_mail_history_OrderMngID" ]) {
            $objSelect->where('mh.d_mail_history_OrderMngID = ?', $arrSearchCondition[ "d_mail_history_OrderMngID" ]);
        }

        // 受注ID
        if ($arrSearchCondition[ "d_mail_history_OrderID" ]) {
            $objSelect->where('mh.d_mail_history_OrderID = ?', $arrSearchCondition[ "d_mail_history_OrderID" ]);
        }

        // 顧客ID        
        $stFormKey = "d_mail_history_CustomerID";
        $stDBKey = "d_mail_history_CustomerID";
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
                $objSelect->where("mh.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                   $objSelect->where("mh.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("mh.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("mh.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }

        // 顧客名
        $stKey = "d_mail_history_CustomerName";
        $stOriginalKey = "d_mail_history_CustomerName";
        if ($arrSearchCondition[ $stKey ] != "") {
            $stColumn = $stOriginalKey;
            $objSelect->where("mh.$stColumn LIKE ? ", "%" . $arrSearchCondition[ $stKey ] . "%");
        }

        // メールタイトル
        $stKey = "d_mail_history_Title";
        $stOriginalKey = "d_mail_history_Title";
        if ($arrSearchCondition[ $stKey ]) {
            $stColumn = $stOriginalKey;
            $objSelect->where("mh.$stColumn LIKE ? ", "%" . $arrSearchCondition[ $stKey ] . "%");
        }

        // 配信日
        $stDBKey = "d_mail_history_SendDate";
        if ($arrSearchCondition["post_from_Year"] != "" && $arrSearchCondition["post_from_Month"] != "" && 
            $arrSearchCondition["post_from_Day"] != "") {
            $objSelect->where("mh.$stDBKey >= ?", $arrSearchCondition["post_from_Year"] . "-" . 
                $arrSearchCondition["post_from_Month"] . "-" . $arrSearchCondition["post_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["post_to_Year"] != "" && $arrSearchCondition["post_to_Month"] != "" && 
            $arrSearchCondition["post_to_Day"] != "") {
            $objSelect->where("mh.$stDBKey <= ?", $arrSearchCondition["post_to_Year"] . "-" . 
                $arrSearchCondition["post_to_Month"] . "-" . $arrSearchCondition["post_to_Day"] . " 23:59:59");
        }
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("mh.d_mail_history_DelFlg = ? ", 0);

        // 表示順
        $objSelect->order("mh.d_mail_history_SendDate DESC");

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
     * 第一引数に紐づく最大値データを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     *
     * @param   array   $stMaxColumn  取得対象のカラム名
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @return  array   $arrResult    検索結果
     */
    public function max($stMaxColumn, $arrColumn = "", $arrWhere = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }

            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("max(" . $stMaxColumn . ")")));
            $objSelect->where("d_mail_history_DelFlg = ? ", 0);

            foreach ($arrWhere as $key => $value) {
                $objSelect->where($key . " = ? ", $value);
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
}
