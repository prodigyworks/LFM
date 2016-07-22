<?php
	require_once("system-header.php");
?>
<div class="basicform">
	<h2>Report Hours - Staff</h2>
	<br>
	<form id="reportform" class="reportform" name="reportform" method="POST" action="reporthoursstafflib.php" target="_new">
		<table>
			<tr>
				<td>
					Year
				</td>
				<td>
					<SELECT id="year" name="year">
	<?php 
						$year = date("Y");
						$from = $year - 5;
						
						while ($year >= $from) {
							echo "<OPTION value='$year'>$year</OPTION>\n";
							$year--;
						}
	?>
					</SELECT>
				</td>
			</tr>
			<tr>
				<td>
					Month
				</td>
				<td>
					<SELECT id="month" name="month">
						<OPTION value="1" <?php if (date("m") == 1) echo "selected"; ?>>January</OPTION>
						<OPTION value="2" <?php if (date("m") == 2) echo "selected"; ?>>February</OPTION>
						<OPTION value="3" <?php if (date("m") == 3) echo "selected"; ?>>March</OPTION>
						<OPTION value="4" <?php if (date("m") == 4) echo "selected"; ?>>April</OPTION>
						<OPTION value="5" <?php if (date("m") == 5) echo "selected"; ?>>May</OPTION>
						<OPTION value="6" <?php if (date("m") == 6) echo "selected"; ?>>June</OPTION>
						<OPTION value="7" <?php if (date("m") == 7) echo "selected"; ?>>July</OPTION>
						<OPTION value="8" <?php if (date("m") == 8) echo "selected"; ?>>August</OPTION>
						<OPTION value="9" <?php if (date("m") == 9) echo "selected"; ?>>September</OPTION>
						<OPTION value="10" <?php if (date("m") == 10) echo "selected"; ?>>October</OPTION>
						<OPTION value="11" <?php if (date("m") == 11) echo "selected"; ?>>November</OPTION>
						<OPTION value="12" <?php if (date("m") == 12) echo "selected"; ?>>December</OPTION>
					</SELECT>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<a class="link1" href="javascript: runreport();"><em><b>Run Report</b></em></a>
				</td>
			</tr>
		</table>
	</form>
</div>

<script>
	function runreport(e) {
		if (! verifyStandardForm("#reportform")) {
			return false;
		}

		$('#reportform').submit();

		try {
			e.preventDefault();

		} catch (e) {

		}
	}
</script>
<?php
	require_once("system-footer.php");
?>
