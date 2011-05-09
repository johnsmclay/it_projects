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
	// Activities build report
	$titles["actbreport"]="Activities build report";
	// ACTFL report
	$titles["actflreport"]="ACTFL report";

	// Sections contents
	//
	// Home
	$contentBodies["home"]=
"<p>Just git clone it (it_projects folder), so it'll work on your localhost services too.</p>
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
</dl>\n";

	// Records flush
	$contentBodies["recflush"]=
"<h2 style=\"color: red;\">Work in progress!</h2>
<p>
	Total records:
	<span id=\"totalRecResult\">0</span>
	<input type=\"button\" value=\"Refresh\" onclick=\"Javascript:getTotalRecords()\" />
	<span id=\"loadingStatus\"></span><br />
	Records older than or aged
	<input id=\"periodRec\" type=\"text\" value=\"1\" /> day(s):
	<span id=\"periodRecResult\">0</span>
	<input type=\"button\" value=\"Refresh\" onclick=\"Javascript:getPeriodRecords()\" /><br />
	Flush old records to the last day:
	<input type=\"radio\" name=\"exactDate\" checked=\"checked\" value=\"true\" /> at midnight -
	<input type=\"radio\" name=\"exactDate\" value=\"false\" /> at the same time.
	<input type=\"button\" value=\"Flush 'em!\" onclick=\"Javascript:flushRecords\" />
</p>
<p>For example: we are on 05/14/2011 at 07:36:58pm. If you searched for the records older than or aged 2 days, you can flush 100 records from the beginning to 05/12/2011 at 07:36:58pm or 97 records from the beginning to 05/12/2011 at midnight.</p>
<p>
	<input type=\"button\" value=\"Flush all records\" onclick=\"Javascript:flushAll()\" />
</p>
<p id=\"queryRecordsResult\"></p>\n";

	// Courses push
	$contentBodies["coursepush"]=
"<p>
	Search a course on
	<select onchange=\"Javascript:searchCourse()\" id=\"searchCourseDB\">
		<option id=\"defaultFrom\" value=\"0\">Select a service</option>
		<option class=\"loDBpglms1DBpglms\" value=\"loDBpglms1DBpglms\">pglms1 @ localhost</option>
		<option class=\"qaDBpglms1DBpglms\" value=\"qaDBpglms1DBpglms\">pglms1 @ QA</option>
		<option class=\"qaDBpglms2DBpglms\" value=\"qaDBpglms2DBpglms\">pglms2 @ QA</option>
		<option class=\"loDBspeakezDBspeakez\" value=\"loDBspeakezDBspeakez\">speakez @ localhost</option>
		<option class=\"qaDBspeakez1DBspeakez\" value=\"qaDBspeakez1DBspeakez\">speakez1 @ QA</option>
		<option class=\"qaDBspeakez2DBspeakez\" value=\"qaDBspeakez2DBspeakez\">speakez2 @ QA</option>
	</select>
</p>
<p>
	Courses found:<br />
	<select id=\"searchCourseResult\"></select>
</p>
<p>
	Push the selected course on
	<select id=\"searchCourseDBResult\"></select>
	<input type=\"button\" id=\"pushCourseButton\" value=\"Run\" onclick=\"Javascript:pushCourse()\" disabled=\"disabled\" /> (This will take a while)
	<span id=\"loadingStatus\"></span>
</p>
<p id=\"queryCourseResult\"></p>\n";

	// SSO contact info
	$contentBodies["ssoinfo"]=
"<p>
	<em>Retrieve SSO contact information on PGLMS live server.</em>
</p>
<p>
	<input type=\"button\" value=\"Refresh school list\" onclick=\"Javascript:getSchoolsList()\" /> (This may take a while)
	<span id=\"loadingStatus\"></span><br />
	Only schools with a secret salt string are shown. The other ones are considered as testing schools.
</p>
<ul>
	<li style=\"color: green;\">Info seems to be complete. Check if it's not a testing one.</li>
	<li style=\"color: #FF8800;\">You have to complete or correct the contact info.</li>
	<li style=\"color: blue;\">There's no contact info and complete users to link seem to be available.</li>
	<li style=\"color: red;\">There's no contact info and incomplete users to link are available.</li>
	<li style=\"color: #552200;\">There's no contact info and you have to create a user to link.</li>
</ul>
<p>
	Found schools:
	<span id=\"numSchools\">0</span>
</p>
<table>
	<thead>
		<tr>
			<th class=\"idTh\">ID</th>
			<th class=\"idTh\">Short name</th>
			<th>Name</th>
			<th class=\"idTh\">Parent ID</th>
			<th>Contact</th>
		</tr>
	</thead>
	<tbody id=\"searchSSOResult\">
	</tbody>
