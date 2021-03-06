<?php
	require('config/config.inc.php');
	if (empty( $_SESSION['username']) ){
		header("Location: login.php");
	}
	echo "<html>";
	include("txt/head.txt");
?>
	<div id="content">
		<?
			echo '<div id="nav">';
			include("txt/menu.php");
			echo '</div>';
		?>
		<div id="main_content">
			<div class="indexTitle">
				Frequently asked questions
			</div>
			<div class="hspacer10"></div>
			<div id="tabs">
				<ul>
					<li><a href="#chiron">Chiron</a></li>
					<li><a href="#gaia">Gaia</a></li>
				</ul>
				<div id="chiron" style="font-size: 0.9em;">
					<div id="chiron-help" style="font-size: 1.2em">
						<h3><a href="#">Scientific</a></h3>
						<div>
							<div id="chiron-stopic" style="font-size: 1.1em">
								<?php  include('txt/documentation/chiron-scientific.htm'); ?>
							</div>
						</div>
						<h3><a href="#">Technical</a></h3>
						<div>
							<div id="chiron-ttopic" style="font-size: 1.1em">
								<?php  include('txt/documentation/chiron-technical.htm'); ?>
							</div>
						</div>
					</div>
				</div>

				<div id="gaia" style="font-size: 0.9em;">
					<div id="gaia-help" style="font-size: 1.2em">
						<h3><a href="#">Scientific</a></h3>
						<div>
							<div id="gaia-stopic" style="font-size: 1.1em">
								<?php  include('txt/documentation/gaia-scientific.htm'); ?>
							</div>
						</div>
						<h3><a href="#">Technical</a></h3>
						<div>
							<div id="gaia-ttopic" style="font-size: 1.1em">
								<?php  include('txt/documentation/gaia-technical.htm'); ?>
							</div>
						</div>
						<h3><a href="#">Output</a></h3>
						<div>
							<div id="gaia-otopic" style="font-size: 1.1em">
								<?php  include('txt/documentation/gaia-output.htm'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
