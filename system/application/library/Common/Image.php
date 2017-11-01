<?php
/**
 * 共通ライブラリ
 * 画像データ編集用ライブラリ
 *
 * @package    Library
 * @author     
 * @version    v1.0
 */
class Image {
    
    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->stTempImagesPath = TEMP_IMG_DIR;
        $this->stUploadImagesPath = UPLOAD_IMG_DIR;
        
        // Library & Models
        $this->objBasicCheck = new BasicCheck();
        $this->objMessage = new Message();
        
        // ブラウザ毎の言語設定取得
        $this->stLanguage = ECS_LANGUAGE;
    }
    
    /**
     * 指定名のファイルを指定のフォルダにコピー&リネームする
     *
     * @param  string   $stImageName        項目名
     * @param  boolean  $bIsForUpload       true: temp_image-->upload_image, false: upload_image-->temp_image
     * @param  boolean  $bIsCopy            true: 画像のコピー, false: 画像の移動
     * @return boolean
     */
    public function copyAndRenameImageFile($stImageName, $bIsForUpload = true, $bIsCopy = true) {
        
        try {
            if (!$this->objBasicCheck->isSetStrings($stImageName))
                throw new Zend_Exception('$stImageNameが空です。');
            
            if ($bIsForUpload == true) {
                $stFromAddress = $this->stTempImagesPath . $stImageName;
                $stDir = pathinfo($this->stTempImagesPath . $stImageName, PATHINFO_DIRNAME);
                $stFileName = pathinfo($this->stTempImagesPath . $stImageName, PATHINFO_FILENAME);
                $stGetFileExtension = pathinfo($this->stTempImagesPath . $stImageName, PATHINFO_EXTENSION);
                if (strlen("cp_" . $stFileName . "." . $stGetFileExtension) >
                    Product::PRODUCT_TYPE_IMAGE_FILENAME_MAX_LENGTH)
                    throw new Zend_Exception("画像ファイル名が長すぎます。");
                $stToAddress = $stDir . "/" . "cp_" . $stFileName . "." . $stGetFileExtension;
            } else {
                $stFromAddress = $this->stUploadImagesPath . $stImageName;
                $stDir = pathinfo($this->stUploadTempImagesPath . $stImageName, PATHINFO_DIRNAME);
                $stFileName = pathinfo($this->stUploadTempImagesPath . $stImageName, PATHINFO_FILENAME);
                $stGetFileExtension = pathinfo($this->stUploadTempImagesPath . $stImageName, PATHINFO_EXTENSION);
                if (strlen("cp_" . $stFileName . "." . $stGetFileExtension) >
                    Product::PRODUCT_TYPE_IMAGE_FILENAME_MAX_LENGTH)
                    throw new Zend_Exception("画像ファイル名が長すぎます。");
                $stToAddress = $stDir . "/" . "cp_" . $stFileName . "." . $stGetFileExtension;
            }
            
            if ($bIsCopy == true) {
                if (copy($stFromAddress, $stToAddress)) {
                    chmod($stToAddress, 0777);
                    return basename($stToAddress);
                } else
                    return "";
            } else {
                if (rename($stFromAddress, $stToAddress))
                    return basename($stToAddress);
                else
                    return "";
            }
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }    
    
    /**
     * 画像ファイルの削除処理 
     *
     * @param  string $stDeleteFileName     画像のファイル名
     * @return void
     */
    public function deleteTempImage($stDeleteFileName) { 
        
        try{ 
            // ファイルパス定義
            $stFilePath = $this->stTempImagesPath . $stDeleteFileName; 
            // アップロード処理
            $bCheck = unlink($stFilePath); 
                        
            // ファイルパスを返す
            if (!$bCheck) {
                throw new Zend_Exception("画像ファイル削除に失敗しました");
            }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 画像ファイルの削除処理(Uploadフォルダ) 
     *
     * @param  string $stDeleteFileName     画像のファイル名
     * @return void
     */
    public function deleteUploadImage($stDeleteFileName) {
        
        try{ 
            // ファイルパス定義
            $stFilePath = $this->stUploadImagesPath . $stDeleteFileName; 
            // アップロード処理
            $bCheck = unlink($stFilePath); 
                        
            // ファイルパスを返す
            if (!$bCheck) {
                throw new Zend_Exception("画像ファイル削除に失敗しました"); 
            }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 画像のリソースを取得
     *
     * @param  string $stFilePath       アップロードされた画像のフルパス・パスファイル名
     * @param  string $stType           アップロードされた画像のイメージタイプ
     * @return object
     */
    public function doImageCreate($stFilePath, $stType) {
        
        try{
            if (!$this->objBasicCheck->isSetStrings($stFilePath))
                throw new Zend_Exception('$stFilePathが空です。');
            if (!$this->objBasicCheck->isSetStrings($stType))
                throw new Zend_Exception('$stTypeが空です');
            
            // ---------------------------- ファイル形式:jpeg
            if ($stType == IMAGETYPE_JPEG)
                // 画像のリソースを取得
                $GetImage = imagecreatefromjpeg($stFilePath);
            // ---------------------------- ファイル形式:png
            else if ($stType == IMAGETYPE_PNG)
                // 画像のリソースを取得
                $GetImage = imagecreatefrompng($stFilePath);
            // ---------------------------- ファイル形式:gif
            else if ($stType == IMAGETYPE_GIF)
                // 画像のリソースを取得
                $GetImage = imagecreatefromgif($stFilePath);
            else
                throw new Zend_Exception("ファイル形式が不正です。");
            
            return $GetImage;        
        
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
   
    /**
     * 縮小した画像リソースを上書きコピー
     *
     * @param  object $OutImage         縮小した画像リソース
     * @param  string $stFilePath       アップロードされた画像のフルパス・パスファイル名
     * @param  string $stType           アップロードされた画像のイメージタイプ
     * @return boolean
     */
    public function doImageOutput($OutImage, $stFilePath, $stType) {
        
        try{
            if (!$this->objBasicCheck->isSetStrings($stFilePath))
                throw new Zend_Exception('$stFilePathが空です');
            if (!$this->objBasicCheck->isSetStrings($stType))
                throw new Zend_Exception('$stTypeが空です');
            
            // ---------------------------- ファイル形式:jpeg
            if ($stType == IMAGETYPE_JPEG)
                // 画像のリソースを取得
                $bReturn = imagejpeg($OutImage, $stFilePath, 100);
            // ---------------------------- ファイル形式:png
            else if ($stType == IMAGETYPE_PNG)
                // 縮小した画像リソースをアップロード
                $bReturn = imagepng($OutImage, $stFilePath);
            // ---------------------------- ファイル形式:gif
            else if ($stType == IMAGETYPE_GIF)
                // 縮小した画像リソースをアップロード
                $bReturn = imagegif($OutImage, $stFilePath);
            else
                throw new Zend_Exception("ファイル形式が不正です。");
            
            return $bReturn;        
        
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * アップロードされた画像ファイルをtempフォルダに作成
     *
     * @param  array  $arrUploadedFileInfo  アップロードされたファイル情報が格納された配列
     * @param  string $stColumn             アップロードされた画像のカラム名
     * @param  string $stColumnName         アップロードされた画像のエラー表示用カラム名
     * @param  array  $arrReturn        　　アップロードされたファイル名等の結果が格納される配列
     * @return array
     */
    public function doUploadFileToCreateTempImage($arrUploadedFileInfo, $stColumn, $stColumnName, $arrReturn) {
        
        try{
            // 保存するファイル名、ファイル拡張子を取得
            $stUploadFileName = $this->getUploadFileName($arrUploadedFileInfo[$stColumn]["name"]);
            
            // リネーム処理
            copy($arrUploadedFileInfo[$stColumn]["tmp_name"], $this->stTempImagesPath . $stUploadFileName);
            $arrReturn["stUploadTempName"] = $stUploadFileName;
            
            // 上限サイズより大きい画像の縮小処理
            $stCreateColumn = $stColumn;
            
            $bCheck = $this->doUploadWithScaling($stCreateColumn, $this->stTempImagesPath . $stUploadFileName);
            if (!$bCheck) {
                $arrReturn["bIsError"] = true;
                $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . "の画像のリサイズに失敗しました。";
            }
            return $arrReturn;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * アップロードされた画像ファイルをtempフォルダに作成
     *
     * @param  array  $arrUploadedFileInfo  アップロードされたファイル情報が格納された配列
     * @param  string $stColumn             アップロードされた画像のカラム名
     * @param  string $stColumnName         アップロードされた画像のエラー表示用カラム名
     * @param  array  $arrReturn        　　アップロードされたファイル名等の結果が格納される配列
     * @param  string $stSalt               同一画像を複数格納するための文字列
     * @return array
     */
    public function doUploadFileToCreateTempMultiImages(
        $arrUploadedFileInfo, $stColumn, $stColumnName, $arrReturn, $stSalt = "") {
        
        try {
            $index = 0;
            foreach ($arrUploadedFileInfo as $value) {
                // 保存するファイル名、ファイル拡張子を取得
                $stUploadFileName = $this->getUploadFileName($value[ "name" ], $stSalt);
                // リネーム処理
                copy($value[ "tmp_name" ], $this->stTempImagesPath . $stUploadFileName);
                $arrReturn[ stUploadTempName ][ $index ] = $stUploadFileName;

                // 上限サイズより大きい画像の縮小処理
                if ($stSalt != "")
                    $stCreateColumn = $this->getCreateColumn($stColumn, $stSalt);
                else
                    $stCreateColumn = $stColumn;
                
                $bCheck = $this->doUploadWithScaling(
                    $stCreateColumn, $this->stTempImagesPath . $stUploadFileName);
                if (!$bCheck) {
                    $arrReturn[ "bIsError" ] = true;
                    $arrReturn[ "arrErrorMessage" ][ $stColumn ] .=
                        $stColumnName . $arrBasicErrorMessage[ "Uploaded_Risize" ];
                }
                $index++;
            }
            return $arrReturn;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 規定サイズより大きい画像を縮小する
     *
     * @param  string $stColumn         アップロードされた画像のカラム名
     * @param  string $stFilePath       アップロードされた画像のフルパス・パスファイル名
     * @return boolean
     */
    public function doUploadWithScaling($stColumn, $stFilePath) {
        try{
            if (!$this->objBasicCheck->isSetStrings($stColumn))
                throw new Zend_Exception('$stColumnが空です。');
            
            if ($this->objBasicCheck->isSetStrings($stFilePath)) {
                // 画像のサイズ情報を取得
                list($arrSize[ "width" ], $arrSize[ "height" ], $stType, $stAttr) = getimagesize($stFilePath);
                // 画像の上限サイズ情報を取得する
                $arrMaxSize = $this->getMaxSize($stColumn);
                // 縮小後のサイズ情報を取得する（縮小の必要がなければfalseを返す）
                $arrNewSize = $this->getNewSize($arrSize, $arrMaxSize);
                // 画像の縮小処理
                if ($arrNewSize != false) {
                    // 画像のリソースを取得
                    $GetImage = $this->doImageCreate($stFilePath, $stType);
                    // 画像を生成して上書きコピー
                    $OutImage = imagecreatetruecolor($arrNewSize[ "width" ], $arrNewSize[ "height" ]);
                    // PNGファイルだった場合透過処理を実行
                    if ($stType == IMAGETYPE_PNG) {
                        imagealphablending($OutImage, true);
                        imageSaveAlpha($OutImage, true);
                        $fillcolor = imagecolorallocatealpha($OutImage, 0, 0, 0, 127);
                        imagefill($OutImage, 0, 0, $fillcolor);
                    }
                    imagecopyresized($OutImage, $GetImage, 0, 0, 0, 0, $arrNewSize[ "width" ], $arrNewSize[ "height" ],
                        $arrSize[ "width" ], $arrSize[ "height" ]);
                    // 画像リソースをアップロード
                    $bReturn = $this->doImageOutput($OutImage, $stFilePath, $stType);
                    // 画像リソースをメモリから解放
                    imagedestroy($OutImage);
                    // 画像リソースをメモリから解放
                    imagedestroy($GetImage);
                } else
                    // 縮小の必要なし
                    $bReturn = true;
                
                // パーミッションを変更
                //chmod($stFilePath, 0777);
                
                if ($bReturn)
                    return true;
                else
                    return false;
            } else
                return false;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * アップロードされた画像から別画像を登録するカラム名を取得する
     *
     * @param  string $stColumn         アップロードされた画像のカラム名
     * @param  string $stSalt       　　同一画像を複数格納するための文字列
     * @return string
     */
    public function getCreateColumn($stColumn, $stSalt ="") {
        
        try {
            //$stCreateColumn = "";
            $stCreateColumn = $stColumn;
            
//            if (!$this->objBasicCheck->isSetStrings($stColumn)) {
//                throw new Zend_Exception('$stColumnが空です。');
//            }
//
//            if ($stColumn == "d_product_detail_DetailImage") {
//                if ($stSalt == "Main") {
//                    $stCreateColumn = "d_product_detail_MainImage";
//                } else if ($stSalt == "Thumbnail") {
//                    $stCreateColumn = "d_product_thumbnail_Image";
//                } else {
//                    $stCreateColumn = "d_product_detail_LargeImage";
//                }
//            } else if ($stColumn == "d_product_detail_SubLargeImage1") {
//                $stCreateColumn = "d_product_detail_SubImage1";
//            } else if ($stColumn == "d_product_detail_SubLargeImage2") {
//                $stCreateColumn = "d_product_detail_SubImage2";
//            } else if ($stColumn == "d_product_detail_SubLargeImage3") {
//                $stCreateColumn = "d_product_detail_SubImage3";
//            } else if ($stColumn == "d_product_detail_SubLargeImage4") {
//                $stCreateColumn = "d_product_detail_SubImage4";
//            } else if ($stColumn == "d_product_detail_SubLargeImage5") {
//                $stCreateColumn = "d_product_detail_SubImage5";
//            } else if ($stColumn == "d_product_detail_SubLargeImage6") {
//                $stCreateColumn = "d_product_detail_SubImage6";
//            } else if ($stColumn == "d_product_detail_SubLargeImage7") {
//                $stCreateColumn = "d_product_detail_SubImage7";
//            } else if ($stColumn == "d_productClass_SubImage") {
//                $stCreateColumn = "d_productClass_SubImage";
//            } else if ($stColumn == "d_productClass_SubLargeImage") {
//                $stCreateColumn = "d_productClass_SubLargeImage";
//            } else {
//                throw new Zend_Exception("アップロードされた画像から別画像を登録するカラム名が未定義のエラーです。");
//            }
            return $stCreateColumn;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 画像アップロードの項目ごとに上限サイズを定義する
     *
     * @param  string $stColumn         アップロードされた画像のカラム名
     * @return array
     */
    public function getMaxSize($stColumn) {
        
        try{
            if (!$this->objBasicCheck->isSetStrings($stColumn)) {
                throw new Zend_Exception('$stColumnが空です。');
            }

            switch ($stColumn) {
                case "d_order_sheet_ImageFileName1":
                    $arrMaxSize = array("width" => 2000, "height" => 2000);
                    break;
                default:
                    throw new Zend_Exception("最大画像サイズ設定が未定義、又はカラム指定のエラーです。");
            }
            return $arrMaxSize;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 縮小後のサイズ情報を取得する
     *
     * @param  array $arrSize       アップロードされた画像のサイズ
     * @param  array $arrMaxSize    アップロードされた画像の規定サイズ
     * @return array or boolean
     */
    public function getNewSize($arrSize, $arrMaxSize) {
        
        try{
            if (!$this->objBasicCheck->isSetArray($arrSize))
                throw new Zend_Exception('$arrSizeが空です。');
            if (!$this->objBasicCheck->isSetArray($arrMaxSize))
                throw new Zend_Exception('$arrMaxSizeが空です。');
            
            // 縦横のどちらかがサイズ上限を超えている
            if ($arrSize[ "width" ] > $arrMaxSize[ "width" ] || $arrSize[ "height" ] > $arrMaxSize[ "height" ]) {
                // 正方形の場合はどちらもサイズ上限で統一
                if ($arrSize[ "width" ] == $arrSize[ "height" ]) {
                    $iWidth = $arrMaxSize[ "width" ];
                    $iHeight = $arrMaxSize[ "height" ];
                } else if ($arrSize[ "width" ] > $arrSize[ "height" ]) {
                    $iWidth = $arrMaxSize[ "width" ];
                    $iBase = round($iWidth / $arrSize[ "width" ], 2);
                    $iHeight = $arrSize[ "height" ] * $iBase;
                } else if ($arrSize[ "width" ] < $arrSize[ "height" ]) {
                    $iHeight = $arrMaxSize[ "height" ];
                    $iBase = round($iHeight / $arrSize[ "height" ], 2);
                    $iWidth = $arrSize[ "width" ] * $iBase;
                } 
                
                $arrNewSize = array("width" => $iWidth, "height" => $iHeight);
                return $arrNewSize;
            
            } else
                return false;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 画像ファイルの名前を取得
     *
     * @param  string $stUploadFile　　 アップロードされたファイル名
     * @param  string $stSalt       　　同一画像を複数格納するための文字列
     * @return string
     */
    public function getUploadFileName($stUploadFile, $stSalt = "") {

        try{
            if ($this->objBasicCheck->isSetStrings($stUploadFile)) {
                // ファイル名、ファイル拡張子取得
                $stGetFileName = pathinfo($stUploadFile, PATHINFO_FILENAME);
                $stGetFileExtension = pathinfo($stUploadFile, PATHINFO_EXTENSION);
                // アップロードファイル名生成(重複禁止)
                $stUploadFileName = session_id() . "_" . date("YmdHis") . $stSalt . "." . $stGetFileExtension;
                
                return $stUploadFileName;
            }
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 画像削除処理
     *
     * @param  array      $arrForm              フォーム値
     * @param  string     $stUploadColumn       アップロード画像のDBカラム名(英字)
     * @param  string     $stUploadColumnName   アップロード画像のDBカラム名(日本語)
     * @return array
     */
    function imageDelete($arrForm, $stUploadColumn, $stUploadColumnName) {

        // 画像削除実行
        if (isset($arrForm[$stUploadColumn]) && !empty($arrForm[$stUploadColumn])) {
            $this->deleteTempImage($arrForm[$stUploadColumn]);
        } else {
            $arrErrorMessage[$stUploadColumn] = $stUploadColumnName . "の削除する画像がございません。";
        }
        
        $arrForm[$stUploadColumn] = "";

        return array($arrForm, $arrErrorMessage);
    }
    
    /**
     * 画像削除処理
     *
     * @param  array      $arrForm              フォーム値
     * @param  string     $stUploadColumn       アップロード画像のDBカラム名(英字)
     * @param  string     $stUploadColumnName   アップロード画像のDBカラム名(日本語)
     * @return array
     */
    function imageDeleteCheckbox($arrForm, $stUploadColumn, $stUploadColumn2, $stUploadColumn3, 
                                    $stUploadColumnName) {
        
        // 画像削除実行
        foreach ($arrForm[ $stUploadColumn2 ] as $key => $value) {
            if (isset($value[ $stUploadColumn3 ])) {
                if (isset($value[ $stUploadColumn ]) && !empty($value[ $stUploadColumn ]))
                    $this->deleteTempImage($value[ $stUploadColumn ]);
                else
                    $arrErrorMessage[ $stUploadColumn ] = $stUploadColumnName . "の削除する画像がございません。";
                unset($arrForm[ $stUploadColumn2 ][ $key ]);
            }
        }
        return array($arrForm, $arrErrorMessage);
    }
    
    /**
     * 画像アップロード処理
     *
     * @param  array      $arrForm                フォーム値
     * @param  string     $stColumn               アップロード画像のDBカラム名(英字)
     * @param  string     $stColumnName           アップロード画像のDBカラム名(日本語)
     * @return array
     */
    function imageUpload($arrForm, $stColumn, $stColumnName) {
        
        // 画像アップロード実行
        $arrUploadImage = $this->uploadTempImageFile($_FILES[$stColumn], $stColumn, $stColumnName);
        
        if ($arrUploadImage["bIsError"]) {
            // エラーの場合
            $arrErrorMessage = $arrUploadImage["arrErrorMessage"];
        } else {
            if (isset($arrForm[$stColumn]) && !empty($arrForm[$stColumn])) {
                // 既存の画像削除
                $this->deleteTempImage($arrForm[$stColumn]);
            }
            $arrForm[$stColumn] = $arrUploadImage["stUploadTempName"];
        }
        return array($arrForm, $arrErrorMessage);
    }
    
    /**
     * 登録済みの画像ファイルを「upload_image」から「temp_image」にコピー&リネーム
     * 登録済みの画像ファイルを「temp_image」から「upload_image」にコピー&リネーム
     *
     * @param  array    $arrForm            フォームデータ
     * @param  boolean  $bIsForUpload       true: temp_image-->upload_image, false: upload_image-->temp_image
     * @param  boolean  $bIsCopy            true: 画像のコピー, false: 画像の移動
     * @return boolean
     */
    public function setProductImageCopyAndRename($arrForm, $bIsForUpload = true, $bIsCopy = true) {
        
        try{
            if (!$this->objBasicCheck->isSetArray($arrForm)) {
                throw new Zend_Exception('$arrFormが配列以外です。');
            }
            
            // 対象項目をセット
            // d_category
            $arrData = CommonTools::getExtractTableData("d_category", $arrForm);
            $arrUploadImage[ "d_category" ] =
                $this->setTableCopyAndRenameImage($arrData, "d_category", $bIsForUpload, $bIsCopy);

            // d_category
            $arrData = CommonTools::getExtractTableData("d_noshi", $arrForm);
            $arrUploadImage[ "d_noshi" ] =
                $this->setTableCopyAndRenameImage($arrData, "d_noshi", $bIsForUpload, $bIsCopy);
            
            // d_product
            $arrData = CommonTools::getExtractTableData("d_product", $arrForm);
            $arrUploadImage[ "d_product" ] =
                $this->setTableCopyAndRenameImage($arrData, "d_product", $bIsForUpload, $bIsCopy);

            // d_product_class
            $arrData = CommonTools::getExtractTableData("d_product_class", $arrForm);
            $arrUploadImage[ "d_product_class" ] =
                $this->setTableCopyAndRenameImage($arrData, "d_product_class", $bIsForUpload, $bIsCopy);

            // d_selected_wappen
            $arrData = CommonTools::getExtractTableData("d_selected_wappen", $arrForm);
            $arrUploadImage[ "d_selected_wappen" ] =
                $this->setTableCopyAndRenameImage($arrData, "d_selected_wappen", $bIsForUpload, $bIsCopy);

            // d_category
            $arrData = CommonTools::getExtractTableData("d_wappen", $arrForm);
            $arrUploadImage[ "d_wappen" ] =
                $this->setTableCopyAndRenameImage($arrData, "d_wappen", $bIsForUpload, $bIsCopy);
            
            return $arrUploadImage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 登録済みの画像ファイルを「upload_image」から「temp_image」にコピーor移動
     * 登録済みの画像ファイルを「temp_image」から「upload_image」にコピーor移動
     *
     * @param  array    $arrForm            フォームデータ
     * @param  boolean  $bIsForUpload       true: temp_image-->upload_image, false: upload_image-->temp_image
     * @param  boolean  $bIsCopy            true: 画像のコピー, false: 画像の移動
     * @return boolean
     */
    public function setProductImageCopyOrRename($arrForm, $bIsForUpload = true, $bIsCopy = true) {
        
        try{
            if (!$this->objBasicCheck->isSetArray($arrForm)) {
                throw new Zend_Exception('$arrFormが配列以外です。');
            }
            
            // 対象項目をセット
            $arrData = CommonTools::getExtractTableData(
                    array("d_category", "d_noshi", "d_product", "d_product_class", "d_selected_wappen", "d_wappen", "d_product_club"), $arrForm);
            $this->setTableCopyOrRenameImage($arrData,
                    array("d_category", "d_noshi", "d_product", "d_product_class", "d_selected_wappen", "d_wappen", "d_product_club"), $bIsForUpload, $bIsCopy);

            return true;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 配列から指定したテーブルのイメージファイル名を取得し、コピー&リネームする
     *
     * @param  array    $arrData            該当テーブル項目が格納された配列
     * @param  array    $arrTableName       該当テーブル名
     * @param  boolean  $bIsForUpload       true: temp_image-->upload_image, false: upload_image-->temp_image
     * @param  boolean  $bIsCopy            true: 画像のコピー, false: 画像の移動
     * @return boolean
     */
    public function setTableCopyAndRenameImage($arrData, $arrTableName, $bIsForUpload = true, $bIsCopy = true) {
        
        try {
            
            $arrColumns = array();
            
            foreach ($arrTableName as $stTableName) {
                switch ($stTableName) {
                    case "d_product_detail":
                        array_push($arrColumns, 
                                            "d_product_detail_MaterialImage", 
                                            "d_product_detail_MainImage",  
                                            "d_product_detail_DetailImage", 
                                            "d_product_detail_LargeImage", 
                                            "d_product_detail_SubImage1", 
                                            "d_product_detail_SubImage2", 
                                            "d_product_detail_SubImage3", 
                                            "d_product_detail_SubImage4", 
                                            "d_product_detail_SubImage5", 
                                            "d_product_detail_SubImage6", 
                                            "d_product_detail_SubImage7", 
                                            "d_product_detail_SubLargeImage1", 
                                            "d_product_detail_SubLargeImage2", 
                                            "d_product_detail_SubLargeImage3", 
                                            "d_product_detail_SubLargeImage4", 
                                            "d_product_detail_SubLargeImage5", 
                                            "d_product_detail_SubLargeImage6", 
                                            "d_product_detail_SubLargeImage7"
                        );
                        break;
                    case "d_category":
                        array_push($arrColumns, "d_category_Image");
                        break;
//                    case "d_product_thumbnail":
//                        array_push($arrColumns, "d_product_thumbnail_Image");
//                        break;
//                    case "d_product_detail_image":
//                        array_push($arrColumns, "d_product_detail_image_Image");
//                        break;
                    case "d_noshi":
                        array_push($arrColumns, "d_noshi_Image");
                        break;
                    case "d_wappen":
                        array_push($arrColumns, "d_wappen_Image");
                        break;
                    case "d_selected_wappen":
                        array_push($arrColumns, "d_selected_wappen_Image");
                        break;
                }
            }
            
            // 保存された各ファイルを指定フォルダに移動
            $arrImageFileName = array();
            foreach ($arrColumns as $val) {
                if ($this->objBasicCheck->isSetStrings($arrData[ $val ])) {
                    $stToFileName = $this->copyAndRenameImageFile($arrData[ $val ], $bIsForUpload, $bIsCopy);
                    $arrImageFileName += array($val => $stToFileName);
                }
            }
            return $arrImageFileName;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 配列から指定したテーブルのイメージファイル名を取得し、コピーor移動する
     *
     * @param  array    $arrData            該当テーブル項目が格納された配列
     * @param  array    $arrTableName       該当テーブル名
     * @param  boolean  $bIsForUpload       true: temp_image-->upload_image, false: upload_image-->temp_image
     * @param  boolean  $bIsCopy            true: 画像のコピー, false: 画像の移動
     * @return boolean
     */
    public function setTableCopyOrRenameImage($arrData, $arrTableName, $bIsForUpload = true, $bIsCopy = true) {
        
        try {
            $arrColumns = array();
            
            foreach ($arrTableName as $stTableName) {
                switch ($stTableName) {
                    case "d_category":
                        array_push($arrColumns, "d_category_Image");
                        break;
                    case "d_noshi":
                        array_push($arrColumns, "d_noshi_Image");
                        break;
                    case "d_product":
                        array_push($arrColumns,
                                "d_product_ListImage",
                                "d_product_MainImage",  
                                "d_product_SubImage1", 
                                "d_product_SubLargeImage1", 
                                "d_product_SubImage2", 
                                "d_product_SubLargeImage2", 
                                "d_product_SubImage3",
                                "d_product_SubLargeImage3"
                        );
                        break;
                    case "d_product_class":
                        array_push($arrColumns,
                                "d_product_class_Image",  
                                "d_product_class_LargeImage"
                        );
                        break;
                    case "d_selected_wappen":
                        array_push($arrColumns, "d_selected_wappen_Image");
                        break;
                    case "d_wappen":
                        array_push($arrColumns, "d_wappen_Image");
                        break;
                    case "d_product_club":
                        array_push($arrColumns, "d_product_club_Image");
                        break;
                }
            }
            
            // 保存された各ファイルを指定フォルダに移動
            foreach ($arrColumns as $val) {
                if ($this->objBasicCheck->isSetStrings($arrData[$val])) {
//                    $this->copyOrRenameImageFile($arrData[ $val ], $bIsForUpload, $bIsCopy);
                    if ($bIsForUpload == true) {
                        $stFromAddress = $this->stTempImagesPath . $arrData[$val];
                        $stToAddress = $this->stUploadImagesPath . $arrData[$val];
                    } else {
                        $stFromAddress = $this->stUploadImagesPath . $arrData[$val];
                        $stToAddress = $this->stTempImagesPath . $arrData[$val];
                    }

                    if ($bIsCopy == true) {
                        if (copy($stFromAddress, $stToAddress)) {
                            chmod($stToAddress, 0777);
                        } else {
                            throw new Zend_Exception("ファイルコピーに失敗しました。(from:$stFromAddress to:$stToAddress");
                        }
                    } else {
                        if (file_exists($stFromAddress)) {
                            if (!rename($stFromAddress, $stToAddress)) {
                                throw new Zend_Exception("ファイルリネームに失敗しました。(from:$stFromAddress to:$stToAddress");
                            }
                        }
                    }                    
                }
            }
            return true;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 画像ファイルのエラーチェック
     * 画像ファイルをtempフォルダに一時的にアップロード
     *
     * @param  array   $arrUploadFile               アップロードされたファイル情報を格納した配列
     * @param  string  $stColumn                    アップロードされた画像のカラム名
     * @param  string  $stColumnName                アップロードされた画像のエラー表示用カラム名
     * @return array
     */
    public function uploadTempImageFile($arrUploadFile, $stColumn, $stColumnName) {
        
        try {
            
            $arrReturn = array("bIsError" => false, "stUploadTempName" => "", "arrErrorMessage" => array());
            
            $arrBasicErrorMessage = $this->objMessage->getBasicErrorMessages($this->stLanguage);
            
            // ファイルがアップされたかのチェック
            if (!is_uploaded_file($arrUploadFile["tmp_name"])) {
                $arrReturn["bIsError"] = true;
                if ($arrUploadFile["error"] == UPLOAD_ERR_INI_SIZE || $arrUploadFile["error"] == UPLOAD_ERR_FORM_SIZE) {
                    $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage["FileSize"];
                } else {
                    $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage["Uploaded"];
                }
                return $arrReturn;
            }

            // ファイルアップロード管理のインスタンスを生成する
            $objAdapter = new Zend_File_Transfer_Adapter_Http();
            
            // 保存ディレクトリを設定する
            $objAdapter->setDestination($this->stTempImagesPath);
            
            // アップロードの必須チェックを解除する（コメントアウトで必須）
            $objAdapter->setOptions(array("ignoreNoFile" => true));

            // ファイルサイズの最大値を設定する
            $objAdapter->addValidator("Size", false, array("max" => "2MB"));
            
            // ローカル(Windows)環境(下のエラーと関連して)
            if (defined("DOMAIN_LOCAL")) {
                // 拡張子は「jpg」または「gif」または「png」のみ許可する
                $objAdapter->addValidator("Extension", false, array("jpg", "png"));
            }
            // ローカル(Windows)環境ではエラーになるため、本番(Linux)環境のみ適用
            if (!defined("DOMAIN_LOCAL")) {
                // MimeTypeは「image/jpg」または「image/gif」または「image/png」のみ許可する
                $objAdapter->addValidator("MimeType", false, array("image/jpeg", "image/pjpeg", "image/png"));
            }

            // 受信したファイルを保存する
            $bReceive = $objAdapter->receive();

            // 画像ファイルのエラーチェック
            if (!$bReceive) {
                // エラーコードを取得する
                $arrErrorCodeList = $objAdapter->getErrors();
                // エラーメッセージをセット
                $arrReturn["bIsError"] = true;
                $arrReturn["arrErrorMessage"][$stColumn] = "";
                foreach ($arrErrorCodeList as $stErrorCode) {
                    if ($arrReturn["arrErrorMessage"][$stColumn]) {
                        $arrReturn["arrErrorMessage"][$stColumn] .= "<br/>";
                    }
                    switch ($stErrorCode) {
                        case Zend_Validate_File_Upload::NO_FILE:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage["Uploaded"];
                            break;
                        case Zend_Validate_File_Size::TOO_BIG:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage["FileSize"];
                            break;
                        case Zend_Validate_File_Extension::FALSE_EXTENSION:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage["Extension"];
                            break;
                        case Zend_Validate_File_MimeType::FALSE_TYPE:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage[ "Mime_False" ];
                            break;
                        case Zend_Validate_File_MimeType::NOT_DETECTED:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage[ "Mime_Not_Detected" ];
                            break;
                        case Zend_Validate_File_MimeType::NOT_READABLE:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage[ "Mime_Not_Readable" ];
                            break;
                        case Zend_Validate_File_NotExists::DOES_EXIST:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage[ "Uploaded_No_Exist" ];
                            break;
                        default:
                            $arrReturn["arrErrorMessage"][$stColumn] .= $stColumnName . $arrBasicErrorMessage[ "Uploaded_No_Exist" ] . "(" . $stErrorCode . ")";
                            break;
                    }
                }
                return $arrReturn;
            }

            // アップロードされた画像ファイル情報取得
            $arrUploadedFileInfo = $objAdapter->getFileInfo();

            // アップロードされた画像ファイルをtempフォルダに作成
            $arrReturn = $this->doUploadFileToCreateTempImage($arrUploadedFileInfo, $stColumn, $stColumnName, $arrReturn);
            if ($arrReturn[ "bIsError" ]) {
                return $arrReturn;
            }
            
            // アップロードした元ファイルを削除
            $this->deleteTempImage($arrUploadedFileInfo[$stColumn]["name"]);

            return $arrReturn;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 画像ファイルのエラーチェック
     * 画像ファイルをtempフォルダに一時的にアップロード
     *
     * @param  array   $arrUploadFile　　アップロードされたファイル情報を格納した配列
     * @param  string  $stColumn         アップロードされた画像のカラム名
     * @param  string  $stColumnName     アップロードされた画像のエラー表示用カラム名
     * @param  boolean $bIsMainImageToDetailImage   一覧メイン画像と同様の画像を詳細メイン画像にも使用するフラグ
     * @return array
     */
    public function uploadTempMultiImageFiles(
        $arrUploadFile, $stColumn, $stColumnName, $bIsMainImageToDetailImage = false) {
        
        try {
            $arrReturn = array("bIsError" => false, "arrErrorMessage" => array());
            
            if (!$this->objBasicCheck->isSetArray($arrUploadFile))
                throw new Zend_Exception('画像ファイル情報 $arrUploadFileが不正です。');
            if (!$this->objBasicCheck->isSetStrings($stColumn))
                throw new Zend_Exception('$stColumnが空です。');
            
            $arrBasicErrorMessage = $this->objMessage->getBasicErrorMessages($this->stLanguage);
            
            $iImageCount = 0;
            // アップロードファイル数の取得
            foreach ($arrUploadFile as $key => $value)
                $iImageCount = count($value);
            
            $arrConvImage = array();
            $arrConvImages = array();

            // 配列の整形
            for ($i = 0; $i < $iImageCount; $i++)  {
                foreach ($arrUploadFile as $key => $value) {
                    $arrConvImage[ $key ] = $value[ $i ];
                }
                $arrConvImages[] = $arrConvImage;
            }
 
            // ファイルアップロード管理のインスタンスを生成する
            $objAdapter = new Zend_File_Transfer_Adapter_Http();
            
            // 保存ディレクトリを設定する
            $objAdapter->setDestination($this->stTempImagesPath);
            
            // アップロードの必須チェックを解除する（コメントアウトで必須）
            $objAdapter->setOptions(array("ignoreNoFile" => true));

            // ファイルサイズの最大値を設定する
            $objAdapter->addValidator("Size", false, array("max" => "1MB"));
            
            // ローカル(Windows)環境(下のエラーと関連して)
            if (defined("DOMAIN_LOCAL"))
                // 拡張子は「jpg」または「gif」または「png」のみ許可する
                $objAdapter->addValidator("Extension", false, array("jpg", "gif", "png"));
            
            // ローカル(Windows)環境ではエラーになるため、本番(Linux)環境のみ適用
            if (!defined("DOMAIN_LOCAL"))
                // MimeTypeは「image/jpg」または「image/gif」または「image/png」のみ許可する
                $objAdapter->addValidator(
                    "MimeType", false, array("image/jpeg", "image/pjpeg", "image/gif", "image/png"));

            foreach ($arrConvImages as $key => $value) {
                // ファイルがアップされたかのチェック
                if (!is_uploaded_file($value[ "tmp_name" ])) {
                    $arrReturn[ "bIsError" ] = true;
                    if ($value[ "error" ] == UPLOAD_ERR_INI_SIZE ||
                        $value[ "error" ] == UPLOAD_ERR_FORM_SIZE) {
                        $arrReturn[ "arrErrorMessage" ][ $stColumn ] .=
                            $stColumnName . $arrBasicErrorMessage[ "FileSize" ];
                    } else
                        $arrReturn[ "arrErrorMessage" ][ $stColumn ] .=
                            $stColumnName . $arrBasicErrorMessage[ "Uploaded" ];
                    return $arrReturn;
                }                
            }

            // 受信したファイルを保存する
            $bReceive = $objAdapter->receive();
            
            // 画像ファイルのエラーチェック
            if (!$bReceive) {
                // エラーコードを取得する
                $arrErrorCodeList = $objAdapter->getErrors();
                // エラーメッセージをセット
                $arrReturn[ "bIsError" ] = true;
                $arrReturn[ "arrErrorMessage" ][ $stColumn ] = "";
                foreach ($arrErrorCodeList as $stErrorCode) {
                    switch ($stErrorCode) {
                        case Zend_Validate_File_Upload::NO_FILE:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "Uploaded" ];
                            break;
                        case Zend_Validate_File_Size::TOO_BIG:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "FileSize" ];
                            break;
                        case Zend_Validate_File_Extension::FALSE_EXTENSION:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "Extension" ];
                            break;
                        case Zend_Validate_File_MimeType::FALSE_TYPE:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "Mime_False" ];
                            break;
                        case Zend_Validate_File_MimeType::NOT_DETECTED:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "Mime_Not_Detected" ];
                            break;
                        case Zend_Validate_File_MimeType::NOT_READABLE:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "Mime_Not_Readable" ];
                            break;
                        case Zend_Validate_File_NotExists::DOES_EXIST:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "Uploaded_No_Exist" ];
                            break;
                        default:
                            if ($arrReturn[ "arrErrorMessage" ][ $stColumn ]) 
                                $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= "<br/>"; 
                            $arrReturn[ "arrErrorMessage" ][ $stColumn ] .= 
                                $stColumnName . $arrBasicErrorMessage[ "Uploaded_No_Exist" ] . "(" . $stErrorCode . ")";
                            break;
                    }
                }
                return $arrReturn;
            }

            // アップロードされた画像ファイル情報取得
            $arrUploadedFileInfo = $objAdapter->getFileInfo();

            // アップロードされた画像ファイルをtempフォルダに作成
            $arrReturn = $this->doUploadFileToCreateTempMultiImages(
                $arrUploadedFileInfo, $stColumn, $stColumnName, $arrReturn);
            if ($arrReturn[ "bIsError" ])
                return $arrReturn;
            
            // アップロードした元ファイルを削除
            foreach ($arrConvImages as $key => $value)
                $this->deleteTempImage($arrConvImages[ $key ][ "name" ]);
            
            return $arrReturn;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