</table>
<p id=\"querySSOResult\"></p>\n";

	// Users
	$contentBodies["user"]=
"<p>
	Search:
	<input type=\"text\" id=\"searchValue\" onkeyup=\"Javascript:searchUser()\" onchange=\"Javascript:searchUser()\" /> on
	<select id=\"searchProduct\" onchange=\"Javascript:searchUser()\">
		<option id=\"serverlo\" value=\"loDBallDB\">All @ localhost</option>
		<option value=\"loDBpglms1DBpglms\">pglms1 @ localhost</option>
		<option value=\"loDBspeakezDBspeakez\">speakez @ localhost</option>
		<option id=\"serverqa\" value=\"qaDBallDB\">All @ QA</option>
		<option value=\"qaDBpglms1DBpglms\">pglms1 @ QA</option>
		<option value=\"qaDBpglms2DBpglms\">pglms2 @ QA</option>
		<option value=\"qaDBspeakez1DBspeakez\">speakez1 @ QA</option>
		<option value=\"qaDBspeakez2DBspeakez\">speakez2 @ QA</option>
	</select><br />
	This is:
	<input type=\"radio\" value=\"username\" name=\"searchType\" class=\"searchType\" checked=\"checked\" onchange=\"Javascript:searchUser()\" /> a name -
	<input type=\"radio\" value=\"id\" name=\"searchType\" class=\"searchType\" onchange=\"Javascript:searchUser()\" /> an ID -
	<input type=\"radio\" value=\"email\" name=\"searchType\" class=\"searchType\" onchange=\"Javascript:searchUser()\" /> an email<br />
	At least 3 characters for a name or an email. The underscore stands for any character.
</p>
<table>
	<thead>
		<tr>
			<th class=\"idTh\">ID</th>
			<th>Username</th>
			<th>Email</th>
			<th class=\"productTh\">Product</th>
			<th>Edit</th>
			<th>
				<input id=\"deleteUserConfirm\" type=\"checkbox\">
				<a href=\"Javascript:deleteUser()\">Delete (check to confirm)</a>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class=\"idTh\">
				<input type=\"text\" value=\"Auto ID\" disabled=\"disabled\" />
			</td>
			<td>
				<input type=\"text\" id=\"addUserUsername\" value=\"Username\" />
			</td>
			<td>
				<input type=\"text\" id=\"addUserEmail\" value=\"Email\" />
			</td>
			<td class=\"productTh\">
				<select id=\"addUserProduct\" onchange=\"Javascript:addUserSetFields()\">
					<option value=\"loDBpglms1DBpglms\">pglms1 @ localhost</option>
					<option value=\"loDBspeakezDBspeakez\">speakez @ localhost</option>
					<option value=\"qaDBpglms1DBpglms\">pglms1 @ QA</option>
					<option value=\"qaDBpglms2DBpglms\">pglms2 @ QA</option>
					<option value=\"qaDBspeakez1DBspeakez\">speakez1 @ QA</option>
					<option value=\"qaDBspeakez2DBspeakez\">speakez2 @ QA</option>
				</select>
			</td>
			<td>&nbsp;</td>
			<td>
				<input type=\"button\" value=\"Add user\" onclick=\"Javascript:addUser()\" />
			</td>
		</tr>
	</tfoot>
	<tbody id=\"searchResult\"></tbody>
</table>
<p id=\"queryUserResult\"></p>\n";

	// Grades flush
	$contentBodies["gradeflush"]=
"<p>
	Search grades with an identifier:<br />
	<input type=\"radio\" name=\"searchGradeType\" onchange=\"searchGrades()\" value=\"user_id\" checked=\"checked\" /> from a user -
	<input type=\"radio\" name=\"searchGradeType\" onchange=\"searchGrades()\" value=\"activity_id\" /> from a test<br />
	<input type=\"text\" id=\"searchGradeValue\" onkeyup=\"Javascript:searchGrades()\" /> on
	<select id=\"searchGradeService\" onchange=\"Javascript:searchGrades()\">
		<option value=\"loDBspeakezDBpglms\">speakez @ localhost</option>
		<option value=\"qaDBspeakez1DBpglms\">speakez1 @ QA</option>
		<option value=\"qaDBspeakez2DBpglms\">speakez2 @ QA</option>
	</select>
</p>
<p>
	This
	<span id=\"queryTypeResult\">Result</span>
	<span id=\"queryDbTypeResult\" class=\"hidden\"></span>
	has
	<span id=\"numGrades\">0</span><span id=\"numGradesId\" class=\"hidden\"></span>
	grade(s).
	<span id=\"server\" class=\"hidden\"></span><br />
	<input type=\"button\" id=\"flushButton\" value=\"Flush these grades\" onclick=\"Javascript:flushGrades()\" disabled=\"disabled\" />
	<span id=\"loadingStatus\"></span>
