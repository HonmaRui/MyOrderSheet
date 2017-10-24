<?php

class Basis_MemberController extends Zend_Controller_Action {

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
            $this->objCommon = new Common();
            $this->objFormat = new Format();
            $this->objMaster = new Master();
            $this->mdlMember = new Application_Model_Member();
            
            // 権限確認
            $bAuthority = $this->objCommon->isAuthority($this->_request->getServer("REQUEST_URI"));
            if (!$bAuthority) {
                $this->_redirect(ADMIN_URL . "home");
            }
            
            // カレントモジュール、コントローラー、アクション名取得
            $objFormReq = $this->getRequest();
            $this->stModuleName = $objFormReq->getModuleName();
            $this->stControllerName = $objFormReq->getControllerName();
            $this->stActionName = $objFormReq->getActionName();
            
            $this->stControllerName = $objFormReq->getControllerName();
            $this->view->assign("stCurrentModule", $this->stModuleName);
            $this->view->assign("stCurrentController", $this->stControllerName);
            $this->view->assign("stCurrentAction", $this->stActionName);
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 画面表示アクション
     * 
     */
    public function indexAction() {

        $bTran = false;
        try {
            
            // (ポップアップでは読み込まないため個別に宣言)
            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();

            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->footer_tpl = "footer.tpl";
            $layout->nav_tpl = "nav.tpl";
            $this->view->assign("layout", $layout);
            
            $this->objAdminSess = new Zend_Session_Namespace("Admin");
            
            $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);
            $this->view->assign("arrGlobalNavPos", $this->objCommon->getGlobalNavCurrentPos($this->objAdminSess->arrMenu));
            
            $objFormReq = $this->getRequest();
            $stMode = $this->_getParam("mode");
            $stPostCsrf = $this->_getParam("csrf", null);
            $stRedirectURL = ADMIN_URL . "basis/member";

            if ($objFormReq->isPost()) {
                $arrForm = $objFormReq->getPost();
                // CSRF対策チェック
                CommonTools::checkTokenData("member", $stPostCsrf);
            }
            
            // 表示順は半角数字に変換する
            foreach ($arrForm as $key => &$value) {
                if (preg_match("/^posnum[0-9]+/", $key)) {
                    $arrForm[$key] = mb_convert_kana($arrForm[$key], "n", "UTF-8");
                }
                unset($value);
            }

            switch ($stMode) {
                case "update":
                    $iMemberID = $this->_getParam("updateMemberID");
                    $this->mdlMember->begin();
                    $bTran = true;
                    $arrUpdate["d_system_member_SystemMemberID"] = $iMemberID;
                    $arrUpdate["d_system_member_Run"] = $arrForm["d_system_member_Run" . $iMemberID];
                    $this->mdlMember->save($arrUpdate);
                    $this->mdlMember->commit();
                    // ログ収集
                    $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                    unset($arrForm);
                    break;
                case "del":
                    $this->mdlMember->begin();
                    $bTran = true;
                    $this->mdlMember->delete($arrForm["delMemberID"]);
                    $this->mdlMember->commit();
                    
                    if ($objFormReq->isPost()) {
                        // ログ収集テーブル
                        $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                    }                    
                    unset($arrForm);
                    break;
                case "order":
                    $this->mdlMember->begin();
                    $bTran = true;

                    $arrResult = array();
                    $arrResult = $this->mdlMember->find(array("d_system_member_SystemMemberID" => $arrForm["srcMemberID"]), array("d_system_member_Rank"));
                    $arrUpdate["d_system_member_SystemMemberID"] = $arrForm["dstMemberID"];
                    $arrUpdate["d_system_member_Rank"] = $arrResult["d_system_member_Rank"];
                    $this->mdlMember->save($arrUpdate);

                    $arrResult = array();
                    $arrResult = $this->mdlMember->find(array("d_system_member_SystemMemberID" => $arrForm["dstMemberID"]), array("d_system_member_Rank"));
                    $arrUpdate["d_system_member_SystemMemberID"] = $arrForm["srcMemberID"];
                    $arrUpdate["d_system_member_Rank"] = $arrResult["d_system_member_Rank"];
                    $this->mdlMember->save($arrUpdate);
                    $this->mdlMember->commit();
                    
                    if ($objFormReq->isPost()) {
                        // ログ収集テーブル
                        $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                    }                    
                    unset($arrForm);
                    // ブラウザの更新による再submitを防ぐためredirectとする
                    $this->_redirect($stRedirectURL);
                case "move":
                    // ○番目へ移動
                    // エラーチェック
                    $arrErrorMessage[$arrForm["moveMemberID"]] = $this->objMaster->errorCheckForMember($arrForm, $stMode);
                    if ($arrErrorMessage[$arrForm["moveMemberID"]]) {
                        $stMode = "";
                        break;
                    }
                    
                    $this->mdlMember->begin();
                    $bTran = true;

                    $arrResult = $this->mdlMember->fetchAll();

                    // 入力された番号取得
                    $iInputRow = $arrForm["posnum"];                // 選択行(0始まり)
                    $iDstRow = $arrForm["posnum" . $iInputRow] - 1; // 入力値
                    
                    // 最大表示順の取得
                    $iRank = 0;
                    foreach ($arrResult as $key => $value) {
                        if ($value["d_system_member_Rank"] > $iRank) {
                            $iRank = $value["d_system_member_Rank"];
                        }
                    }

                    // 入力値が最大表示順-1(現在の表示の最下部)を上回る場合は、入力値を最大表示順-1に修正する
                    if ($iRank - 1 < $iDstRow) {
                        $iDstRow = $iRank - 1;
                    }

                    // 移動処理
                    if ($iInputRow < $iDstRow) {
                        // 移動元より下に移動する場合
                        for($i = $iInputRow; $i <= $iDstRow ;$i++) {
                            if ($i == $iInputRow) {
                                // 移動元の表示順を移動先の表示順に設定する
                                $arrUpdate["d_system_member_SystemMemberID"] = $arrForm["moveMemberID"];
                                $arrUpdate["d_system_member_Rank"] = $arrResult[$iDstRow]["d_system_member_Rank"];
                                $this->mdlMember->save($arrUpdate);
                            } else {
                                // 移動元から移動先の間の表示順を1ずつ繰り上げる
                                $arrUpdate["d_system_member_SystemMemberID"] = $arrResult[$i]["d_system_member_SystemMemberID"];
                                $arrUpdate["d_system_member_Rank"] = $arrResult[$i]["d_system_member_Rank"] - 1;
                                $this->mdlMember->save($arrUpdate);
                            }
                        }
                    } else {
                        // 移動元より上に移動する場合(移動元と同じ表示順が入力されていた場合も含む)
                        for($i = $iInputRow; $i >= $iDstRow ;$i--) {
                            if ($i == $iInputRow) {
                                // 移動元の表示順を移動先の表示順に設定する
                                $arrUpdate["d_system_member_SystemMemberID"] = $arrForm["moveMemberID"];
                                $arrUpdate["d_system_member_Rank"] = $arrResult[$iDstRow]["d_system_member_Rank"];
                                $this->mdlMember->save($arrUpdate);
                            } else {
                                // 移動元から移動先の間の表示順を1ずつ繰り下げる
                                $arrUpdate["d_system_member_SystemMemberID"] = $arrResult[$i]["d_system_member_SystemMemberID"];
                                $arrUpdate["d_system_member_Rank"] = $arrResult[$i]["d_system_member_Rank"] + 1;
                                $this->mdlMember->save($arrUpdate);
                            }
                        }
                    }
                    $this->mdlMember->commit();

                    // ログ収集テーブル
                    $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                    
                    // ブラウザの更新による再submitを防ぐためredirectとする
                    $this->_redirect($stRedirectURL);
                default;
                    break;
            }            
 
            // メンバー管理テーブルの検索実行
            $arrResult = $this->mdlMember->fetchAll();
            
            for ($i = 0; $i < count($arrResult); $i++) {
                // 表示順入れ替え用に、前後の表示順を設定する
                $arrResult[$i]["beforeMemberID"] = 0;
                $arrResult[$i]["nextMemberID"] = 0;
                if ($i > 0) {
                    $arrResult[$i]["beforeMemberID"] = $arrResult[$i - 1]["d_system_member_SystemMemberID"];
                    $arrResult[$i - 1]["nextMemberID"] = $arrResult[$i]["d_system_member_SystemMemberID"];
                }
            }
            
            // 権限
            $arrAuthority = array(
                Application_Model_Member::SYSTEM_AUTHORITY_SYSTEMMANAGER => "システム管理者",
                Application_Model_Member::SYSTEM_AUTHORITY_SITEMANAGER => "サイト管理者",
                Application_Model_Member::SYSTEM_AUTHORITY_OPERATOR => "一般オペレータ",
                Application_Model_Member::SYSTEM_AUTHORITY_LIMITEDOPERATOR => "制限オペレータ",
                Application_Model_Member::SYSTEM_AUTHORITY_COUNTREADER => "売上集計閲覧者",
                Application_Model_Member::SYSTEM_AUTHORITY_SYSTEMDEVELOPER => "システム開発者"
            );
            
            // 有効/無効
            $arrRun = array(
                Application_Model_Member::SYSTEM_RUN => "有効",
                Application_Model_Member::SYSTEM_NOTRUN => "無効"
            );
            
            // CSRF対策実装
            $stCsrf = CommonTools::generateTokenData("member");
            
            $iSystemMemberID = $this->objAdminSess->MemberID;
            $this->view->assign("iSystemMemberID", $iSystemMemberID);
 
            $this->view->assign("arrAuthority", $arrAuthority);
            $this->view->assign("arrRun", $arrRun);
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            $this->view->assign("stCsrf", $stCsrf);
            $this->view->assign("stMode", $stMode);
            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("arrResult", $this->objFormat->Escape($arrResult));
            $this->view->assign("stPageTitle", "担当者マスタ");

        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlMember->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 登録アクション
     * 
     */
    public function addPopupAction() {

        $bTran = false;
        try {
            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();
            
            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->footer_tpl = "footer.tpl";
            $this->view->assign("layout", $layout);

            $this->objAdminSess = new Zend_Session_Namespace("Admin");

            // 共通テンプレへの変数渡し
            $view = $layout->getView();
            $view->assign("arrAuthority", $this->arrAuthority);

            $objFormReq = $this->getRequest();
            $stMode = $this->_getParam("mode");
            $stPostCsrf = $this->_getParam("csrf", null);

            $iMemberID = $this->_getParam("editMemberID");

            if (!$objFormReq->isPost()) {
                // Get request
            } else {
                if ($iMemberID && ($stMode == "create" || $stMode == "edit")) {
                    $arrForm = $this->mdlMember->find($iMemberID);
                }
                // Post request
                if ($stMode == "add" || $stMode == "edit") {
                    $stSalt = "member";
                } else {
                    $stSalt = "create";
                }
                CommonTools::checkTokenData($stSalt, $stPostCsrf);
                $arrFormTemp = $objFormReq->getPost();

                // 入力データの反映
                if ($arrForm != "") {
                    $arrForm = array_merge($arrForm, $arrFormTemp);
                } else {
                    $arrForm = $arrFormTemp;
                }

                switch ($stMode) {
                    case "create":
                        $arrErrorMessage = $this->objMaster->errorCheckForMember($arrForm, $stMode);
                        if ($arrErrorMessage) {
                            $stMode = "";
                            break;
                        }

                        // ログインID重複チェック
                        $arrMember = $this->mdlMember->findAll(array("d_system_member_LoginID" => $arrForm["d_system_member_LoginID"]), array("d_system_member_SystemMemberID"));
                        if ($arrForm["d_system_member_SystemMemberID"] == "") {
                            // 新規
                            if (count($arrMember) > 0) {
                                $arrErrorMessage["d_system_member_LoginID"] = "入力されたログインIDは既に登録されています。";
                                break;
                            }
                        } else {
                            // 更新
                            if ($arrForm["d_system_member_SystemMemberID"] == $arrMember[0]["d_system_member_SystemMemberID"]) {
                                if (count($arrMember) > 1) {
                                    $arrErrorMessage["d_system_member_LoginID"] = "入力されたログインIDは既に登録されています。";
                                    break;
                                }
                            } else {
                                if (count($arrMember) > 0) {
                                    $arrErrorMessage["d_system_member_LoginID"] = "入力されたログインIDは既に登録されています。";
                                    break;
                                }
                            }
                        }

                        $this->mdlMember->begin();
                        $bTran = true;
                        if ($arrForm["d_system_member_SystemMemberID"] == "") {
                            // 現在の最大表示順を取得
                            $arrResult = $this->mdlMember->max("d_system_member_Rank", array("d_system_member_Rank"));
                            $arrInsert["d_system_member_Authority"] = $arrForm["d_system_member_Authority"];
                            $arrInsert["d_system_member_Name"] = $arrForm["d_system_member_Name"];
                            $arrInsert["d_system_member_Department"] = $arrForm["d_system_member_Department"];
                            $arrInsert["d_system_member_LoginID"] = $arrForm["d_system_member_LoginID"];
                            
                            // パスワードを暗号化する
                            $arrInsert["d_system_member_Password"] = $this->objCommon->makePassword($arrForm["d_system_member_Password"]);                            
                            
                            $arrInsert["d_system_member_Run"] = Application_Model_Member::SYSTEM_RUN;
                            $arrInsert["d_system_member_Rank"] = $arrResult["max(d_system_member_Rank)"] + 1;
                            
                            $this->mdlMember->insert($arrInsert);
                        } else {
                            $arrUpdate["d_system_member_SystemMemberID"] = $arrForm["d_system_member_SystemMemberID"];
                            $arrUpdate["d_system_member_Authority"] = $arrForm["d_system_member_Authority"];
                            $arrUpdate["d_system_member_Name"] = $arrForm["d_system_member_Name"];
                            $arrUpdate["d_system_member_Department"] = $arrForm["d_system_member_Department"];
                            $arrUpdate["d_system_member_LoginID"] = $arrForm["d_system_member_LoginID"];

                            // パスワードが変更されている場合のみ暗号化する
                            $arrMember = $this->mdlMember->find($arrForm["d_system_member_SystemMemberID"], array("d_system_member_Password"));
                            if ($arrForm["d_system_member_Password"] == $arrMember["d_system_member_Password"]) {
                                $arrUpdate["d_system_member_Password"] = $arrForm["d_system_member_Password"];
                            } else {
                                $arrUpdate["d_system_member_Password"] = $this->objCommon->makePassword($arrForm["d_system_member_Password"]);
                            }
                            $arrUpdate["d_system_member_Run"] = Application_Model_Member::SYSTEM_RUN;
                            $arrUpdate["d_system_member_Rank"] = $arrForm["d_system_member_Rank"];
                            
                            $this->mdlMember->save($arrUpdate);
                        }
                        $this->mdlMember->commit();
                        
                        // ログ収集
                        $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                        
                        // 登録が完了したらポップアップを閉じて呼び出し元ウィンドウをリロードする
                        $this->stOnload = "window.opener.location.href = '" . ADMIN_URL . "basis/member';window.close()";
                        
                        break;
                    default:
                        break;
                }
            }

            // ----------------------------- tplへの変数作成
            unset($arrForm["csrf"]);
            // CSRF対策実装
            $stCsrf = CommonTools::generateTokenData("create");

            // 引継ぎフォームデータのアンシリアライズ
            $stFormData = CommonTools::getSerializeFormData($arrForm);
            
            // 権限
            $arrSystemAuthority = array(
                "" => "選択してください",
                Application_Model_Member::SYSTEM_AUTHORITY_SYSTEMMANAGER => "システム管理者",
                Application_Model_Member::SYSTEM_AUTHORITY_SITEMANAGER => "サイト管理者",
                Application_Model_Member::SYSTEM_AUTHORITY_OPERATOR => "一般オペレータ",
                Application_Model_Member::SYSTEM_AUTHORITY_LIMITEDOPERATOR => "制限オペレータ",
                Application_Model_Member::SYSTEM_AUTHORITY_COUNTREADER => "売上集計閲覧者",
                Application_Model_Member::SYSTEM_AUTHORITY_SYSTEMDEVELOPER => "システム開発者"
            );

            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("stCsrf", $stCsrf);
            $this->view->assign("stFormData", $stFormData);
            $this->view->assign("stMode", $stMode);
            $this->view->assign("iMemberID", $iMemberID);
            $this->view->assign("arrSystemAuthority", $arrSystemAuthority);
            $this->view->assign("stOnload", $this->stOnload);
            // ページ見出し
            if ($iMemberID != "") {
                $stPageTitle = "担当者編集";
            } else {
                $stPageTitle = "担当者新規登録";
            }
            $this->view->assign("stPageTitle", $stPageTitle);

            // エラー
            if (isset($arrErrorMessage)) {
                $this->view->assign("arrErrorMessage", $arrErrorMessage);
            }

        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlMember->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }
    }
}
