-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017 年 10 朁E23 日 13:41
-- サーバのバージョン： 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mos`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `d_customer`
--

CREATE TABLE `d_customer` (
  `d_customer_CustomerID` int(10) NOT NULL,
  `d_customer_Name` varchar(100) DEFAULT NULL,
  `d_customer_EmailAddress` varchar(100) NOT NULL,
  `d_customer_Password` varchar(100) NOT NULL,
  `d_customer_DelFlg` tinyint(1) DEFAULT NULL,
  `d_customer_CreatedTime` datetime NOT NULL,
  `d_customer_UpdatedTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `d_order_sheet`
--

CREATE TABLE `d_order_sheet` (
  `d_order_sheet_OrderSheetID` int(10) NOT NULL,
  `d_order_sheet_CustomerID` int(10) NOT NULL,
  `d_order_sheet_CustomerName` varchar(100) NOT NULL,
  `d_order_sheet_Contents` text NOT NULL,
  `d_order_sheet_DelFlg` tinyint(1) NOT NULL,
  `d_order_sheet_CreatedTime` datetime NOT NULL,
  `d_order_sheet_UpdatedTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `d_system_member`
--

CREATE TABLE `d_system_member` (
  `d_system_member_SystemMemberID` smallint(4) NOT NULL COMMENT 'メンバー管理ID',
  `d_system_member_Authority` tinyint(1) NOT NULL COMMENT '権限',
  `d_system_member_Name` varchar(20) NOT NULL COMMENT '名前',
  `d_system_member_Department` varchar(20) DEFAULT NULL COMMENT '所属',
  `d_system_member_LoginID` varchar(50) NOT NULL COMMENT 'ログインID',
  `d_system_member_Password` varchar(100) NOT NULL COMMENT 'パスワード',
  `d_system_member_Run` tinyint(1) NOT NULL COMMENT '稼動/非稼動',
  `d_system_member_Rank` smallint(4) NOT NULL COMMENT '表示順',
  `d_system_member_DelFlg` tinyint(1) NOT NULL COMMENT '削除フラグ',
  `d_system_member_CreatedTime` datetime NOT NULL COMMENT '作成日時',
  `d_system_member_UpdatedTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  `d_system_member_CreatedByID` smallint(4) NOT NULL COMMENT '作成者ID',
  `d_system_member_UpdatedByID` smallint(4) NOT NULL COMMENT '更新者ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='メンバー管理テーブル';

--
-- テーブルのデータのダンプ `d_system_member`
--

INSERT INTO `d_system_member` (`d_system_member_SystemMemberID`, `d_system_member_Authority`, `d_system_member_Name`, `d_system_member_Department`, `d_system_member_LoginID`, `d_system_member_Password`, `d_system_member_Run`, `d_system_member_Rank`, `d_system_member_DelFlg`, `d_system_member_CreatedTime`, `d_system_member_UpdatedTime`, `d_system_member_CreatedByID`, `d_system_member_UpdatedByID`) VALUES
(1, 1, '管理者', '', 'admin', 'a90c605ea101c3808ca0145a6bba8ce013ad0c82', 1, 1, 0, '2017-02-03 15:26:23', '2017-10-23 04:40:20', 1, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `m_log`
--

CREATE TABLE `m_log` (
  `m_log_LogID` bigint(20) NOT NULL COMMENT 'ログ管理ID',
  `m_log_SystemMemberID` smallint(4) DEFAULT NULL COMMENT 'メンバーID',
  `m_log_IPAddress` varchar(15) DEFAULT NULL COMMENT 'IPアドレス',
  `m_log_Data` longtext COMMENT 'POST値格納用フィールド',
  `m_log_SessionData` longtext COMMENT 'SESSION値格納用フィールド',
  `m_log_DelFlg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `m_log_CreatedTime` datetime NOT NULL COMMENT '作成日時',
  `m_log_UpdatedTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新日時',
  `m_log_CreatedByID` smallint(4) NOT NULL DEFAULT '0' COMMENT '作成者ID',
  `m_log_UpdatedByID` smallint(4) NOT NULL DEFAULT '0' COMMENT '更新者ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ログ管理マスタ';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `d_customer`
--
ALTER TABLE `d_customer`
  ADD PRIMARY KEY (`d_customer_CustomerID`);

--
-- Indexes for table `d_order_sheet`
--
ALTER TABLE `d_order_sheet`
  ADD PRIMARY KEY (`d_order_sheet_OrderSheetID`);

--
-- Indexes for table `d_system_member`
--
ALTER TABLE `d_system_member`
  ADD PRIMARY KEY (`d_system_member_SystemMemberID`);

--
-- Indexes for table `m_log`
--
ALTER TABLE `m_log`
  ADD PRIMARY KEY (`m_log_LogID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `d_customer`
--
ALTER TABLE `d_customer`
  MODIFY `d_customer_CustomerID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `d_order_sheet`
--
ALTER TABLE `d_order_sheet`
  MODIFY `d_order_sheet_OrderSheetID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `d_system_member`
--
ALTER TABLE `d_system_member`
  MODIFY `d_system_member_SystemMemberID` smallint(4) NOT NULL AUTO_INCREMENT COMMENT 'メンバー管理ID', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `m_log`
--
ALTER TABLE `m_log`
  MODIFY `m_log_LogID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ログ管理ID';COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
