<?php
	session_start();
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Admin dashboard</title>

		<!-- Add some link tags here (CSS, x-icon...) -->
		<link rel="stylesheet" media="screen" type="text/css" title="styles" href="./css/styles.css" />

		<script type="text/javascript" src="./scripts/mtlib.js"></script>
		<script type="text/javascript" src="./scripts/xhr.js"></script>
		<script type="text/javascript" src="./scripts/panels.js"></script>
                <script type="text/javascript" src="./scripts/users.js"></script>
		<script type="text/javascript" src="./scripts/gradeflush.js"></script>
                <script type="text/javascript" src="./scripts/recflush.js"></script>
                <script type="text/javascript" src="./scripts/coursepush.js"></script>
		<script type="text/javascript" src="./scripts/ssoinfo.js"></script>
		<script type="text/javascript" src="./scripts/actbreport.js"></script>
		<script type="text/javascript" src="./scripts/actflreport.js"></script>

		<!-- Add some IE conditions here, if necessary -->

	</head>
	<body>
		<div id="header">
			<h1>
				<span>Middlebury Interactive - Admin dashboard</span>
			</h1>
		</div>
		<div id="mainMenu">
			<ul>
				<li><a href="Javascript:loadPanel(0)">Home</a></li>
				<li>|</li>
				<li><a href="Javascript:loadPanel(1)">Records flush</a></li>
				<li>|</li>
				<li><a href="Javascript:loadPanel(2)">Courses push</a></li>
				<li>|</li>
				<li><a href="Javascript:loadPanel(3)">SSO info</a></li>
				<li>|</li>
				<li><a href="Javascript:loadPanel(4)">Users management</a></li>
				<li>|</li>
				<li><a href="Javascript:loadPanel(5)">Grades flush</a></li>
				<li>|</li>
				<li><a href="Javascript:loadPanel(6)">Activities build report</a></li>
				<li>|</li>
				<li><a href="Javascript:loadPanel(7)">ACTFL report</a></li>
			</ul>
		</div>
		<div id="content">
			<h1 id="contentH1">Welcome to the dashboard!</h1>
			<div id="contentBody">
				<p>Just git clone it (it_projects folder), so it'll work on your localhost services too.</p>
				<p>Current working features:</p>
				<dl>
					<dt>- Course push:</dt>
					<dd>Copy a course from a service to another one.</dd>
					<dt>- SSO info:</dt>
					<dd>Retrieve SSO contact information on PGLMS live server.</dd>
					<dt>- Users management:</dt>
					<dd>Create admins, search, edit and delete users on many services.</dd>
					<dt>- Grades flush:</dt>
					<dd>Clear all grades of a user or a test on SpeakEZ services.</dd>
				</dl>
			</div>
		</div>
		<div id="footer">
			<p>Dashboard under construction</p>
		</div>
	</body>
</html>
