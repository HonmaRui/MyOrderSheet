<?php
/**
 * 共通ライブラリ
 *
 * @category   ECSite
 * @package    Library
 * @subpackage Common
 * @copyright  
 * @author     
 * @version    v1.0
 */

class Common {

    // 有効・無効
    const COMMON_ENABLED = 1;                     // 有効
    const COMMON_DISABLED = 2;                    // 無効

    // クラス定数宣言
    
    // マスタ
    
    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        // Library & Models
        $this->objMessage = new Message();
        $this->mdlBaseInfo = new Application_Model_BaseInfo();
        $this->mdlCustomer = new Application_Model_Customer();
        $this->mdlMailHistory = new Application_Model_MailHistory();
        $this->mdlMailSetting = new Application_Model_MailSetting();
        $this->mdlMember = new Application_Model_Member();
        $this->mdlLog = new Application_Model_Log();

        //System
        $this->objSystem = new System();

        //Validate
        $this->objValidate = new Validate();

        //Session
        $this->objAdminSess = new Zend_Session_Namespace('Admin');
     }
    
    /**
     * 顧客ログイン判定処理
     *
     * @param  string     $stEmailAddress  メールアドレス
     * @param  string     $stPassword      パスワード
     * @return int        $iCustomerID     顧客ID
     * @return Array      $arrErrorMessage エラーメッセージ
     */
    function checkLogin($stEmailAddress, $stPassword) {
        
        // ログイン判定
        $iCustomerID = -1;
        $arrErrorMessage = array();
        $stKey = "d_customer_EmailAddress";
        $stColumnName = "メールアドレス";
        $this->objValidate->Execute(
            array($stKey => $stColumnName), 
            array("NotEmpty", $stEmailAddress));
        $arrErrorMessage = array();
        $stKey = "d_customer_Password";
        $stColumnName = "パスワード";
        $this->objValidate->Execute(
            array($stKey => $stColumnName), 
            array("NotEmpty", $stPassword));
        $arrErrorMessage = $this->objValidate->getResult();
        if (!$arrErrorMessage) {
            $arrCheck = array();
            $arrCheck = $this->mdlCustomer->findAll(array("d_customer_EmailAddress" => $stEmailAddress));
            if (!$arrCheck) {
                $arrErrorMessage = array("d_customer_EmailAddress"=>"このメールアドレスは登録されていません。");
            }
            if (!$arrErrorMessage) {
                if ($arrCheck[0]["d_customer_Password"] !== $stPassword) {
                    $arrErrorMessage = array("d_customer_Password"=>"パスワードが違います。");
                } else {
                    $iCustomerID = $arrCheck[0]["d_customer_CustomerID"];
                }
            }
        }

        return array($iCustomerID, $arrErrorMessage);
    }

    /**
     * 管理者ログイン判定処理
     *
     * @param  string     $stLoginID       ログインID
     * @param  string     $stPassword      パスワード
     * @return int        $iMemberID       管理者ID
     * @return Array      $arrErrorMessage エラーメッセージ
     */
    function checkSystemLogin($stLoginID, $stPassword) {
        
        // ログイン判定
        $iMemberID = -1;
        $arrErrorMessage = array();
        $stKey = "d_system_member_LoginID";
        $stColumnName = "ログインID";
        $this->objValidate->Execute(
            array($stKey => $stColumnName), 
            array("NotEmpty", $stLoginID));
        $arrErrorMessage = array();
        $stKey = "d_system_member_Password";
        $stColumnName = "パスワード";
        $this->objValidate->Execute(
            array($stKey => $stColumnName), 
            array("NotEmpty", $stPassword));
        $arrErrorMessage = $this->objValidate->getResult();
        if (!$arrErrorMessage) {
            $arrCheck = array();
            $arrCheck = $this->mdlMember->findAll("d_system_member_LoginID", $stLoginID);
            if (!$arrCheck) {
                $arrErrorMessage = array("d_system_member_LoginID"=>"このログインIDは登録されていません。");
            }
            if (!$arrErrorMessage) {
                if ($arrCheck[0]["d_system_member_Password"] !== $stPassword) {
                    $arrErrorMessage = array("d_system_member_Password"=>"パスワードが違います。");
                } else {
                    // 稼動チェック
                    if ($arrCheck[0]["d_system_member_Run"] == System::SYSTEM_RUN) {
                        $iMemberID = $arrCheck[0][ "d_system_member_SystemMemberID" ];
                    }
                }
            }
        }

        return array($iMemberID, $arrErrorMessage);
    }

    /**
     * 顧客ログイン登録チェック処理
     *
     * @param  string     $stEmailAddress   メールアドレス
     * @param  string     $stPassword      パスワード
     * @param  string     $iCustomerID     顧客ID ･･･ 新規登録時は不要。更新時は必要。
     * @return boolean    $bEnabled        有効/無効
     * @return Array      $arrErrorMessage エラーメッセージ
     */
    function checkMailAddress($stEmailAddress, $stPassword, $iCustomerID = null) {

        // ログイン判定
        $bEnabled = false;
        $arrErrorMessage = array();
        $stKey = "d_customer_EmailAddress";
        $stColumnName = "メールアドレス";
        $this->objValidate->Execute(
            array($stKey => $stColumnName), 
            array("NotEmpty", $stEmailAddress));
        $arrErrorMessage = array();
        $stKey = "d_customer_Password";
        $stColumnName = "パスワード";
        $this->objValidate->Execute(
            array($stKey => $stColumnName), 
            array("NotEmpty", $stPassword));
        $arrErrorMessage = $this->objValidate->getResult();
        if (!$arrErrorMessage) {
            $arrErrorMessage = array();
            $arrCheck = array();
            $arrCheck = $this->mdlCustomer->findAll(array("d_customer_EmailAddress" => $stEmailAddress));
            if ($arrCheck) {
                if ($iCustomerID) {
                    $bExist = false;
                    // 自分以外の同一IDが存在するかチェック
                    foreach ($arrCheck as $value) {
                        if ($value["d_customer_CustomerID"] != $iCustomerID) {
                            $bExist = true;
                            break;
                        }
                    }
                } else
                    $bExist = true;

                if ($bExist == true) {
                    $arrErrorMessage = array("d_customer_EmailAddress"=>"このメールアドレスは他のお客様が使用されています。");
                } else {
                    $bEnabled = true;
                }
            } else {
                $bEnabled = true;
            }
        }

        return array($bEnabled, $arrErrorMessage);
    }

    /***
     *
     * 現在の顧客ログイン状態をtrue か falseで返すFunction
     *
     *
     */
    public function isLogin(){
        try{
            // Login状態判定フラグ
            $bIsLogin = false;
            // ----------------------------- チェック処理
            if( $_SESSION["Customer"]["Login"] == true && $_SESSION["Customer"]["CustomerID"] != null ){
                $bIsLogin = true;
            }
            
            return $bIsLogin;
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     *
     * 現在の管理者ログイン状態をtrue か falseで返すFunction
     *
     *
     */
    public function isSystemLogin($stRedirectURL = NULL){
        try{
            // Login状態判定フラグ
            $bIsSystemLogin = false;
            // ----------------------------- チェック処理
            if( isset($_SESSION['Admin']) ){
                if( $_SESSION["Admin"]["Login"] == true && $_SESSION["Admin"]["MemberID"] != null ){
                    $this->startAdminSession();
                    $bIsSystemLogin = true;
                }
            } else {
                // ログイン中でない場合は指定されたURLかMypageのログイン画面にリダイレクトさせる
                if( $stRedirectURL != '' ){
                    $this->objHttp->Redirect($stRedirectURL);
                }else{
                    $bIsSystemLogin = false;
                }
            }
            return $bIsSystemLogin;
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     *
     * ログイン成功時にCustomerSessionを
     * スタートさDBのデータをSession内に格納する関数
     *
     * $arrData = ログインに成功し、取得された顧客データ
     *
     *
    */
    function startAdminSession( $arrData = null ) {
        try {
            // ----------------------------- 初期設定のロード
            $this->Init();

            // ----------------------------- メンバ変数定義
            // sessionId
            $stSessionId = "";

            //Session 定義（名前空間定義することでキーの衝突を防ぐ）
            $this->objAdminSess = new Zend_Session_Namespace('Admin');

            // session_idをGlobal変数に格納
            $stSessionID = session_id();

            // ----------------------------- Sessionへのデータ格納処理
            if( !is_null($arrData) ){
                foreach( $arrData[0] as $key => $val ){
                    // Sessionのkeyに共通する文字列部分を削る
                    $Name = str_replace("d_system_member_", "", $key);
                    // Session内にkeyと値を格納する
                    $this->objAdminSess->$Name = $val;
                }
                // ログイン状態のフラグをセット
                $this->objAdminSess->Login = true;
            } else {
                // objCustomerSessにSession情報を格納
                $this->objAdminSess = $_SESSION['Admin'];
            }

            // Sessionが開始されたかチェック
            if( is_null($this->objAdminSess) ){
                // MemberAuthority が管理者の場合は管理者ホーム画面へ遷移させる
                if($this->objAdminSess->Authority == System::SYSTEM_AUTHORITY_SYSTEMMANAGER ||
                    $this->objAdminSess->Authority == System::SYSTEM_AUTHORITY_SITEMANAGER ||
                    $this->objAdminSess->Authority == System::SYSTEM_AUTHORITY_SYSTEMDEVELOPER) {
                    $this->objHttp->Redirect(ADMIN_URL."home");
                } else {
                    $this->objHttp->Redirect(ADMIN_URL."basis");
                }
                //throw new Zend_Exception('Session が正常に開始されませんでした');
            }

        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /*
     *
     * 現在AdminSession内に保持されているデータを返すFunction
     *
    */
    public function getAdminSessionData($bNonTableName = false){
        try{
            $this->Init();
            // ----------------------------- メンバ変数定義
            // returnする為の配列データ
            $arrData = array();

            // ----------------------------- Session取得
            $this->startAdminSession();

            // Session情報をreturnする変数にセット
            $arrSess = $this->objAdminSess;

            // foreachでkeyの名前をカラム名と合致させる
            foreach( $arrSess as $key=>$val ){
                // DelFlg以降のSession情報はカラムとして存在しないのでいらない
                if( $key == "DelFlg" ){
                    break;
                }
                
                if($bNonTableName) {
                    $arrTemp[$key] = $val;
                } else {
                    // Sessionのkeyをカラム名に変更
                    $arrTemp["d_system_member_".$key] = $val;
                }
            }

            // return用にセット
            $arrData = $arrTemp;


            // ----------------------------- 実行結果を判定
            if( isset( $this->objAdminSess ) ){
                //return
                return $arrData;
            } else {
                throw new Zend_Exception('管理者のセッション情報の取得に失敗しました。');
            }

        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /*
     *
     * メニュー配列作成関数
     *
    */
    public function createMenuArray(){
        try{

            $arrMenu = array();
            //管理者ログイン済みであることが前提
            //セッション内に機能管理マスタ、機能管理詳細マスタを読み込み済み
            if (!isset($_SESSION["Admin"])) {
                return $arrMenu;
            }

            $arrSystemFunction = array();
            $arrSystemFunctionDetail1 = array();
            $arrFirstURL = array();
            $arrSystemFunction = $_SESSION["Admin"]["arrSystemFunction"];
            $arrSystemFunctionDetail1 = $_SESSION["Admin"]["arrSystemFunctionDetail1"];
            $arrSystemFunctionDetail2 = $_SESSION["Admin"]["arrSystemFunctionDetail2"];

            $iIndex1 = 0;
            $iFunctionNoSave1 = $arrSystemFunctionDetail1[0]["m_system_function_detail1_SystemFunctionID"];

            // 機能詳細1マスタのデータ加工
            foreach ($arrSystemFunctionDetail1 as $key => $value) {
                if ($iFunctionNoSave1 != $value["m_system_function_detail1_SystemFunctionID"]) {
                    $iFunctionNoSave1 = $value["m_system_function_detail1_SystemFunctionID"];
                    $iIndex1 = 0;
                }
                if ($value["m_system_function_detail1_MenuFlg"] == 1) {
                    $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]]["FunctionName"] = 
                        $arrSystemFunction[$value["m_system_function_detail1_SystemFunctionID"]]["m_system_function_Name"];
                    $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]]["m_system_function_detail1_SystemFunctionID"] = $value["m_system_function_detail1_SystemFunctionID"];
                    if ($iIndex1 == 0) {
                        $arrFirstURL[$value["m_system_function_detail1_SystemFunctionID"]] = $value["m_system_function_detail1_URL"];
                    }
                    $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]][$iIndex1]["SystemFunctionID"] = $value["m_system_function_detail1_SystemFunctionDetail1ID"];
                    $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]][$iIndex1]["DetailName"] = $value["m_system_function_detail1_Name"];
                    $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]][$iIndex1]["DetailURL"] = $value["m_system_function_detail1_URL"];
                    $iIndex1++;
                    
                    // 機能詳細2マスタのデータ加工
                    $arrMenuDetail2 = array();
                    $iIndex2 = 0;
                    $iFunctionNoSave2 = $arrSystemFunctionDetail2[0]["m_system_function_detail2_SystemFunctionDetail1ID"];
                    foreach ($arrSystemFunctionDetail2 as $k => $v) {
                        if ($iFunctionNoSave2 != $value["m_system_function_detail2_SystemFunctionDetail1ID"]) {
                            $iFunctionNoSave2 = $value["m_system_function_detail2_SystemFunctionDetail1ID"];
                            $iIndex2 = 0;
                        }
                        if ($v["m_system_function_detail2_MenuFlg"] == 1) {
                            $arrMenuDetail2[$iIndex2] = $arrSystemFunction[$value["m_system_function_detail1_SystemFunctionID"]]["m_system_function_Name"];
                            $arrMenuDetail2["m_system_function_detail1_SystemFunctionID"] = $value["m_system_function_detail1_SystemFunctionID"];
                            if ($iIndex2 == 0) {
                                $arrFirstURL[$value["m_system_function_detail1_SystemFunctionID"]] = $value["m_system_function_detail1_URL"];
                            }
                            $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]][$iIndex1]["SystemFunctionID"] = $value["m_system_function_detail1_SystemFunctionDetail1ID"];
                            $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]][$iIndex1]["DetailName"] = $value["m_system_function_detail1_Name"];
                            $arrMenu[$value["m_system_function_detail1_SystemFunctionID"]][$iIndex1]["DetailURL"] = $value["m_system_function_detail1_URL"];
                            $iIndex1++;
                        }
                    }
                }
            }
            foreach ($arrMenu as $key => $value) {
                $arrMenu[$key]["FunctionURL"] = $arrFirstURL[$key];
            }
            

            return $arrMenu;

        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     *
     * 画面操作権限をtrue か falseで返すFunction
     *
    */
    function isAuthority($stRequestURI) {

        try {
            
            $bAuthority = false;
            $arrFunctionDetail1 = $this->objAdminSess->arrSystemFunctionDetail1;
            foreach ($arrFunctionDetail1 as $key => $value) {
                // 末尾にスラッシュが入っている場合は取り除く
                if (mb_substr($value["m_system_function_detail1_URL"], -1) == "/") {
                    $stURL = rtrim($value["m_system_function_detail1_URL"], "/");
                } else {
                    $stURL = $value["m_system_function_detail1_URL"];
                }
                
                if (preg_match("{^" . $stURL . "}", $stRequestURI)) {
                    $bAuthority = true;
                    break;
                }
            }
            
            // 開発者メニュー用
            if ($stRequestURI == "/admin/dev/migration") {
                $bAuthority = true;
            }
            
            return $bAuthority;

        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     *
     * Publicのtempフォルダの指定フォルダにファイルをダウンロードする関数
     * $url             string ダウンロードするファイルのURL
     * $dir             string 格納フォルダ名(public/temp配下)
     * $save_base_name  string 保存ファイル名 省略時は原本のファイル名で保存
    */
    function file_download($url, $dir = '', $save_base_name = '') {
        try {
            if (!is_dir(CSV_DIR . $dir)) {
                throw new Zend_Exception("ディレクトリ(" . $dir . ")が存在しません。");
            }
            $dir = preg_replace("{/$}","",$dir);
            $p = pathinfo($url);
            $local_filename = '';
            if ( $save_base_name ){
                $local_filename = CSV_DIR . $dir . "/" . $save_base_name . "." . $p['extension']; 
            } else {
                $local_filename = CSV_DIR . $dir . "/" . $p['filename'] . "." . $p['extension']; 
            }
            if ( is_file( $local_filename ) ) {
                // 既に同名ファイルが存在する場合、削除する
                unlink($local_filename);
            }
            $tmp = file_get_contents($url);
            if (!$tmp) {
                throw new Zend_Exception("URL(" . $url .")からダウンロードできませんでした。");
            }
            $fp = fopen($local_filename, 'w');
            fwrite($fp, $tmp);
            fclose($fp);
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     *
     * Publicのtempフォルダの指定フォルダにファイルをダウンロードする関数
     * $url             string ダウンロードするファイルのURL
     * $dir             string 格納フォルダ名(public/temp配下)
     * $save_base_name  string 保存ファイル名 省略時は原本のファイル名で保存
    */
    function file_downloadAsProductCSV($url, $dir = '') {
        try {
            if (!is_dir(CSV_DIR . $dir)) {
                throw new Zend_Exception("ディレクトリ(" . $dir . ")が存在しません。");
            }
            $dir = preg_replace("{/$}","",$dir);
            $p = pathinfo($url);
            $local_filename = '';
            $local_filename = CSV_DIR . $dir . "/product.csv";
            if ( is_file( $local_filename ) ) {
                // 既に同名ファイルが存在する場合、削除する
                unlink($local_filename);
            }
            $tmp = file_get_contents($url);
            if (!$tmp) {
                throw new Zend_Exception("URL(" . $url .")からダウンロードできませんでした。");
            }
            $fp = fopen($local_filename, 'w');
            fwrite($fp, $tmp);
            fclose($fp);
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     *
     * zip形式書庫ファイルの解凍を行う関数
     * $zip_path        string ZIP書庫ファイル名
     * $zip_dir         string 解凍先フォルダ
    */    
    function zipfile_unzip($zip_path, $zip_dir) {
        $zip = new ZipArchive();
        $res = $zip->open($zip_path);
        if ($res === true) {
            // 圧縮ファイル内のすべてのファイルを解凍先に展開する場合
            $zip->extractTo($zip_dir);
            // zip ファイルを閉じる
            $zip->close();
        }
   }

   /***
     *
     * 条件に一致した配列のキーを返す
     * 一致なし：返り値= -1
     * $arrData array 対象配列
     * $stColumn string 対象カラム
     * $iValue  int     検索値
    */    
    function array_column_key($arrData, $stColumn, $iValue) {
        
        $iKey = -1;
        foreach ($arrData as $key => $value) {
            foreach ($value as $column => $check) {
                if ($column == $stColumn) {
                    if ($check == $iValue) {
                        $iKey = $key;
                        break;
                    }
                }
            }
        }
        return $iKey;
   }
   
//    /***
//     *
//     * 税込み金額の消費税を計算
//     * $iTotal       int 総額
//     * $arrBaseInfo  array 基本情報配列
//     * return $iTax  int 内消費税
//    */
//    function calcTax($iTotal, $arrBaseInfo = "") {
//
//        if (empty($arrBaseInfo)) {
//            $arrBaseInfo = $this->mdlBaseInfo->find(1);
//        }
//        if (empty($arrBaseInfo["d_baseinfo_ChangeTaxDate"]) || $arrBaseInfo["d_baseinfo_ChangeTaxDate"] == "0000-00-00 00:00:00") {
//            $iTaxRate = $arrBaseInfo["d_baseinfo_TaxRate"];
//        } else {
//            $dNowDate = date("Y-m-d");
//            if (date("Y-m-d", strtotime($arrBaseInfo["d_baseinfo_ChangeTaxDate"])) <= $dNowDate) {
//                $iTaxRate = intval($arrBaseInfo["d_baseinfo_ChangeTaxRate"]);
//            } else {
//                $iTaxRate = $arrBaseInfo["d_baseinfo_TaxRate"];
//            }
//        }
//        $iTaxRule = $arrBaseInfo["d_baseinfo_TaxRule"];
//
//        $iTax = $iTotal * $iTaxRate / (100 + $iTaxRate);
//        switch ($iTaxRule) {
//            case 1:
//                //四捨五入
//                $iTax = round($iTax);
//                break;
//            case 2:
//                //切捨て
//                $iTax = floor($iTax);
//                break;
//            case 3:
//                //切上げ
//                $iTax = ceil($iTax);
//                break;
//            default:
//                break;
//        }
//        
////        $BasePrice = $iTotal / ((100 + $iTaxRate) / 100);
////        
////        switch ($iTaxRule) {
////            case 1:
////                //四捨五入
////                $iTax = round($BasePrice * $iTaxRate / 100);
////                break;
////            case 2:
////                //切捨て
////                $iTax = floor($BasePrice * $iTaxRate / 100);
////                break;
////            case 3:
////                //切上げ
////                $iTax = ceil($BasePrice * $iTaxRate / 100);
////                break;
////            default:
////                break;
////        }
//        // 参考：本体価格＝総額-消費税額
//        return $iTax;
//   }
//   
    /**
     * 税込み金額の消費税を計算
     * 
     * @param  int      $iTotal         税込金額
     * @param  array    $arrBaseInfo    基本設定マスタテーブル配列
     * @param  int      $iTaxRate       税率
     * @param  int      $iTaxFraction   消費税端数区分
     * @return int      $iTax           内消費税
    */
    function calcTax($iTotal, $arrBaseInfo = null, $iTaxRate = null, $iTaxFraction = null) {

        if ($arrBaseInfo == null) {
            // 基本設定マスタテーブル配列が指定されていない場合は、取得する
            $arrBaseInfo = $this->mdlBaseInfo->find(1);
        }
        $iBaseInfoTaxRate = $arrBaseInfo["d_baseinfo_TaxRate"];

        // 変更後消費税率
        if (!empty($arrBaseInfo["d_baseinfo_ChangeTaxDate"]) && $arrBaseInfo["d_baseinfo_ChangeTaxDate"] != "0000-00-00 00:00:00") {
            if (date("Y-m-d", strtotime($arrBaseInfo["d_baseinfo_ChangeTaxDate"])) <= strtotime(date("Y-m-d"))) {
                $iBaseInfoTaxRate = intval($arrBaseInfo["d_baseinfo_ChangeTaxRate"]);
            }
        }
        
        // 税率が指定されていなければ、基本設定マスタテーブルの税率を使用する
        if ($iTaxRate == null) {
            $iTaxRate = $iBaseInfoTaxRate;
        }
        
        // 消費税端数区分が指定されていなければ、基本設定マスタテーブルの消費税端数区分を使用する
        if (empty($iTaxFraction)) {
            $iTaxFraction = $arrBaseInfo["d_baseinfo_TaxFraction"];
        }
        
        $iTax = $iTotal * $iTaxRate / (100 + $iTaxRate);
        switch ($iTaxFraction) {
            case Application_Model_BaseInfo::TAX_FRACTION_CEIL:
                // 切上げ
                $iTax = ceil($iTax);
                break;
            case Application_Model_BaseInfo::TAX_FRACTION_ROUND:
                // 四捨五入
                $iTax = round($iTax);
                break;
            case Application_Model_BaseInfo::TAX_FRACTION_FLOOR:
                //切捨て
                $iTax = floor($iTax);
                break;
            default:
                break;
        }
        // 参考：本体価格＝総額-消費税額
        return $iTax;
   }
   
    /**
     * 税別金額から税込金額を計算
     * 
     * @param  int      $iTotal         税別金額
     * @param  array    $arrBaseInfo    基本設定マスタテーブル配列
     * @param  int      $iTaxRate       税率
     * @param  int      $iTaxFraction   消費税端数区分
     * @return int      $iTaxIncluded   税込金額
    */
    function calcTaxIncluded($iTotal, $arrBaseInfo = null, $iTaxRate = null, $iTaxFraction = null) {

        if ($arrBaseInfo == null) {
            // 基本設定マスタテーブル配列が指定されていない場合は、取得する
            $arrBaseInfo = $this->mdlBaseInfo->find(1);
        }
        $iBaseInfoTaxRate = $arrBaseInfo["d_baseinfo_TaxRate"];

        // 変更後消費税率
        if (!empty($arrBaseInfo["d_baseinfo_ChangeTaxDate"]) && $arrBaseInfo["d_baseinfo_ChangeTaxDate"] != "0000-00-00 00:00:00") {
            if (date("Y-m-d", strtotime($arrBaseInfo["d_baseinfo_ChangeTaxDate"])) <= strtotime(date("Y-m-d"))) {
                $iBaseInfoTaxRate = intval($arrBaseInfo["d_baseinfo_ChangeTaxRate"]);
            }
        }
        
        // 税率が指定されていなければ、基本設定マスタテーブルの税率を使用する
        if ($iTaxRate == null) {
            $iTaxRate = $iBaseInfoTaxRate;
        }
        
        // 消費税端数区分が指定されていなければ、基本設定マスタテーブルの消費税端数区分を使用する
        if (empty($iTaxFraction)) {
            $iTaxFraction = $arrBaseInfo["d_baseinfo_TaxFraction"];
        }

        $iTaxIncluded = $iTotal * (1 + $iTaxRate / 100);
        $iTax = $iTaxIncluded - $iTotal;
        switch ($iTaxFraction) {
            case Application_Model_BaseInfo::TAX_FRACTION_CEIL:
                // 切上げ
                $iTax = ceil($iTax);
                break;
            case Application_Model_BaseInfo::TAX_FRACTION_ROUND:
                // 四捨五入
                $iTax = round($iTax);
                break;
            case Application_Model_BaseInfo::TAX_FRACTION_FLOOR:
                //切捨て
                $iTax = floor($iTax);
                break;
            default:
                break;
        }
        $iTaxIncluded = $iTotal + $iTax;
        return $iTaxIncluded;
   }
   
    /**
     * フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheck($arrForm) {
        
        try {
            $objValidate = new Validate();

            switch ($arrForm["mode"]) {
                case "csvDownload":
                    // 日付チェック
                    $bSetDate = false;
                    if ($arrForm["history_from_Year"] != "" && $arrForm["history_from_Month"] != "" && $arrForm["history_from_Day"] != "") {
                        $bSetDate = true;
                    }
                    if ($arrForm["history_to_Year"] != "" && $arrForm["history_to_Month"] != "" && $arrForm["history_to_Day"] != "") {
                        $bSetDate = true;
                    }
                    if ($bSetDate == false) {
                        $stFormKey = "d_customer_point_history_Date";
                        $stColumnName = "抽出期間";
                        $objValidate->Execute(array($stFormKey => $stColumnName), array("NotEmpty", ""));
                    }
                    
                    if ($arrForm["history_from_Year"] && $arrForm["history_from_Month"] && $arrForm["history_from_Day"]) {
                        $stFromDate = $arrForm["history_from_Year"] . "-" . $arrForm["history_from_Month"] . "-" . $arrForm["history_from_Day"];
                        $stFormKey = "d_customer_point_history_DateStart";
                        $stColumnName = "抽出期間(開始)";
                        $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stFromDate));
                    }
                    if ($arrForm["history_to_Year"] && $arrForm["history_to_Month"] && $arrForm["history_to_Day"]) {
                        $stToDate = $arrForm["history_to_Year"] . "-" . $arrForm["history_to_Month"] . "-" . $arrForm["history_to_Day"];
                        $stFormKey = "d_customer_point_history_DateEnd";
                        $stColumnName = "抽出期間(終了)";
                        $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stToDate));
                    }
                    
                    // 抽出日付の前後関係のチェックを行う
                    if ($arrForm["history_from_Year"] && $arrForm["history_from_Month"] && $arrForm["history_from_Day"] &&
                        $arrForm["history_to_Year"] && $arrForm["history_to_Month"] && $arrForm["history_to_Day"]) {
                        $stFromDate = $arrForm["history_from_Year"] . "/" . $arrForm["history_from_Month"] . "/" . $arrForm["history_from_Day"];
                        $stToDate = $arrForm["history_to_Year"] . "/" . $arrForm["history_to_Month"] . "/" . $arrForm["history_to_Day"];
                        $stFormKey = "d_customer_point_history_Date";
                        $stColumnName = "抽出期間";
                        $objValidate->Execute(array($stFormKey => $stColumnName), array("DateTimeCompare", array($stFromDate, $stToDate)));
                    }
                    break;
                default:
                    break;
            }
            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }   

    /***
     *
     * パスワードを生成する関数
     *
     * $stPassword = 入力されたパスワード
     *
     *
    */
    function makePassword($stPassword) {
        return sha1($stPassword . ":" . AUTH_MAGIC);
    }
    
    /***
     *
     * パスワードを照合する為のパスワードを生成する関数
     *
     * $stPassword = 入力されたパスワード
     * $stDBPassword = DBに保存されているパスワード
     * 基本フロントからはこの関数が呼ばれる
     * 
     * 現行EC-CUBEのパスワードロジックを踏襲するため、この関数は使用しない
     *
    */
    function verificatePassword($stPassword, $stDBPassword) {

        $stEncryptedPassword = $this->makePassword($stPassword);

        if ($stEncryptedPassword == $stDBPassword) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /***
     *
     * パスワードを生成する関数
     *
     * $stPassword = 入力されたパスワード
     *
     * 現行EC-CUBEのパスワードロジックを踏襲するため、この関数は使用しない
     *
    */
    function makePasswordDifferentLogic($stPassword) {

        $iIterationCount = 2;
//        $iPasswordLength = 22;
        $iSaltLength = 18;
        
        // salt
        $stSalt = substr(md5(uniqid(rand(), true)), 0, $iSaltLength);
        
        // password
//        $stEncryptedPassword = $stPassword . $stSalt;
        $stEncryptedPassword = $stSalt . $stPassword;
        
        // Encrypt start
        for ($i = 0; $i < $iIterationCount; $i++) {
            $stEncryptedPassword = sha1($stEncryptedPassword);
        }
        
//        $stPassword = $stEncryptedPassword . $stSalt;
        $stPassword = $stSalt . $stEncryptedPassword;
        
        return $stPassword;
    }
    
    /***
     *
     * パスワードを照合する為のパスワードを生成する関数
     *
     * $stPassword = 入力されたパスワード
     * $stEncryptedPassword = DBに保存されているパスワード
     * 基本フロントからはこの関数が呼ばれる
     * 
     * 現行EC-CUBEのパスワードロジックを踏襲するため、この関数は使用しない
     *
    */
    function verificatePasswordDifferentLogic($stPassword, $stEncryptedPassword) {

        // password for verification
        $stPassword = $this->makePasswordForVerificate($stPassword, $stEncryptedPassword);

        if($stPassword == $stEncryptedPassword) {
            return true;
        } else {
            return false;
        }
    }

    /***
     *
     * パスワードを照合する為のパスワードを生成する関数
     *
     * $stPassword = 入力されたパスワード
     * $stEncryptedPassword = DBに保存されているパスワード
     *
     *
    */
    function makePasswordForVerificate($stPassword, $stEncryptedPassword) {
        $iIterationCount = 2;
//        $iPasswordLength = 22;
        $iSaltLength = 18;
        
        // salt
//        $stSalt = $this->splitSalt($stEncryptedPassword);
//        $stSalt = substr($stEncryptedPassword, 40, $iSaltLength);
        $stSalt = substr($stEncryptedPassword, 0, $iSaltLength);
        
        // password
//        $stEncryptedPassword = $stPassword . $stSalt;
        $stEncryptedPassword = $stSalt . $stPassword;
        
        // Encrypt start
        for ($i = 0; $i < $iIterationCount; $i++) {
            $stEncryptedPassword = sha1($stEncryptedPassword);
        }
        
//        $stPassword = $stEncryptedPassword . $stSalt;
        $stPassword = $stSalt . $stEncryptedPassword;
        
        return $stPassword;
    }

    /***
     *
     * DB更新の履歴をログテーブル(m_log)に残す
     * 
     * @param   object   $objSession    セッションオブジェクト
     * @param   object   $objFormReq    リクエストオブジェクト
     * @param   array    $arrForm       フォーム
     * @param   int      $iLogLevel
     * @param   bool     $bIsAdmin      管理画面からの登録
     * @param   bool     $bExecTran     トランザクションを行うかどうか
     *
    */
    function writeLog($objSession, $objFormReq, $arrForm, $iLogLevel, $bIsAdmin = true, $bExecTran = true) {
        
        try {
            
            if (CURRENT_LOG_LEVEL < $iLogLevel) {
                // begin
                if ($bExecTran) {
                    $this->mdlLog->begin();
                }
                $arrLog = array();
                
                $arrLog["m_log_SystemMemberID"] = $objSession->MemberID ? $objSession->MemberID : 0;
                $arrLog["m_log_IPAddress"] = $objFormReq->getClientIp();
                $arrLog["m_log_Data"] = serialize($arrForm);
                $arrLog["m_log_SessionData"] = serialize($objSession);
                $this->mdlLog->insert($arrLog, $bIsAdmin);

                // commit
                if ($bExecTran) {
                    $this->mdlLog->commit();
                }
            }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }        
    }
    
    /**
    * 年齢計算関数
    * @params String $birthday Birthday(Y-m-d)
    * @params String $calcday Reference date(Y-m-d)
    * @params Integer $law 0:Normal 1:Law
    * @return Integer age integer
    */
    function getCalcAge($birthday, $calcday = NULL, $law = 0) {
        $calcAge = null;

        // Check Birthday
        if (strlen($birthday)==10) {
            $birthday_year = substr($birthday , 0 , 4) ;
            $birthday_month = substr($birthday , 5 , 2) ;
            $birthday_day = substr($birthday , 8 , 2) ;
        } else {
            return $calcAge;
        }
        if (!checkdate($birthday_month, $birthday_day, $birthday_year)) {
            return $calcAge;
        } 

        // Check Reference date
        if (!$calcday) {
            $calcday = date("Y-m-d", time());
        }
        if (strlen($calcday)==10) {
            $calcday_year = substr($calcday , 0 , 4) ;
            $calcday_month = substr($calcday , 5 , 2) ;
            $calcday_day = substr($calcday , 8 , 2) ;
            // 日本の法令上で年齢計算をする場合、誕生日の前日が加齢日となります。
            if ($law) {
                $calcday_unixtime = mktime(0, 0, 0, $calcday_month, $calcday_day+1, $calcday_year);
                $calcday = date("Y-m-d", $calcday_unixtime);
                $calcday_year = substr($calcday , 0 , 4) ;
                $calcday_month = substr($calcday , 5 , 2) ;
                $calcday_day = substr($calcday , 8 , 2) ;
            } else {
              // 日本の法令上の計算をしない場合、閏年の誕生日を考慮します。
              if ($birthday_month == 2 && $birthday_day == 29 && 
                  $calcday_month == 2 && $calcday_day == 28 &&
                  !checkdate(2, 29, $calcday_year)) {
                  $calcday_unixtime = mktime(0, 0, 0, $calcday_month, $calcday_day+1, $calcday_year);
                  $calcday = date("Y-m-d", $calcday_unixtime);
                  $calcday_year = substr($calcday , 0 , 4) ;
                  $calcday_month = substr($calcday , 5 , 2) ;
                  $calcday_day = substr($calcday , 8 , 2) ;
              }
            }
        } else {
          return $calcAge;
        }
        if (!checkdate($calcday_month, $calcday_day, $calcday_year)) {
          return $calcAge;
        } 

        //Age calculation
        $calcAge = $calcday_year - $birthday_year;
        if ( $birthday_month > $calcday_month ||
            ($birthday_month == $calcday_month && $birthday_day > $calcday_day)) {
            $calcAge -= 1;
        }

        return $calcAge;
    }
    
    
    
    
   /**
    * メール送信
    * 
    * @param    int         $iTempleteID    メールテンプレートID
    * @param    array       $arrData        メール埋め込みデータ
    * @param    array       $arrHistoryData メール履歴用データ
    * @return
    */
    function sendMailAndSaveHistory($iTempleteID, $arrData, $arrHistoryData) {
        
        try {
            
            // ----- メール送信 -----
            // HTMLデコードする
            foreach ($arrData as $key => $value) {
                // 配列の場合
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k2 => $v2) {
                                $arrData[$key][$k][$k2] = htmlspecialchars_decode($v2, ENT_QUOTES);
                            }
                        } else {
                            $arrData[$key][$k] = htmlspecialchars_decode($v, ENT_QUOTES);
                        }
                    }
                } else {
                    if ($value != "") {
                        $arrData[$key] = htmlspecialchars_decode($value, ENT_QUOTES);
                    }
                }
            }
            
            foreach ($arrHistoryData as $key => $value) {
                // 配列の場合
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $arrData[$key][$k] = htmlspecialchars_decode($v, ENT_QUOTES);
                    }
                } else {
                    if ($value != "") {
                        $arrHistoryData[$key] = htmlspecialchars_decode($value, ENT_QUOTES);
                    }
                }
            }
            
            // テンプレート読み込み
            $arrSearchCondition["d_mail_setting_TemplateID"] = $iTempleteID;
            $this->mdlMailSetting->setSearchCondition($arrSearchCondition);
            $arrResult = $this->mdlMailSetting->search();
            if ($arrResult) {
                $stTitleString = $arrResult[0]["d_mail_setting_Title"];
                $stMailString = $arrResult[0]["d_mail_setting_Content"];
            }
            
            $stTitleString = $this->getTemplateMailString($stTitleString, $arrData);
            $stMailString =  $this->getTemplateMailString($stMailString, $arrData);

            //言語設定、内部エンコーディングを指定する
            mb_language("japanese");
            mb_internal_encoding("UTF-8");

            $to = $arrData["toAddress"];
            $subject = $stTitleString;
            $body = $stMailString;
            $arrBaseInfo = $this->mdlBaseInfo->find(1);
            $header = "MIME-Version: 1.0\n"
                    . "Content-Transfer-Encoding: 7bit\n"
                    . "Content-Type: text/plain; charset=ISO-2022-JP\n"
                    . "Message-Id: <" . md5(uniqid(microtime())) . "@" . DOMAIN_LOCAL . ">\n"
                    . "From:" . mb_encode_mimeheader(mb_convert_encoding($arrBaseInfo["d_baseinfo_Name"],
                        "JIS", "UTF-8")) . "<" . $arrBaseInfo["d_baseinfo_MailAddress4"] . ">";
            $erraddress = $arrBaseInfo["d_baseinfo_MailAddress3"];
            $bResult = mb_send_mail($to, $subject, $body, $header, "-f " . $erraddress);
            $to = $arrBaseInfo["d_baseinfo_MailAddress1"];//　管理者にも送信
            mb_send_mail($to, $subject, $body, $header, "-f " . $erraddress);
            
            // メール送信履歴保存
            if ($arrHistoryData) {
                $this->mdlMailHistory->begin();
                $arrInsert = array();
                $arrInsert["d_mail_history_MailHistoryID"] = null;
                $arrInsert["d_mail_history_TemplateID"] = $iTempleteID;
                $arrInsert["d_mail_history_OrderMngID"] = null;
                $arrInsert["d_mail_history_OrderID"] = null;
                $arrInsert["d_mail_history_CustomerID"] = $arrHistoryData["d_mail_history_CustomerID"];
                $arrInsert["d_mail_history_CustomerName"] = $arrHistoryData["d_mail_history_CustomerName"];
                $arrInsert["d_mail_history_SendDate"] = date("Y-m-d H:i:s");
                $arrInsert["d_mail_history_Title"] = $subject;
                $arrInsert["d_mail_history_Content"] = $body;
                $arrInsert["d_mail_history_DelFlg"] = 0;
                $this->mdlMailHistory->insert($arrInsert, false);
                $this->mdlMailHistory->commit();
            }
            
            return $bResult;
                        
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
        
    }
    
   /**
    * メールテンプレート文字列の出力
    * 
    * @param    string      $stData     変換文字列
    * @param    array       $arrData    パラメータ
    * @return   string      $stString   メール変換後文字列
    */
    function getTemplateMailString($stData, $arrData) {

        try {
            
            $stString = "";
            // 対象文字列内のコメント(<!--,-->)を外す
            $arrReplace = array();
            $arrReplace[] = "/<!--/";
            $arrReplace[] = "/-->/";
            $stExchange = preg_replace($arrReplace, "", $stData);

            $arrConf = array();
            $arrBaseInfo = $this->mdlBaseInfo->find(1);
            $arrConf["shop_name"] = $arrBaseInfo["d_baseinfo_Name"];

            // smartyで変換
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
     * ランダム文字列生成 (英数字)
     * $length: 生成する文字数
     */
    function makeRandStr($length = 8) {
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; ++$i) {
            $str .= $chars[mt_rand(0, 61)];
        }
        return $str;
    }
    
    /**
     * セッション情報の保存
     */
    function setSessionToDB($objFormReq, $bCallerAjax = false) {
        // セッション管理
        $this->objFrontSess = new Zend_Session_Namespace("Front");
        $this->mdlSession = new Application_Model_Session();
        
//        // セッションIDを取得
        $stSessionID = session_id();
        
        // ログアウト直後はセッションIDをもたないため、DB保存を行わない
        if ($stSessionID == "") {
            return;
        }
        
        // クライアントIPアドレスを取得
        $stClientIP = $objFormReq->getClientIp();
        
        $arrSessTemp = array();
        foreach ($this->objFrontSess as $key => $value) {
            $arrSessTemp[$key] = $value;
        }
        
        // DB同期タイムラグ対策
        $arrSessions = $this->mdlSession->findForMaster($stSessionID, $stClientIP);
        $arrSession = $arrSessions[0];
        
        if (empty($arrSession)) {
            // DBにデータが存在しないのに、cookieが存在していれば有効期限切れ
            if (!empty($_COOKIE["hiraboku_seq"])) {
                unset($this->objFrontSess);
                if (isset($_COOKIE[session_name()])) {
                    setcookie(session_name(), '', time() - 42000, '/');
                }
                if (isset($_COOKIE["hiraboku_seq"])) {
                    setcookie("hiraboku_seq", '', time() - 42000, '/');
                }
                session_destroy();
                
                if ($bCallerAjax == true) {
                    return "expired";
                }
                throw new Zend_Exception("Session Expired");
            }            
            
            // Zend SessionをDBに新規追加
            $arrInsert = array();
            $arrInsert["d_session_SessionID"] = $stSessionID;
            $arrInsert["d_session_IPAddress"] = $stClientIP;
//            $arrInsert["d_session_Data"] = serialize($this->objFrontSess);
            $arrInsert["d_session_Data"] = serialize($arrSessTemp);
            $stLastInsertID = $this->mdlSession->insert($arrInsert);
            
            // DBに新規追加したプライマリキーをクッキーに保存しておく
            setcookie("hiraboku_seq", $stLastInsertID, 0, '/');
            
        } else {
            // DBとcookieのプライマリキーが不一致の場合はエラー
            if ($_COOKIE["hiraboku_seq"] != $arrSession["d_session_SessionNo"]) {
                unset($this->objFrontSess);
                if (isset($_COOKIE[session_name()])) {
                    setcookie(session_name(), '', time() - 42000, '/');
                }
                if (isset($_COOKIE["hiraboku_seq"])) {
                    setcookie("hiraboku_seq", '', time() - 42000, '/');
                }
                session_destroy();
                
                if ($bCallerAjax == true) {
                    return "invalid";
                }
                throw new Zend_Exception("Session Invalid");
            }
            
            // Zend SessionをDBを更新
            $arrUpdate = array();
            $arrUpdate["d_session_SessionNo"] = $arrSession["d_session_SessionNo"];
//            $arrUpdate["d_session_Data"] = serialize($this->objFrontSess);
            $arrUpdate["d_session_Data"] = serialize($arrSessTemp);
            $this->mdlSession->save($arrUpdate);
        }
        
        if ($bCallerAjax == true) {
            return "";
        }
    }

    /**
     * アクセスログの保存
     */
    function writeAccessLog($objFormReq, $logDir, $stSessionID, $stCustomerID, $arrCart, $stErrorMessage = "") {

        // 画像データ取得はログに残さない
        if (preg_match("/^\/images\//", $_SERVER['REQUEST_URI']) || preg_match("/^\/img\//", $_SERVER['REQUEST_URI'])) {
            return;
        }
        
        // クライアントIPアドレスを取得
        $stClientIP = $objFormReq->getClientIp();
        
        // アクセスログを残す
        $stMessage = date("YmdHis") . "," . $stClientIP . ",\"" . $_SERVER["HTTP_USER_AGENT"] . "\"," .$_SERVER["REQUEST_URI"] . "," . $stCustomerID . ",";
        $stMessage .= "\"";
        foreach ($arrCart as $key => $value) {
            $stMessage .= $value["ProductClassID"] . ",";
        }
        $stMessage = rtrim($stMessage, ",");
        $stMessage .= "\"";
        $stMessage .= ",\"" . $stErrorMessage . "\"\n";
        $fp = fopen($logDir . $stSessionID, 'a+');
        fwrite($fp, $stMessage);
        fclose($fp);    
    }

   /*
    * メールアドレスキャリアチェック 
    */
    function checkCareerMail($stMailAddress) {
        //キャリアメールの場合True
        $bCareer = false;
        if (preg_match("/docomo.ne.jp/i", $stMailAddress) || preg_match("/mopera.net/i", $stMailAddress) ||
            preg_match("/softbank.ne.jp/i", $stMailAddress) || preg_match("/vodafone.ne.jp/i", $stMailAddress) ||
            preg_match("/disney.ne.jp/i", $stMailAddress) || preg_match("/i.softbank.jp/i", $stMailAddress) ||
            preg_match("/ezweb.ne.jp/i", $stMailAddress) || preg_match("/biz.ezweb.ne.jp/i", $stMailAddress) ||
            preg_match("/augps.ezweb.ne.jp/i", $stMailAddress) || preg_match("/ido.ne.jp/i", $stMailAddress) ||
            preg_match("/emnet.ne.jp/i", $stMailAddress) || preg_match("/emobile.ne.jp/i", $stMailAddress) ||
            preg_match("/emobile-s.ne.jp/i", $stMailAddress) || preg_match("/ymobile1.ne.jp/i", $stMailAddress) ||    
            preg_match("/ymobile.ne.jp/i", $stMailAddress) || preg_match("/pdx.ne.jp/i", $stMailAddress) ||
            preg_match("/willcom.com/i", $stMailAddress) || preg_match("/wcm.ne.jp/i", $stMailAddress) ||
            preg_match("/y-mobile.ne.jp/i", $stMailAddress)) {
            $bCareer = true;
        }
        return $bCareer;
    }    
    
    /***
     *
     * 総額から本体金額、消費税を計算
     * $iTotal             int 総額
     * $arrBaseInfo        array 基本情報配列
     * return $iBodyPrice  int 本体金額
     * return $iTax        int 消費税額
    */    
    function calcPrice($iTotal, $arrBaseInfo = "") {
        
        $iTax = $this->calcTax($iTotal, $arrBaseInfo);
        
        $iBodyPrice = $iTotal - $iTax;
        
        return array($iBodyPrice, $iTax);
   }
   
    /***
     *
     * グローバルナビの現在アクティブなメニューを取得する
     * 
     * return $array アクティブなメニューは「on」、非アクティブは「off」の配列
    */    
    function getGlobalNavCurrentPos($arrMenu) {

        $iGlobalNavPos = 9999999;
        if ($arrMenu) {
            foreach ($arrMenu as $key => $value) {
                foreach ($value["middleMenu"] as $v) {
                    // 現在アクセスとしているURLより、アクティブなメニューを特定する
                    if (strpos($_SERVER["REQUEST_URI"], $v["m_system_function_detail1_URL"], 0) === 0) {
                        $iGlobalNavPos = $key;
                        break;
                    }
                }
            }
        }

        $arrGlobalNavPos = array();
        if ($arrMenu) {
            foreach ($arrMenu as $key => $value) {
                // アクティブなメニューは「on」、非アクティブは「off」とする
                if ($key == $iGlobalNavPos) {
                    $arrGlobalNavPos[] = "on";
                } else {
                    $arrGlobalNavPos[] = "off";
                }
            }
        }
        return $arrGlobalNavPos;
   }
   
    /***
     *
     * IDやコードのor検索・範囲検索をselect文に加える関数
     * param  $arrSearchCondition        検索条件フォーム値配列
     * param  $stFormKey       フォーム値のキー
     * param  $stDBKey　　　　  DBの物理名
     * param  $stDBAlias　　   テーブル結合時に宣言したテーブル名のエイリアス(ピリオドは関数内で付与するため不要) 空白可
     * param  $objSelect
     * return $objSelect 
    */    
    function addIdConfigToObjSelect($arrSearchCondition, $stFormKey, $stDBKey, $stDBAlias, &$objSelect) {
        
        // $stDBAliasに値が入っていれば末尾にピリオドをつける
        if ($stDBAlias != "") {
            $stDBAlias = $stDBAlias . ".";
        }
        
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
                $objSelect->where($stDBAlias . "$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("cast(" . $stDBAlias . "$stDBKey as SIGNED) >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("cast(" . $stDBAlias . "$stDBKey as SIGNED) <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where($stDBAlias . "$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
            
    }
    
    /***
     *
     * フォームデータから特定のテーブルのデータのみを抽出する
     * 
     * param  $arrFrom         フォーム用配列
     * param  $stTableName     テーブル名
     * return $arrTableData    特定のテーブルのデータ 
    */    
    function getTableDataExtractForm($arrForm, $stTableName) {
        
        // 商品テーブルの抽出カラムの取得
        $arrTableData = array();
        foreach ($arrForm as $key => $value) {
            if (preg_match("/^{$stTableName}_[A-Z]+/", $key)) {
                $arrTableData[$key] = $value;
            }
        }
        return $arrTableData;
    }
    
    /***
     *
     * 西暦を和暦に変換
     * 
     * param  $stDate    西暦表記の年月日(2016-07-07)
     * return $stWareki  西暦を和暦に変換した年(年のみ)
    */
    function convertJapanYear ($stDate) {
        //年月日の値を年、月、日にそれぞれ分割する
        $arrDate = explode( '-' , $stDate);
        //日付後ろの時間を取り除く
        $arrDate[2] = substr("$arrDate[2]" , 0, 2);
        
        //年月日の値が適正であるかチェックする
        if (!checkdate($arrDate[1], $arrDate[2], $arrDate[0]) || $arrDate[0] < 1800) {
            return false;
        }
        
        // 文字列から数字に変換
        $iDate = (int) sprintf('%04d%02d%02d', $arrDate[0], $arrDate[1], $arrDate[2]);
        
        if ($iDate >= 19890108) {
            // 1989年1月8日から平成
            // $stEra = '平成';
            $iJpYear = $arrDate[0] - 1988;
        } elseif ($iDate >= 19261225) {
            // 1926年12月25日から昭和
            //$stEra = '昭和';
            $iJpYear = $arrDate[0] - 1925;
        }
        
        if ($iJpYear == 1) {
            // 元年
            $stWareki = '元';
        } else {
            // 元年でない
            $stWareki = $iJpYear ;
        }
                
        return $stWareki;
    }
        
}