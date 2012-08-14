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
		<td><label for="region"></label>
			<select name="region" id="region">
				<option value="a">A Region</option>
				<option value="b">B Region</option>
			</select></td>
	</tr>
	<tr>
		<td>Grape Variety:</td>
		<td><select name="grape_type" id="grape_type">
			<option value="a">Type A</option>
			<option value="b">Type B</option>
		</select></td>
	</tr>
	<tr>
		<td>Year range</td>
		<td><select name="year_low" id="year_low">
			<option value="1986">1986</option>
			<option value="1987">1987</option>
		</select> 
			to 
			<select name="year_high" id="year_high">
				<option value="2012">2012</option>
				<option value="2013">2013</option>
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
		<td><input name="price_min" type="text" id="price_min" size="8" maxlength="8" /> 
			to 
			<input name="price_max" type="text" id="price_max" size="8" maxlength="8" /></td>
	</tr>
</table>
<p>
	<input type="submit" name="search_btn" id="search_btn" value="Search Wines" />
</p>
</form>
