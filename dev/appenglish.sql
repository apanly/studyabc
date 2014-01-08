--
-- Database: `appenglish`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chititle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `engtitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picuri` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `artid` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `idate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `engtitle` (`engtitle`),
  KEY `idate` (`idate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `articles_content`
--

CREATE TABLE IF NOT EXISTS `articles_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) NOT NULL,
  `englishjson` text COLLATE utf8_unicode_ci NOT NULL,
  `chinesejson` text COLLATE utf8_unicode_ci NOT NULL,
  `idate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `cet`
--

CREATE TABLE IF NOT EXISTS `cet` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cettitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cetdate` date NOT NULL,
  `idate` date NOT NULL,
  `suri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1=>cet4,2=>cet6',
  `has_desc` int(1) NOT NULL DEFAULT '0' COMMENT '1=>has,0=>dont',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `has_desc` (`has_desc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=142 ;

-- --------------------------------------------------------

--
-- Table structure for table `cetdesc`
--

CREATE TABLE IF NOT EXISTS `cetdesc` (
  `id` int(10) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `idate` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tinyenglish`
--

CREATE TABLE IF NOT EXISTS `tinyenglish` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idate` int(10) NOT NULL,
  `md5hash` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '英语语句的hash数值用以保证不会重复采集',
  `econtent` text COLLATE utf8_unicode_ci NOT NULL COMMENT '英文内容',
  `zcontent` text COLLATE utf8_unicode_ci NOT NULL COMMENT '中文内容',
  `imguri` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片地址',
  `innermguri` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '''''',
  `createtime` int(10) NOT NULL COMMENT '采集时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5hash` (`md5hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=678 ;