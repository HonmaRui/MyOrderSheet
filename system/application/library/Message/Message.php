<?php
/**
 *
 *検索条件をPOST値から受け取り、検索条件を生成するパーツです。
 *
 *@author   M-PIC鈴木
 *@version  v1.0
 *
 */

class Message {
    
    public function Init(){
        // ----------------------------- 初期設定
//        // default_ini.phpファイルのクラス定義
//        $this->objIni = new ini();

        // ----------------------------- 共通クラスのロード処理
//        $Parts = $this->objIni->init();

        // ----------------------------- 共通クラスの変数化
        // 共通tplメニュー生成クラス
        // コレクション取得項目
//        $this->objCollection = new Collection();
        // 日付整形用の項目
//        $this->objPreperDate = new PreperDate();
        // Query
//        $this->objQuery = new Query();
        // 基本チェッククラス
//        $this->objBasicCheck = new BasicCheck();
        // UserAgent
//        $this->objUserAgent = new UserAgent();
        // BaseSession
//        $this->objBaseSess = new BaseSession();
        // CartSession
//        $this->objCartSess = new CartSession();
    }
    
    
    
    // Execption用エラーメッセージを返す関数
    public function getExceptionMessage($stClassName, $stMethodName, $e, $stPriority = "Warning") {
        try {
//            $this->Init();
//            $objHttp = new Http();
//            $stErrorURL = null;
//            $stUserAgent = $_SERVER['HTTP_USER_AGENT'];
//            if($stMethodName && $e) {
//                if(USE_ERROR_FORWARD) {
//                    if(!$this->objUserAgent->isAdmin()) {
//                        if($_SERVER["REMOTE_ADDR"] != DEBUGGER_IP_ADDRESS) {
//                            if($this->objUserAgent->isMobile()) {
//                                $objHttp->Redirect(ERROR_URL . '?PHPSESSID=' . Zend_Session::getId());
//                            } else {
//                                $objHttp->Redirect(ERROR_URL);
//                            }
//                        }
//                    }
//                }
//                
//                // ErrorURLをセット
//                if(isset($_SESSION['Base'])){
//                    $stRefererURL = $_SESSION['Base']['RefererUrl'];
//                    if(!isset($_SESSION['Admin'])){
//                        $stRefererURL = $_SESSION['Base']['RefererUrl'];
//                        $stErrorURL = $_SESSION['Base']['RefererUrl'].'⇒'.$_SESSION['Base']['LandingPageRow'];
//                        $stUserAgent = $_SESSION['Base']['Carrier'];
//                    }
//                }
//                
//                // エラーデータ生成
//                $arrData = array('ecs_exception_Priority' => $stPriority,
//                                 'ecs_exception_Message' => $e->getMessage(),
//                                 'ecs_exception_FileName' => $e->getFile(),
//                                 'ecs_exception_Line' => $e->getLine(),
//                                 'ecs_exception_Trace' => $e->getTraceAsString(),
//                                 'ecs_exception_IP' => $_SERVER["REMOTE_ADDR"],
//                                 'ecs_exception_ErrorURL' => $stErrorURL,
//                                 'ecs_exception_CustomerID' => $_SESSION['Customer']['CustomerID'],
//                                 'ecs_exception_UserAgent' => $stUserAgent
//                                 );
//                
//                // DBにエラー内容を登録
//                $iNewErrorId = $this->objQuery->doInsert( $arrData , null, "ecs_exception" );
//                
//                
//                if( preg_match("/cart/", $stRefererURL) ){
//                    // カートSessionをUnset
//                    Zend_Session::namespaceUnset('Cart');
//                }
//                
//                
//                echo "例外キャッチ " . $stClassName . ".$stMethodName: \n";
//                echo '<h3>Message</h3>';
//                echo $e->getMessage();
//                echo '<h3>File</h3>';
//                echo $e->getFile();
//                echo '<h3>Line</h3>';
//                echo $e->getLine();
//                echo '<h3>Trace</h3>';
//                echo '<pre>';
//                echo $e->getTraceAsString();
//                echo '</pre>';
//                exit;
//            } else {
//                $error = '例外キャッチ ' . get_called_class() . "." . __FUNCTION__ . "エラーメッセージが取得出来ませんでした。";
//                throw new Zend_Exception($error);
//            }
        } catch(Zend_Exception $e) {
//            echo '<h3>Message</h3>';
//            echo $e->getMessage();
//            echo '<h3>File</h3>';
//            echo $e->getFile();
//            echo '<h3>Line</h3>';
//            echo $e->getLine();
//            echo '<h3>Trace</h3>';
//            echo '<pre>';
//            echo $e->getTraceAsString();
//            echo '</pre>';
        }
    }
    
