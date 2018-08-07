<?php
	require('config/config.inc.php');
	if (empty($_SESSION['username'])) {
		header("Location: login.php");
	}
	print "<html>";
	include("txt/head.txt");
?>
	<div id="content">
		<?php 
			echo '<div id="nav">';
			include("txt/menu.php");
			echo "</div>";
		?>
		
		<div id="main_content">

		<div class="indexTitle" id="step"> Submit a task </div>
		Dear guests,<br><br>
		We have recently migrated to a newer, faster server to ensure shorter turn-around times for submitted jobs. If you are a new user, you may not notice a difference. If you are returning user, you may notice that some things have changed or have become better. If you face problems with any of the features, please let us know and we will be glad to work with you.<br><br>Chiron team
		<div id="submitform" class="mdiv">
			<strong>Step 1 : Enter task parameters</strong>
			<form id="jobform" enctype="multipart/form-data" action="filter.php" method="POST" autocomplete="off">
				<fieldset><legend align=center>Task Parameters</legend>
					<input type="hidden" id="username" name="username" value="<?php  echo $_SESSION['username'] ?>">
					<div class="cdiv">
						<div class="ldiv">Job Title
							<span class='formInfo'>
								<a href="txt/submit/jobtitle.htm?width=400" name="What's this?" class="jTip" id='title'>
									<img src="style/img/help.png" border="0px">
								</a>
							</span> :
						</div>
						<div class="rdiv"><input id="title" maxlength=10 type="text" name="title" value=""></div>
					</div>

					<div class="cdiv">
						<div class="ldiv">Input Type :</div>
						<div class="rdiv"><input id=rpdb class=radio type=radio name=inptype value=pdb align=middle checked>PDB ID&nbsp;&nbsp;<input id=rfile class=radio type=radio name=inptype value=file align=middle>File</div>
					</div>
					
					<div class="cdiv" id="pdbdiv">
						<div class="ldiv">PDB ID :</div>
						<div class="rdiv"><input type=text name=pdbid value="" maxlength=4 id=pdbid><img id="verifying" style="display: none;" width="15px" height="15px" align="middle" src="style/img/running.gif" border="0px" /></div>
						
						<div class="cdiv">
							<div class="ldiv"></div>
							<div class="rdiv" id="pdbinfo"></div>
						</div>
						
						<div class="cdiv" id="pdb_mlcyn">
							<div class="ldiv">Consider Small Molecules : </div>
							<div class="rdiv"><input id="pysmlcs" class="radio" type="radio" name="psmlcs" value="yes" align="middle">Yes<input id="pnsmlcs" class="radio" type="radio" name="psmlcs" value="no" align="middle" checked>No</div>
						</div>

						<div class="cdiv" id="pdb_mlcs">
							<div class="ldiv"></div>
							<div class="rdiv" id="pdb_mlclist"></div>
						</div>

					</div>

					<div class="cdiv" id="filediv">
						<div class="ldiv">Choose File :</div>
						<div class="rdiv"><input type=file name=uploadedpdb value="" id=file><img id="uploading" style="display: none;" width="15px" height="15px" align="middle" src="style/img/running.gif" border="0px" /></div>
						
						<div class="cdiv">
							<div class="ldiv"></div>
							<div class="rdiv" id="fileinfo"></div>
						</div>
					
						<div class="cdiv" id="file_mlcyn">
							<div class="ldiv">Consider Small Molecules : </div>
							<div class="rdiv"><input id="fysmlcs" class="radio" type="radio" name="fsmlcs" value="yes" align="middle">Yes<input id="fnsmlcs" class="radio" type="radio" name="fsmlcs" value="no" align="middle" checked>No</div>
						</div>

						<div class="cdiv" id="file_mlcs">
							<div class="ldiv"></div>
							<div class="rdiv" id="file_mlclist">List of small molecules<br><small>Note 1: Not all atom types are supported. Please review documentation</small><br><small>Note 2: Please do not include small molecules bonded to the protein</small></div>
						</div>

					</div>

					<div class="cdiv">
						<div class="ldiv">Constrain Sidechain
							<span class='formInfo'>
								<a href="txt/submit/sidechain.htm?width=400" name="What's this?" class="jTip" id='constr'>
									<img src="style/img/help.png" border="0px">
								</a>
							</span> :
						</div>
						<div class="rdiv"><input class="chkbox" id="constrain" type="checkbox" name="constrain" value=""></div>
					</div>
						
					<?php
						if($_SESSION[ 'username'] != "guest") {
							echo '<div class="cdiv">';
							echo '<div class="ldiv">E-mail Notification :</div>';
							echo '<div class="rdiv"><input class="chkbox" id="notify" type="checkbox" name="notify" value=""></div>';
							echo '</div>';
						}
						if($_SESSION[ 'username'] == "guest" ) {
							/*echo '<div id="guest_email" class="cdiv">';
							echo '<div class="ldiv">E-mail :</div>';
							echo '<div class="rdiv" style="color: #990000"><input type="text" name="guestEmail" id="guestEmail" value=""><br><small>Please enter a valid email. This field is not validated</small></div>';
							echo '</div>';*/
						}
						?>

					<input type=hidden id=maxsize name=maxsize value=10000000>

					
					<!--<div class="cdiv">
						<div id="submit" style="padding-left: 220px;"><input type="submit" id="submitbtn" value="Submit"></div>
					</div>-->
					

					</div>
				</fieldset>
			</form>
			<div id="task" class="mdiv">
			<strong>Step 2 : Choose relevant task</strong>
			<fieldset>
				<legend align=center>Choose Task </legend>
				<div id="accordion">
					<h3><a href="#">Chiron</a></h3>
					<div>Chiron minimizes the number of nonphysical atomic interactions (clashes) in the given protein structure. Named after the thessalian god of healing, this tool attempts to minimize the clashes in protein structures. Chiron has been benchmarked on high and low resolution crystal structures and homology models. For more information, please see the relevant publication listed below.<br><br>
					<div id="submit" align=right style="padding-left: 220px;">
						<input type="button" id="chiron_submit" class="submitbtn" value="Resolve clashes">
					</div>
					</div>
					<h3><a href="#">Gaia</a></h3>
					<div>Gaia, named after the Greek personification of mother nature, is a tool to estimate the nature and quality of a given protein structure. Gaia compares a given protein structure against high resolution crystal structures for certain parameters including but not limited to unphysical atomic overlaps, unsatisfied hydrogen bonds and packing artifacts and reports the standing of the input structure with respect to high resolution crystal structure. For more information, please see the relevant publication listed below.<br><br> 
						<div id="submit" align=right style="padding-left: 220px;">
							<input type="button" id="gaia_submit" class="submitbtn" value="Validate structure">
							<!--<input type="button" id="gaia_submit" class="submitbtn" value="Maintainance in progress...">-->
						</div>
					</div>
				</div>
			</fieldset>
			</div>

			<div id=status></div>

			<div id="processing"></div>
			<div class=cdiv id="jobstatus">
				<fieldset><legend align=center>Job Report</legend>
					<?php  include('txt/results.htm'); ?>
				</fieldset>
			</div>
		</div>
		<div class="cdiv"></div>
		<div id=dialog  style="font-size: 13px;" title="Job Status Notification">
		</div>

		<!-- --------------------END OF PAGE------------------------ -->
		<br>
		<div id="instruction" style="border-top: 1px dotted #990000; width:100%; padding-top: 10px;">
<!--<li> If you want to submit multiple tasks or have trouble submitting your task, please try 
<a href="submit1.php"> here </a>.-->
			If you are using Chiron for the first time, we recommend that you read the documentation before submitting your job.
		</div>



<!--<form style="display:none">
<input id="hash" type="hidden" value="aa">
<input id="seq" type="hidden" value = "bb">
<input id="error" type="hidden" value = "1">
<?php 
   $userid = $_SESSION['userid'];
   $query = "SELECT username,email,emailConfirmed ".
           " FROM $tableusers WHERE id='$userid' LIMIT 1";
   $result = mysql_query($query);
   $row = mysql_fetch_array($result);
   $emailConfirmed = $row['emailConfirmed'];
?>
<input id="emailConfirmed" type="hidden" value = "<?php  print $emailConfirmed; ?>">
</form>	

		</div>

		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>

	</div>
</div>
<script language="javascript">
var errorfield = document.getElementById("error");
errorfield.value = "1";
</script>-->
</body>
</html>
