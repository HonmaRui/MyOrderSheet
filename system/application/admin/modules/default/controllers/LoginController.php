<?php

/*--- require -----------------------------------------------------------------*/
// ZendのController読み込み
require_once 'Zend/Controller/Action.php';
/*-----------------------------------------------------------------------------*/

class LoginController extends Zend_Controller_Action {

    // default.iniのグローバル定義
    private $objIni;

    /* パーツのグローバル定義 */
    /* ----- 開発補助関連 ----- */ 
    // コレクション関連
    private $Collection = "";
    // 日付整形用の変数
    private $PreperDate = "";

    /* ----- 表示言語格納用 ----- */
    private $stLanguage = "";

    /* ----- 検索画面限定の変数 ----- */
    // 検索条件項目のキャプション
    private $SearchConditionCaption = "";
    // 検索結果項目のキャプション
    private $SearchResultCaption = "";
    // 検索取得対象
    private $SearchTarget = "";
    // 検索条件
    private $SearchCondition = "";
    // 検索条件のエラーチェック
    private $SearchErrCheck = "";

    private $CommonTemplates = "";


    /***
    * 初期化処理
    */
    public function init() {
        try{
            // Message
            $this->objMessage = new Message();
            // Format
//            $this->objFormat = new Format();
            //Common
            $this->objCommon = new Common();
            //System
            $this->objSystem = new System();
            //Validate
            $this->objValidate = new Validate();
            // Http
            $this->objHttp = new Http();
            // 基本チェッククラス
            $this->objBasicCheck = new BasicCheck();

            // テーブル定義
            $this->mdlMember = new Application_Model_Member();

            // 固定値定義
            // 権限
            $this->arrAuthority = array(System::SYSTEM_AUTHORITY_SYSTEMMANAGER => "システム管理者", 
                System::SYSTEM_AUTHORITY_SITEMANAGER => "サイト管理者", System::SYSTEM_AUTHORITY_OPERATOR => "一般オペレータ", 
                System::SYSTEM_AUTHORITY_LIMITEDOPERATOR => "制限オペレータ", System::SYSTEM_AUTHORITY_COUNTREADER => "売上集計閲覧者", 
                System::SYSTEM_AUTHORITY_SYSTEMDEVELOPER => "システム開発者");

            // ----------------------------- 共通レイアウトのセット処理
            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();

            $objFormReq = $this->getRequest();

            // 共通テンプレへの変数渡し
            $view = $layout->getView();
            $view->assign("arrAuthority",  $this->arrAuthority);

            $this->objAdminSess = new Zend_Session_Namespace('Admin');

        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
    * default アクション
    */    
    public function indexAction() {
        try{
            

//            echo "<p>【admin-Default-Login】" . get_class($this) . "::" . __FUNCTION__ . "</p>";
            
            // ----------------------------- メンバ変数宣言
            // POST 値取得
            $objFormReq = $this->getRequest();
            $stMode = $this->_getParam("mode");
            $stErrMessage = '';

            // ----------------------------- 「戻る」ボタンでこのアクションに戻って来た時の為の判定
            // 現在ログイン中かどうかを判定する(ログイン中=管理者ID, 非ログイン = false)
            $bLogin = $this->isCheckLoginStatus();

            // ログイン中だった場合、returnする変数にセッションデータを格納する
            if( $bLogin == true ){
                // ログイン中だった場合AdminSessionの情報を$this->arrDataにセット
                $arrSess =  $this->getAdminSessionData();
                // 取得結果の形式を合わせる
                $this->arrData = array( $arrSess );
                // 管理画面TOPへリダイレクト
                $this->_redirect(ADMIN_URL.'home');
            }

            // ----------------------------- ログインボタン押下後エラー出力する際のみ意味を成す処理
            // 条件を配列に格納
            $arrForm = $this->_getParam('arrForm');
            // エラー配列を格納
            $arrErrMsg = $this->_getParam('errmsg');

            // エラーメッセージをセット
            if($stMode == "ERROR"){
                $stErrMessage = "ID,もしくはPasswordが不正です。";
            }else{
                $stErrMessage = "";
            }

            // 画面タイトル
            $stPageTitle = "管理者ログイン";

            // ----------------------------- tplファイルへの変数渡し
            // zend_view
            $layout = new Zend_Layout();
            $view = $layout->getView();
            // 共通レイアウトの読み込み

            // ページタイトル
            $view->assign('stPageTitle', $stPageTitle);
            
            // login/judgementアクションから飛ばされてきた場合はエラーとし、エラーメッセージを表示する
            if ($_SERVER["HTTP_REFERER"] == ADMIN_URL . "login" || $_SERVER["HTTP_REFERER"] == ADMIN_URL . "judgement") {
                $this->view->assign("arrErrMessage", "IDまたはパスワードが正しくありません。");
            }

        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }
    }

   /***
    *
    * ログイン判定アクション
    *
    */  
    public function judgementAction() {
        try{
            // ----------------------------- メンバ変数の初期化
            // Request取得用object
            $objFormReq = "";
            // 入力内容のエラー一覧
            $arrErrMsg = array();
            // 検索結果の配列
            $arrData = array();
            // ログイン判定用変数
            $bJudge = false;

            // ----------------------------- メンバ変数の値セット
            // objFormreqにPOST値を格納
            $objFormReq = $this->getRequest();
            // ブラウザの設定言語取得
            $stLanguage = ECS_LANGUAGE;

            if (!$objFormReq->isPost()) {
                // Get request
                $arrForm = $arrEdit;
            } else {
                // Post request
                $arrFormTemp = $objFormReq->getPost();
                // 入力データの反映
                if ($arrForm != "") {
                    $arrForm = array_merge($arrForm, $arrFormTemp);
                } else {
                    $arrForm = $arrFormTemp;
                }
            }

            // ----------------------------- 「戻る」ボタンでこのアクションに戻って来た時の為の判定
            // 現在ログイン中かどうかを判定する
            $bLogin = $this->isCheckLoginStatus();

            // ログイン中だった場合、returnする変数にセッションデータを格納する
            if( $bLogin == true ){
                // ログイン中だった場合AdminSessionの情報を$this->arrDataにセット
                $arrTemp = $this->getAdminSessionData();

                // 通常の取得結果形式に合わせる
                $this->arrData = array( $arrTemp );
            } else {
                // Email, Passwordが未入力の場合、再度入力画面へ転送する
                if($arrForm['loginID']  != '' && $arrForm['password']  != '') {
                    // 未ログインの場合入力されたアドレスとパスワードでログイン出来るか判定( 成功:管理者データ, 失敗:false )
                    $this->arrData = $this->isJudgeLogin($arrForm['loginID'],  $arrForm['password']);
                } else {
                    // ログイン用エラーメッセージ一覧を取得
                    $arrErrMessage = $this->objMessage ->getMessage("login", $stLanguage);
                    // 入力されたID, Password
                    $this->_setParam('arrForm', $arrForm);
                    // 検索条件のエラー配列をerrmsgにセット
                    $this->_setParam('arrErrMessage', $arrErrMessage);
                    // ログイン画面に遷移
                    $this->_forward( 'index' );
                }
            }

            // ----------------------------- tplへの変数渡し
            // 検索条件のエラー配列をerrmsgにセット
            $this->_setParam( 'arrErrMsg', $arrErrMsg );
            // 検索結果をresultにセット
            $this->_setParam( 'arrResult',  $this->arrData );

            // ----------------------------- 管理者情報判定によるリダイレクト処理
            if($this->arrData){
                // DefaultControllerのTopへリダイレクト
                $this->objHttp->Redirect(ADMIN_URL.'home');
            }else{
                // ログイン用エラーメッセージ一覧を取得
                $arrErrMessage = $this->objMessage ->getMessage( "login", $stLanguage );
                // 入力されたID, Password
                $this->_setParam( 'arrForm', $arrForm );
                // 検索条件のエラー配列をerrmsgにセット
                $this->_setParam( 'errmsg', $arrErrMessage );
                // LoginControllerのindexへリダイレクト
                $this->_forward( 'index' );
            }

        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    //----------------------------------------------------------------------------------------------------------------**
    /***
     *
     * AdminSessionが存在するかどうかで現在のログイン状態を判定する関数
     *
    */
    public function isCheckLoginStatus( $stRedirectURL = NULL ){
        try{
            // ----------------------------- 初期設定のロード
            $this->Init();

            // ----------------------------- メンバ変数定義
            //Rtn用配列
            $bLogin = '';

            // ----------------------------- チェック実行
            // カラムを取得
            if( isset($_SESSION['Admin']) ){
                if( $_SESSION['Admin']['MemberID'] != null){
                    // objAdminSessにSESSION情報を格納
                    $this->startAdminSession();
                    $bLogin = true;
                }
            } else {
                // ログイン中でない場合は指定されたURLか管理ログイン画面にリダイレクトさせる
                if( $stRedirectURL != '' ){
                    $this->objHttp->Redirect($stRedirectURL);
                }else{
                    $bLogin = false;
                }
            }

            // ----------------------------- 実行結果を判定
            if( is_null($bLogin) ){
                throw new Zend_Exception('ログイン判定に失敗しました。');
            } else {
                //return
                return $bLogin;
            }
            
        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     *
     * ログイン成功時にAdminSessionを格納する関数
     *
     * $arrData = ログインに成功し、取得された管理者データ
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
                $this->objAdminSess->MemberID = $this->objAdminSess->SystemMemberID;

                // ログイン状態のフラグをセット
                $this->objAdminSess->Login = true;

                // 権限情報を読み込み
//                $arrSystemFunctionWork = $this->mdlSystemFunction->fetchAll();
//                $arrSystemFunctionDetailWork1 = $this->mdlSystemFunctionDetail1->fetchAll();
//                $arrSystemFunctionDetailWork2 = $this->mdlSystemFunctionDetail2->fetchAll();
//                $arrSystemFunction = array();
//                foreach ($arrSystemFunctionWork as $key => $value) {
//                    $arrSystemFunction[$value["m_system_function_SystemFunctionID"]] = $value;
//                }
//                $arrSystemFunctionDetail1 = array();
//                // 自分にアクセス権がある機能詳細を取得
//                foreach ($arrSystemFunctionDetailWork1 as $key => $value) {
//                    if ($value["m_system_function_detail1_Authority" . $this->objAdminSess->Authority] == 1) {
//                        $arrSystemFunctionDetail1[] = $value;
//                    }
//                }
//                foreach ($arrSystemFunctionDetailWork2 as $key => $value) {
//                    if ($value["m_system_function_detail2_Authority" . $this->objAdminSess->Authority] == 1) {
//                        $arrSystemFunctionDetail2[] = $value;
//                    }
//                }
//                
//                $this->objAdminSess->arrSystemFunction = $arrSystemFunction;
//                $this->objAdminSess->arrSystemFunctionDetail1 = $arrSystemFunctionDetail1;
//                $this->objAdminSess->arrSystemFunctionDetail2 = $arrSystemFunctionDetail2;
//
//                //メニュー用配列を作成
//                if (empty($this->objAdminSess->arrMenu)) {
//                    $this->objAdminSess->arrMenu = $this->mdlSystemFunction->createMenuArray();
//                    $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);
//                }

            } else {
                // objAdminSessにSession情報を格納
                $this->objAdminSess = $_SESSION['Admin'];
            }

            // Sessionが開始されたかチェック
            if( is_null($this->objAdminSess) ){
                // Authority がSupplierの場合はサプライヤーメイン画面へ遷移させる
                if($this->objAdminSess->Authority == System::SYSTEM_AUTHORITY_SYSTEMMANAGER ||
                    $this->objAdminSess->Authority == System::SYSTEM_AUTHORITY_SITEMANAGER ||
                    $this->objAdminSess->Authority == System::SYSTEM_AUTHORITY_SYSTEMDEVELOPER) {
                    $this->objHttp->Redirect(ADMIN_URL."home");
                } else {
                    $this->objHttp->Redirect(ADMIN_URL."basis");
                }
            }

        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
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

        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }

    }

    /***
     *
     * 各画面に応じた取得、対象カラムを取得する関数
     *
     * $stType = 画面タイプ( login, entry, edit のどれか )
     *
    */
    public function isJudgeLogin( $stAdmin_ID, $stAdmin_Password ){
        try{
                // ----------------------------- 初期設定のロード
                $this->Init();

                // ----------------------------- メンバ変数定義
                //Rtn用判定変数
                $arrData = array();
                $iMemberID = null;

                // ----------------------------- 取得処理を実行する為の変数設定
                // 入力されたログインIDとパスワードから管理者データを取得
                $arrData = $this->getLoginAdmin( $stAdmin_ID, $stAdmin_Password );
                // 取得結果が0件の場合falseをreturnにセット
                if( empty($arrData) ){
                    $arrData = false;
                } else {
                    // 取得したデータの管理者IDをセット
                    $iMemberID = $arrData[0]["d_system_member_SystemMemberID"];
                    // ログインした管理者用のSessionをスタートする
                    $this->startAdminSession( $arrData );
                }

                // ----------------------------- 実行結果を判定
                if( is_null($arrData) ){
                    throw new Zend_Exception('エラー');
                } else {
                    //return
                    return $arrData;
                }
            
        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     *
     * 各画面に応じた取得、対象カラムを取得する関数
     *
     * $stType = 画面タイプ( login, entry, edit のどれか )
     *
    */
    public function getLoginAdmin($stAdminID, $stAdminPassword ){
        try{
                // ----------------------------- 初期設定のロード
                $this->Init();

                // ----------------------------- メンバ変数定義
                //Rtn用判定配列
                $arrData = array();
                // ログイン判定結果用変数
                $bJudge = false;

                // ----------------------------- 取得処理を実行する為の変数設定
                $arrCondition = array();
                $arrCondition["d_system_member_LoginID"] = $stAdminID;
//                $arrCondition["d_system_member_Password"] = $stAdminPassword;
                $arrCondition["d_system_member_Run"] = System::SYSTEM_RUN;
                $this->mdlMember->setSearchCondition($arrCondition);
                $arrData = $this->mdlMember->search();
                
                // ----------------------------- 実行結果を判定
                if( is_null($arrData) ){
                    //throw new Zend_Exception( 'ログイン判定用検索関数エラー' );
                } else {
                    if ($this->objCommon->verificatePassword($stAdminPassword, $arrData[0]["d_system_member_Password"])) {
                        return $arrData;
                    } else {
                        //throw new Zend_Exception( 'ログイン判定用検索関数エラー' );
//return $arrData;
                    }
                }
            
        } catch (Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }
    }

}