    public function getMessage($stMessageType, $stLanguage) {
        try{
            switch( $stMessageType ){
                // 新規・編集登録エラーメッセージ一覧
                case "login":
                    $arrMsg = $this->setLogIn_ErrMsg($stLanguage);
                    break;
                
                // 新規・編集登録エラーメッセージ一覧
                case "customer_entry":
                case "Edit_Confirm_ErrMessage":
                    $arrMsg = $this->setEdit_Confirm_ErrMsg($stLanguage);
                    break;          
                
                // 顧客検索検索エラーメッセージ一覧
                case "Search_Condirion_ErrMessage":
                    $arrMsg = $this->setSearch_Condition_ErrMsg($stLanguage);
                    break;
                    
                // 商品管理画面用メッセージ一覧
                case "product":
                    $arrMsg = $this->setProduct_ErrMsg($stLanguage);
                    break;
                    
                // 画像ファイルアップロード用エラーメッセージ一覧
                case "upload_file":
                    $arrMsg = $this->setUpload_File_ErrMsg($stLanguage);
                    break;

                // 受注管理画面用メッセージ一覧
                case "order":
                    $arrMsg = $this->setOrder_ErrMsg($stLanguage);
                    break;

                // default
                default:
                    $arrMsg = array();
                    break;
            }

            return $arrMsg;

        } catch (Exception $e) {
            echo "例外キャッチ search.getSearchCondition エラー：", $e->getMessage(), "\n";
        }
    }
    


