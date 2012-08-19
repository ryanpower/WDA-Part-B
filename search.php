<?php 

	mysql_connect("localhost", "ultim43_public", "powersports") or die(mysql_error()); 
	mysql_select_db("ultim43_winestore") or die(mysql_error()); 

?>
<h1>Wine Search
</h1>
<form id="form1" name="form1" method="get" action="results.php">
<table width="600" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td>Wine name:</td>
		<td>
			<input type="text" name="wine_name" id="wine_name" />
		</td>
	</tr>
	<tr>
		<td>Winery:</td>
		<td><input type="text" name="winery_name" id="winery_name" /></td>
	</tr>
	<tr>
		<td>Region:</td>
		<td>
			<select name="region" id="region">
<?php
	$region_q = mysql_query("SELECT * FROM region");
	while ($a = mysql_fetch_assoc($region_q)) {
		print '<option value="'.$a["region_id"].'">'.$a["region_name"].'</option>';
	}
?>
			</select></td>
	</tr>
	<tr>
		<td>Grape Variety:</td>
		<td><select name="grape_type" id="grape_type">
			<option value="ALL">All</option>
<?php
	$grape_q = mysql_query("SELECT * FROM grape_variety ORDER BY variety ASC");
	while ($a = mysql_fetch_assoc($grape_q)) {
		print '<option value="'.$a["variety_id"].'">'.$a["variety"].'</option>';
	}
?>
		</select></td>
	</tr>
	<tr>
		<td>Year range</td>
		<td><select name="year_low" id="year_low">
<?php
$year_q = mysql_query("SELECT min(year) as min, max(year) as max FROM wine");
$year_a = mysql_fetch_assoc($year_q);
$min_year = $year_a["min"];
$max_year = $year_a["max"];

for ($year = $min_year; $year <= $max_year; $year++) {
if ($year == $min_year) {
$selected = 'selected="selected" ';
} else {
$selected = '';
}
print '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
}
?>
		</select> 
			to 
			<select name="year_high" id="year_high">
<?php
for ($year = $min_year; $year <= $max_year; $year++) {
if ($year == $max_year) {
$selected = 'selected="selected" ';
} else {
$selected = '';
}
print '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
}
?>
			</select></td>
	</tr>
	<tr>
		<td>Min Stock</td>
		<td><input name="stock_min" type="text" id="stock_min" size="4" maxlength="4" /></td>
	</tr>
	<tr>
		<td>Min Ordered</td>
		<td><input name="ordered_min" type="text" id="ordered_min" size="4" maxlength="4" /></td>
	</tr>
	<tr>
		<td>Dollar range</td>
		<td>$<input name="price_min" type="text" id="price_min" size="8" maxlength="8" /> 
			to 
			$<input name="price_max" type="text" id="price_max" size="8" maxlength="8" /></td>
	</tr>
</table>
<p>
	<input type="submit" name="search_btn" id="search_btn" value="Search Wines" />
</p>
</form>