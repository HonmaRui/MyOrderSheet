<?php

class Basis_MailController extends Zend_Controller_Action {

    /***
     * 
     * 初期化処理
     * 
     */
    public function init() {

        try {

            // Format
            $this->objFormat = new Format();

            // HTTP
            $objHttp = new Http();
            $objHttp->allowClientCacheCurrent();
            
            //Common
            $this->objCommon = new Common();
            //Basis
            $this->objBasis = new Basis();
            
            $this->mdlMailSetting = new Application_Model_MailSetting();

            // メールテンプレート
            $this->arrTemplate = CommonTools::changeDbArrayForFormTag(
                $this->mdlMailSetting->fetchAll(array("d_mail_setting_TemplateID", "d_mail_setting_Name")));
            
            //権限確認
            $bAuthority = $this->objCommon->isAuthority($_SERVER['REQUEST_URI']);
            if (!$bAuthority) {
                $this->_redirect(ADMIN_URL.'home');
            }

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
        
        $bTran = false;

        try {

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

            // 共通テンプレへの変数渡し
            $view = $layout->getView();
            $view->assign("arrTemplate",  $this->arrTemplate);

            $objFormReq = $this->getRequest();
            $iMailSettingID = $this->_getParam("mailsettingid");
            $iTemplateID = $this->_getParam("templateid");
            $stMode = $this->_getParam("mode");
            $stCsrfUpdate = $this->_getParam("csrfupdate");

            $arrErrorMessage = array();
            if (!$objFormReq->isPost()) {
                // Get request
                $arrForm = array();
            } else {
                // Post request
                // formDataのアンシリアライズ
                if ($this->_hasParam("stFormData")) {
                    // 前回までのフォーム値復元
                    $arrForm = CommonTools::getUnserializeFormData($this->_getParam("stFormData"));
                } else
                    throw new Zend_Exception("フォーム値の取得が出来ません。");
                
                $arrFormTemp = $objFormReq->getPost();
                
                // 入力データの反映
                if ($arrForm != "") {
                    $arrForm = array_merge($arrForm, $arrFormTemp);
                } else {
                    $arrForm = $arrFormTemp;
                }

                switch ($stMode) {
                    case "search":
                        // テンプレート読み込み
                        if ($iTemplateID) {
                            $arrSearchCondition = array();
                            $arrSearchCondition["d_mail_setting_TemplateID"] = $iTemplateID;
                            $this->mdlMailSetting->setSearchCondition($arrSearchCondition);
                            $arrResult = $this->mdlMailSetting->search();
                            unset($arrForm["edit_mail"]);
                            $arrForm["edit_mail"] = $arrResult[0];
                        } else {
                            unset($arrForm["edit_mail"]);
                            $arrForm["edit_mail"] = "";
                        }
                        break;
                    case "update":
                        // CSRF対策チェック
                        CommonTools::checkTokenData("update", $stCsrfUpdate);
                        
                        $arrErrorMessage = $this->objBasis->errorCheckMail($arrForm["edit_mail"]);
                        if ($arrErrorMessage) {
                            $stMode = "";
                            break;
                        }
                        
                        $this->mdlMailSetting->begin();
                        $bTran = true;
                        $arrUpdate = array();
                        $arrUpdate["d_mail_setting_MailSettingID"] = $iMailSettingID;
                        $arrUpdate["d_mail_setting_TemplateID"] = $iTemplateID;
                        $arrUpdate["d_mail_setting_Title"] = $arrForm["edit_mail"]["d_mail_setting_Title"];
                        $arrUpdate["d_mail_setting_Content"] = $arrForm["edit_mail"]["d_mail_setting_Content"];
                        $arrUpdate["d_mail_setting_DelFlg"] = 0;
                        $this->mdlMailSetting->save($arrUpdate);
                        $this->mdlMailSetting->commit();

                        // ログ収集
                        $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrForm, SET_LOG_LEVEL_ALL);
                        
                        unset($arrForm);
                        $this->stOnload = "alert('メール設定を更新しました。');";
                        break;
                    default;
                        break;
                }
            }

            // ----------------------------- tplへの変数作成
            unset($arrForm["csrfupdate"]);

            // 引継ぎフォームデータのアンシリアライズ
            if (!empty($arrForm)) {
                unset($arrForm["stFormData"]);
                $stFormData = CommonTools::getSerializeFormData($arrForm);
            }
            
            // CSRF対策実装
            $stCsrfUpdate = CommonTools::generateTokenData("update");

            // 画面タイトル
            $stPageTitle = "メール設定";

            $this->view->assign("arrForm", $this->objFormat->Escape($arrForm));
            $this->view->assign("arrResult", $this->objFormat->Escape($arrResult));
            $this->view->assign("stCsrfUpdate", $stCsrfUpdate);
            $this->view->assign("stFormData", $stFormData);
            $this->view->assign("stMode", $stMode);
            $this->view->assign("stOnload", $this->stOnload);
            $this->view->assign("stPageTitle", $stPageTitle);

            // エラー
            if (isset($arrErrorMessage)) {
                $this->view->assign("arrErrorMessage", $arrErrorMessage);
            }

        } catch (Zend_Exception $e) {
            if ($bTran)
                $this->mdlMailSetting->rollBack();
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     * 
     * プレビューポップアップアクション
     * 
     */
    public function previewPopupAction() {

        try {

            // 共通テンプレ生成の為のクラスを生成
            $layout = new Zend_Layout();
            
            // 共通レイアウトの読み込み
            $layout->header_tpl = "header.tpl";
            $layout->footer_tpl = "footer.tpl";
            $this->view->assign("layout", $layout);

            $this->objAdminSess = new Zend_Session_Namespace("Admin");
            $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);

            // 共通テンプレへの変数渡し
            $view = $layout->getView();
            $view->assign("arrTemplate",  $this->arrTemplate);

            $objFormReq = $this->getRequest();

            if (!$objFormReq->isPost()) {
                // Get request
            } else {
                // Post request

                // formDataのアンシリアライズ
                if ($this->_hasParam("stFormData")) {
                    // 前回までのフォーム値復元
                    $arrForm = CommonTools::getUnserializeFormData($this->_getParam("stFormData"));
                } 

                $arrFormTemp = $objFormReq->getPost();
                
                // 入力データの反映
                if ($arrForm != "") {
                    $arrForm = array_merge($arrForm, $arrFormTemp);
                } else {
                    $arrForm = $arrFormTemp;
                }

            }

            // ----------------------------- tplへの変数作成
            unset($arrForm["stFormData"]);
            $arrEdit = array();
            $arrWork = $arrForm["edit_mail"];
            // データ加工
            // ①テストデータ作成＋コード変換
            $stTitle = $this->objBasis->getTemplateMailStringTest(
                $arrWork["d_mail_setting_Title"]);
            $stContent = $this->objBasis->getTemplateMailStringTest(
                $arrWork["d_mail_setting_Content"]);
            // ②改行文字を<br />にする
            $arrWork["d_mail_setting_Title"] = nl2br($stTitle);
            $arrWork["d_mail_setting_Content"] = nl2br($stContent);

            $arrResult = $arrWork;
            
            // Escapeしない
            $this->view->assign("arrResult", $arrResult);
            $this->view->assign("stOnload", $this->stOnload);

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
