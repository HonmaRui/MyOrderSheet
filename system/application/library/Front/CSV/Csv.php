<?php

class Csv {
    
    private $arrErrorLog = array();
    
    // 初期化
    public function __construct() {
        // Query
//        $this->objQuery = new Query();
    }
    
    public function init() {
        // default_ini.phpファイルのクラス定義
//        $this->objIni = new ini();
        // ini -> init() 実行(Partsファイルの読み込みとクラスのグローバル変数化)
//        $Parts = $this->objIni->init();
        // BasicCheck
        $this->objBasicCheck = new BasicCheck();
        // メッセージ
        $this->objMessage = new Message();
        // debug
//        $this->objDebug = new Debug();
        // Format
        $this->objFormat = new Format();
        // ErrorCheck
//        $this->objErrorCheck = new ErrorCheck();
        // Product
//        $this->objProduct = new Product();
        // Check
//        $this->objCheck = new Check();
        // Order
//        $this->objOrder = new Order();
    }
    
    
// ----------------- CSV出力項目設定 -------------------------------------
   /***
    * "d_csv"テーブルからカテゴリID指定でデータを取得
    */    
    public function getCsvOutputColumn( $iCategoryID ) {
        try {
            $this->Init();
            if( $this->objBasicCheck->isSetStrings( $iCategoryID )) {
                // 取得対象カラムを取得
                $arrColumn = array( "*" );
                // 条件配列定義
                $arrWhere = array( "d_csv_CategoryID" => $iCategoryID,
                                   "d_csv_Status" => 1 );
                // クエリ実行
                $arrReturn = $this->objQuery->doSelect( $arrColumn, $arrWhere, "d_csv", null, array( "d_csv_Rank" ));
                if( $this->objBasicCheck->isSetArray( $arrReturn )) {
                    $arrReturn = $this->objFormat->initFormData("d_csv", $arrReturn, $reverse = false);
                    return $arrReturn;
                } else {
                    return false;
                }
            } else {
                throw new Zend_Exception( "$iCategoryIDが空です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
   /***
    * 該当CSV出力カテゴリで使用するテーブルを取得する
    */    
    public function getUseTableList( $iCategoryID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iCategoryID )) throw new Zend_Exception( "$iCategoryIDが空です" );
                
            switch( $iCategoryID ) {
                // 商品
                case "1":
                    $arrReturn = array(  "d_product", "d_product_class", "d_product_class_type", "d_product_detail", "d_product_thumbnail", "d_product_size_detail" );
                    break;
                case "2":
                    $arrReturn = array(  "d_customer" );
                    break;
                case "3":
                    $arrReturn = array( "d_order_master", "d_order_details", "d_order_purchaser", "d_order_destination", "d_order_deliver" );
                    break;
                default:
                    throw new Zend_Exception( "指定のCategoryIDがテーブルに存在しません");
                    break;
            }
            return $arrReturn;
            
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * "d_csv"テーブルからID指定で名前を取得
    */    
    public function getCsvColumnNameFromID( $iCsvID ) {
        try {
            $this->Init();
            if( $this->objBasicCheck->isSetStrings( $iCsvID )) {
                // 取得対象カラムを取得
                $arrColumn = array( "d_csv_Name" );
                // 条件配列定義
                $arrWhere = array( "d_csv_CsvID" => $iCsvID );
                // クエリ実行
                $arrReturn = $this->objQuery->doSelectOne( $arrColumn, $arrWhere, "d_csv", null, array( "d_csv_Rank" => "ASC" ) );
                if( $this->objBasicCheck->isSetArray( $arrReturn )) {
                    return $arrReturn["d_csv_Name"];
                } else {
                    return false;
                }
            } else {
                throw new Zend_Exception( "$iCsvIDが空です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * "d_csv"テーブルからID指定で名前を取得
    */    
    public function getCsvNameList() {
        try {
            $this->Init();
            // 取得対象カラムを取得
            $arrColumn = array( "d_csv_Name", "d_csv_Column" );
            $arrWhere = null;
            // クエリ実行
            $arrGet = $this->objQuery->doSelect( $arrColumn, $arrWhere, "d_csv", null );
            if( $this->objBasicCheck->isSetArray( $arrGet )) {
                foreach( $arrGet as $key => $val ) {
                    $stKey = $val["d_csv_Column"];
                    $arrReturn[ $stKey ] = $val[ "d_csv_Name" ];
                }
                return $arrReturn;
            } else {
                throw new Zend_Exception( "名前データの取得に失敗しました" );
            }
        } catch(Zend_Exception $e) {
             throw new Zend_Exception($e->getMessage());
       }
    }
    
    
    public function getCsvNameFromColumn( $stColumn ) {
        try {
            $this->Init();
            if( $this->objBasicCheck->isSetStrings( $stColumn )) {
                // 取得対象カラムを取得
                $arrColumn = array( "d_csv_Name" );
                // 条件配列定義
                $arrWhere = array( "d_csv_Column" => $stColumn );
                // クエリ実行
                $arrReturn = $this->objQuery->doSelectOne( $arrColumn, $arrWhere, "d_csv", null, array( "d_csv_Rank" ));
                if( $this->objBasicCheck->isSetArray( $arrReturn )) {
                    return $arrReturn["d_csv_Name"];
                } else {
                    return false;
                }
            } else {
                throw new Zend_Exception( "$stColumnが空です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定のマスターをIDから取得
    */
    public function getCsvSettingNameFromID( $iSettingID ) {
        try {
            $this->Init();
            if( $this->objBasicCheck->isSetStrings( $iSettingID )) {
                $arrColumn = array( "d_csv_settings_master_SettingName" );
                $arrWhere = array( "d_csv_settings_master_SettingID" => $iSettingID );
                $arrReturn = $this->objQuery->doSelectOne( $arrColumn, $arrWhere, "d_csv_settings_master" );
                if( $this->objBasicCheck->isSetArray( $arrReturn )) {
                    return $arrReturn["d_csv_settings_master_SettingName"];
                } else {
                    return false;
                }
            } else {
                throw new Zend_Exception( "$iSettingIDが空です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /**
    *
    */
    public function getCsvSettingMasterList( $iCategoryID ) {
        try {
            $this->Init();
            if( $this->objBasicCheck->isSetStrings( $iCategoryID )) {
                $arrColumn = array( "*" );
                $arrWhere = array( "d_csv_settings_master_CategoryID" => $iCategoryID );
                $arrReturn = $this->objQuery->doSelect( $arrColumn, $arrWhere, "d_csv_settings_master" );
                if( $this->objBasicCheck->isSetArray( $arrReturn )) {
                    $arrReturn = $this->objFormat->initFormData("d_csv_settings_master", $arrReturn, $reverse = false);
                    return $arrReturn;
                } else {
                    return false;
                }
            } else {
                throw new Zend_Exception( "$iCategoryIDが空です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定の詳細設定をIDから取得
    */
    public function getCsvSettingFromID( $iSettingID ) {
        try {
            $this->Init();
            if( $this->objBasicCheck->isSetStrings( $iSettingID )) {
                $arrColumn = array( "*" );
                $arrWhere = array( "d_csv_settings_SettingID" => $iSettingID );
                $arrReturn = $this->objQuery->doSelect( $arrColumn, $arrWhere, "d_csv_settings", null, array( "d_csv_settings_Rank" ));
                if( $this->objBasicCheck->isSetArray( $arrReturn )) {
                    $arrReturn = $this->objFormat->initFormData("d_csv_settings", $arrReturn, $reverse = false);
                    return $arrReturn;
                } else {
                    return false;
                }
            } else {
                throw new Zend_Exception( "$iSettingIDが空です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力セッテイング登録の為の配列を生成
    */    
    public function getSetData( $iSettingID, $arrSetColumn, $arrTableSetting ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です" );
            if( !$this->objBasicCheck->isSetArray( $arrSetColumn )) throw new Zend_Exception( "$arrSetColumnが空です" );
            if( !$this->objBasicCheck->isSetArray( $arrTableSetting )) throw new Zend_Exception( "$arrTableSettingが空です" );
            // 配列の格納順にRANK値を指定する
            $iRank = 0;
            // 各テーブル名で調査
            foreach( $arrSetColumn as $key => $stColumnName ) {
                foreach( $arrTableSetting as $k => $stTableName ) {
                    // カラム名からテーブル名文字列を取り除く（テーブル名が一致しなければfalse）
                    $stGetColumn = $this->getCsvColumnNonTableName( $stTableName, $stColumnName );
                    if( $stGetColumn ) {
                        $arrReturn[$key]["SettingID"] = $iSettingID;
                        $arrReturn[$key]["TableName"] = $stTableName;
                        $arrReturn[$key]["Column"] = $stGetColumn;
                        $arrReturn[$key]["Rank"] = $iRank;
                    }
                }
                $iRank++;
            }
            if( isset( $arrReturn )) {
                return $arrReturn;
            } else {
                throw new Zend_Exception( "設定登録情報の生成に失敗しました" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定を登録する
    */    
    public function insertCsvSetting( $arrData ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetArray( $arrData )) throw new Zend_Exception( "$arrDataが配列以外です。" );
            foreach( $arrData as $key => $arrInsert ) {
                // ----------------------------- 登録処理を実行
                $arrInsert = $this->objFormat->initFormData("d_csv_settings", $arrInsert, $reverse = true);
                $iNewSettingID = $this->objQuery->doInsert( $arrInsert , null, "d_csv_settings" );
                if( $iNewSettingID == "" ) {
                    throw new Zend_Exception('設定登録処理に失敗しました。');
                }
            }
            return true;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    
   /***
    * CSV出力設定を更新する
    */    
    public function updateCsvSetting( $arrData, $iSettingID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetArray( $arrData )) throw new Zend_Exception( "$arrDataが空です。" );
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です。" );
            $this->deleteCsvSetting( $iSettingID );
            $this->insertCsvSetting( $arrData, $iSettingID );
            return true;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定を削除する
    */
    public function deleteCsvSetting( $iSettingID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です。" );
            $this->objQuery->doDelete( array( $iSettingID ), "d_csv_settings" );
            return true;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定のマスター情報を登録する
    */    
    public function insertCsvSettingMaster( $stSettingName, $iCategoryID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $stSettingName )) throw new Zend_Exception( "$stSettingNameが空です。" );
            if( !$this->objBasicCheck->isSetStrings( $iCategoryID )) throw new Zend_Exception( "$iCategoryIDが空です。" );
            // ----------------------------- 登録処理を実行
            $arrInsert["SettingName"] = $stSettingName;
            $arrInsert["CategoryID"] = $iCategoryID;
            $arrInsert = $this->objFormat->initFormData("d_csv_settings_master", $arrInsert, $reverse = true);
            $iNewSettingID = $this->objQuery->doInsert( $arrInsert , null, "d_csv_settings_master" );
            if( $iNewSettingID == "" ) {
                throw new Zend_Exception('設定登録処理に失敗しました。');
            }
            return $iNewSettingID;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定のマスター情報を更新する
    */    
    public function updateCsvSettingMaster( $stSettingName, $iSettingID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $stSettingName )) throw new Zend_Exception( "$stSettingNameが空です。" );
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です。" );
            // ----------------------------- 登録処理を実行
            $arrInsert["SettingName"] = $stSettingName;
            $arrInsert = $this->objFormat->initFormData("d_csv_settings_master", $arrInsert, $reverse = true);
            $arrWhere = array( "d_csv_settings_master_SettingID" => $iSettingID );
            $iNewSettingID = $this->objQuery->doUpdate( $arrInsert , null, "d_csv_settings_master", $arrWhere );
            //if( $iNewSettingID ) {
            //    throw new Zend_Exception('設定登録処理に失敗しました。');
           // }
            return $iNewSettingID;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定のマスター情報を更新する
    */    
    public function deleteCsvSettingMaster( $iSettingID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です。" );
            $this->objQuery->doDelete( array( $iSettingID ), "d_csv_settings_master" );
            return true;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定のマスター情報を削除する
    */    
    public function getHtmlOptionsFromDefault( $iCategoryID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iCategoryID )) throw new Zend_Exception( "$iCategoryIDが空です" );
            $arrDefault = $this->getCsvOutputColumn( $iCategoryID );
            if( $this->objBasicCheck->isSetArray( $arrDefault )) {
                foreach( $arrDefault as $key => $val ) {
                    $stColumn = $val["Column"];
                    $stName = $val["Name"];
                    $arrReturn[ $stColumn ] = $stName;
                }
                return $arrReturn;
            } else {
                throw new Zend_Exception( "Csv出力設定取得に失敗しました。" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * 登録ボックスに表示する内容を設定から取得する
    */    
    public function getHtmlOptionsFromSetting( $iSettingID ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です" );
            $arrSetting = $this->getCsvSettingFromID( $iSettingID );
            if( $this->objBasicCheck->isSetArray( $arrSetting )) {
                foreach( $arrSetting as $key => $val ) {
                    $stColumn = $val["TableName"] . "_" . $val["Column"];
                    $stName = $this->getCsvNameFromColumn( $stColumn );
                    $arrReturn[ $stColumn ] = $stName;
                }
                return $arrReturn;
            } else {
                throw new Zend_Exception( "Csv出力設定取得に失敗しました。" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * 登録ボックスに表示する内容をFORM値から取得する
    */ 
    public function getHtmlOptionsFromForm( $arrColumn ) {
        try {
            $this->Init();
            if( $this->objBasicCheck->isSetArray( $arrColumn )) {
                foreach( $arrColumn as $key => $stColumn ) {
                    $stName = $this->getCsvNameFromColumn( $stColumn );
                    $arrReturn[ $stColumn ] = $stName;
                }
                return $arrReturn;
            } else {
                return array();
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * 指定IDのCSV出力設定が存在するかチェック
    */ 
    public function checkSettingExists( $iSettingID ) {
        try{
            $this->Init();
            if( $this->objBasicCheck->isSetStrings( $iSettingID )) {
                $arrSetting = $this->getCsvSettingFromID( $iSettingID );
                if( $this->objBasicCheck->isSetArray( $arrSetting )) {
                    return true;
                }
            }
            return false;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力設定IDのA_I値を取得する
    */ 
    public function getInsertSettingID() {
        try{
            $this->Init();
            $iMaxSettingID = $this->objQuery->doSelectMax( "d_csv_settings_master_SettingID", "d_csv_settings_master" );
            if( $this->objBasicCheck->isSetStrings( $iMaxSettingID )) {
                $iSetID = $iMaxSettingID + 1;
                return $iSetID;
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /*** !! FIXME !!!
    * カラム名からテーブル名文字列を取り除く
    */    
    public function getCsvColumnNonTableName( $stTableName, $stColumnName ) {
        try {
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $stTableName )) throw new Zend_Exception( "$stTableNameが空です" );
            if( !$this->objBasicCheck->isSetStrings( $stColumnName )) throw new Zend_Exception( "$stColumnNameが空です" );
            $stReturn = "";
            $stReplaceStrings = $stTableName . "_";
            $stRegEx = "/^" . $stReplaceStrings . "/";
            if( preg_match( $stRegEx, $stColumnName )) {
                $stReturn = str_replace( $stReplaceStrings, "", $stColumnName );
            }
            if( $this->objBasicCheck->isSetStrings( $stReturn )) {
                return $stReturn;
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
// ----------------- CSV出力実行 -------------------------------------    
   /***
    * CSV出力元の検索結果をセットする
    */    
    public function setExportResultData( &$arrSearchResult ) {
        try{
            $this->Init();
            if(!$this->objBasicCheck->isSetArray($arrSearchResult)) {
                throw new Zend_Exception( "$arrSearchResultが配列以外です");
            }
            // 出力する検索結果をグローバル変数へセット
            $this->arrSearchResult = $arrSearchResult;
            
            return true;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * CSV出力元の検索結果を取得する
    */ 
    public function getExportResultData() {
        try{
            $this->Init();
            $arrReturn = $this->arrSearchResult;
            if( $this->objBasicCheck->isSetArray( $arrReturn )) {
                return $arrReturn;
            } else {
                throw new Zend_Exception("$arrReturnが配列以外です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * 検索結果をCSVとしてエクスポートする
    */
    public function DownloadCsvData( $iSettingID ) {
        try{
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが配列以外です" );
            // CSV出力文字列
            $stCsvData = "";
            // 項目名リストを取得
            $this->arrCsvName = $this->getCsvNameList();

            //検索結果の分類IDの未定義情報「empty」を空文字列に変換する
            $this->removeEmptyStrings();

            // 出力するカラムを配列で取得
            $arrColumn = $this->getCsvColumnFromSettingID( $iSettingID );
            
            // 出力CSV文字列に項目名を追加
            $stCsvData = $this->setCsvHeader( $stCsvData, $arrColumn );
            // 出力CSV文字列に出力データを追加
            $stCsvData = $this->setCsvData( $stCsvData, $arrColumn );

            // Csvをダウンロードする
            $this->OutputCsv( $stCsvData );
            return true;

        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * 商品検索結果上で、分類IDの未定義情報「empty」を空文字列に変換する
    */    
    public function removeEmptyStrings() {
        try{
            $this->Init();
            if( !$this->objBasicCheck->isSetArray( $this->arrSearchResult )) throw new Zend_Exception( "$arrSearchResultが空です" );
            foreach( $this->arrSearchResult as $key => $val ) {
                $stKey = "d_product_class_TypeIDs";
                if( array_key_exists( $stKey, $val ) && $val[$stKey] == "empty" ) {
                    $this->arrSearchResult[$key][$stKey] = "";
                }
                $stKey = "d_order_details_TypeID";
                if( array_key_exists( $stKey, $val ) && $val[$stKey] == "empty" ) {
                    $this->arrSearchResult[$key][$stKey] = "";
                }
            }
            return true;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    // FIXME
    public function complementTimeStamp( &$arrSearchResult ) {
        try{
            $this->init();
            if( $this->objBasicCheck->isSetArray( $arrSearchResult )) {
                foreach( $arrSearchResult as $key => $val ) {
                    foreach( $val as $k => $v ) {
                        if( $v == "0000-00-00 00:00:00" || $v == "0000-00-00" || $v == "00:00:00" ) {
                            $arrSearchResult[$key][$k] = "";
                        }
                    }
                }
                return $arrSearchResult;
            } else {
                return false;
            }
            
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    // FIXME
    public function clearZeroID( &$arrSearchResult, $arrClearZeroColumn ) {
        try{
            $this->init();
            if( $this->objBasicCheck->isSetArray( $arrSearchResult )) {
                foreach( $arrSearchResult as $key => $val ) {
                    foreach( $val as $k => $v ) {
                        if( in_array( $k, $arrClearZeroColumn )) {
                            if( $val[$k] == "0" ) {
                                $arrSearchResult[$key][$k] = "";
                            }
                        }
                    }
                }
                return $arrSearchResult;
            } else {
                return false;
            }
            
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * 出力CSV文字列に項目名を追加
    * $bB2　B2連携ファイル = true
    */    
    public function setCsvHeader( $stCsvData, $arrColumn ,$bB2 = false) {
        try{
            $this->Init();
            if(!$this->objBasicCheck->isSetArray( $arrColumn )) {
                throw new Zend_Exception('$arrColumnが配列以外です');
            }
            foreach ($arrColumn as $key => $val) {
                $val = str_replace('"', "'", $val);
                $stCsvData .= '"' . $this->arrCsvName[$val] . '"' . ",";
            }
            $stCsvData = substr($stCsvData, 0, -1);
            //$stCsvData .= "\n";
            if ($bB2) {
                $stCsvData .= "\r\n"; // 2014/09/29
            } else {
                $stCsvData .= "\n";
            }
            return $stCsvData;

        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

   /***
    * 出力CSV文字列に項目名を追加
    * 
    * この関数の呼び出し元では、カラム名が重複してもよいように、カラム接頭辞に[1]、[2]…等、順番を付加していることが前提
    * 
    */    
    public function setCsvHeaderEx( $stCsvData, $arrColumn ) {
        
        try{
            $iIndex = 1;
            foreach ($arrColumn as $key => $val) {
                $val = str_replace('"', "'", $val);
                $stCsvData .= '"' . $this->arrCsvName["[" . $iIndex . "]" .$val] . '"' . ",";
                $iIndex++;
            }
            $stCsvData = substr($stCsvData, 0, -1);
            $stCsvData .= "\n";
            
            return $stCsvData;

        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }    
    
   /***
    * 出力CSV文字列に出力データを追加
    * $bB2　B2連携ファイル = true
    */ 
    public function setCsvData(&$stCsvData, $arrColumn ,$bB2 = false) {
        try{
            $this->Init();
            if( !$this->objBasicCheck->isSetArray( $arrColumn )) {
                throw new Zend_Exception( "$arrColumnが配列以外です" );
            }
            
            // 出力元の検索結果をグローバル変数から取得
            $arrSetData = $this->getExportResultData();
            foreach ($arrSetData as $key => $arrRow) { 
                foreach ($arrColumn as $k => $v) {
                    //$arrRow[ $v ] = str_replace( ",", "/", $arrRow[ $v ] );
                    $arrRow[$v] = str_replace('"', "'", $arrRow[$v]);
                    $stCsvData .= '"' . $arrRow[$v] . '"' . ","; 
                }
                $stCsvData = substr($stCsvData, 0, -1);
                //$stCsvData .= "\n";
                if ($bB2) {
                    $stCsvData .= "\r\n"; // 2014/09/29
                } else {
                    $stCsvData .= "\n";
                }
            }
            
            return $stCsvData;
            
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***
    * 出力するカラムを配列で取得
    */    
    public function getCsvColumnFromSettingID( $iSettingID ) {
        try{
            $this->Init();
            
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが配列以外です" );
            // CSV出力設定を取得
            $arrSetting = $this->getCsvSettingFromID( $iSettingID );
            // カラム名を取得
            foreach( $arrSetting as $key => $val ) {
                $arrReturn[] = $val["TableName"] . "_" . $val["Column"];
            }
            return $arrReturn;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
   /***
    * Selectクエリのためのカラム名を取得
    * ex) 「p.d_product_ProductID」
    */    
    public function getTargetColumn( $iSettingID ) {
        try{
            $this->Init();
            // テーブルIdsをグローバル変数にセット
            $arrTableIds = $this->objIni->GloarrTableIds;
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です" );
            // 出力設定情報を取得
            $arrSetting = $this->getCsvSettingFromID( $iSettingID );
            // 省略テーブル名.カラム名 の文字列を取得する
            if( $this->objBasicCheck->isSetArray( $arrSetting )) {
                foreach( $arrSetting as $key => $value ) { 
                    $stTable = $value["TableName"];
                    $stTableID = $arrTableIds[ $stTable ];
                    $stColumn =  $stTable . "_" . $value["Column"];
                    $arrReturn[] = $stTableID .".". $stColumn;
                }
            } else {
                throw new Zend_Exception( "CSV出力設定取得に失敗しました。" );
            }
            return $arrReturn;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***　FIXME
    * CSV出力カラムを取得
    * TODO： 検索ロジック改修後、上2つの関数と統合する
    */    
    public function getSearchColumns( $iSettingID ) {
        try{
            $this->Init();
            if( !$this->objBasicCheck->isSetStrings( $iSettingID )) throw new Zend_Exception( "$iSettingIDが空です" );
            // CSV出力設定を取得
            $arrSetting = $this->getCsvSettingFromID( $iSettingID );
            
            foreach( $arrSetting as $key => $val ) {
                $stKey = $val["TableName"] . "_" . $val["Column"];
                $arrReturn[$stKey] = "";
            }

            return $arrReturn;
            
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /***　FIXME
    * Orderの検索結果を出力のために変換
    */    
    public function ExpansionOrderResult( &$arrResult ) {
        try{
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrResult )) throw new Zend_Exception( "$arrResultが空です" );
            $iCount = 0;
            
            foreach( $arrResult as $iRowKey => $arrRowData ) {
                
                $arrSetDetailData = array();
                foreach( $arrRowData["Details"] as $key => $val ) {
                    foreach( $val as $k => $v ) {
                        $arrSetDetailData[$key][$k] = $v;
                    }
                }
                
                $arrDeliver = $this->objOrder->getOrderDeliverData( $arrRowData["Master"]["d_order_master_OrderID"] );
                
                foreach( $arrSetDetailData as $key => $val ) {
                    foreach( $arrRowData["Master"] as $k => $v ) {
                        $arrReturnResult[$iCount][$k] = $v;
                    }
                    foreach( $arrRowData["Regular"][$key] as $k => $v ) {
                        $arrReturnResult[$iCount][$k] = $v;
                    }
                    foreach( $arrDeliver as $k => $v ) {
                        $arrReturnResult[$iCount][$k] = $v;
                    }
                    foreach( $val as $k => $v ) {
                        $arrReturnResult[$iCount][$k] = $v;
                    }
                    $iCount++;
                }
            }
            return $arrReturnResult;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    /**
     * CSV出力項目設定のエラーチェック
     * 引き値：（チェック値, キャプション配列, 言語設定, メッセージ配列)
     */
    public function ErrorCheck( $arrTargetData, $arrCaptionName, $arrErrorMessages = null) {
        try {
            // 初期設定のロード
            $this->init();
            $arrError = array();
            $this->objErrorCheck->setErrorMessages($arrErrorMessages);
            $this->objErrorCheck->setTargetData($arrTargetData);
            $this->objErrorCheck->setCaptionName($arrCaptionName);
            
            /**
             * 設定名
             */
            $stKey = "SettingName";
            $this->objErrorCheck->setCheckType( $stKey, "NotEmpty", null, null);
            $this->objErrorCheck->setCheckType( $stKey, "StringLength", 1, 50);
            $this->objErrorCheck->setCheckType($stKey, "Meta", null, null);
            $this->objErrorCheck->Execute($stKey, $this->objErrorCheck->getCheckType($stKey));
            
            // エラーメッセージ取得
            $arrErrMsg = $this->objErrorCheck->getError();
            
            // !! FIXME !!
            if( !$this->objBasicCheck->isSetArray( $arrTargetData["SelectColumn"] )) {
                $arrErrMsg["SelectColumn"] = "出力項目を１つ以上選択してください。";
            }
            
            if( $this->objBasicCheck->isSetArray( $arrErrMsg )) {
                return $arrErrMsg;
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    public function OutputCsv( $stOutput, $stFileName ) {
        try {
            // 初期設定のロード
            $this->init();
            if( $this->objBasicCheck->isSetStrings( $stOutput )) {
                $log = $stOutput;
                $path = DATA_PATH . "upload/csv/";
                $file_name = $stFileName . date(ymdhis) . ".csv";
                $file_path = $path . $file_name;
                
                $log = mb_convert_encoding($log, "sjis-win", "UTF-8");
                
                header('Content-Description: File Transfer');
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=$file_name");
                header('Pragma: public');
                ob_clean();
                flush();
                echo $log;
                // フロントでCSVをダウンロードすることはないため
                // セッション情報の保存(setSessionToDB)はコール不要
                exit;
            } else {
                throw new Zend_Exception( "$stOutputが空です" );
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    
    // ------------------------------------------ CSV一括登録処理
    
    
   /**
    * ファイルの中身をチェックし、配列で取得する
    */
    function getCsvData( $stUploadColumn ) {
    
        // ファイルがアップされたかのチェック
        $csvfile = $_FILES[ $stUploadColumn ]["tmp_name"];
        if(is_uploaded_file($csvfile)) {
        
            // ファイル名をPOST値から取得
            $stFileName = $_FILES[ $stUploadColumn ]["name"];
            
            // サーバにうpされたTmpファイルをfopenで展開
            $stTarget = $_FILES[ $stUploadColumn ]['tmp_name'];
            $handle = fopen($stTarget,"r");
            
            // ループで回してCSVデータを配列に格納する
            while(($lines = $this->fgetcsv_reg($handle)) !== FALSE) {
                //csvファイルを配列に格納します。
                //カンマ区切り（「,]）の場合です。
                foreach( $lines as $key => $val ) {
                    $lines[$key] = mb_convert_encoding($val, "UTF-8", "sjis-win");
                }
                $arrCsvData[] = $lines;
                //$stCsvData = $stCsvData.implode(",",$lines)."\n";
            }
        }
        return $arrCsvData;
    }
    
   /**
    * ファイルの中身をチェックし、配列で取得する
    */
    function getCsvDataForProductCSV( $stUploadColumn ) {
    
        // ファイルがアップされたかのチェック
        $csvfile = $_FILES[ $stUploadColumn ]["tmp_name"];
        if(is_uploaded_file($csvfile)) {
        
            // ファイル名をPOST値から取得
            $stFileName = $_FILES[ $stUploadColumn ]["name"];
            
            // サーバにうpされたTmpファイルをfopenで展開
            $stTarget = $_FILES[ $stUploadColumn ]['tmp_name'];
            $handle = fopen($stTarget,"r");
            
            // ループで回してCSVデータを配列に格納する
            $iCount = 1;
            while(($lines = $this->fgetcsv_reg($handle)) !== FALSE) {
                //csvファイルを配列に格納します。
                //カンマ区切り（「,]）の場合です。
                // BOM削除処理
                if ($iCount == 1) {
                    $lines[0] = "商品ID";
                }
                $iCount++;
                
                foreach( $lines as $key => $val ) {
                    $stValue = $val;
                    $stValue = str_replace("\r\n", "<br>", $stValue);
                    $stValue = str_replace("\n", "<br>", $stValue);
                    $stValue = str_replace("\r", "<br>", $stValue);
                    $lines[$key] = $stValue;
                }
                
                $arrCsvData[] = $lines;
            }
        }

        return $arrCsvData;
    }
    
   /**
    * ファイルの中身をチェックし、配列で取得する
    */
    function getCsvDataForProductCategory( $stUploadColumn ) {
    
        // ファイルがアップされたかのチェック
        $csvfile = $_FILES[ $stUploadColumn ]["tmp_name"];
        if(is_uploaded_file($csvfile)) {
        
            // ファイル名をPOST値から取得
            $stFileName = $_FILES[ $stUploadColumn ]["name"];
            
            // サーバにうpされたTmpファイルをfopenで展開
            $stTarget = $_FILES[ $stUploadColumn ]['tmp_name'];
            $handle = fopen($stTarget,"r");
            
            // ループで回してCSVデータを配列に格納する
            $iCount = 1;
            while(($lines = $this->fgetcsv_reg($handle)) !== FALSE) {
                //csvファイルを配列に格納します。
                //カンマ区切り（「,]）の場合です。
                // BOM削除処理
                if ($iCount == 1) {
                    $lines[0] = "カテゴリID";
                }
                $iCount++;
                
                foreach( $lines as $key => $val ) {
                    $stValue = $val;
                    $stValue = str_replace("\r\n", "<br>", $stValue);
                    $stValue = str_replace("\n", "<br>", $stValue);
                    $stValue = str_replace("\r", "<br>", $stValue);
                    $lines[$key] = $stValue;
                }
                
                $arrCsvData[] = $lines;
            }
        }

        return $arrCsvData;
    }
    
    
   /**
    * 商品一括登録の為の配列を出力する
    */
    public function setAddCsvRowData( $arrColumns, $arrCsvRowData ) {
        try {
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrColumns )) throw new Zend_Exception( "$arrColumnsが配列以外です" );
            
            $iCount = 0;
            foreach( $arrColumns as $key => $val ) {
                foreach( $val as $k => $v ) {
                    $arrReturn[$key][ $v ] = $arrCsvRowData[ $iCount ];
                    $iCount++;
                }
            }
            return $arrReturn;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /**
    * 商品並べ替えの為の配列を出力する
    */
    public function setSortCsvRowData( $arrColumns, $arrCsvRowData ) {
        try {
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrColumns )) throw new Zend_Exception( "$arrColumnsが配列以外です" );
            
            $iCount = 0;
            foreach( $arrColumns as $key => $val ) {
                $arrReturn[ $val ] = $arrCsvRowData[ $iCount ];
                $iCount++;
            }
            return $arrReturn;
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /**
    * 取得したエラーメッセージを展開する
    */
    public function implodeErrorMsg( $arrErrorMsg ) {
        try{
            if( !$this->objBasicCheck->isSetArray( $arrErrorMsg ))  throw new Zend_Exception( "$arrErrorMsgが空です" );
            $stErrorMsgs = implode( $arrErrorMsg );
            return $stErrorMsgs;
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /**
    * エラーログ情報をセットする
    */
    public function setErrorLog( $stErrorMsg, $iRowNumber, $iColumnNumber = "") {
        try{
            $this->init();
            if( !$this->objBasicCheck->isSetStrings( $stErrorMsg ))  throw new Zend_Exception( "$stErrorMsgが空です" );
            // 該当データに対するエラーメッセージの生成処理
            $stGetError = ( $iRowNumber )."行目 ";
            if ($iColumnNumber != "") {
                $stGetError .= $iColumnNumber . "列目：";
            }
            $stGetError .= $stErrorMsg . "\n";
            $this->arrErrorLog[] = $stGetError;
            return true;
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /**
    * エラーログ情報を取得する
    */
    public function getErrorLog() {
        try{
            if( isset( $this->arrErrorLog ) && $this->objBasicCheck->isSetArray( $this->arrErrorLog )) {
                return $this->arrErrorLog;
            } else {
                return false;
            }
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /**
    * 重複エラーログ情報をセットする
    */
    public function setDuplicatedErrorLog( $arrCsvData ) {
        try{
            $arrGetError = array();
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrCsvData ))  throw new Zend_Exception( "$arrCsvDataが空です" );
            // IDを1次元配列に格納 
            foreach( $arrCsvData as $key => $val ) { 
                $arrChkID[] = (string)$val[0]; 
            } 
            // 同IDが複数検出された場合エラー 
            foreach( $arrChkID as $key => $val ) { 
                $arrHitID = array_keys( $arrChkID, $val );
                if( count( $arrHitID ) > 1 ) { 
                    // エラーログをセット
                    $arrSetMessege = "商品IDが重複しています。";
                    $this->setErrorLog( $arrSetMessege, $key );
                    unset( $arrCsvData[$key] );
                } 
            } 
            return $arrCsvData;
        
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
   /**
    * カンマ(,)区切りの文字列に変換する
    */
    public function ReplaceString( $stValue ) {
        try{
            $this->init();
            if( $this->objBasicCheck->isSetStrings( $stValue )) {
                //$stValue = str_replace( "/", ",", $stValue );
            }
            return $stValue;
            
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    /**
     * 登録商品規格の重複をチェックする
     */    
    public function CheckDuplicatedClass( $arrInsData ) {
        try{
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrInsData ))  throw new Zend_Exception( "$arrInsDataが空です" );
            
            $stColumn = array( "d_product_class_ProductID" );
            $arrWhere = array( "d_product_class_ProductID" => $arrInsData["d_product_class_ProductID"],
                               "d_product_class_TypeIDs" => $arrInsData["d_product_class_TypeIDs"] );
            
            $arrResult = $this->objQuery->doSelect( $stColumn, $arrWhere, "d_product_class" );

            if( $this->objBasicCheck->isSetArray( $arrResult )) {
                return true;
            } else {
                return false;
            }
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    public function blankDataUnset( $arrInsData ) {
        try{
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrInsData ))  throw new Zend_Exception( "$arrInsDataが空です" );
            foreach( $arrInsData as $key => $val ) {
                if( $this->objBasicCheck->isSetStrings( $val ) == false) {
                    unset( $arrInsData[$key] );
                }
            }
            return $arrInsData;
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
                
    
    /**
     * 規格/分類の名前文字列をIDに変換する
     */    
    public function ConvertNameValue( $stIDValue, $arrCollection ) {
        try{
            $this->init();
            if( !$this->objBasicCheck->isSetStrings( $stIDValue ))  throw new Zend_Exception( "$stIDValueが空です" );
            
            $arrID = array();
            $arrName = explode( "/", $stIDValue );

            if( $this->objBasicCheck->isSetArray( $arrName )) {
                foreach( $arrName as $key => $val ) {
                    $iGetID = array_search( $val, $arrCollection );
                    //$iGetID = $this->objProduct->getIDFromName( $val );
                    if( $this->objBasicCheck->isSetStrings( $iGetID )) {
                        $arrID[] = $iGetID;
                    } else {
                        return false;
                    }
                }
            }
            $stReturnIDs = implode( ",", $arrID );
            return $stReturnIDs;
            
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
        
        
    /**
     * データ上書きを行わない空白データをunsetする
     */    
    function setBlankData( $arrInsData, $bBlankEntry ) {
        try{
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrInsData ))  throw new Zend_Exception( "$arrInsDataが空です" );
            if( !$this->objBasicCheck->isSetStrings( $bBlankEntry ))  throw new Zend_Exception( "$bBlankEntryが空です" );
            if( !$bBlankEntry ) {
                foreach( $arrInsData as $key => $val ) {
                    foreach( $val as $k => $v ) {
                        if( !$this->objBasicCheck->isSetStrings( $v )) {
                            unset( $arrInsData[$key][$k] );
                        }
                    }
                }
            }
            return $arrInsData;
            
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    /**
     * 概要: 共通クラス「check」のエラーチェックを実行する
     */
    public function checkInsData( $arrInsData, $stLanguage, $arrCaption ) {
        try{
            if( !$this->objBasicCheck->isSetArray( $arrInsData )) throw new Zend_Exception( "arrInsDataが配列以外です" );
            if( !$this->objBasicCheck->isSetStrings( $stLanguage )) throw new Zend_Exception( "stLanguageが配列以外です" );
            if( !$this->objBasicCheck->isSetArray( $arrCaption )) throw new Zend_Exception( "arrCaptionが配列以外です" );
            // チェックを行うカラムを定義
            $stStatus = "d_product_Status";
            $stShippingDate = "d_product_ShippingDate";
            
            // エラーメッセージ一覧を取得 
            $arrErrMessages = $this->objMessage->getMessage( "product", $stLanguage );
            
            // 各テーブルに関するデータに分け、「check」共通関数のエラーチェックを実行する
            $arrMsgProduct = $this->objCheck->ErrCheck( $arrInsData["d_product"],  $arrCaption, "product", $stLanguage, $arrErrMessages );
            $arrMsgClass = $this->objCheck->ErrCheck( $arrInsData["d_product_class"],  $arrCaption, "product_class", $stLanguage, $arrErrMessages );
            $arrMsgDetail = $this->objCheck->ErrCheck( $arrInsData["d_product_detail"],  $arrCaption, "product_detail", $stLanguage, $arrErrMessages );
            
            // 格納した各配列を一つの配列にまとめる
            $arrErrorMsg = array_merge( $arrMsgProduct, $arrMsgClass, $arrMsgDetail );
            
            return $arrErrorMsg;
        } catch ( Zend_Exception $e ) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    /**
     * 概要: 商品IDをキーにテーブル間を結びつけ
     */
    function setParentID( $arrData, $bBlankEntry ) {
        // 商品IDを定義
        $iParentID = $arrData["d_product"]["d_product_ProductID"];
        
        if( $bBlankEntry == false ) {
            // 商品IDを他テーブルでの親に指定する
            $arrData["d_product_detail"]["d_product_detail_ProductID"] = $iParentID;
            $arrData["d_product_class"]["d_product_class_ProductID"] = $iParentID;
        } 
        
        return $arrData;
    }
    
    
    /**
     * CSVの一括インポートを実行
     */
    public function doProductImport( $arrInsData, $bUpdateFlag, $bClassUpdateFlag ) {
        try{
            $this->init();
            if( !$this->objBasicCheck->isSetArray( $arrInsData ))  throw new Zend_Exception( "$arrInsDataが空です" );
            
            if( $bUpdateFlag ) {
                /*
                $stTable = "d_product";
                if( $this->objBasicCheck->isSetArray( $arrInsData[$stTable] )) {
                    $stKeyColumn = $stTable."_ProductID";
                    $arrWhere = array( $stKeyColumn => $arrInsData[$stTable][$stKeyColumn] );
                    $this->objQuery->doUpdate( $arrInsData[$stTable], null, $stTable, $arrWhere );
                }
                */
                $stTable = "d_product_detail";
                if( $this->objBasicCheck->isSetArray( $arrInsData[$stTable] )) {
                    $stKeyColumn = $stTable."_ProductID";
                    $arrWhere = array( $stKeyColumn => $arrInsData[$stTable][$stKeyColumn] );
                    $this->objQuery->doUpdate( $arrInsData[$stTable], null, $stTable, $arrWhere );
                }
            } else {
                /*
                $stTable = "d_product";
                if( $this->objBasicCheck->isSetArray( $arrInsData[$stTable] )) {
                    $this->objQuery->doInsert( $arrInsData[$stTable], null, $stTable );
                }
                */
                $stTable = "d_product_detail";
                if( $this->objBasicCheck->isSetArray( $arrInsData[$stTable] )) {
                    $this->objQuery->doInsert( $arrInsData[$stTable], null, $stTable );
                }
            }
            /*
            $stTable = "d_product_class";
            if( $this->objBasicCheck->isSetArray( $arrInsData[$stTable] )) {
                $stKeyColumn = $stTable."_ProductID";
                if( $bClassUpdateFlag ) {
                    // 引数の商品IDで未定義の規格が有るかどうか調べる
                    if( $this->objProduct->checkEmptyClass( $arrInsData[$stTable][$stKeyColumn] )) {
                        $arrWhere = array( $stKeyColumn => $arrInsData[$stTable][$stKeyColumn] );
                    } else {
                        $arrWhere = array( $stKeyColumn => $arrInsData[$stTable][$stKeyColumn],
                                           "d_product_class_TypeIDs" => $arrInsData[$stTable]["d_product_class_TypeIDs"] );
                    }
                    $this->objQuery->doUpdate( $arrInsData[$stTable], null, $stTable, $arrWhere );
                    
                } else {
                    // insert実行
                    $this->objQuery->doInsert( $arrInsData[$stTable], null, $stTable );
                }
            }
            */
            return true;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    /**
     * 概要: 登録情報の定義を取得する
     */
    function setAddInformation( $arrCaption ) {
        try{
            $this->init();
            //カラム情報を取得
            $arrColumns = $this->setImportProductColumns();
            
            //登録情報の定義カラム
            $arrAddInfo = array();
            
            //定義したカラムからリストを自動生成　※FIX
            $iDumpCnt = 1;
            foreach( $arrColumns as $key => $val ) {
                foreach( $val as $k => $v ) {
                    //if( array_key_exists( $v, $this->arrCaption )) {
                        $arrAddInfo[] .= $iDumpCnt . "項目 :  " . $arrCaption[$v];
                        $iDumpCnt++;
                    //}
                }
            }
            
            return $arrAddInfo;
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    function setAddColumn( $arrCaption ) {
        try{
            $this->init();
            //カラム情報を取得
            $arrColumns = $this->setImportProductColumns();
            
            //登録情報の定義カラム
            $arrAddColumn = array();
            
            //定義したカラムからリストを自動生成　※FIX
            $iDumpCnt = 1;
            foreach( $arrColumns as $key => $val ) {
                foreach( $val as $k => $v ) {
                    //if( array_key_exists( $v, $this->arrCaption )) {
                        $arrAddColumn[] .= $arrCaption[$v];
                        $iDumpCnt++;
                    //}
                }
            }
            
            return $arrAddColumn;
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    /**
     * 概要: CSVでインポートするカラムを定義
     */
    function setImportProductColumns() {
        $this->init();
        // "d_product"テーブルへの登録を除くカラム
        $arrUnsetColumns = array(
                            "d_product_SingleProductID",
                            "d_product_ShippingIntervalIDs",
                            "d_product_Rank",
                            "d_product_NormalProduct",
                            "d_product_OptionName",
                            "d_product_DelFlg",
                            "d_product_CreateTime",
                            "d_product_UpdateTime"
                           );
        $arrColumns["d_product"] = $this->objQuery->getColumns( "d_product", $arrUnsetColumns );
        
        // "d_produc_class"テーブルへの登録を除くカラム
        $arrUnsetColumns = array(
                            "d_product_class_ProductID",
                            "d_product_class_ClassID",
                            "d_product_class_DelFlg",
                            "d_product_class_CreateTime",
                            "d_product_class_UpdateTime",
                           );
        $arrColumns["d_product_class"] = $this->objQuery->getColumns( "d_product_class", $arrUnsetColumns );
    
        // "d_produc_deteil"テーブルへの登録を除くカラム
        $arrUnsetColumns = array(
                            "d_product_detail_DetailID",
                            "d_product_detail_ProductID",
                            "d_product_detail_ThumbnailIDs",
                            "d_product_detail_DelFlg",
                            "d_product_detail_AuthorTag",
                            "d_product_detail_CreateTime",
                            "d_product_detail_UpdateTime",
                            "d_product_detail_SearchWord",
                            "d_product_detail_URL",
                            //"d_product_detail_TitleTag",
                            //"d_product_detail_H1Tag",
                            //"d_product_detail_DescriptionTag",
                            //"d_product_detail_KeywordTag",
                            //"d_product_detail_Note",
                           );
        $arrColumns["d_product_detail"] = $this->objQuery->getColumns( "d_product_detail", $arrUnsetColumns );
        
        return $arrColumns;
    } 
    
    
    public function getBulkAddSample( $arrCaption ) {
        try{ 
            $stOutput = "";
            $arrColumnInfo = $this->setAddColumn( $arrCaption );
            // 取得した配列データを文字列情報に変換する
            foreach( $arrColumnInfo as $key => $val ) {
                $stOutput .= $val . ",";
            }
            if( $this->objBasicCheck->isSetStrings( $stOutput )) {
                return $stOutput;
            } else {
                return false;
            }
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    /**
     * 概要: PHP標準関数よりも高機能なCSV展開用関数
     */
    function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $i = 0;
        $eof = "";
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
            $i++;
            if($i > 60) {
                $this->arrErr["csvfile"] = "※ CSVファイルのフォーマットが正しくありません。<br>"
                                         . "内容を確認の上、再度アップロードして下さい。<br>"
                                         . "(ファイルが破損している可能性があります)";
                break;
            }
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
           $_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
           $_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }
}