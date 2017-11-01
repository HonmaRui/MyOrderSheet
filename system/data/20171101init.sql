-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- ホスト: mysql1.php.xdomain.ne.jp
-- 生成時間: 2017 年 11 月 01 日 19:42
-- サーバのバージョン: 5.0.95
-- PHP のバージョン: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `yamagata01_mos`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `d_baseinfo`
--

CREATE TABLE IF NOT EXISTS `d_baseinfo` (
  `d_baseinfo_BaseinfoID` tinyint(1) NOT NULL COMMENT '基本設定ID',
  `d_baseinfo_Zip` varchar(7) NOT NULL COMMENT '事業者郵便番号',
  `d_baseinfo_PrefCode` tinyint(2) NOT NULL COMMENT '都道府県コード',
  `d_baseinfo_Address1` varchar(120) NOT NULL COMMENT '事業者住所１',
  `d_baseinfo_Address2` varchar(120) default NULL COMMENT '事業者住所２',
  `d_baseinfo_Address3` varchar(120) default NULL COMMENT '事業者住所３',
  `d_baseinfo_Name` varchar(60) NOT NULL COMMENT '事業者名',
  `d_baseinfo_TelNo` varchar(13) NOT NULL COMMENT '事業者電話番号',
  `d_baseinfo_FaxNo` varchar(13) default NULL COMMENT '事業者FAX番号',
  `d_baseinfo_EmailAddress` varchar(100) default NULL COMMENT '事業者メールアドレス',
  `d_baseinfo_SiteURL` varchar(100) default NULL COMMENT '事業者サイトURL',
  `d_baseinfo_MailAddress1` varchar(100) NOT NULL COMMENT '受注情報受付メールアドレス',
  `d_baseinfo_MailAddress2` varchar(100) NOT NULL COMMENT '問い合わせ受付メールアドレス',
  `d_baseinfo_MailAddress3` varchar(100) NOT NULL COMMENT '送信エラー受付メールアドレス',
  `d_baseinfo_MailAddress4` varchar(100) NOT NULL COMMENT 'メール送信元メールアドレス',
  `d_baseinfo_MailAddress5` varchar(100) NOT NULL COMMENT 'メルマガ送信元メールアドレス',
  `d_baseinfo_ExecutiveName` varchar(50) default NULL COMMENT '事業者代表役職名',
  `d_baseinfo_ManagingDirectorName` varchar(50) default NULL COMMENT '事業者代表取締役名',
  `d_baseinfo_TaxRate` tinyint(2) NOT NULL COMMENT '消費税率',
  `d_baseinfo_ChangeTaxRate` tinyint(2) NOT NULL COMMENT '変更後消費税率',
  `d_baseinfo_ChangeTaxDate` datetime NOT NULL COMMENT '消費税率変更日',
  `d_baseinfo_PostageFreePrice` int(10) default NULL COMMENT '送料無料条件',
  `d_baseinfo_FeeFreePrice` int(10) default NULL COMMENT '手数料無料条件',
  `d_baseinfo_TaxCalc` tinyint(1) NOT NULL COMMENT '税計算区分',
  `d_baseinfo_TaxFraction` tinyint(1) NOT NULL COMMENT '消費税端数区分',
  `d_baseinfo_CreatedTime` datetime NOT NULL COMMENT '作成日時',
  `d_baseinfo_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT '更新日時',
  `d_baseinfo_CreatedByID` smallint(4) NOT NULL COMMENT '作成者ID',
  `d_baseinfo_UpdatedByID` smallint(4) NOT NULL COMMENT '更新者ID',
  PRIMARY KEY  (`d_baseinfo_BaseinfoID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基本設定マスタテーブル';

--
-- テーブルのデータをダンプしています `d_baseinfo`
--

INSERT INTO `d_baseinfo` (`d_baseinfo_BaseinfoID`, `d_baseinfo_Zip`, `d_baseinfo_PrefCode`, `d_baseinfo_Address1`, `d_baseinfo_Address2`, `d_baseinfo_Address3`, `d_baseinfo_Name`, `d_baseinfo_TelNo`, `d_baseinfo_FaxNo`, `d_baseinfo_EmailAddress`, `d_baseinfo_SiteURL`, `d_baseinfo_MailAddress1`, `d_baseinfo_MailAddress2`, `d_baseinfo_MailAddress3`, `d_baseinfo_MailAddress4`, `d_baseinfo_MailAddress5`, `d_baseinfo_ExecutiveName`, `d_baseinfo_ManagingDirectorName`, `d_baseinfo_TaxRate`, `d_baseinfo_ChangeTaxRate`, `d_baseinfo_ChangeTaxDate`, `d_baseinfo_PostageFreePrice`, `d_baseinfo_FeeFreePrice`, `d_baseinfo_TaxCalc`, `d_baseinfo_TaxFraction`, `d_baseinfo_CreatedTime`, `d_baseinfo_UpdatedTime`, `d_baseinfo_CreatedByID`, `d_baseinfo_UpdatedByID`) VALUES
(1, '9970000', 6, '鶴岡市', '', '', 'マイオーダーシート', '', '', 'honma4rui@yahoo.co.jp', 'http://yamagata01.php.xdomain.jp/MyOrderSheet/', 'honma4rui@yahoo.co.jp', 'honma4rui@yahoo.co.jp', 'honma4rui@yahoo.co.jp', 'honma4rui@yahoo.co.jp', 'honma4rui@yahoo.co.jp', '', '', 8, 8, '2016-04-01 00:00:00', NULL, NULL, 4, 3, '2015-10-21 00:00:00', '2017-11-01 11:25:33', 1, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `d_category`
--

CREATE TABLE IF NOT EXISTS `d_category` (
  `d_category_CategoryID` int(10) NOT NULL auto_increment,
  `d_category_CategoryName` varchar(100) NOT NULL,
  `d_category_ColorClass` varchar(100) default NULL,
  `d_category_Rank` int(5) default NULL,
  `d_category_DelFlg` tinyint(1) NOT NULL,
  `d_category_CreatedTime` datetime NOT NULL,
  `d_category_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`d_category_CategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- テーブルのデータをダンプしています `d_category`
--

INSERT INTO `d_category` (`d_category_CategoryID`, `d_category_CategoryName`, `d_category_ColorClass`, `d_category_Rank`, `d_category_DelFlg`, `d_category_CreatedTime`, `d_category_UpdatedTime`) VALUES
(1, 'スターバックス・コーヒー', 'panel-success', 1, 0, '2017-10-25 09:35:27', '2017-10-25 09:35:27'),
(2, 'サブウェイ', 'panel-success', 2, 0, '2017-10-25 09:35:27', '2017-10-25 09:35:27'),
(3, '二郎系ラーメン', 'panel-warning', 3, 0, '2017-10-25 09:35:27', '2017-10-25 09:35:27'),
(4, '家系ラーメン', 'panel-warning', 4, 0, '2017-10-25 09:35:27', '2017-10-25 09:35:27'),
(5, 'その他', 'panel-info', 5, 0, '2017-10-25 09:35:27', '2017-10-25 09:35:27');

-- --------------------------------------------------------

--
-- テーブルの構造 `d_customer`
--

CREATE TABLE IF NOT EXISTS `d_customer` (
  `d_customer_CustomerID` int(10) NOT NULL auto_increment,
  `d_customer_Name` varchar(100) default NULL,
  `d_customer_EmailAddress` varchar(100) NOT NULL,
  `d_customer_Password` varchar(100) NOT NULL,
  `d_customer_SignedOut` tinyint(1) NOT NULL,
  `d_customer_DelFlg` tinyint(1) default NULL,
  `d_customer_CreatedTime` datetime NOT NULL,
  `d_customer_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`d_customer_CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `d_customer`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `d_mail_history`
--

CREATE TABLE IF NOT EXISTS `d_mail_history` (
  `d_mail_history_MailHistoryID` int(10) NOT NULL auto_increment COMMENT 'メール履歴ID',
  `d_mail_history_TemplateID` smallint(4) default NULL COMMENT 'テンプレートID',
  `d_mail_history_OrderMngID` int(10) default NULL COMMENT '注文管理ID',
  `d_mail_history_OrderID` int(10) default NULL COMMENT '受注ID',
  `d_mail_history_CustomerID` int(10) default NULL COMMENT '顧客ID',
  `d_mail_history_CustomerName` varchar(100) NOT NULL COMMENT '顧客名',
  `d_mail_history_SendDate` datetime NOT NULL COMMENT '配信日時',
  `d_mail_history_Title` varchar(200) NOT NULL COMMENT 'タイトル',
  `d_mail_history_Content` mediumtext NOT NULL COMMENT '内容',
  `d_mail_history_DelFlg` tinyint(1) NOT NULL default '0' COMMENT '削除フラグ',
  `d_mail_history_CreatedTime` datetime NOT NULL COMMENT '作成日時',
  `d_mail_history_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '更新日時',
  `d_mail_history_CreatedByID` smallint(4) NOT NULL default '0' COMMENT '作成者ID',
  `d_mail_history_UpdatedByID` smallint(4) NOT NULL default '0' COMMENT '更新者ID',
  PRIMARY KEY  (`d_mail_history_MailHistoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='メール履歴テーブル' AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `d_mail_history`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `d_mail_setting`
--

CREATE TABLE IF NOT EXISTS `d_mail_setting` (
  `d_mail_setting_MailSettingID` smallint(4) NOT NULL auto_increment COMMENT 'メール設定ID',
  `d_mail_setting_TemplateID` smallint(4) NOT NULL COMMENT 'テンプレートID',
  `d_mail_setting_Name` varchar(20) NOT NULL COMMENT 'テンプレート名',
  `d_mail_setting_Title` varchar(200) NOT NULL COMMENT 'タイトル',
  `d_mail_setting_Content` mediumtext NOT NULL COMMENT '内容',
  `d_mail_setting_Rank` smallint(4) NOT NULL default '0' COMMENT '表示順',
  `d_mail_setting_DelFlg` tinyint(1) NOT NULL default '0' COMMENT '削除フラグ',
  `d_mail_setting_CreatedTime` datetime NOT NULL COMMENT '作成日時',
  `d_mail_setting_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '更新日時',
  `d_mail_setting_CreatedByID` smallint(4) NOT NULL default '0' COMMENT '作成者ID',
  `d_mail_setting_UpdatedByID` smallint(4) NOT NULL default '0' COMMENT '更新者ID',
  PRIMARY KEY  (`d_mail_setting_MailSettingID`),
  KEY `FK_TemplateID` (`d_mail_setting_TemplateID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='メール設定テーブル' AUTO_INCREMENT=7 ;

--
-- テーブルのデータをダンプしています `d_mail_setting`
--

INSERT INTO `d_mail_setting` (`d_mail_setting_MailSettingID`, `d_mail_setting_TemplateID`, `d_mail_setting_Name`, `d_mail_setting_Title`, `d_mail_setting_Content`, `d_mail_setting_Rank`, `d_mail_setting_DelFlg`, `d_mail_setting_CreatedTime`, `d_mail_setting_UpdatedTime`, `d_mail_setting_CreatedByID`, `d_mail_setting_UpdatedByID`) VALUES
(1, 1, '注文確認及び合計金額の案内メール', '【<!--{$arrConf.shop_name}-->】 カットサンプルのご請求ありがとうございます。', '<!--{$arrData.co01}--><!--{$arrData.co02}-->\r\n<!--{$arrData.name01}--> 様\r\n\r\nこの度はカットサンプルをご請求いただき誠にありがとうございます。\r\n下記ご依頼内容にお間違えがないかご確認お願いいたします。\r\n\r\n******************************************************************\r\n　配送情報\r\n******************************************************************\r\n\r\n◎お届け先\r\n〒<!--{$arrData.d_order_OrderDeliveryZip}-->　<!--{$arrData.AllAddress}-->\r\n<!--{$arrData.d_order_OrderDeliveryCompanyName}-->\r\n<!--{$arrData.d_order_OrderDeliveryDepartmentName}-->\r\n<!--{$arrData.d_order_OrderDeliveryName}-->　様\r\n\r\n電話番号:<!--{$arrData.d_order_OrderDeliveryTelNo}-->\r\n\r\n連絡事項:\r\n<!--{$arrData.d_order_Memo}-->\r\n\r\n\r\n******************************************************************\r\n　ご依頼アイテム明細\r\n******************************************************************\r\n\r\n<!--{foreach item=detail from=$arrData.orderDetail}-->\r\n商品ID：<!--{$detail.product_code}-->\r\n商品番：<!--{$detail.d_order_detail_ProductCode}-->\r\n商品ブランド：<!--{$detail.d_order_detail_ProductBrand}-->\r\n商品コレクション：<!--{$detail.d_order_detail_ProductCollection}-->\r\n商品名：<!--{$detail.d_order_detail_ProductName}-->\r\n点　数：<!--{$detail.d_order_detail_Quantity}-->\r\n---\r\n<!--{/foreach}-->\r\n==============================================================\r\n\r\n※誠に申し訳ございませんが、こちらのＥメールはお知らせ専用のアドレスとなっておりますため、\r\nお問い合わせ等のメッセージを受け付けることができません。\r\n\r\n\r\nご質問やご不明な点がございましたら、こちらからお願いいたします。\r\nhttp://www.manas.co.jp/inquiry-business-user/\r\n\r\n========================================\r\nマナトレーディング株式会社\r\nhttp://www.manas.co.jp/\r\n========================================', 1, 0, '2016-06-10 13:52:04', '2017-10-31 18:57:14', 0, 1),
(2, 2, 'お問い合わせ時自動返信メール', 'お問い合わせを受付いたしました', '-----------------------------------------------------\r\nこのメールは、『<!--{$arrConf.shop_name}-->』より\r\nお問い合わせをいただいたお客様への自動送信メールです。\r\n-----------------------------------------------------\r\n\r\n<!--{$arrData.name01}-->　様\r\n\r\nいつも<!--{$arrConf.shop_name}-->をご利用いただきまして、誠にありがとうございます。\r\n\r\n下記の内容にて、お問い合わせを受付いたしました。\r\n\r\nご返答は、<!--{$arrData.email}-->宛てに３営業日以内を目安にご連絡予定です。\r\nしばらくお待ちいただけますよう、お願い申し上げます。\r\n\r\n\r\n***************************************************************\r\n■お名前\r\n  <!--{$arrData.name01}-->　様\r\n\r\n■お電話番号\r\n  <!--{$arrData.tel01}-->\r\n\r\n■メールアドレス\r\n  <!--{$arrData.email}-->\r\n\r\n■お問い合わせ項目\r\n  <!--{$arrData.genre}-->\r\n\r\n■注文番号\r\n  <!--{$arrData.orderNo}-->\r\n\r\n■お問い合わせの内容\r\n<!--{$arrData.contents|indent:2}-->\r\n\r\n***************************************************************\r\n\r\n\r\nなお、下記の状況におきましては、ご返答までにさらにお時間をいただく場合がございます。\r\nご迷惑をお掛けいたしますが、あらかじめご了承くださいますようお願いいたします。\r\n\r\n・お問い合わせやご注文が、集中した場合\r\n・メールをお送りできない不測の事態が発生した場合\r\n・夜間、土日、祝日、休日、長期休暇中にいただいたお問い合わせへのご返答\r\n\r\n\r\n\r\n今後ともご愛顧賜りますよう、宜しくお願い申し上げます。\r\n\r\n\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n　株式会社　デモ\r\n――――――――――――――――――――――――――\r\n【住　所】　〒999-9999　デモ県デモ市1丁目\r\n【ＴＥＬ】　0000-00-0000\r\n　　　　 　[受付時間：9～17時]\r\n【ＦＡＸ】　0000-00-0000\r\n【メール】　demo@demo.com\r\n【お問合せ】https://demo.com/contact/\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n\r\n※このメールは送信専用アドレスで送信しております。\r\n  お問合せ・当ショップへのご連絡等がございます場合には、お問合せフォームか、または 【demo@demo.com】 宛てにご連絡をお送りくださいますようお願い申し上げます。\r\n                      ', 2, 1, '2016-06-13 10:43:32', '2017-02-24 15:00:08', 0, 1),
(3, 3, '出荷予告メール', '≪注文番号．{$arrData.order_id}≫　発送日のご案内', '<!--{$arrData.order_name01}-->  様\r\n\r\nこのたびは<!--{$arrConf.shop_name}-->をご利用いただきまして、誠にありがとうございました。\r\n\r\nご注文をいただきました商品の、発送予定日をご案内申し上げます。\r\nご確認くださいますよう、お願いいたします。\r\n\r\n※本メールは、発送単位（お届け先様×温度帯）別にご案内をお送りいたしております。\r\n\r\n\r\nなお、誠に恐れ入りますが、本メール配信以降のキャンセル・ご注文内容変更・お届け日時の変更等は承る事ができません。\r\n\r\n何卒ご了承くださいますよう、お願い申し上げます。\r\n\r\n\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n  ■ご注文内容につきまして\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n[注文番号]　　 　<!--{$arrData.order_id}-->\r\n[注文日時]　　 　<!--{$arrData.create_date}-->\r\n<!--{assign var=''use_agency'' value=''0''}-->\r\n<!--{if $arrData.agency_name ne $arrData.order_name01}--><!--{assign var=''use_agency'' value=''1''}--><!--{/if}-->\r\n<!--{if $arrData.agency_zip  ne $arrData.order_zip01 }--><!--{assign var=''use_agency'' value=''1''}--><!--{/if}-->\r\n<!--{if $arrData.agency_pref ne $arrData.order_pref  }--><!--{assign var=''use_agency'' value=''1''}--><!--{/if}-->\r\n<!--{if $arrData.agency_addr ne $arrData.order_addr01}--><!--{assign var=''use_agency'' value=''1''}--><!--{/if}-->\r\n<!--{if $arrData.agency_tel  ne $arrData.order_tel01 }--><!--{assign var=''use_agency'' value=''1''}--><!--{/if}-->\r\n<!--{if $arrData.agency_name eq ""                   }--><!--{assign var=''use_agency'' value=''0''}--><!--{/if}-->\r\n<!--{if ''1'' ne $use_agency}-->\r\n\r\n◎送り主様\r\n    [お名前]　　　<!--{$arrData.order_name01}--> 様\r\n    [カナ]　　　　<!--{$arrData.order_kana01}--> 様\r\n    [ご住所]　　　〒<!--{$arrData.order_zip01}-->\r\n                    <!--{$arrData.order_pref}--><!--{$arrData.order_addr01}-->\r\n    [お電話番号]　<!--{$arrData.order_tel01}-->\r\n<!--{else}-->\r\n\r\n◎ご注文者様（送り主様）情報\r\n    [お名前]　　　<!--{$arrData.agency_name}--> 様\r\n    [カナ]　　　　<!--{$arrData.agency_kana}--> 様\r\n    [郵便番号]　　〒<!--{$arrData.agency_zip}-->\r\n    [ご住所]　　　<!--{$arrData.agency_pref}--><!--{$arrData.agency_addr}-->\r\n    [お電話番号]　<!--{$arrData.agency_tel}-->\r\n<!--{/if}-->　\r\n    [お支払方法]　<!--{$arrData.payment_method}--><!--{$arrData.gmo_pay_method}-->\r\n    [お支払合計金額]　 ￥<!--{$arrData.payment_total}-->\r\n――――――――――――――――――――――\r\n◎お届け先様\r\n    [お名前]　　　<!--{$arrData.deliv_name01}--> 様\r\n    [ご住所]　　　〒<!--{$arrData.deliv_zip01}-->\r\n                    <!--{$arrData.deliv_pref}--><!--{$arrData.deliv_addr01}-->\r\n    [お電話番号]　<!--{$arrData.deliv_tel01}-->\r\n\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n  ■お届け予定のご案内\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n◎商品のお届け情報\r\n    [発送予定日]　　　<!--{$arrData.commit_date}-->\r\n\r\n    [お届け予定日時]　<!--{$arrData.deliv_date}-->\r\n    [お届け指定時間]　<!--{$arrData.deliv_time}-->\r\n    [送り状伝票番号]　<!--{$arrData.deliv_no}-->\r\n\r\n平田牧場より商品を発送後、下記URL内のお問合わせ伝票番号内に上記の送り状伝票番号をご登録いただきますと、配達状況がご確認いただけます。\r\n\r\n■クロネコヤマトの荷物お問い合わせシステム\r\nhttp://toi.kuronekoyamato.co.jp/cgi-bin/tneko\r\n\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n  ■ご注文商品明細\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n<!--{foreach item=detail from=$arrData.detail}-->\r\n商品名：<!--{$detail.product_code}-->　<!--{$detail.product_name}-->\r\n単価：￥ <!--{$detail.price02|number_format}-->\r\n数量：　<!--{$detail.quantity}--> 点\r\n\r\n<!--{/foreach}-->\r\n------------------------------------------------\r\n小　計　￥ <!--{$arrData.subtotal}--> (うち消費税 ￥<!--{$arrData.tax}-->）\r\n送　料　￥ <!--{$arrData.deliv_fee}-->\r\n<!--{if $arrData.parent_flg eq 1}-->\r\n手数料　￥ <!--{$arrData.charge}-->\r\n<!--{if $arrData.ismember eq 1}-->\r\nポイント利用額　￥ <!--{$arrData.use_point}-->\r\n<!--{/if}-->\r\n<!--{/if}-->\r\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\r\n注文合計　￥ <!--{$arrData.total}-->\r\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\r\n<!--{if $arrData.parent_flg eq 1}-->\r\n総 合 計　￥ <!--{$arrData.payment_total}-->\r\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\r\n<!--{/if}-->\r\n\r\n<!--{if $arrData.parent_flg eq 1 && $arrData.ismember eq 1}-->\r\n発送後の加算ポイント　 <!--{$arrData.add_point}--> pt\r\n<!--{/if}-->\r\n\r\n\r\n商品の発送準備ができましたら、改めてご連絡を申し上げます。\r\n今しばらくお待ちいただけますよう、お願いいたします。\r\n\r\n\r\n今後とも一層のご愛顧をよろしくお願い申し上げます。\r\nご利用いただきまして、誠にありがとうございました。\r\n\r\n\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n　株式会社　デモ\r\n――――――――――――――――――――――――――\r\n【住　所】　〒999-9999　デモ県デモ市1丁目\r\n【ＴＥＬ】　0000-00-0000\r\n　　　　 　[受付時間：9～17時]\r\n【ＦＡＸ】　0000-00-0000\r\n【メール】　demo@demo.com\r\n【お問合せ】https://demo.com/contact/\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n\r\n※このメールは送信専用アドレスで送信しております。\r\n  お問合せ・当ショップへのご連絡等がございます場合には、お問合せフォームか、または 【demo@demo.com】 宛てにご連絡をお送りくださいますようお願い申し上げます。\r\n                      ', 3, 1, '2016-06-15 00:00:00', '2017-02-24 15:00:19', 0, 1),
(4, 4, '出荷完了メール', '≪注文番号．{$arrData.order_id}≫商品発送のご案内【<!--{$arrConf.shop_name}-->】', '----------------------------------------------------------\r\nこのメールは、ご注文商品の発送に関する自動送信メールです。\r\n----------------------------------------------------------\r\n　\r\n<!--{$arrData.order_name01}--> 様\r\n　\r\nこのたびは<!--{$arrConf.shop_name}-->を\r\nご利用いただきまして、誠にありがとうございます。\r\n\r\nご注文をいただいておりました商品の発送が完了いたしましたので\r\nご案内を申し上げます。\r\n　\r\nなお、高速道路の渋滞や天候等により、事前のご案内ができないまま\r\n一部地域で商品のお届けが、遅れる場合がございます。\r\n　\r\n誠に恐れ入りますが、商品のお届けにつきましては\r\n直接ヤマト運輸へご確認くださいますようお願い申し上げます。\r\n　\r\n　\r\n◆ヤマト運輸　お電話でのお問合せ先\r\n　固定電話から　　0120-01-9625　（受付時間：9：00～21：00）\r\n　　\r\n　携帯電話からのお問合せ先は、下記をご覧ください。\r\n　http://www.kuronekoyamato.co.jp/service/service_index.html\r\n　\r\n　\r\nご迷惑をおかけいたしますが、宜しくお願い申し上げます。\r\n　\r\n―――――――――――――――――――――――――――――――\r\n　■ご注文商品明細\r\n―――――――――――――――――――――――――――――――\r\n<!--{foreach item=detail from=$arrData.detail}-->\r\n商品名：<!--{$detail.product_code}-->　<!--{$detail.product_name}--> × <!--{$detail.quantity}-->点\r\n単　価：￥<!--{$detail.price02|number_format}-->\r\n　\r\n<!--{/foreach}-->\r\n　\r\n―――――――――――――――――――――――――――――――\r\n　■商品代金明細\r\n―――――――――――――――――――――――――――――――\r\n小　計　￥ <!--{$arrData.subtotal}--> (うち消費税 ￥<!--{$arrData.tax}-->）\r\n送　料　￥ <!--{$arrData.deliv_fee}-->\r\n<!--{if $arrData.parent_flg eq 1}-->手数料　￥ <!--{$arrData.charge}-->\r\n<!--{/if}-->\r\n==========================================================\r\n合　計　￥ <!--{$arrData.total}-->\r\n==========================================================\r\n　\r\n　\r\nお支払い方法：<!--{$arrData.payment_method}-->\r\n　\r\n　\r\n―――――――――――――――――――――――――――――――\r\n　■商品の配送につきまして\r\n―――――――――――――――――――――――――――――――\r\n<!--{if $arrData.agency_name}-->\r\n送り主様：<!--{$arrData.agency_name}--> 様\r\n\r\n<!--{/if}-->\r\nお届け先様：<!--{$arrData.deliv_name01}--> 様\r\n　\r\n配送方法：クロネコヤマト　<!--{$arrData.deliv_temperature}-->便\r\n送り状伝票番号：<!--{$arrData.deliv_no}-->\r\n　\r\n下記のサイトより、配達状況がご確認いただけます。\r\n◆クロネコヤマト・荷物お問い合わせシステム\r\nhttp://toi.kuronekoyamato.co.jp/cgi-bin/tneko\r\n　\r\n　\r\nお届け予定日　：<!--{$arrData.deliv_date}-->\r\nお届け指定時間：<!--{$arrData.deliv_time}-->\r\n　\r\n　\r\n※交通事情や運送会社の配達状況等により、お届け日時を指定されました場合でも\r\n　遅れる場合もございます。あらかじめご了承ください。　\r\n　　\r\n　　　\r\n―――――――――――――――――――――――――――――――\r\n■商品に関するご感想やオリジナルレシピを募集しています！\r\n―――――――――――――――――――――――――――――――\r\n下記のページ内にあるフォームより、ぜひ<!--{$arrData.order_name01}-->様のご意見・ご感想を\r\nお聞かせくださいませ。\r\nhttps://\r\n　　\r\n　\r\n今後とも一層のご愛顧をよろしくお願い申し上げます。\r\nご利用いただきまして、誠にありがとうございました。\r\n\r\n\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n　株式会社　デモ\r\n――――――――――――――――――――――――――\r\n【住　所】　〒999-9999　デモ県デモ市1丁目\r\n【ＴＥＬ】　0000-00-0000\r\n　　　　 　[受付時間：9～17時]\r\n【ＦＡＸ】　0000-00-0000\r\n【メール】　demo@demo.com\r\n【お問合せ】https://demo.com/contact/\r\n━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n\r\n※このメールは送信専用アドレスで送信しております。\r\n  お問合せ・当ショップへのご連絡等がございます場合には、お問合せフォームか、または 【demo@demo.com】 宛てにご連絡をお送りくださいますようお願い申し上げます。', 4, 1, '2016-10-27 00:00:00', '2017-02-24 15:00:39', 0, 1),
(5, 5, 'パスワードリマインダー', '【{$arrConf.shop_name}】パスワード確認のご連絡', '------------------------------------------------\r\nこのメールは、「<!--{$arrConf.shop_name}-->」よりパスワード再発行依頼をされたお客様への自動送信メールです。\r\n------------------------------------------------\r\n　\r\n　	\r\n<!--{$arrData.name01}-->　様\r\n　\r\nいつも<!--{$arrConf.shop_name}-->をご利用いただきまして、誠にありがとうございます。\r\nお問い合わせをいただきました、<!--{$arrData.name01}-->様のパスワード新規再発行につきまして、ご案内いたします。\r\n　\r\n　\r\n■新パスワード\r\n　\r\n<!--{$arrData.name01}-->様の新パスワードは下記になります。\r\n{$arrData.password}\r\n　\r\n　　\r\n■ご注意ください\r\n　\r\n※パスワードを新規発行した場合、再発行前のパスワードは無効になります。\r\nログイン後、MYページの「会員登録内容変更」より、任意のパスワードに変更する事ができます。　\r\n　\r\n<!--{$arrConf.shop_name}-->をご利用の際には、メールアドレスとパスワードが必要になりますので、大切に保管してください。\r\n　\r\n　\r\n　\r\n今後ともよろしくお願い申し上げます。\r\n\r\n\r\n\r\n========================================\r\nマナトレーディング株式会社\r\nhttp://www.manas.co.jp/\r\n========================================', 5, 0, '2017-07-24 13:43:35', '2017-07-24 13:43:35', 1, 1),
(6, 6, '会員登録完了メール', '【<!--{$arrConf.shop_name}-->】 会員登録完了のお知らせ', '<!--{$arrData.name01}--> 様\r\n\r\nこの度は <!--{$arrConf.shop_name}--> のご利用ならびに会員登録をいただきまして\r\n誠にありがとうございます。\r\n\r\n下記の内容で会員登録が完了いたしましたのでご連絡いたします。\r\n●ニックネーム：<!--{$arrData.name01}-->\r\n●メールアドレス：<!--{$arrData.toAddress}-->\r\n●パスワード：<!--{$arrData.pass}-->\r\n\r\n今後ともよろしくお願い申し上げます。\r\n\r\n# このメールアドレスは送信専用のため、返信は受け付けておりません。\r\n# このメールに心当たりが無い方はお手数ですが、下記お問い合わせ先にご連絡いた \r\nだけますようお願い致します。\r\n# お問い合わせに関しましては、honma4rui@yahoo.co.jp 宛にお願い致します。\r\n\r\n===============================================\r\nマイオーダーシート\r\nhttp://yamagata01.php.xdomain.jp/MyOrderSheet/\r\n===============================================', 6, 0, '2017-07-25 14:21:19', '2017-07-25 14:21:19', 1, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `d_order_sheet`
--

CREATE TABLE IF NOT EXISTS `d_order_sheet` (
  `d_order_sheet_OrderSheetID` int(10) NOT NULL auto_increment,
  `d_order_sheet_CategoryID` int(10) NOT NULL,
  `d_order_sheet_CategoryName` varchar(100) NOT NULL,
  `d_order_sheet_CategoryColorClass` varchar(100) default NULL,
  `d_order_sheet_CustomerID` int(10) NOT NULL,
  `d_order_sheet_CustomerName` varchar(100) NOT NULL,
  `d_order_sheet_Title` varchar(50) NOT NULL,
  `d_order_sheet_Contents` text NOT NULL,
  `d_order_sheet_Keyword` mediumtext,
  `d_order_sheet_ImageFileName1` varchar(100) default NULL,
  `d_order_sheet_DelFlg` tinyint(1) NOT NULL,
  `d_order_sheet_CreatedTime` datetime NOT NULL,
  `d_order_sheet_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`d_order_sheet_OrderSheetID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `d_order_sheet`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `d_system_member`
--

CREATE TABLE IF NOT EXISTS `d_system_member` (
  `d_system_member_SystemMemberID` smallint(4) NOT NULL auto_increment COMMENT 'メンバー管理ID',
  `d_system_member_Authority` tinyint(1) NOT NULL COMMENT '権限',
  `d_system_member_Name` varchar(20) NOT NULL COMMENT '名前',
  `d_system_member_Department` varchar(20) default NULL COMMENT '所属',
  `d_system_member_LoginID` varchar(50) NOT NULL COMMENT 'ログインID',
  `d_system_member_Password` varchar(100) NOT NULL COMMENT 'パスワード',
  `d_system_member_Run` tinyint(1) NOT NULL COMMENT '稼動/非稼動',
  `d_system_member_Rank` smallint(4) NOT NULL COMMENT '表示順',
  `d_system_member_DelFlg` tinyint(1) NOT NULL COMMENT '削除フラグ',
  `d_system_member_CreatedTime` datetime NOT NULL COMMENT '作成日時',
  `d_system_member_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT '更新日時',
  `d_system_member_CreatedByID` smallint(4) NOT NULL COMMENT '作成者ID',
  `d_system_member_UpdatedByID` smallint(4) NOT NULL COMMENT '更新者ID',
  PRIMARY KEY  (`d_system_member_SystemMemberID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='メンバー管理テーブル' AUTO_INCREMENT=2 ;

--
-- テーブルのデータをダンプしています `d_system_member`
--

INSERT INTO `d_system_member` (`d_system_member_SystemMemberID`, `d_system_member_Authority`, `d_system_member_Name`, `d_system_member_Department`, `d_system_member_LoginID`, `d_system_member_Password`, `d_system_member_Run`, `d_system_member_Rank`, `d_system_member_DelFlg`, `d_system_member_CreatedTime`, `d_system_member_UpdatedTime`, `d_system_member_CreatedByID`, `d_system_member_UpdatedByID`) VALUES
(1, 1, '管理者', '', 'admin', 'a90c605ea101c3808ca0145a6bba8ce013ad0c82', 1, 1, 0, '2017-02-03 15:26:23', '2017-10-23 13:40:20', 1, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `m_log`
--

CREATE TABLE IF NOT EXISTS `m_log` (
  `m_log_LogID` bigint(20) NOT NULL auto_increment COMMENT 'ログ管理ID',
  `m_log_SystemMemberID` smallint(4) default NULL COMMENT 'メンバーID',
  `m_log_IPAddress` varchar(15) default NULL COMMENT 'IPアドレス',
  `m_log_Data` longtext COMMENT 'POST値格納用フィールド',
  `m_log_SessionData` longtext COMMENT 'SESSION値格納用フィールド',
  `m_log_DelFlg` tinyint(1) NOT NULL default '0' COMMENT '削除フラグ',
  `m_log_CreatedTime` datetime NOT NULL COMMENT '作成日時',
  `m_log_UpdatedTime` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '更新日時',
  `m_log_CreatedByID` smallint(4) NOT NULL default '0' COMMENT '作成者ID',
  `m_log_UpdatedByID` smallint(4) NOT NULL default '0' COMMENT '更新者ID',
  PRIMARY KEY  (`m_log_LogID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ログ管理マスタ' AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `m_log`
--