</p>
<p id=\"queryFlushResult\"></p>\n";

	// Activities build report
	$contentBodies["actbreport"]=
"<h2 style=\"color: red;\">Work in progress!</h2>
<div id=\"detailsBox\"></div>
<p>
	Search for activities<br />
	<input type=\"checkbox\" checked=\"checked\" id=\"isChecked\" /> with following tags checked:
</p>
<p>
	<input type=\"checkbox\" value=\"initialbuild\" name=\"tags\" /> Initial build<br />
	<input type=\"checkbox\" value=\"mediaplaced\" name=\"tags\" /> Media placed<br />
	<input type=\"checkbox\" value=\"functional\" name=\"tags\" /> Functional<br />
	<input type=\"checkbox\" value=\"targetedit\" name=\"tags\" /> Target edit<br />
	<input type=\"checkbox\" value=\"englishedit\" name=\"tags\" /> English edit<br />
	<input type=\"checkbox\" value=\"layout\" name=\"tags\" /> Layout<br />
	<input type=\"checkbox\" value=\"writerreview\" name=\"tags\" /> Writer review
</p>
<p>
	<input type=\"button\" onclick=\"Javascript:searchBuildActivities()\" value=\"Search\" />
	<span id=\"loadingStatus\"></span>
</p>
<ul id=\"queryActivitiesResult\"></ul>\n";

	// ACTFL report
	$contentBodies["actflreport"]=
"<div class=\"rightColumn\">
	<p>
		Or browse courses to get a list of their activities and their ACTFL tags:<br />
		<input type=\"button\" value=\"Refresh courses list\" onclick=\"Javascript:getACTFLCoursesList()\" />
	</p>
	<table>
		<tbody>
			<tr>
				<td class=\"idTh\">Course:</td>
				<td>
					<select id=\"courseId\" disabled=\"disabled\" onchange=\"Javascript:getACTFLList('unit')\">
						<option value=\"0\">Choose a course&hellip;</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class=\"idTh\">Unit:</td>
				<td>
					<select id=\"unitId\" disabled=\"disabled\" onchange=\"Javascript:getACTFLList('lesson')\">
						<option value=\"0\">Choose a unit&hellip;</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class=\"idTh\">Lesson:</td>
				<td>
					<select id=\"lessonId\" disabled=\"disabled\" onchange=\"Javascript:getACTFLList('chain')\">
						<option value=\"0\">Choose a lesson&hellip;</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class=\"idTh\">Chain:</td>
				<td>
					<select id=\"chainId\" disabled=\"disabled\" onchange=\"Javascript:getACTFLActivitiesList()\">
						<option value=\"0\">Choose a chain&hellip;</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div>
	<p>Search for activities with following tags checked:</p>
	<p>
		<strong>ACTFL</strong>
		(<a href =\"Javascript:searchACTFLActivitiesSelectAll()\">check</a> /
		<a href =\"Javascript:searchACTFLActivitiesSelectNone()\">uncheck</a> all)<br />
		<input type=\"checkbox\" value=\"oneone\" name=\"tags\" /> 1.1
		<input type=\"checkbox\" value=\"onetwo\" name=\"tags\" /> 1.2
		<input type=\"checkbox\" value=\"onethree\" name=\"tags\" /> 1.3<br />
		<input type=\"checkbox\" value=\"twoone\" name=\"tags\" /> 2.1
		<input type=\"checkbox\" value=\"twotwo\" name=\"tags\" /> 2.2<br />
		<input type=\"checkbox\" value=\"threeone\" name=\"tags\" /> 3.1
		<input type=\"checkbox\" value=\"threetwo\" name=\"tags\" /> 3.2<br />
		<input type=\"checkbox\" value=\"fourone\" name=\"tags\" /> 4.1
		<input type=\"checkbox\" value=\"fourtwo\" name=\"tags\" /> 4.2<br />
		<input type=\"checkbox\" value=\"fiveone\" name=\"tags\" /> 5.1
		<input type=\"checkbox\" value=\"fivetwo\" name=\"tags\" /> 5.2
	</p>
	<p>
		<input type=\"button\" onclick=\"Javascript:searchACTFLActivities()\" value=\"Search\" />
		<span id=\"loadingStatus\"></span>
	</p>
</div>
<div id=\"queryActivitiesResult\"></div>\n";

	// Tab rendering
	$panel=array();
	$panel["title"]=$titles[$_POST["pname"]];
        $panel["body"]=$contentBodies[$_POST["pname"]];
	// Sends the tab as JSON response.
	echo json_encode($panel);
?>
