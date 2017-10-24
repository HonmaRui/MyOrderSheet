<?php

class Basis_NewsController extends Zend_Controller_Action {

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
            $this->mdlContentsNewinfo = new Application_Model_ContentsNewinfo();
            
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
            $stRedirectURL = ADMIN_URL . "basis/news";

            if ($objFormReq->isPost()) {
                $arrForm = $objFormReq->getPost();
                // CSRF対策チェック
                CommonTools::checkTokenData("news", $stPostCsrf);
            }
            
            // 表示順は半角数字に変換する
            foreach ($arrForm as $key => &$value) {
                if (preg_match("/^posnum[0-9]+/", $key)) {
                    $arrForm[$key] = mb_convert_kana($arrForm[$key], "n", "UTF-8");
                }
                unset($value);
            }

            switch ($stMode) {
                case "add":
                    // バリデート処理実行
                    $arrErrorMessage = $this->objMaster->errorCheckForNews($arrForm, $stMode);
                    if ($arrErrorMessage == "") {
                        // 現在の最大表示順を取得
                        $arrResult = $this->mdlContentsNewinfo->max("d_contents_newinfo_Rank", array("d_contents_newinfo_Rank"));
                        $this->mdlContentsNewinfo->begin();
                        $bTran = true;
                        $arrInsert["d_contents_newinfo_Date"] = $arrForm["news_from_datepicker"];
                        $arrInsert["d_contents_newinfo_Title"] = $arrForm["d_contents_newinfo_Title"];
                        $arrInsert["d_contents_newinfo_Text"] = $arrForm["d_contents_newinfo_Text"];
                        $arrInsert["d_contents_newinfo_Visibility"] = 1;
                        $arrInsert["d_contents_newinfo_Rank"] = $arrResult["max(d_contents_newinfo_Rank)"] + 1;
                        $this->mdlContentsNewinfo->insert($arrInsert);
                        $this->mdlContentsNewinfo->commit();
                        
                        if ($objFormReq->isPost()) {
                            // ログ収集テーブル
                            $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                        }                        
                        unset($arrForm);
                    }
                    // ブラウザの更新による再submitを防ぐためredirectとする
                    if ($arrErrorMessage == "") {
                        $this->_redirect($stRedirectURL);
                    } else {
                        break;
                    }
                case "save":
                    // バリデート処理実行
                    $arrErrorMessage = $this->objMaster->errorCheckForNews($arrForm, $stMode);
                    if ($arrErrorMessage == "") {
                        $this->mdlContentsNewinfo->begin();
                        $bTran = true;
                        $arrUpdate["d_contents_newinfo_ContentsNewinfoID"] = $arrForm["editNewsID"];
                        $arrUpdate["d_contents_newinfo_Date"] = $arrForm["news_from_datepicker"];
                        $arrUpdate["d_contents_newinfo_Title"] = $arrForm["d_contents_newinfo_Title"];
                        $arrUpdate["d_contents_newinfo_Text"] = $arrForm["d_contents_newinfo_Text"];
                        $this->mdlContentsNewinfo->save($arrUpdate);
                        $this->mdlContentsNewinfo->commit();
                        
                        if ($objFormReq->isPost()) {
                            // ログ収集テーブル
                            $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                        }                        
                        unset($arrForm);
                    }
                    // ブラウザの更新による再submitを防ぐためredirectとする
                    if ($arrErrorMessage == "") {
                        $this->_redirect($stRedirectURL);
                    } else {
                        break;
                    }
                case "edit":
                    $arrEdit = $this->mdlContentsNewinfo->find($arrForm["editNewsID"]);
                    $arrForm["news_from_Year"] = date("Y", strtotime($arrEdit["d_contents_newinfo_Date"]));
                    $arrForm["news_from_Month"] = date("m", strtotime($arrEdit["d_contents_newinfo_Date"]));
                    $arrForm["news_from_Day"] = date("d", strtotime($arrEdit["d_contents_newinfo_Date"]));

                    $arrForm["d_contents_newinfo_Title"] = $arrEdit["d_contents_newinfo_Title"];
                    $arrForm["d_contents_newinfo_Text"] = $arrEdit["d_contents_newinfo_Text"];
                    break;
                case "del":
                    $this->mdlContentsNewinfo->begin();
                    $bTran = true;
                    $this->mdlContentsNewinfo->delete($arrForm["delNewsID"]);
                    $this->mdlContentsNewinfo->commit();
                    
                    if ($objFormReq->isPost()) {
                        // ログ収集テーブル
                        $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                    }                    
                    unset($arrForm);
                    break;
                case "order":
                    $this->mdlContentsNewinfo->begin();
                    $bTran = true;
                    $arrResult = array();
                    $arrResult = $this->mdlContentsNewinfo->find($arrForm["srcNewsID"], array("d_contents_newinfo_Rank"));
                    $arrUpdate["d_contents_newinfo_ContentsNewinfoID"] = $arrForm["dstNewsID"];
                    $arrUpdate["d_contents_newinfo_Rank"] = $arrResult["d_contents_newinfo_Rank"];
                    $this->mdlContentsNewinfo->save($arrUpdate);

                    $arrResult = array();
                    $arrResult = $this->mdlContentsNewinfo->find($arrForm["dstNewsID"], array("d_contents_newinfo_Rank"));
                    $arrUpdate["d_contents_newinfo_ContentsNewinfoID"] = $arrForm["srcNewsID"];
                    $arrUpdate["d_contents_newinfo_Rank"] = $arrResult["d_contents_newinfo_Rank"];
                    $this->mdlContentsNewinfo->save($arrUpdate);
                    $this->mdlContentsNewinfo->commit();
                    
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
                    $arrErrorMessage[$arrForm["moveNewsID"]] = $this->objMaster->errorCheckForMember($arrForm, $stMode);
                    if ($arrErrorMessage[$arrForm["moveNewsID"]]) {
                        $stMode = "";
                        break;
                    }
                    
                    $this->mdlContentsNewinfo->begin();
                    $bTran = true;

                    $arrResult = $this->mdlContentsNewinfo->fetchAll();

                    // 入力された番号取得
                    $iInputRow = $arrForm["posnum"];                // 選択行(0始まり)
                    $iDstRow = $arrForm["posnum" . $iInputRow] - 1; // 入力値
                    
                    // 最大表示順の取得
                    $iRank = 0;
                    foreach ($arrResult as $key => $value) {
                        if ($value["d_contents_newinfo_Rank"] > $iRank) {
                            $iRank = $value["d_contents_newinfo_Rank"];
                        }
                    }

                    // 入力値が最大表示順-1(現在の表示の最下部)を上回る場合は、入力値を最大表示順-1に修正する
                    if ($iRank + 1 < $iDstRow) {
                        $iDstRow = $iRank + 1;
                    }

                    // 移動処理
                    if ($iInputRow < $iDstRow) {
                        // 移動元より下に移動する場合
                        for($i = $iInputRow; $i <= $iDstRow ;$i++) {
                            if ($i == $iInputRow) {
                                // 移動元の表示順を移動先の表示順に設定する
                                $arrUpdate["d_contents_newinfo_ContentsNewinfoID"] = $arrForm["moveNewsID"];
                                $arrUpdate["d_contents_newinfo_Rank"] = $arrResult[$iDstRow]["d_contents_newinfo_Rank"];
                                $this->mdlContentsNewinfo->save($arrUpdate);
                            } else {
                                // 移動元から移動先の間の表示順を1ずつ繰り上げる
                                $arrUpdate["d_contents_newinfo_ContentsNewinfoID"] = $arrResult[$i]["d_contents_newinfo_ContentsNewinfoID"];
                                $arrUpdate["d_contents_newinfo_Rank"] = $arrResult[$i]["d_contents_newinfo_Rank"] + 1;
                                $this->mdlContentsNewinfo->save($arrUpdate);
                            }
                        }
                    } else {
                        // 移動元より上に移動する場合(移動元と同じ表示順が入力されていた場合も含む)
                        for($i = $iInputRow; $i >= $iDstRow ;$i--) {
                            if ($i == $iInputRow) {
                                // 移動元の表示順を移動先の表示順に設定する
                                $arrUpdate["d_contents_newinfo_ContentsNewinfoID"] = $arrForm["moveNewsID"];
                                $arrUpdate["d_contents_newinfo_Rank"] = $arrResult[$iDstRow]["d_contents_newinfo_Rank"];
                                $this->mdlContentsNewinfo->save($arrUpdate);
                            } else {
                                // 移動元から移動先の間の表示順を1ずつ繰り下げる
                                $arrUpdate["d_contents_newinfo_ContentsNewinfoID"] = $arrResult[$i]["d_contents_newinfo_ContentsNewinfoID"];
                                $arrUpdate["d_contents_newinfo_Rank"] = $arrResult[$i]["d_contents_newinfo_Rank"] - 1;
                                $this->mdlContentsNewinfo->save($arrUpdate);
                            }
                        }
                    }
                    $this->mdlContentsNewinfo->commit();

                    // ログ収集テーブル
                    $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                    
                    // ブラウザの更新による再submitを防ぐためredirectとする
                    $this->_redirect($stRedirectURL);
                default;
                    break;
            }            
 
