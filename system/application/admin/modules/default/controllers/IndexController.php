<?php

require_once 'Zend/Controller/Action.php';

class IndexController extends Zend_Controller_Action {

    /***
     * 
     * 初期化処理
     * 
     */
    public function init() {

        try {
            
            // Message
            $this->objMessage = new Message();

            // HTTP
            $objHttp = new Http();
            $objHttp->allowClientCacheCurrent();

            // Library & Models
            $this->objCommon = new Common();
            $this->objFormat = new Format();

            // テーブル定義
            $this->mdlCustomer = new Application_Model_Customer();
            $this->mdlMember = new Application_Model_Member();
            $this->mdlOrder = new Application_Model_Order();
            
            // 固定値定義
            // 権限
            $this->arrAuthority = array(System::SYSTEM_AUTHORITY_SYSTEMMANAGER => "システム管理者", 
                System::SYSTEM_AUTHORITY_SITEMANAGER => "サイト管理者", System::SYSTEM_AUTHORITY_OPERATOR => "一般オペレータ", 
                System::SYSTEM_AUTHORITY_LIMITEDOPERATOR => "制限オペレータ", System::SYSTEM_AUTHORITY_COUNTREADER => "売上集計閲覧者", 
                System::SYSTEM_AUTHORITY_SYSTEMDEVELOPER => "システム開発者");

            $this->objAdminSess = new Zend_Session_Namespace('Admin');

            // ----------------------------- 共通レイアウトのセット処理
            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();
            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->nav_tpl = "nav.tpl";
            $layout->footer_tpl = "footer.tpl";

            $this->view->assign("layout", $layout);
            $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);
            $this->view->assign("arrGlobalNavPos", $this->objCommon->getGlobalNavCurrentPos($this->objAdminSess->arrMenu));
            
            $objFormReq = $this->getRequest();
            // カレントモジュール、コントローラー、アクション名取得
            $this->stModuleName = $objFormReq->getModuleName();
            $this->stControllerName = $objFormReq->getControllerName();
            $this->stActionName = $objFormReq->getActionName();
            $this->stControllerName = $objFormReq->getControllerName();
            $this->view->assign("stCurrentModule", $this->stModuleName);
            $this->view->assign("stCurrentController", $this->stControllerName);
            $this->view->assign("stCurrentAction", $this->stActionName);

            // 共通テンプレへの変数渡し
            $view = $layout->getView();
            $view->assign("arrAuthority",  $this->arrAuthority);

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * ホーム画面表示アクション
     * 
     */
    public function indexAction() {

        try {

            // POST 値取得
            $stMode = $this->_getParam("mode");
            $objFormReq = $this->getRequest();
            
            if ($objFormReq->isPost()) {
                // post
                $arrForm = $objFormReq->getPost();
            } else {
                // get
            }
            
            $stOnload = $_SESSION["stOnload"];
            if ($stOnload != "") {
                $this->stOnload = "alert('" . $stOnload . "');";
                unset($_SESSION["stOnload"]);
            }

            // tplへの変数渡し
            $this->view->assign("stPageTitle", "HOME");
            $this->view->assign("stMode", $stMode);
            $this->view->assign("stOnload", $this->stOnload);
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            $this->view->assign("arrForm", $arrForm);

        } catch (Zend_Exception $e) {
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

            //入力されたログインIDとパスワードを取得
            $arrForm['loginid'] = $this->_getParam("loginid");
            $arrForm['password'] = $this->_getParam("password");

            // ----------------------------- 「戻る」ボタンでこのアクションに戻って来た時の為の判定
            // 現在ログイン中かどうかを判定する
            $bLogin = $this->isCheckLoginStatus();

            // ログイン中だった場合、returnする変数にセッションデータを格納する
            if( $bLogin == true ){
                // ログイン中だった場合AdminSessionの情報を$this->arrDataにセット
                $arrTemp =  $this->objAdminSess->getAdminSessionData();
                // 通常の取得結果形式に合わせる
                $this->arrData = array( $arrTemp );
            } else {
                // Email, Passwordが未入力の場合、再度入力画面へ転送する
                if($arrForm['loginid']  != '' && $arrForm['password']  != ''){
                    // 未ログインの場合入力されたアドレスとパスワードでログイン出来るか判定( 成功:顧客データ, 失敗:false )
                    $this->arrData = $this->isJudgeLogin( $arrForm['loginid'],  $arrForm['password'] );
                }else{
                    // ログイン用エラーメッセージ一覧を取得
                    $arrErrMessage = $this->objMessage ->getMessage( "login", $stLanguage );
                    // 入力されたID, Password
                    $this->_setParam( 'arrForm', $arrForm );
                    // 検索条件のエラー配列をerrmsgにセット
                    $this->_setParam( 'mode', 'ERROR' );
                    // Login画面に遷移
                    $this->_forward( 'index' );
                }
            }

            // ----------------------------- tplへの変数渡し
            // 検索条件のエラー配列をerrmsgにセット
            $this->_setParam( 'errmsg', $arrErrMsg );
            // 検索結果をresultにセット
            $this->_setParam( 'result',  $this->arrData );

            // ----------------------------- 会員情報判定によるリダイレクト処理
            if($this->arrData){
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
            throw new Zend_Exception($e->getMessage());
        }
    }

   /***
    *
    * 管理画面ログアウトアクション
    *
    *
    */
    public function logoutAction() {
        try {

            //ログイン画面へ
            if( isset($_SESSION['Admin'])){
                unset($_SESSION['Admin']);
                
                if (isset($_COOKIE[session_name()])) {
                    setcookie(session_name(), '', time()-42000, '/');
                }
                // 最終的に、セッションを破壊する
                session_destroy();
                return $this->_redirect(ADMIN_URL);
            }else{
                return $this->_redirect(ADMIN_URL);
            }

        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlMember->rollBack();
            }
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

//unset($_SESSION['Admin']);
//if (isset($_COOKIE[session_name()])) {
//    setcookie(session_name(), '', time()-42000, '/');
//}
//session_destroy();
//die('delete session');

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
            
        } catch(Zend_Exception $e) {
//            echo "<div>キャッチした例外: " . get_class($e) . "</div>";
//            echo "<div>メッセージ: " . $e->getMessage() . "</div>";
//            exit;
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     *
     * ログイン成功時にAdminSessionを
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

            // セッション初期化
            session_start();

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

        } catch(Zend_Exception $e) {
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
                // 入力されたメールアドレスとパスワードから顧客データを取得
                $arrData = $this->getLoginAdmin( $stAdmin_ID, $stAdmin_Password );
                // 取得結果が0件の場合falseをreturnにセット
                if( empty($arrData) ){
                    $arrData = false;
                } else {
                    // 取得したデータの顧客IDをセット
                    $iMemberID = $arrData[0]["d_system_member_SystemMemberID"];
                    // ログインした顧客用のSessionをスタートする
                    $this->startAdminSession( $arrData );
                }

                // ----------------------------- 実行結果を判定
                if( is_null($arrData) ){
                    throw new Zend_Exception('エラー');
                } else {
                    //return
                    return $arrData;
                }
            
        } catch(Zend_Exception $e) {
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
                $arrCondition["d_system_member_Password"] = $stAdminPassword;
                $arrCondition["d_system_member_Run"] = System::SYSTEM_RUN;
                $this->mdlMember->setSearchCondition($arrCondition);
                $arrData = $this->mdlMember->search();

                // ----------------------------- 実行結果を判定
                if( is_null($arrData) ){
                    //throw new Zend_Exception( 'ログイン判定用検索関数エラー' );
                } else {
                    //return
                    return $arrData;
                }
            
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     *
     * パスワードを照合する為のパスワードを生成する関数
     *
     * $stPassword = 入力されたパスワード
     * $stEncryptedPassword = DBに保存されているパスワード
     * 基本フロントからはこの関数が呼ばれる
     *
    */
    function VerificatePassword($stPassword,$stEncryptedPassword) {

        // password for verification
        $stPassword = $this->makePasswordForVerificate($stPassword,$stEncryptedPassword);

        if($stPassword == $stEncryptedPassword) {
            return true;
        } else {
            return false;
        }
    }

}
