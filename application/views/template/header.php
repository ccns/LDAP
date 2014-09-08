<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CCNS Membership Directory</title>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css" type="text/css" />
<link rel="stylesheet" href="/css/styles.css" type="text/css" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--
corps, a free CSS web template by ZyPOP (zypopwebtemplates.com/)

Download: http://zypopwebtemplates.com/

License: Creative Commons Attribution
//-->
</head>
<body>
<div id="container">
    <header>
	<div class="width">
    		<h1><a href="/">CCNS<span class="span10"></span><span>社員資訊管理系統</span></a></h1>
        	<h2>各位大大們記得登錄個人資訊哦~</h2>
	</div>
    </header>
    <nav>
	<div class="width">
    		<ul>
        		<li class="start <?= isset($tab['home'])? "selected" : "" ?>"><a href="/">首頁</a></li>
        	    	<li class="<?= isset($tab['user'])? "selected" : "" ?>"><a href="/index.php/user/">個人資訊</a></li>
         	   	<li class="end <?= isset($tab['member_directory'])? "selected" : "" ?>"><a href="/index.php/member_directory/">通訊錄</a></li>
        	</ul>
	</div>
    </nav>