            // メンバー管理テーブルの検索実行
            $arrResult = $this->mdlContentsNewinfo->fetchAll();
            
            for ($i = 0; $i < count($arrResult); $i++) {
                // 表示順入れ替え用に、前後の表示順を設定する
                $arrResult[$i]["beforeNewsID"] = 0;
                $arrResult[$i]["nextNewsID"] = 0;
                if ($i > 0) {
                    $arrResult[$i]["beforeNewsID"] = $arrResult[$i - 1]["d_contents_newinfo_ContentsNewinfoID"];
                    $arrResult[$i - 1]["nextNewsID"] = $arrResult[$i]["d_contents_newinfo_ContentsNewinfoID"];
                }
            }
            
            
            // 日付の設定
            if ($arrForm["news_from_Year"] && $arrForm["news_from_Month"] && $arrForm["news_from_Day"]) {
                $this->view->assign("newsFromDate", $arrForm["news_from_Year"] . "-" . $arrForm["news_from_Month"] . "-" . $arrForm["news_from_Day"]);
                $this->view->assign("news_from_datepicker", $arrForm["news_from_Year"] . "/" . $arrForm["news_from_Month"] . "/" . $arrForm["news_from_Day"]);
            }
            
            // CSRF対策実装
            $stCsrf = CommonTools::generateTokenData("news");

            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            $this->view->assign("stCsrf", $stCsrf);
            $this->view->assign("stMode", $stMode);
            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("arrResult", $this->objFormat->Escape($arrResult));
            $this->view->assign("stPageTitle", "新着情報管理");

        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlContentsNewinfo->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }
    }
}
