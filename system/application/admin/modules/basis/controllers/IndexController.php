<?php

class Basis_IndexController extends Zend_Controller_Action {

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
            $this->objBasis = new Basis();
            $this->objCommon = new Common();
            $this->objFormat = new Format();
            $this->mdlBaseInfo = new Application_Model_BaseInfo();
            $this->mdlPref = new Application_Model_Pref();

            // 権限確認
            $bAuthority = $this->objCommon->isAuthority($this->_request->getServer("REQUEST_URI"));
            if (!$bAuthority) {
                $this->_redirect(ADMIN_URL . "home");
            }
            
            // 初期データの読み込み
            // 都道府県マスタ
            $this->arrPref = CommonTools::changeDbArrayForFormTag(
                $this->mdlPref->fetchAll(array("m_pref_PrefCode", "m_pref_Name")));
            $this->view->assign("arrPref", $this->arrPref);
            
            // カレントモジュール、コントローラー、アクション名取得
            $objFormReq = $this->getRequest();
            $this->stModuleName = $objFormReq->getModuleName();
            $this->stControllerName = $objFormReq->getControllerName();
            $this->stActionName = $objFormReq->getActionName();
            
            $this->stControllerName = $objFormReq->getControllerName();
            $this->view->assign("stCurrentModule", $this->stModuleName);
            $this->view->assign("stCurrentController", $this->stControllerName);
            $this->view->assign("stCurrentAction", $this->stActionName);
            
            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();

            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->nav_tpl = "nav.tpl";
            $layout->footer_tpl = "footer.tpl";
            $this->view->assign("layout", $layout);
            
            $this->objAdminSess = new Zend_Session_Namespace("Admin");
            $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);
            $this->view->assign("arrGlobalNavPos", $this->objCommon->getGlobalNavCurrentPos($this->objAdminSess->arrMenu));

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     * 
     * 検索画面表示アクション
     * 
     */
    public function indexAction() {

        try {
            $objFormReq = $this->getRequest();
            $stMode = $this->_getParam("mode");
            $stPostCsrf = $this->_getParam("csrf", null);

            $arrErrorMessage = array();
            if (!$objFormReq->isPost()) {
                // Get request
                // データ読み込み
                $arrForm = $this->mdlBaseInfo->find(1);
            } else {
                // Post request
                CommonTools::checkTokenData("basis", $stPostCsrf);
                // formDataのアンシリアライズ
                if ($this->_hasParam("stFormData")) {
                    // 前回までのフォーム値復元
                    $arrForm = CommonTools::getUnserializeFormData($this->_getParam("stFormData"));
                } else {
                    throw new Zend_Exception("フォーム値の取得が出来ません。");
                }
                $arrFormTemp = $objFormReq->getPost();
                
                // 入力データの反映
                if ($arrForm != "") {
                    $arrForm = array_merge($arrForm, $arrFormTemp);
                } else {
                    $arrForm = $arrFormTemp;
                }

                // 全角数字は半角数字へ変換する
                $arrForm["d_baseinfo_Zip"] = mb_convert_kana($arrForm["d_baseinfo_Zip"], "n", "UTF-8");
                $arrForm["d_baseinfo_TelNo"] = mb_convert_kana($arrForm["d_baseinfo_TelNo"], "n", "UTF-8");
                $arrForm["d_baseinfo_FaxNo"] = mb_convert_kana($arrForm["d_baseinfo_FaxNo"], "n", "UTF-8");
                
                switch ($stMode) {
                    case "save":
                        // バリデート処理実行
                        $arrErrorMessage = $this->objBasis->errorCheckBaseInfo($arrForm);

                        if (!empty($arrErrorMessage)) {
                            $this->stOnload = "alert('入力された情報にエラーがあります。');";
                        } else {
                            // 保存ボタン押下処理
                            $this->objBasis->createBaseInfo($arrForm, $objFormReq);
                            $this->stOnload = "alert('基本設定の更新が完了しました。');";
                        }
                        break;
                    default:
                        break;
                }
            }
            
            unset($arrForm["stFormData"]);
            unset($arrForm["csrf"]);

            // 引継ぎフォームデータのアンシリアライズ
            $stFormData = CommonTools::getSerializeFormData($arrForm);
            // CSRF対策実装
            $stCsrf = CommonTools::generateTokenData("basis");
            
            // ----------------------------- tplファイルへの変数渡し処理
            $this->view->assign("mode", $stMode);
            $this->view->assign("arrErrorMessage", $arrErrorMessage);
            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("stCsrf", $stCsrf);
            $this->view->assign("stFormData", $stFormData);
            $this->view->assign("stOnload", $this->stOnload);
            $this->view->assign("stPageTitle", "基本設定マスタ");

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