    /**
    * ログイン判定エラーメッセージ
    *
    */
    public function setLogIn_ErrMsg($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "Email"=>"メールアドレスが未入力です。",
                    "Password"=>"パスワードが未入力です。",
                    "Other"=>"メールアドレス、または入力されたパスワードが無効です。",
                            );
                break;
            case "en_US":  // 英語
                $arrMsg = array(
                    "Email"=>"メールアドレスが未入力です。",
                    "Password"=>"パスワードが未入力です。",
                    "Other"=>"メールアドレス、または入力されたパスワードが無効です。",
                );
                break;
            default:
                $arrMsg = array(
                    "Email"=>"メールアドレスが未入力です。",
                    "Password"=>"パスワードが未入力です。",
                    "Other"=>"メールアドレス、または入力されたパスワードが無効です。",
                );
                break;

        }
    

            return $arrMsg;
    }


    /**
    * 顧客検索結果項目キャプション
    *
    */
    public function setSearch_Condition_ErrMsg($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "NotEmpty"=>"は空文字列を許可しません。",
                    "Int"=>"は数値を入力して下さい。",
                    "Alpha"=>"は半角英字で入力してください。",
                    "Alnum"=>"は半角英数字で入力してください。",
                    "Digits"=>"は数字を入力して下さい。",
                    "Date"=>"は日付を入力して下さい。'",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                );
                break;
            case "en_US":  // 英語
                $arrMsg = array(
                    "NotEmpty"=>" is NotEmpty!!! ",
                    "Int"=>" is Number Only!!! ",
                    "Alpha"=>"は半角英字で入力してください。",
                    "Alnum"=>"は半角英数字で入力してください。",
                    "Digits"=>" is Digits Only!!! ",
                    "Date"=>" is Date Only!!! ",
                    "Float"=>" is Float Only!!! ",
                );
                break;
            default:
                $arrMsg = array(
                    "NotEmpty"=>"は空文字列を許可しません。",
                    "Int"=>"は数値を入力して下さい。",
                    "Alpha"=>"は半角英字で入力してください。",
                    "Alnum"=>"は半角英数字で入力してください。",
                    "Digits"=>"は数字を入力して下さい。",
                    "Date"=>"は日付を入力して下さい。'",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                );
                break;

        }
    

            return $arrMsg;
    }

    /**
    * 顧客検索結果キャプション
    *
    */
    public function setEdit_Confirm_ErrMsg($stLanguage) {

        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "NotEmpty"=>"は必須入力です。",
                    "Int"=>"は数値を入力して下さい。",
                    "Alpha"=>"は半角英字で入力して下さい。",
                    "Alnum"=>"は半角英数字で入力してください。",
                    "Digits"=>"は数字を入力して下さい。",
                    "Date"=>"は日付を入力して下さい。'",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "Kana"=>"はカタカナのみ入力可です。",
                    "StringLength1"  => "は",
                    "StringLength2"  => "文字以上",
                    "StringLength3"  => "文字以下で入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Duplicate1" => "この",
                    "Duplicate2" => "は<br />既に登録されています。",
                );
                break;
            case "en_US":  // 英語
                $arrMsg = array(
                    "NotEmpty"=>" is NotEmpty!!! ",
                    "Int"=>" is Number only!!! ",
                    "Alpha"=>"は半角英字で入力して下さい。",
                    "Alnum"=>"は半角英数字で入力してください。",
                    "Digits"=>" is Digits only!!! ",
                    "Date"=>" is Date only!!! ",
                    "Float"=>" is Float only!!! ",
                    "Kana"=>"is Katakana only!",
                    "StringLength1"  => " is between ",
                    "StringLength2"  => " to ",
                    "StringLength3"  => " only!!",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                );
                break;
            default:
                $arrMsg = array(
                    "NotEmpty"=>"は必須入力です。",
                    "Int"=>"は数値を入力して下さい。",
                    "Alpha"=>"は半角英字で入力して下さい。",
                    "Alnum"=>"は半角英数字で入力してください。",
                    "Digits"=>"は数字を入力して下さい。",
                    "Date"=>"は日付を入力して下さい。'",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "Kana"=>"はカタカナのみ入力可です。",
                    "StringLength1"  => "は",
                    "StringLength2"  => "文字以上",
                    "StringLength3"  => "文字以下で入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                );
                break;

        }
        return $arrMsg;
    }


    /**
    * 商品管理画面用エラーキャプション
    *
    */
    public function setProduct_ErrMsg($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は数字で入力して下さい。",
                    "Alpha"=>"は半角英数のみで入力して下さい。",
                    "Alnum"=>"は英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Kana"=>"はカタカナで入力してください。",
                    "Date"=>"は日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "Reverse"=>"の入力が不正です。",
                    "Coupon"=>"が無効です。",
                    "Reverse1"=>"(下限)が入力されていません。",
                    "Reverse2"=>"(上限)が入力されていません。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。"
                
                );
                break;
            case "en_US":  // 英語（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は数字で入力して下さい。",
                    "Alpha"=>"は半角英数のみで入力して下さい。",
                    "Alnum"=>"は英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Kana"=>"はカタカナで入力してください。",
                    "Date"=>"は日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "Reverse"=>"の入力が不正です。",
                    "Coupon"=>"が無効です。",
                    "Reverse1"=>"(下限)が入力されていません。",
                    "Reverse2"=>"(上限)が入力されていません。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。"
                
                );
                break;
            default://（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は数字で入力して下さい。",
                    "Alpha"=>"は半角英数のみで入力して下さい。",
                    "Alnum"=>"は英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Kana"=>"はカタカナで入力してください。",
                    "Date"=>"は日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "Reverse"=>"の入力が不正です。",
                    "Coupon"=>"が無効です。",
                    "Reverse1"=>"(下限)が入力されていません。",
                    "Reverse2"=>"(上限)が入力されていません。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                );
                break;

        }
    
            return $arrMsg;
    }
    
    /**
    * 受注管理画面用エラーキャプション
    *
    */
    public function setOrder_ErrMsg($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は数字で入力して下さい。",
                    "Alpha"=>"は半角英数のみで入力して下さい。",
                    "Alnum"=>"は英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "Reverse"=>"の入力が不正です。",
                    "Coupon"=>"が無効です。",
                    "Reverse1"=>"(下限)が入力されていません。",
                    "Reverse2"=>"(上限)が入力されていません。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。"
                
                );
                break;
            case "en_US":  // 英語（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は数字で入力して下さい。",
                    "Alpha"=>"は半角英数のみで入力して下さい。",
                    "Alnum"=>"は英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "Reverse"=>"の入力が不正です。",
                    "Coupon"=>"が無効です。",
                    "Reverse1"=>"(下限)が入力されていません。",
                    "Reverse2"=>"(上限)が入力されていません。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。"
                
                );
                break;
            default://（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は数字で入力して下さい。",
                    "Alpha"=>"は半角英数のみで入力して下さい。",
                    "Alnum"=>"は英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "Reverse"=>"の入力が不正です。",
                    "Coupon"=>"が無効です。",
                    "Reverse1"=>"(下限)が入力されていません。",
                    "Reverse2"=>"(上限)が入力されていません。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                    
                );
                break;

        }
    
            return $arrMsg;
    }
    
    
    /**
    * 基本エラーキャプション
    *
    */
    public function getBasicErrorMessages($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は整数で入力して下さい。",
                    "Alpha"=>"は半角英字のみで入力して下さい。",
                    "Alnum"=>"は半角英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は正しい日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "IntOrFloat"=>"は整数か浮動小数点値で入力してください。",
                    "Kana"=>"は全角カタカナのみ入力可です。",
                    "Hiragana"=>"はひらがなのみ入力可です。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "StringLength4"=>"字以内で入力して下さい。",
                    "NumberLength1"=>"は",
                    "NumberLength2"=>"桁から",
                    "NumberLength3"=>"桁の間で入力して下さい。",
                    "LessThan1"=>"は",
                    "LessThan2"=>"未満で入力して下さい。",
                    "GreaterThan1"=>"は",
                    "GreaterThan2"=>"以上で入力して下さい。",
                    "Reverse1"=>"（下限）が",
                    "Reverse2"=>"（上限）を下回るよう条件を設定してください。",
                    "ReverseRange1"=>"が",
                    "ReverseRange2"=>"を下回るよう条件を設定してください。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                    "ReverseDateRange1"=>"は",
                    "ReverseDateRange2"=>"より前の日付になるよう条件を設定してください。",
                    "PreDate"=>"は現在より後の日付になるよう設定してください。",
                    "Coupon"=>"が無効です。",
                    "DuplicateCoupon1"=>"この",
                    "DuplicateCoupon2"=>"は既に登録されています。",
                    "Point"=>"が足りません。",
                    "EnablePoint"=>"ご利用ポイントは%value%pt以下で入力してください。",
                    "Tel" => "が入力されていません。",
                    "Mobile" => "が入力されていません。",
                    "Reserve" => "が入力されていません。",
                    "AllExist" => "が入力されていません。",
                    "Duplicate1" => "この",
                    "Duplicate2" => "は<br />既に登録されています。",
                    "Email" => "は正しい形式で入力して下さい。 ",
                    "Confirm" => "が一致しません。",
                    "Meta" => "に記号が含まれています。記号を削除してから再度送信して下さい。",
                    "MetaWithHyphen" => "にハイフン以外の記号が含まれています。記号を削除してから再度送信して下さい。",
                    "Url" => "のURLは正しい形式で入力してください。",
                    "MasterID" => "のIDが不正です。",
                    "Uploaded" => "が未指定です。",
                    "Extension" => "のファイル形式が不正です。",
                    "FileSize" => "はアップロードできる画像ファイルサイズは1MBまでです。",
                    "NotOverDate" => "は明日以降の日付は入力出来ません。",
                    "MobileEmail" => "は正しい形式で入力して下さい。",
                    "CouponUsable" => "ご入力いただいたクーポン番号は使用済みか利用可能な先着数を超えているためご利用いただけません。",
                    "CouponFloorPrice" => "ご入力いただいたクーポン番号は、商品金額合計%value%円以上からご利用いただけます。<br>現在の商品金額合計ではご利用いただけません。",
                    "CouponStartDate" => "ご入力いただいたクーポン番号は、%value%から有効です。現在はご利用いただけません。",
                    "CouponExpirationDate" => "ご入力いただいたクーポン番号は有効期限が切れているため、ご利用いただけません。",
                    "CouponStatus" => "ご入力いただいたクーポン番号に誤りがあるか、有効期間ではないなどの理由でご利用いただけません。<br>クーポン番号や有効期間をいま一度ご確認ください。<br>誤りがない場合は当サイトのお問い合わせ先までその旨お問い合わせください。",
                    "Int_Max" => "は数値の範囲内で入力して下さい。",
                    "Two_Byte" => "は半角文字列で入力して下さい。",
                    "Mime_False" => "のファイル形式が不正です。画像ファイルを選択してください。",
                    "Mime_Not_Detected" => "のファイル形式が不正です。",
                    "Mime_Not_Readable" => "のファイル形式が不正です。",
                    "Uploaded_No_Exist" => "のファイル形式が不正です。",
                );
                break;
            case "en_US":  // 英語（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は整数で入力して下さい。",
                    "Alpha"=>"は半角英字のみで入力して下さい。",
                    "Alnum"=>"は半角英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は正しい日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "IntOrFloat"=>"は整数か浮動小数点値で入力してください。",
                    "Kana"=>"は全角カタカナのみ入力可です。",
                    "Hiragana"=>"はひらがなのみ入力可です。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "StringLength4"=>"字以内で入力して下さい。",
                    "NumberLength1"=>"は",
                    "NumberLength2"=>"桁から",
                    "NumberLength3"=>"桁の間で入力して下さい。",
                    "LessThan1"=>"は",
                    "LessThan2"=>"以下で入力して下さい。",
                    "GreaterThan1"=>"は",
                    "GreaterThan2"=>"以上で入力して下さい。",
                    "Reverse1"=>"（下限）が",
                    "Reverse2"=>"（上限）を下回るよう条件を設定してください。",
                    "ReverseRange1"=>"が",
                    "ReverseRange2"=>"を下回るよう条件を設定してください。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                    "ReverseDateRange1"=>"は",
                    "ReverseDateRange2"=>"より前の日付になるよう条件を設定してください。",
                    "PreDate"=>"は現在より後の日付になるよう設定してください。",
                    "Coupon"=>"が無効です。",
                    "DuplicateCoupon1"=>"この",
                    "DuplicateCoupon2"=>"は既に登録されています。",
                    "Point"=>"が足りません。",
                    "Tel" => "が入力されていません。",
                    "Mobile" => "が入力されていません。",
                    "Reserve" => "が入力されていません。",
                    "AllExist" => "が入力されていません。",
                    "Duplicate1" => "この",
                    "Duplicate2" => "は<br />既に登録されています。",
                    "Email" => "は正しい形式で入力して下さい。 ",
                    "Confirm" => "が一致しません。",
                    "Meta" => "に記号が含まれています。記号を削除してから再度送信して下さい。",
                    "MetaWithHyphen" => "にハイフン以外の記号が含まれています。記号を削除してから再度送信して下さい。",
                    "Url" => "のURLは正しい形式で入力してください。",
                    "MasterID" => "のIDが不正です。",
                    "Uploaded" => "が未指定です。",
                    "Extension" => "のファイル形式が不正です。",
                    "FileSize" => "はアップロードできる画像ファイルサイズは1MBまでです。",
                    "NotOverDate" => "は明日以降の日付は入力出来ません。",
                    "MobileEmail" => "は正しい形式で入力して下さい。",
                    "CouponUsable" => "ご入力いただいたクーポン番号は使用済みか利用可能な先着数を超えているためご利用いただけません。",
                    "CouponFloorPrice" => "ご入力いただいたクーポン番号は、商品金額合計%value%円以上からご利用いただけます。<br>現在の商品金額合計ではご利用いただけません。",
                    "CouponStartDate" => "ご入力いただいたクーポン番号は、%value%から有効です。現在はご利用いただけません。",
                    "CouponExpirationDate" => "ご入力いただいたクーポン番号は有効期限が切れているため、ご利用いただけません。",
                    "CouponStatus" => "ご入力いただいたクーポン番号に誤りがあるか、有効期間ではないなどの理由でご利用いただけません。<br>クーポン番号や有効期間をいま一度ご確認ください。<br>誤りがない場合は当サイトのお問い合わせ先までその旨お問い合わせください。",
                    "Int_Max" => "は数値の範囲内で入力して下さい。",
                    "Two_Byte" => "は半角文字列で入力して下さい。",
                    "Mime_False" => "のファイル形式が不正です。画像ファイルを選択してください。",
                    "Mime_Not_Detected" => "のファイル形式が不正です。",
                    "Mime_Not_Readable" => "のファイル形式が不正です。",
                    "Uploaded_No_Exist" => "のファイル形式が不正です。",
                );
                break;
            default://（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "Int"=>"は整数で入力して下さい。",
                    "Alpha"=>"は半角英字のみで入力して下さい。",
                    "Alnum"=>"は半角英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は正しい日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "IntOrFloat"=>"は整数か浮動小数点値で入力してください。",
                    "Kana"=>"は全角カタカナのみ入力可です。",
                    "Hiragana"=>"はひらがなのみ入力可です。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "StringLength4"=>"字以内で入力して下さい。",
                    "NumberLength1"=>"は",
                    "NumberLength2"=>"桁から",
                    "NumberLength3"=>"桁の間で入力して下さい。",
                    "LessThan1"=>"は",
                    "LessThan2"=>"以下で入力して下さい。",
                    "GreaterThan1"=>"は",
                    "GreaterThan2"=>"以上で入力して下さい。",
                    "Reverse1"=>"（下限）が",
                    "Reverse2"=>"（上限）を下回るよう条件を設定してください。",
                    "ReverseRange1"=>"が",
                    "ReverseRange2"=>"を下回るよう条件を設定してください。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                    "ReverseDateRange1"=>"は",
                    "ReverseDateRange2"=>"より前の日付になるよう条件を設定してください。",
                    "PreDate"=>"は現在より後の日付になるよう設定してください。",
                    "Coupon"=>"が無効です。",
                    "DuplicateCoupon1"=>"この",
                    "DuplicateCoupon2"=>"は既に登録されています。",
                    "Point"=>"が足りません。",
                    "Tel" => "が入力されていません。",
                    "Mobile" => "が入力されていません。",
                    "Reserve" => "が入力されていません。",
                    "AllExist" => "が入力されていません。",
                    "Duplicate1" => "この",
                    "Duplicate2" => "は<br />既に登録されています。",
                    "Email" => "は正しい形式で入力して下さい。 ",
                    "Confirm" => "が一致しません。",
                    "Meta" => "に記号が含まれています。記号を削除してから再度送信して下さい。",
                    "MetaWithHyphen" => "にハイフン以外の記号が含まれています。記号を削除してから再度送信して下さい。",
                    "Url" => "のURLは正しい形式で入力してください。",
                    "MasterID" => "のIDが不正です。",
                    "Uploaded" => "が未指定です。",
                    "Extension" => "のファイル形式が不正です。",
                    "FileSize" => "はアップロードできる画像ファイルサイズは1MBまでです。",
                    "NotOverDate" => "は明日以降の日付は入力出来ません。",
                    "MobileEmail" => "は正しい形式で入力して下さい。",
                    "CouponUsable" => "ご入力いただいたクーポン番号は使用済みか利用可能な先着数を超えているためご利用いただけません。",
                    "CouponFloorPrice" => "ご入力いただいたクーポン番号は、商品金額合計%value%円以上からご利用いただけます。<br>現在の商品金額合計ではご利用いただけません。",
                    "CouponStartDate" => "ご入力いただいたクーポン番号は、%value%から有効です。現在はご利用いただけません。",
                    "CouponExpirationDate" => "ご入力いただいたクーポン番号は有効期限が切れているため、ご利用いただけません。",
                    "CouponStatus" => "ご入力いただいたクーポン番号に誤りがあるか、有効期間ではないなどの理由でご利用いただけません。<br>クーポン番号や有効期間をいま一度ご確認ください。<br>誤りがない場合は当サイトのお問い合わせ先までその旨お問い合わせください。",
                    "Int_Max" => "は数値の範囲内で入力して下さい。",
                    "Two_Byte" => "は半角文字列で入力して下さい。",
                    "Mime_False" => "のファイル形式が不正です。画像ファイルを選択してください。",
                    "Mime_Not_Detected" => "のファイル形式が不正です。",
                    "Mime_Not_Readable" => "のファイル形式が不正です。",
                    "Uploaded_No_Exist" => "のファイル形式が不正です。",
                );
                break;

        }
    
            return $arrMsg;
    }
    
    /**
     * 管理画面クーポン有効性チェック用エラーメッセージ
     */
    public function getCouponErrorMessageForAdmin($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "CouponUsable" => "ご入力いただいたクーポン番号は使用済みか利用可能な先着数を超えているためご利用いただけません。",
                    "CouponFloorPrice" => "ご入力いただいたクーポン番号は、商品金額合計%value%円以上からご利用いただけます。<br>現在の商品金額合計ではご利用いただけません。",
                    "CouponStartDate" => "ご入力いただいたクーポン番号は、%value%から有効です。現在はご利用いただけません。",
                    "CouponExpirationDate" => "ご入力いただいたクーポン番号は有効期限が切れているため、ご利用いただけません。",
                    "CouponStatus" => "ご入力いただいたクーポン番号に誤りがあるか、有効期間ではないなどの理由でご利用いただけません。<br>クーポン番号や有効期間をいま一度ご確認ください。<br>誤りがない場合は当サイトのお問い合わせ先までその旨お問い合わせください。",
                );
                break;
            case "en_US":  // 英語（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "CouponUsable" => "ご入力いただいたクーポン番号は使用済みか利用可能な先着数を超えているためご利用いただけません。",
                    "CouponFloorPrice" => "ご入力いただいたクーポン番号は、商品金額合計%value%円以上からご利用いただけます。<br>現在の商品金額合計ではご利用いただけません。",
                    "CouponStartDate" => "ご入力いただいたクーポン番号は、%value%から有効です。現在はご利用いただけません。",
                    "CouponExpirationDate" => "ご入力いただいたクーポン番号は有効期限が切れているため、ご利用いただけません。",
                    "CouponStatus" => "ご入力いただいたクーポン番号に誤りがあるか、有効期間ではないなどの理由でご利用いただけません。<br>クーポン番号や有効期間をいま一度ご確認ください。<br>誤りがない場合は当サイトのお問い合わせ先までその旨お問い合わせください。",

                );
                break;
            default://（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "CouponUsable" => "ご入力いただいたクーポン番号は使用済みか利用可能な先着数を超えているためご利用いただけません。",
                    "CouponFloorPrice" => "ご入力いただいたクーポン番号は、商品金額合計%value%円以上からご利用いただけます。<br>現在の商品金額合計ではご利用いただけません。",
                    "CouponStartDate" => "ご入力いただいたクーポン番号は、%value%から有効です。現在はご利用いただけません。",
                    "CouponExpirationDate" => "ご入力いただいたクーポン番号は有効期限が切れているため、ご利用いただけません。",
                    "CouponStatus" => "ご入力いただいたクーポン番号に誤りがあるか、有効期間ではないなどの理由でご利用いただけません。<br>クーポン番号や有効期間をいま一度ご確認ください。<br>誤りがない場合は当サイトのお問い合わせ先までその旨お問い合わせください。",
                );
                break;
        }
    
            return $arrMsg;
    }
    
    /**
    * 基本エラーキャプション
    *
    */
    public function getFrontSearchErrorMessages($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "NotSelect"=>"を選択して下さい。",
                    "Int"=>"は整数で入力して下さい。",
                    "Alpha"=>"は半角英字のみで入力して下さい。",
                    "Alnum"=>"は半角英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は正しい日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "Kana"=>"は全角カタカナのみ入力可です。",
                    "Hiragana"=>"はひらがなのみ入力可です。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "StringLength4"=>"字以内で入力して下さい。",
                    "NumberLength1"=>"は",
                    "NumberLength2"=>"桁から",
                    "NumberLength3"=>"桁の間で入力して下さい。",
                    "LessThan1"=>"は",
                    "LessThan2"=>"以下で入力して下さい。",
                    "GreaterThan1"=>"は",
                    "GreaterThan2"=>"以上で入力して下さい。",
                    "Reverse1"=>"（下限）が",
                    "Reverse2"=>"（上限）を下回るよう条件を設定してください。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                    "Coupon"=>"が無効です。",
                    "DuplicateCoupon1"=>"この",
                    "DuplicateCoupon2"=>"は既に登録されています。",
                    "Point"=>"が足りません。",
                    "Tel" => "が入力されていません。",
                    "Mobile" => "が入力されていません。",
                    "Reserve" => "が入力されていません。",
                    "AllExist" => "が入力されていません。",
                    "Duplicate1" => "この",
                    "Duplicate2" => "は<br />既に登録されています。",
                    "Email" => "は正しい形式で入力して下さい。 ",
                    "Confirm" => "が一致しません。",
                    "Meta" => "に記号が含まれています。記号を削除してから再度検索して下さい。",
                    "MetaWithHyphen" => "にハイフン以外の記号が含まれています。記号を削除してから再度検索して下さい。",
                    "Url" => "のURLは正しい形式で入力してください。",
                    "MasterID" => "のIDが不正です。",
                    "Uploaded" => "が未指定です。",
                    "Extension" => "のファイル形式が不正です。",
                    "FileSize" => "はアップロードできる画像ファイルサイズは1MBまでです。",
                    "NotOverDate" => "は明日以降の日付は入力出来ません。",
                    "MobileEmail" => "は正しい形式で入力して下さい。",
                    "Int_Max" => "は数値の範囲内で入力して下さい。",
                    "Two_Byte" => "は半角文字列で入力して下さい。"
                );
                break;
            case "en_US":  // 英語（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "NotSelect"=>"を選択して下さい。",
                    "Int"=>"は整数で入力して下さい。",
                    "Alpha"=>"は半角英字のみで入力して下さい。",
                    "Alnum"=>"は半角英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は正しい日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "Kana"=>"は全角カタカナのみ入力可です。",
                    "Hiragana"=>"はひらがなのみ入力可です。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "StringLength4"=>"字以内で入力して下さい。",
                    "NumberLength1"=>"は",
                    "NumberLength2"=>"桁から",
                    "NumberLength3"=>"桁の間で入力して下さい。",
                    "LessThan1"=>"は",
                    "LessThan2"=>"以下で入力して下さい。",
                    "GreaterThan1"=>"は",
                    "GreaterThan2"=>"以上で入力して下さい。",
                    "Reverse1"=>"（下限）が",
                    "Reverse2"=>"（上限）を下回るよう条件を設定してください。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                    "Coupon"=>"が無効です。",
                    "DuplicateCoupon1"=>"この",
                    "DuplicateCoupon2"=>"は既に登録されています。",
                    "Point"=>"が足りません。",
                    "Tel" => "が入力されていません。",
                    "Mobile" => "が入力されていません。",
                    "Reserve" => "が入力されていません。",
                    "AllExist" => "が入力されていません。",
                    "Duplicate1" => "この",
                    "Duplicate2" => "は<br />既に登録されています。",
                    "Email" => "は正しい形式で入力して下さい。 ",
                    "Confirm" => "が一致しません。",
                    "Meta" => "に記号が含まれています。記号を削除してから再度検索して下さい。",
                    "MetaWithHyphen" => "にハイフン以外の記号が含まれています。記号を削除してから再度検索して下さい。",
                    "Url" => "のURLは正しい形式で入力してください。",
                    "MasterID" => "のIDが不正です。",
                    "Uploaded" => "が未指定です。",
                    "Extension" => "のファイル形式が不正です。",
                    "FileSize" => "はアップロードできる画像ファイルサイズは1MBまでです。",
                    "NotOverDate" => "は明日以降の日付は入力出来ません。",
                    "MobileEmail" => "は正しい形式で入力して下さい。",
                    "Int_Max" => "は数値の範囲内で入力して下さい。",
                    "Two_Byte" => "は半角文字列で入力して下さい。"
                );
                break;
            default://（未実装）
                $arrMsg = array(
                    "NotEmpty"=>"が入力されていません。",
                    "NotSelect"=>"を選択して下さい。",
                    "Int"=>"は整数で入力して下さい。",
                    "Alpha"=>"は半角英時のみで入力して下さい。",
                    "Alnum"=>"は半角英数字で入力して下さい。",
                    "Digits"=>"は数字で入力して下さい。",
                    "Date"=>"は正しい日付を入力して下さい。",
                    "Float"=>"は浮動小数点値を入力して下さい。",
                    "Kana"=>"は全角カタカナのみ入力可です。",
                    "Hiragana"=>"はひらがなのみ入力可です。",
                    "EmailAddress"=> array("INVALID" => "メールアドレスは文字列で入力してください。",
                                           "INVALID_FORMAT" => "メールアドレスの形式が不正です。",
                                           "INVALID_HOSTNAME" => "入力されたメールアドレスは無効です。",
                                           "INVALID_MX_RECORD" => "入力されたメールアドレスは無効です。",
                                           "INVALID_SEGMENT" => "入力されたメールアドレスは無効です。",
                                           "DOT_ATOM" => "メールアドレスの形式が不正です。",
                                           "QUOTED_STRING" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_PART" => "入力されたメールアドレスは無効です。",
                                           "LENGTH_EXCEEDED" => "メールアドレスの形式が不正です。",
                                           "INVALID_LOCAL_NAME" => "入力されたメールアドレスは無効です。",
                                          ),
                    "Ccnum"=>"が不正です。",
                    "Ip"=>"のアドレスが不正です。",
                    "StringLength1"=>"は",
                    "StringLength2"=>"字から",
                    "StringLength3"=>"字の間で入力して下さい。",
                    "StringLength4"=>"字以内で入力して下さい。",
                    "NumberLength1"=>"は",
                    "NumberLength2"=>"桁から",
                    "NumberLength3"=>"桁の間で入力して下さい。",
                    "LessThan1"=>"は",
                    "LessThan2"=>"以下で入力して下さい。",
                    "GreaterThan1"=>"は",
                    "GreaterThan2"=>"以上で入力して下さい。",
                    "Reverse1"=>"（下限）が",
                    "Reverse2"=>"（上限）を下回るよう条件を設定してください。",
                    "ReverseDate"=>"は下限が上限を下回るよう条件を設定してください。",
                    "Coupon"=>"が無効です。",
                    "DuplicateCoupon1"=>"この",
                    "DuplicateCoupon2"=>"は既に登録されています。",
                    "Point"=>"が足りません。",
                    "Tel" => "が入力されていません。",
                    "Mobile" => "が入力されていません。",
                    "Reserve" => "が入力されていません。",
                    "AllExist" => "が入力されていません。",
                    "Duplicate1" => "この",
                    "Duplicate2" => "は<br />既に登録されています。",
                    "Email" => "は正しい形式で入力して下さい。 ",
                    "Confirm" => "が一致しません。",
                    "Meta" => "に記号が含まれています。記号を削除してから再度検索して下さい。",
                    "MetaWithHyphen" => "にハイフン以外の記号が含まれています。記号を削除してから再度検索して下さい。",
                    "Url" => "のURLは正しい形式で入力してください。",
                    "MasterID" => "のIDが不正です。",
                    "Uploaded" => "が未指定です。",
                    "Extension" => "のファイル形式が不正です。",
                    "FileSize" => "はアップロードできる画像ファイルサイズは1MBまでです。",
                    "NotOverDate" => "は明日以降の日付は入力出来ません。",
                    "MobileEmail" => "は正しい形式で入力して下さい。",
                    "Int_Max" => "は数値の範囲内で入力して下さい。",
                    "Two_Byte" => "は半角文字列で入力して下さい。"
                );
                break;

        }
    
            return $arrMsg;
    }
    
   /**
    * ファイルアップロード時のエラーメッセージ
    */
    public function setUpload_File_ErrMsg($stLanguage) {
    
        // 検索項目定義
        switch($stLanguage){
            case "ja":  // 日本語
                $arrMsg = array(
                    "Extension"=>"ファイルの拡張子が不正です。",
                    "Uploaded"=>"ファイルの取得に失敗しました。",
                    "Size"=>"画像ファイルのサイズが大きすぎます"
                );
                break;
                
            case "en_US":  // 英語(未実装)
                $arrMsg = array(
                    "Extension"=>"ファイルの拡張子が不正です。",
                    "Uploaded"=>"ファイルの取得に失敗しました。"
                );
                break;
                
            default:
                $arrMsg = array(
                    "Extension"=>"ファイルの拡張子が不正です。",
                    "Uploaded"=>"ファイルの取得に失敗しました。"
                );
                break;
                
        }
        return $arrMsg;
    }

}
