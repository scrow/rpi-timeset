<?php
/*
#       This file is part of rpi-timeset
#
#       Copyright (c) 2017 Steve Crow
#       Licensed under the BSD 2-clause “Simplified” License
#
#       For license information, see the LICENSE.md file or visit
#       http://github.com/scrow/rpi-timeset
*/

if (isset($_GET['time_stamp']) && (trim($_GET['time_stamp'])!=="")) {
	// Turn $_GET['time_string'] into a valid Unix timestamp
	$new_time_string = date('mdHiY.s', ($_GET['time_stamp'] / 1000));
	$original_time = time();
	$result = shell_exec('sudo /bin/date ' . $new_time_string);
	$new_time = time(); ?>
	<HTML>
		<HEAD>
			<TITLE>rpi-timeset: complete</TITLE>
		</HEAD>
		<BODY>
			<H1>Request complete</H1>

			<P>Original system time: <?php echo(date('r', $original_time));?>
			<BR/>Requested system time: <?php echo(date('r', ($_GET['time_stamp'] / 1000)));?>
			<BR/>New system time: <?php echo(date('r', $new_time));?></P>

			<?php
			if(date('r', ($_GET['time_stamp'] / 1000)) == date('r', $new_time)) {
				echo ('<P>Result: Success</P>');
			} else {
				echo ('<P>Result: Fail</P>');
			};

			echo('<P><A HREF="'.basename(__FILE__).'">Run again</A></P>');

			if(file_exists('postrun.sh')) {
				$full_file_path=realpath('postrun.sh');
				shell_exec($full_file_path);
			};
			?>
		</BODY>
	</HTML>
<?php
} else {
	?>
	<HTML>
		<HEAD>
			<TITLE>rpi-timeset: working</TITLE>
			<SCRIPT>
				function send_system_time() {
					var ts = document.getElementById('time_stamp');
					var d = new Date();
					ts.value = d.getTime();

					document.getElementById('time_form').submit();
				}
			</SCRIPT>
		</HEAD>
	<BODY onLoad="send_system_time()">
		<FORM NAME="time_form" ID="time_form" ACTION="<?php echo(basename(__FILE__));?>" METHOD="GET">
			<INPUT TYPE="HIDDEN" ID="time_stamp" NAME="time_stamp" VALUE=""/>
		</FORM>
	</BODY>
	</HTML>
	<?php
};

?>
