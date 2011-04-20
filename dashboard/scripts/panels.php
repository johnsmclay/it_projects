<?php
// Content lib.
//
// This file contains each tab HTML content.
// Each tab has a title and a body.
// For example:
// $titles["myTab"]="A title";
// $contentBodies["myTab"]="<p>Hello world!</p>";
//
// Import every needed Javascript file in the dashboard main page, as new Javascript files can't be loaded dynamically.

	// Initializes arrays.
	$titles=array();
	$contentBodies=array();

	// Sections titles
	//
	// Home
	$titles["home"]="Welcome to the dashboard!";
	// Records flush
	$titles["recflush"]="Users' records flush";
	// Courses push
	$titles["coursepush"]="Courses push";
	// SSO contact info
	$titles["ssoinfo"]="SSO contact information";
	// Users
	$titles["user"]="User accounts management";
	// Grades flush
	$titles["gradeflush"]="Tests grades flush";

	// Sections contents
	//
	// Home
	$contentBodies["home"]="<p>Just git clone it (it_projects folder), so it'll work on your localhost services too.</p>";
	$contentBodies["home"].="<p>Current working features:</p>\n";
	$contentBodies["home"].="<dl>\n";
	$contentBodies["home"].="<dt>- Course push:</dt>\n";
	$contentBodies["home"].="<dd>Copy a course from a service to another one.</dd>\n";
	$contentBodies["home"].="<dt>- SSO info:</dt>\n";
	$contentBodies["home"].="<dd>Retrieve SSO contact information on PGLMS live server.</dd>\n";
	$contentBodies["home"].="<dt>- Users management:</dt>\n";
	$contentBodies["home"].="<dd>Create admins, search, edit and delete users on many services.</dd>\n";
	$contentBodies["home"].="<dt>- Grades flush:</dt>\n";
	$contentBodies["home"].="<dd>Clear all grades of a user or a test on SpeakEZ services.</dd>\n";
	$contentBodies["home"].="</dl>\n";
	// Records flush
	$contentBodies["recflush"]="<p>Total records: <span id=\"totalRecResult\" onload=\"Javascript:getTotalRecords()\">N/A yet</span><br />\n";
	$contentBodies["recflush"].="Records older than or aged <input id=\"periodRec\" type=\"text\" value=\"1\" onkeyup=\"Javascript:getPeriodRecords()\" /> day(s): <span id=\"periodRecResult\" onload=\"Javascript:getPeriodRecords()\">N/A yet</span><br />\n";
	$contentBodies["recflush"].="Flush old records to the last day: <input type=\"radio\" name=\"exactDate\" checked=\"checked\" value=\"true\" /> at midnight - <input type=\"radio\" name=\"exactDate\" value=\"false\" /> at the same time. <input type=\"button\" value=\"Flush 'em!\" onclick=\"Javascript:flushRecords\" /></p>\n";
	$contentBodies["recflush"].="<p>For example: we are on 05/14/2011 at 07:36:58pm. If you searched for the records older than or aged 2 days, you can flush 100 records from the beginning to 05/12/2011 at 07:36:58pm or 97 records from the beginning to 05/12/2011 at midnight.</p>\n";
	$contentBodies["recflush"].="<p><input type=\"button\" value=\"Flush all records\" onclick=\"Javascript:flushAll()\" /></p>\n";
	$contentBodies["recflush"].="<p id=\"queryRecordsResult\"></p>\n";
	// Courses push
	$contentBodies["coursepush"]="<p>Search a course with its identifier:<br />\n";
	$contentBodies["coursepush"].="<input type=\"text\" id=\"searchCourseValue\" onkeyup=\"Javascript:searchCourse()\" onchange=\"Javascript:searchCourse()\" />\n";
	$contentBodies["coursepush"].="on <select onchange=\"Javascript:searchCourse()\" id=\"searchCourseDB\">\n";
	$contentBodies["coursepush"].="<option class=\"loDBpglms1DBpglms\" value=\"loDBpglms1DBpglms\">pglms1 @ localhost</option>\n";
	$contentBodies["coursepush"].="<option class=\"qaDBpglms1DBpglms\" value=\"qaDBpglms1DBpglms\">pglms1 @ QA</option>\n";
	$contentBodies["coursepush"].="<option class=\"qaDBpglms2DBpglms\" value=\"qaDBpglms2DBpglms\">pglms2 @ QA</option>\n";
	$contentBodies["coursepush"].="<option class=\"loDBspeakezDBspeakez\" value=\"loDBspeakezDBspeakez\">speakez @ localhost</option>\n";
	$contentBodies["coursepush"].="<option class=\"qaDBspeakez1DBspeakez\" value=\"qaDBspeakez1DBspeakez\">speakez @ QA</option>\n";
	$contentBodies["coursepush"].="<option class=\"qaDBspeakez2DBspeakez\" value=\"qaDBspeakez2DBspeakez\">speakez @ QA</option>\n";
	$contentBodies["coursepush"].="</select>\n";
	$contentBodies["coursepush"].="</p>\n";
	$contentBodies["coursepush"].="<p>Course found:</p>\n";
	$contentBodies["coursepush"].="<p id=\"searchCourseResult\">None</p>\n";
	$contentBodies["coursepush"].="<p>Push it on <select id=\"searchCourseDBResult\"></select> <input type=\"button\" id=\"pushCourseButton\" value=\"Run\" onclick=\"Javascript:pushCourse()\" disabled=\"disabled\" /> (This will take a while) <span id=\"loadingStatus\"></span></p>\n";
	$contentBodies["coursepush"].="<p id=\"queryCourseResult\"></p>\n";
	// SSO contact info
	$contentBodies["ssoinfo"]="<p><em>Retrieve SSO contact information on PGLMS live server.</em></p>\n";
	$contentBodies["ssoinfo"].="<p><input type=\"button\" value=\"Refresh school list\" onclick=\"Javascript:getSchoolsList()\" /> (This may take a while) <span id=\"loadingStatus\"></span><br />\n";
	$contentBodies["ssoinfo"].="Only schools with a secret salt string are shown. The other ones are considered as testing schools.</p>\n";
	$contentBodies["ssoinfo"].="<ul>\n";
	$contentBodies["ssoinfo"].="<li style=\"color: green;\">Info seems to be complete. Check if it's not a testing one.</li>\n";
	$contentBodies["ssoinfo"].="<li style=\"color: #FF8800;\">You have to complete or correct the contact info.</li>\n";
	$contentBodies["ssoinfo"].="<li style=\"color: blue;\">There's no contact info and complete users to link seem to be available.</li>\n";
	$contentBodies["ssoinfo"].="<li style=\"color: red;\">There's no contact info and incomplete users to link are available.</li>\n";
	$contentBodies["ssoinfo"].="<li style=\"color: #552200;\">There's no contact info and you have to create a user to link.</li>\n";
	$contentBodies["ssoinfo"].="</ul>\n";
	$contentBodies["ssoinfo"].="<p>Found schools: <span id=\"numSchools\">0</span></p>\n";
	$contentBodies["ssoinfo"].="<table>\n";
	$contentBodies["ssoinfo"].="<thead>\n";
	$contentBodies["ssoinfo"].="<tr>\n";
	$contentBodies["ssoinfo"].="<th class=\"idTh\">ID</th>\n";
        $contentBodies["ssoinfo"].="<th class=\"idTh\">Short name</th>\n";
	$contentBodies["ssoinfo"].="<th>Name</th>\n";
	$contentBodies["ssoinfo"].="<th class=\"idTh\">Parent ID</th>\n";
 	$contentBodies["ssoinfo"].="<th>Contact</th>\n";
	$contentBodies["ssoinfo"].="</tr>\n";
	$contentBodies["ssoinfo"].="</thead>\n";
	$contentBodies["ssoinfo"].="<tbody id=\"searchSSOResult\">\n";
	$contentBodies["ssoinfo"].="</tbody>\n";
	$contentBodies["ssoinfo"].="</table>\n";
	$contentBodies["ssoinfo"].="<p id=\"querySSOResult\"></p>\n";
	// Users
	$contentBodies["user"]="<p>Search:\n";
	$contentBodies["user"].="<input type=\"text\" id=\"searchValue\" onkeyup=\"Javascript:searchUser()\" onchange=\"Javascript:searchUser()\" /> on <select id=\"searchProduct\" onchange=\"Javascript:searchUser()\">\n";
	$contentBodies["user"].="<option id=\"serverlo\" value=\"loDBallDB\">All @ localhost</option>\n";
	$contentBodies["user"].="<option value=\"loDBpglms1DBpglms\">pglms1 @ localhost</option>\n";
	$contentBodies["user"].="<option value=\"loDBspeakezDBspeakez\">speakez @ localhost</option>\n";
	$contentBodies["user"].="<option id=\"serverqa\" value=\"qaDBallDB\">All @ QA</option>\n";
	$contentBodies["user"].="<option value=\"qaDBpglms1DBpglms\">pglms1 @ QA</option>\n";
	$contentBodies["user"].="<option value=\"qaDBpglms2DBpglms\">pglms2 @ QA</option>\n";
	$contentBodies["user"].="<option value=\"qaDBspeakez1DBspeakez\">speakez1 @ QA</option>\n";
	$contentBodies["user"].="<option value=\"qaDBspeakez2DBspeakez\">speakez2 @ QA</option>\n";
	$contentBodies["user"].="</select><br />\n";
	$contentBodies["user"].="This is: <input type=\"radio\" value=\"username\" name=\"searchType\" class=\"searchType\" checked=\"checked\" onchange=\"Javascript:searchUser()\" /> a name - <input type=\"radio\" value=\"id\" name=\"searchType\" class=\"searchType\" onchange=\"Javascript:searchUser()\" /> an ID - <input type=\"radio\" value=\"email\" name=\"searchType\" class=\"searchType\" onchange=\"Javascript:searchUser()\" /> an email<br />\n";
	$contentBodies["user"].="At least 3 characters for a name or an email. The underscore stands for any character.</p>\n";
	$contentBodies["user"].="<table>\n";
	$contentBodies["user"].="<thead>\n";
	$contentBodies["user"].="<tr>\n";
	$contentBodies["user"].="<th class=\"idTh\">ID</th>\n";
        $contentBodies["user"].="<th>Username</th>\n";
	$contentBodies["user"].="<th>Email</th>\n";
	$contentBodies["user"].="<th class=\"productTh\">Product</th>\n";
        $contentBodies["user"].="<th>Edit</th>\n";
        $contentBodies["user"].="<th><input id=\"deleteUserConfirm\" type=\"checkbox\"> <a href=\"Javascript:deleteUser()\">Delete (check to confirm)</a></th>\n";
	$contentBodies["user"].="</tr>\n";
	$contentBodies["user"].="</thead>\n";
	$contentBodies["user"].="<tfoot>\n";
	$contentBodies["user"].="<tr>\n";
	$contentBodies["user"].="<td class=\"idTh\"><input type=\"text\" value=\"Auto ID\" disabled=\"disabled\" /></td>\n";
        $contentBodies["user"].="<td><input type=\"text\" id=\"addUserUsername\" value=\"Username\" /></td>\n";
        $contentBodies["user"].="<td><input type=\"text\" id=\"addUserEmail\" value=\"Email\" /></td>\n";
        $contentBodies["user"].="<td class=\"productTh\">\n";
	$contentBodies["user"].="<select id=\"addUserProduct\" onchange=\"Javascript:addUserSetFields()\">\n";
	$contentBodies["user"].="<option value=\"loDBpglms1DBpglms\">pglms1 @ localhost</option>\n";
	$contentBodies["user"].="<option value=\"loDBspeakezDBspeakez\">speakez @ localhost</option>\n";
	$contentBodies["user"].="<option value=\"qaDBpglms1DBpglms\">pglms1 @ QA</option>\n";
	$contentBodies["user"].="<option value=\"qaDBpglms2DBpglms\">pglms2 @ QA</option>\n";
	$contentBodies["user"].="<option value=\"qaDBspeakez1DBspeakez\">speakez1 @ QA</option>\n";
	$contentBodies["user"].="<option value=\"qaDBspeakez2DBspeakez\">speakez2 @ QA</option>\n";
	$contentBodies["user"].="</select>\n";
	$contentBodies["user"].="</td>\n";
	$contentBodies["user"].="<td>&nbsp;</td>\n";
        $contentBodies["user"].="<td><input type=\"button\" value=\"Add user\" onclick=\"Javascript:addUser()\" /></td>\n";
	$contentBodies["user"].="<tbody id=\"searchResult\">\n";
	$contentBodies["user"].="</tbody>\n";
	$contentBodies["user"].="</table>\n";
	$contentBodies["user"].="<p id=\"queryUserResult\"></p>\n";
	// Grades flush
	$contentBodies["gradeflush"]="<p>Search grades with an identifier:<br />\n";
	$contentBodies["gradeflush"].="<input type=\"radio\" name=\"searchGradeType\" onchange=\"searchGrades()\" value=\"user_id\" checked=\"checked\" /> from a user - <input type=\"radio\" name=\"searchGradeType\" onchange=\"searchGrades()\" value=\"activity_id\" /> from a test<br />\n";
	$contentBodies["gradeflush"].="<input type=\"text\" id=\"searchGradeValue\" onkeyup=\"Javascript:searchGrades()\" /> on <select id=\"searchGradeService\" onchange=\"Javascript:searchGrades()\">\n";
	$contentBodies["gradeflush"].="<option value=\"loDBspeakezDBpglms\">speakez @ localhost</option>\n";
	$contentBodies["gradeflush"].="<option value=\"qaDBspeakez1DBpglms\">speakez1 @ QA</option>\n";
	$contentBodies["gradeflush"].="<option value=\"qaDBspeakez2DBpglms\">speakez2 @ QA</option>\n";
	$contentBodies["gradeflush"].="</select></p>\n";
	$contentBodies["gradeflush"].="<p>This <span id=\"queryTypeResult\">Result</span><span id=\"queryDbTypeResult\" class=\"hidden\"></span> has <span id=\"numGrades\">0</span><span id=\"numGradesId\" class=\"hidden\"></span> grade(s).<span id=\"server\" class=\"hidden\"></span><br />\n";
	$contentBodies["gradeflush"].="<input type=\"button\" id=\"flushButton\" value=\"Flush these grades\" onclick=\"Javascript:flushGrades()\" disabled=\"disabled\" /> <span id=\"loadingStatus\"></span></p>\n";
	$contentBodies["gradeflush"].="<p id=\"queryFlushResult\"></p>\n";

	// Tab rendering
	$panel=array();
	$panel["title"]=$titles[$_POST["pname"]];
        $panel["body"]=$contentBodies[$_POST["pname"]];
	// Sends the tab as JSON response.
	echo json_encode($panel);
?>
