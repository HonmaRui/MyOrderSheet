<?php

class Format {
    
    public function debug() {
        $this->init();
        
        /**
         * エスケープ処理
         */
        $arrData = array("tags" => "<a href='test'>Test</a>",
                         "sql" => "SELECT * FROM users WHERE user='aidan' AND password='' OR ''=''"
                         );
        $arrData2 = array("tags" => array("tags2" => "<a href='test'>Test</a>"),
                          "sql" => array("sql2" => "SELECT * FROM users WHERE user='aidan' AND password='' OR ''=''")
                          );
        
        /**
         * 一次元の配列
         */
        $arrEscapedData = $this->Escape($arrData);
        $this->objDebug->EcsDump("Escape - arrData", $arrEscapedData);
        
        /**
         * 二次元の配列
         */
        $arrEscapedData2 = $this->Escape($arrData2);
        $this->objDebug->EcsDump("Escape - arrData", $arrEscapedData);
        
        /**
         * 名前整形
         */
        $arrName = array("名前1", "名前2");
        // 区切り文字有り
        $this->objDebug->EcsDump("Name_Union", $this->Name($arrName, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("Name", $this->Name($arrName));
        
        /**
         * 名前カナ整形
         */
        $arrNameKana = array("ナマエ1", "ナマエ2");
        // 区切り文字有り
        $this->objDebug->EcsDump("NameKana_Union", $this->NameKana($arrNameKana, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("NameKana", $this->NameKana($arrNameKana));
        
        /**
         * 電話番号整形
         */
        $arrTel = array("000", "000", "0000");
        // 区切り文字有り
        $this->objDebug->EcsDump("Tel_Union", $this->Tel($arrTel, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("Tel", $this->Tel($arrTel));

        /**
         * 携帯電話番号整形
         */
        $arrMobile = array("090", "1111", "2222");
        // 区切り文字有り
        $this->objDebug->EcsDump("Mobile_Union", $this->Mobile($arrMobile, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("Mobile", $this->Mobile($arrMobile));
        
        /**
         * FAX電話番号整形
         */
        $arrFax = array("045", "999", "9999");
        // 区切り文字有り
        $this->objDebug->EcsDump("Fax_Union", $this->Fax($arrFax, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("Fax", $this->Mobile($arrFax));

        /**
         * 予約電話番号整形
         */
        $arrReserve = array("045", "333", "4444");
        // 区切り文字有り
        $this->objDebug->EcsDump("Reserve_Union", $this->Reserve($arrReserve, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("Reserve", $this->Reserve($arrReserve));

        /**
         * 郵便番号整形
         */
        $arrZip = array("232", "0000");
        // 区切り文字有り
        $this->objDebug->EcsDump("Zip_Union", $this->Zip($arrZip, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("Zip", $this->Zip($arrZip));

        /**
         * 住所整形
         */
        $arrAddress = array("住所1","住所2");
        // 区切り文字有り
        $this->objDebug->EcsDump("Address_Union", $this->Address($arrAddress, $bUnion = true));
        // 区切り文字無し
        $this->objDebug->EcsDump("Address", $this->Address($arrAddress));
        
    }
    
    private function init() {
        $this->objBasicCheck = new BasicCheck();
        $this->objMessage = new Message();
//        $this->objDebug = new Debug();
    }
    
    // 渡されたarrForm内のデータに対し、以下の変換を実行する
    // 半角カナ -> 全角カナ
    // 半角スペース -> 削除
    // 全角スペース -> 削除
    public function trimData($arrForm) {
        try {
            $this->init();
            
            if(!$this->objBasicCheck->isSetArray($arrForm))
                throw new Zend_Exception('$arrFormが空です。');
            /*
            if($_SERVER["X_KTAI_INFO_CARRIER_ID"] > 0) {
                mb_convert_variables('UTF-8','SJIS',$arrForm);
            }
            */
            foreach($arrForm as $key => $value) {
                switch($key) {
                    case "FamilyName":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "GivenName":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "FamilyNameKana":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "GivenNameKana":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "NameKana":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        break;
                    case "Message":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        break;
                    case "Addr01":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Addr02":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'KVs', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Zip01":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Zip02":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Tel01":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Tel02":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Tel03":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Fax01":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Fax02":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Fax03":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                        
                    case "Mobile01":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Mobile02":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Mobile03":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                        
                    case "Email":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "EmailConfirm":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "MobileEmail":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "Password":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    case "PasswordConfirm":
                        $arrForm[$key] = mb_convert_kana($arrForm[$key], 'as', 'UTF-8');
                        $arrForm[$key] = str_replace(" ", "", $arrForm[$key]);
                        break;
                    default:
                        break;
                }
            }
            return $arrForm;
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function parseData($stDelimiter, $arrData) {
        try {
            if(!$this->objBasicCheck->isSetStrings($stDelimiter))
                throw new Zend_Exception('$stDelimiterが空です。');
            if(!$this->objBasicCheck->isSetArray($arrData))
                throw new Zend_Exception('$arrDataが空です。');
            
            foreach($arrData as $key => $value) {
                $array = explode($stDelimiter, $key);
                if(count($array) > 2) 
                    throw new Zend_Exception('explode結果の配列要素数が2個以下になるようにしてください');
                if(isset($array[0])) $stColumnName = $array[0];
                if(isset($array[1])) $stNewKey = $array[1];
                if(empty($array[1])) {
                    $arrTemp[$key] = $value;
                } else {
                    $arrTemp[$stNewKey][$stColumnName] = $value;
                }
            }
            $arrData = $arrTemp;
            return $arrData;
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     *
     * $arrFormの配列keyにテーブル名を付ける/削除する
     *
     */
    public function initFormData($stTableName, $arrForm, $reverse = false) {
        try {
            $this->init();
            /*
            if($_SERVER["X_KTAI_INFO_CARRIER_ID"] > 0) {
                mb_convert_variables('UTF-8','SJIS',$arrForm);
            }
            */
            if($this->objBasicCheck->isSetStrings($stTableName)) {
                if($this->objBasicCheck->isSetArray($arrForm)) {
                    if($reverse === true) {
                        foreach($arrForm as $key => $value) {
                            if(is_array($value)) {
                                foreach($value as $k => $v) {
                                    $new_key = $stTableName . "_" . $k;
                                    $arrForm[$key][$new_key] = $v;
                                    unset($arrForm[$key][$k]);
                                }
                            } else {
                                $new_key = $stTableName . "_" . $key;
                                $arrForm[$new_key] = $arrForm[$key];
                                unset($arrForm[$key]);
                            }
                        }
                    } else {
                        foreach($arrForm as $key => $value) {
                            if(is_array($value)) {
                                foreach($value as $k => $v) {
                                    $stReplaceStrings = $stTableName . "_";
                                    $new_key = str_replace($stReplaceStrings, "", $k);
                                    $arrForm[$key][$new_key] = $arrForm[$key][$k];
                                    unset($arrForm[$key][$k]);
                                }
                            } else {
                                $stReplaceStrings = $stTableName . "_";
                                $new_key = str_replace($stReplaceStrings, "", $key);
                                $arrForm[$new_key] = $arrForm[$key];
                                unset($arrForm[$key]);
                            }
                        }
                    }
                    return $arrForm;
                } else {
                    throw new Zend_Exception('$arrFormが空です。');
                }
            } else {
                throw new Zend_Exception('$stTableNameが空です。');
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * 受注データ配列からテーブル名を追加または除去する
     */
    public function initOrderData($arrOrder, $bReverse = false) {
        try {
            $this->init();
            if(!$this->objBasicCheck->isSetArray($arrOrder))
                throw new Zend_Exception('$arrOrderがセットされていません。');
            if(isset($arrOrder["Master"])) {
                $arrOrder["Master"] = $this->initFormData("d_order_master", $arrOrder["Master"], $bReverse);
            }
            if(isset($arrOrder["Purchaser"])) {
                $arrOrder["Purchaser"] = $this->initFormData("d_order_purchaser", $arrOrder["Purchaser"], $bReverse);
            }
            if(isset($arrOrder["Destination"])) {
                $arrOrder["Destination"] = $this->initFormData("d_order_destination", $arrOrder["Destination"], $bReverse);
            }
            if($this->objBasicCheck->isSetArray($arrOrder["Details"])) {
                foreach($arrOrder["Details"] as $key => $arrDetail) {
                    $arrOrder["Details"][$key] = $this->initFormData("d_order_details", $arrDetail, $bReverse);
                }
            }
            if(isset($arrOrder["Deliver"])) {
                $arrOrder["Deliver"] = $this->initFormData("d_order_deliver", $arrOrder["Deliver"], $bReverse);
            }
            return $arrOrder;
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     *
     * $arrDataの配列keyにサブテーブルの略称を付ける/削除する
     *
     */
    public function addAbbreviationToColumns($stAbbreviation, $arrData, $reverse = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetStrings($stAbbreviation)) {
                if($this->objBasicCheck->isSetArray($arrData)) {
                    if($reverse === true) {
                        foreach($arrData as $key => $value) {
                            $stReplaceStrings = $stAbbreviation . ".";
                            $value = str_replace($stReplaceStrings, "", $value);
                            $arrData[$key] = $value;
                        }
                    } else {
                        foreach($arrData as $key => $value) {
                            $value = $stAbbreviation . "." . $value;
                            $arrData[$key] = $value;
                        }
                    }
                    return $arrData;
                } else {
                    throw new Zend_Exception('$arrDataが空です。');
                }
            } else {
                throw new Zend_Exception('$stAbbreviationが空です。');
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    // 配列の値とキーを入れ替える
    public function ValueToKey($arrData) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrData)) {
                foreach($arrData as $key => $value) {
                    if(is_array($value)) {
                        foreach($value as $k => $v) {
                            $arrData[$key][$v] = "";
                            unset($arrData[$key][$v]);
                        }
                    } else {
                        $arrData[$value] = "";
                        unset($arrData[$key]);
                    }
                }
                
                return $arrData;
                
            } else {
                throw new Zend_Exception('$arrDataが空です。');
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    public function Escape($arrData) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrData)) {
                foreach($arrData as $key => $value) {
                    if(is_array($value)) {
                        foreach($value as $k => $v) {
                            //$arrData[$key][$k] = htmlspecialchars($v, ENT_QUOTES);
                            $arrData[$key][$k] = $this->getArrayFormat($v);
                            //$arrData[$key][$k] = mysql_real_escape_string($v);
                        }
                    } else {
                        $arrData[$key] = htmlspecialchars($value, ENT_QUOTES);
                        //$arrData[$key] = mysql_real_escape_string($value);
                    }
                }

                return $arrData;
                
            } elseif ($this->objBasicCheck->isSetStrings($arrData)) {
                $stData = $arrData;
                $stData = htmlspecialchars($stData, ENT_QUOTES);
                //$stData = mysql_real_escape_string($stData);
                return $stData;
            } else {
                return $arrData;
            }
        } catch(Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    public function Name($arrName, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrName)) {
                $stName = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrName);
                
                if($bUnion && $iCount > 1) {
                    $stName = implode(NAME_UNION_CHARACTER, $arrName);
                } else {
                    $stName = implode("", $arrName);
                }
                
                return $stName;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function NameKana($arrNameKana, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrNameKana)) {
                $stNameKana = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrNameKana);
                if($bUnion && $iCount > 1) {
                    $stNameKana = implode(NAME_KANA_UNION_CHARACTER, $arrNameKana);
                } else {
                    $stNameKana = implode("", $arrNameKana);
                }
                
                return $stNameKana;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function Tel($arrTel, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrTel)) {
                $stTel = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrTel);
                if($bUnion && $iCount > 1) {
                    $stTel = implode(TEL_UNION_CHARACTER, $arrTel);
                } else {
                    $stTel = implode("", $arrTel);
                }
                
                return $stTel;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function Mobile($arrMobile, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrMobile)) {
                $stMobile = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrMobile);
                if($bUnion && $iCount > 1) {
                    $stMobile = implode(MOBILE_UNION_CHARACTER, $arrMobile);
                } else {
                    $stMobile = implode("", $arrMobile);
                }
                
                return $stMobile;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function Fax($arrFax, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrFax)) {
                $stFax = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrFax);
                if($bUnion && $iCount > 1) {
                    $stFax = implode(FAX_UNION_CHARACTER, $arrFax);
                } else {
                    $stFax = implode("", $arrFax);
                }
                
                return $stFax;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function Reserve($arrReserve, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrReserve)) {
                $stReserve = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrReserve);
                if($bUnion && $iCount > 1) {
                    $stReserve = implode(RESERVE_UNION_CHARACTER, $arrReserve);
                } else {
                    $stReserve = implode("", $arrReserve);
                }
                
                return $stReserve;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function Zip($arrZip, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrZip)) {
                $stZip = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrZip);
                if($bUnion && $iCount > 1) {
                    $stZip = implode(ZIP_UNION_CHARACTER, $arrZip);
                } else {
                    $stZip = implode("", $arrZip);
                }
                
                return $stZip;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function Address($arrAddress, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrAddress)) {
                $stAddress = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrAddress);
                if($bUnion && $iCount > 1) {
                    $stAddress = implode(ADDRESS_UNION_CHARACTER, $arrAddress);
                } else {
                    $stAddress = implode("", $arrAddress);
                }
                
                return $stAddress;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }

    public function Birth($arrBirth, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrBirth)) {
                $stBirth = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrBirth);
                if($bUnion && $iCount > 1) {
                    $stBirth = implode(BIRTH_UNION_CHARACTER, $arrBirth);
                } else {
                    $stBirth = implode("", $arrBirth);
                }

                return $stBirth;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function CommonDate($arrDate, $bUnion = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetArray($arrDate)) {
                $stDate = "";
                $iCount = $this->objBasicCheck->countValueFromArray($arrDate);
                if($bUnion && $iCount > 1) {
                    $stDate = implode(DATE_UNION_CHARACTER, $arrDate);
                } else {
                    $stDate = implode("", $arrDate);
                }
                return $stDate;
                
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function parseDate($stDate) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetStrings($stDate)) {
                // 年月日
                preg_match("|[0-9]+\-[0-9]+\-[0-9]+|", $stDate, $match);
                $arrTempDate = explode("-", $match[0]);
                // 時刻
                preg_match("|[0-9]+\:[0-9]+\:[0-9]+|", $stDate, $match2);
                $arrTempTime = explode(":", $match2[0]);
                // 戻り値用配列にセット
                $arrDatetime["Year"] = $arrTempDate[0];
                $arrDatetime["Month"] = $arrTempDate[1];
                $arrDatetime["Day"] = $arrTempDate[2];
                $arrDatetime["Hour"] = $arrTempTime[0];
                $arrDatetime["Min"] = $arrTempTime[1];
                $arrDatetime["Sec"] = $arrTempTime[2];

                return $arrDatetime;
            } else {
//                throw new Zend_Exception('$stDateが空です。');
//                exit;
                throw new Zend_Exception($e->getMessage());
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function formatDate($stDate, $bTime = false) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetStrings($stDate)) {
                $arrDatetime = $this->parseDate($stDate);
                $stFormatDate = sprintf(PARSE_DATE_FORMAT, $arrDatetime["Year"], $arrDatetime["Month"], $arrDatetime["Day"]);
                $stFormatTime = sprintf(PARSE_TIME_FORMAT, $arrDatetime["Hour"], $arrDatetime["Min"], $arrDatetime["Sec"]);
                if($bTime) {
                    $stDatetime = $stFormatDate . " " . $stFormatTime;
                } else {
                    $stDatetime = $stFormatDate;
                }
                return $stDatetime;
            } else {
//                throw new Zend_Exception('$stDateが空です。');
//                exit;
                throw new Zend_Exception($e->getMessage());
            }
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    // 配列のキーと値を入れ替える
    // 例: $array = array("key" => "");
    //     $array = array("key");
    public function arrayKeyToValue($array) {
        try {
            $this->init();
            $this->objIni = new ini();
            $this->objIni->init();
            if($this->objBasicCheck->isSetArray($array)) {
                foreach($array as $key => $value) {
                    if(is_array($value)) {
                        throw new Zend_Exception('$arrayには一次元の配列をセットして下さい。');
                    } else {
                        $arrTemp[] = $key;
                    }
                }
            }
            
            $array = $arrTemp;
            return $array;
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * JavaScriptのalert文を生成して返す
     */
    public function makeAlert($stMessage) {
        try {
            $this->init();
            if(!$this->objBasicCheck->isSetStrings($stMessage))
                throw new Zend_Exception('$stMessageが空です。');
            
            $stMessage = htmlspecialchars($stMessage, ENT_QUOTES);
            $stAlert = "alert('" . $stMessage . "');";
            
            return $stAlert;
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * JavaScriptのconfirm文を生成して返す
     */
    public function makeConfirm($stMessage) {
        try {
            $this->init();
            if(!$this->objBasicCheck->isSetStrings($stMessage))
                throw new Zend_Exception('$stMessageが空です。');
            
            $stMessage = htmlspecialchars($stMessage, ENT_QUOTES);
            $stConfirm = "confirm('" . $stMessage . "');";
            
            return $stConfirm;
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    function getArrayFormat($v) {
        
        if (is_array($v)) {
            foreach($v as $k2 => $v2) {
                if (is_array($v2)) {
                    $v[$k2] = $this->getArrayFormat($v2);
                } else {
                    $v[$k2] = htmlspecialchars($v2, ENT_QUOTES);
                    $bIsExchange = true;
                }
            }
        } else {
            $v = htmlspecialchars($v, ENT_QUOTES);
        }
        
        return $v;
    }

}