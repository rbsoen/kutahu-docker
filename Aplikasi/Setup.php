<?php

	define("RelativePath", ".");
	include(RelativePath . "/Common.php");
	$db = new clsDBConnection1();
	
// cek lagi disini, jika memang valid balik lagi, menghindari setup 2x
	if ($db->try_connect()) {
		if (@mysql_select_db($db->DBDatabase,$db->link_id())) {
			if (@mysql_list_fields($db->DBDatabase, "authors")) {
				if (@mysql_list_fields($db->DBDatabase, "users")) {
					header("Location: Login.php"); die();
				}
			}
		}
	}

	function CreateTableQuery($dbObject, $query) {
		if ($dbObject->query($query)){ echo '<p>OK!</p>'; }
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Setup &mdash; Kutahu</title>
		<style>
			body {
				padding: .5em 1em;
				background-color: beige;
				line-height: 1.5em;
			}
			#conninfo {
				text-align: left;
				width: 100%;
			}
			#conninfo th {
				width: 12em;
			}
			#conninfo td, #conninfo th {
				padding: .25em;
			}
			#conninfo small {
				font-weight: normal;
			}
			#buttonnav {
				list-style: none;
				width: 100%;
				display: table;
				text-align: center;
			}
			#buttonnav > * {
				display: table-cell;
			}
		</style>
	</head>
	<body>
	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$db->connect();
			echo '
				<h1>Setup</h1>
				<p>
					Berikut hasil inisialisasi database&hellip;
				</p>
			';
			echo '<h2>Authors (administrator)</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `authors` (
				  `AutUsername` varchar(15) NOT NULL DEFAULT '' PRIMARY KEY,
				  `AutPassword` varchar(15) NOT NULL DEFAULT '',
				  `AutName` varchar(50) NOT NULL DEFAULT '',
				  `AutDept` varchar(50) DEFAULT NULL,
				  `AutInstance` varchar(50) DEFAULT NULL,
				  `AutAddress` varchar(100) DEFAULT NULL,
				  `AutPhone` varchar(15) NOT NULL DEFAULT '',
				  `AutEmail` varchar(150) NOT NULL DEFAULT '',
				  `AutPhoto` varchar(150) DEFAULT NULL,
				  `AutLevel` char(1) DEFAULT NULL,
				  `AutActive` char(1) DEFAULT NULL,
				  `AutExperience1` varchar(150) DEFAULT NULL,
				  `AutExperience2` varchar(150) DEFAULT NULL,
				  `AutExperience3` varchar(150) DEFAULT NULL
				)
			");
			echo '<h2>Category</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `category` (
				  `CatID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `Cat_CatID` int(11) DEFAULT NULL,
				  `ModID` int(11) NOT NULL DEFAULT '0',
				  `CatTitle` varchar(150) NOT NULL DEFAULT '',
				  `CatDesc` text
				)
			");
			echo '<h2>Glossary</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `glossary` (
				  `GlosID` int(11) NOT NULL PRIMARY KEY,
				  `GlosTitle` varchar(150) NOT NULL DEFAULT '',
				  `GlosDesc` text NOT NULL,
				  `SubKnowItemID` int(11) NOT NULL DEFAULT '0'
				)
			");
			echo '<h2>Knowledge Area</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `knowledgearea` (
				  `KnowAreaID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `CatID` int(11) NOT NULL DEFAULT '0',
				  `KnowAreaTitle` varchar(150) NOT NULL DEFAULT ''
				)
			");
			echo '<h2>Knowledge Item</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `knowledgeitem` (
				  `KnowItemID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `KnowAreaID` int(11) NOT NULL DEFAULT '0',
				  `KnowItemTitle` varchar(150) NOT NULL DEFAULT '',
				  `KnowItemContent` text
				)
			");
			echo '<h2>Glossary</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `glossary` (
				  `GlosID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `GlosTitle` varchar(150) NOT NULL DEFAULT '',
				  `GlosDesc` text NOT NULL,
				  `SubKnowItemID` int(11) NOT NULL DEFAULT '0'
				)
			");
			echo '<h2>Module</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `module` (
				  `ModID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `Mod_ModID` int(11) DEFAULT NULL,
				  `AutUsername` varchar(15) NOT NULL DEFAULT '',
				  `ModTitle` varchar(150) NOT NULL DEFAULT '',
				  `ModDesc` text,
				  `ModVersion` varchar(15) DEFAULT NULL,
				  `ModCreated` datetime DEFAULT NULL,
				  `ModModify` datetime DEFAULT NULL
				)
			");
			echo '<h2>Question</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `question` (
				  `QueID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `KnowAreaID` int(11) NOT NULL DEFAULT '0',
				  `QueTitle` varchar(255) NOT NULL DEFAULT '',
				  `QueChoiceA` varchar(255) NOT NULL DEFAULT '',
				  `QueChoiceB` varchar(255) NOT NULL DEFAULT '',
				  `QueChoiceC` varchar(255) DEFAULT NULL,
				  `QueChoiceD` varchar(255) DEFAULT NULL,
				  `QueChoiceE` varchar(255) DEFAULT NULL,
				  `QueAnswer` char(1) NOT NULL DEFAULT '',
				  `QueModule` tinyint(1) DEFAULT NULL,
				  `QueCategory` tinyint(1) DEFAULT NULL
				)
			");
			echo '<h2>Subknowledge Item</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `subknowledgeitem` (
				  `SubKnowItemID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `KnowItemID` int(11) NOT NULL DEFAULT '0',
				  `SubKnowlItemTitle` varchar(150) NOT NULL DEFAULT '',
				  `SubKnowlItemContent` text
				)
			");
			echo '<h2>Test</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `test` (
				  `TestID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				  `ModID` int(11) DEFAULT NULL,
				  `KnowAreaID` int(11) DEFAULT NULL,
				  `CatID` int(11) DEFAULT NULL,
				  `UserUsername` char(15) NOT NULL DEFAULT '',
				  `TestType` char(1) NOT NULL DEFAULT '',
				  `TestValue` float NOT NULL DEFAULT '0',
				  `TestDateTime` date DEFAULT NULL
				)
			");
			echo '<h2>Users</h2>';
			CreateTableQuery($db, "
				CREATE TABLE IF NOT EXISTS `users` (
				  `UserUsername` varchar(15) NOT NULL DEFAULT '' PRIMARY KEY,
				  `UserPassword` varchar(15) NOT NULL DEFAULT '',
				  `UserFullName` varchar(50) NOT NULL DEFAULT '',
				  `UserAddress` text,
				  `UserActive` char(1) DEFAULT NULL,
				  `UserEmail` varchar(150) DEFAULT NULL
				)
			");
			echo '<h2>Setup akun administrator</h2>';
			CreateTableQuery($db, "
				INSERT INTO `authors` (
					`AutUsername`, `AutPassword`, `AutName`, `AutPhone`,
					`AutEmail`, `AutActive`, `AutLevel`
				) VALUES  (
					'admin', 'admin', 'Administrator', '080989999',
					'admin@localhost', 1, 1
				)
			");
			echo '
				<p>
					<b>Selesai!</b> Akses administrator:
				</p>
				<ul>
					<li><b>Username:</b> admin</li>
					<li><b>Password:</b> admin</li>
					<li><b>Pastikan login sebagai Penulis!</b></li>
				</ul>
			';
			echo '
				<form id="bk" method="get" action="Login.php" hidden></form>
				<ul id="buttonnav">
					<li><button form="bk">kembali ke login</button></li>
				</ul>
			';
		} else {
			echo '
				<h1>Setup</h1>
				<p>
					Sebelum menggunakan software ini, pastikan konfigurasi
					berikut benar. Jika konfigurasi ini tidak benar, silahkan
					edit <code>Common.php</code>, baris 82&ndash;85. Sistem
					setup interaktif kemungkinan akan ditambahkan di kemudian
					hari.
				</p>
				<table id="conninfo" border=1>
					<tbody>
						<tr>
							<th scope="row">
								Host<br>
								<small>
									Nama host yang digunakan untuk
									koneksi ke database.
								</small>
							</th>
							<td>
								'.$db->DBHost.'
							</td>
						</tr>
						<tr>
							<th scope="row">
								Nama Database<br>
								<small>
									Nama database yang akan digunakan
									untuk semua penyimpanan data.
								</small>
							</th>
							<td>
								'.$db->DBDatabase.'
							</td>
						</tr>
						<tr>
							<th scope="row">
								User Database<br>
								<small>
									User yang memiliki privilege
									yang cukup untuk dapat memanipulasi
									database di atas.
								</small>
							</th>
							<td>
								'.$db->DBUser.'
							</td>
						</tr>
						<tr>
							<th scope="row">
								Password User Database<br>
								<small>
									Password untuk user di atas.
								</small>
							</th>
							<td>
								'.$db->DBPassword.'
							</td>
						</tr>
					</tbody>
				</table>
				<form id="bk" method="get" action="Login.php" hidden></form>
				<form id="hf" method="post" hidden></form>
				<ul id="buttonnav">
					<li><button form="bk">kembali ke login</button></li>
					<li><button form="hf">inisialisasi semua</button></li>
				</ul>
			';
		}
	?>
	</body>
</html>
