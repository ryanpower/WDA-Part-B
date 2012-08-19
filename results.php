<?php 

	mysql_connect("localhost", "ultim43_public", "powersports") or die(mysql_error()); 
	mysql_select_db("ultim43_winestore") or die(mysql_error()); 


//	wine_name=&winery_name=&region=a&grape_type=a&year_low=1986&year_high=2012&stock_min=&ordered_min=&price_min=&price_max=&search_btn=Search+Wines
	
	$req_fields_a = array('wine_name','winery_name','region','grape_type','year_low','year_high','stock_min','ordered_min','price_min','price_max');

	// Validate
	
	// 1. Check query valid
	foreach ($req_fields_a as $field) {
		if (!isset($_GET[$field])) {
			die("Invalid query. You should not access this page directly.");	
		}
	}
	
	// 2. Check year
	if ($_GET["year_low"] > $_GET["year_high"]) {
		die("Year range {$_GET["year_low"]} to {$_GET["year_high"]} is invalid");	
	}
	// 3. Check price
	if ($_GET["price_min"] > $_GET["price_max"] and $_GET["price_max"] > 0) {
		die("Price range {$_GET["price_min"]} to {$_GET["price_max"]} is invalid");	
	}
	
	$wine_name = $_GET["wine_name"];
	$winery_name = $_GET["winery_name"];
	$year_low = $_GET["year_low"];
	$year_high = $_GET["year_high"];
	$region_id = $_GET["region"];
	$region_cond = '';
	if ($region_id > 1) {
		$region_cond = "AND r.region_id = '$region_id'";
	}
	
	$variety_id = $_GET["grape_type"];
	$variety_cond = '';
	if ($variety_id != 'ALL') {
		$variety_cond = "AND wv.variety_id = '$variety_id'";
	}
	
	$min_stock = $_GET["stock_min"];
	$min_stock_cond = '';
	if ($min_stock > 0) {
		$min_stock_cond = "AND stock_num >= '$min_stock'";
	}
	$min_ordered = $_GET["ordered_min"];
	$min_ordered_cond = '';
	if ($min_ordered > 0) {
		$min_ordered_cond = "AND sales_num >= '$min_ordered'";
	}
	
	$min_price = $_GET["price_min"];
	$min_price_cond = '';
	if ($min_price > 0) {
		$min_price_cond = "AND stock_price >= '$min_price'";
	}

	$max_price = $_GET["price_max"];
	$max_price_cond = '';
	if ($max_price > 0) {
		$max_price_cond = "AND stock_price <= '$max_price'";
	}
	
	$q = "
		SELECT w.wine_id, wine_name, year, winery_name, region_name, grape_varieties, stock_num, stock_price, sales_num, sales_value
		FROM (
			wine w, winery wr, region r, wine_variety wv)
		LEFT JOIN (
			SELECT wine_id, SUM( qty ) AS sales_num, SUM( price ) AS sales_value
			FROM items
			GROUP BY wine_id
		) s ON w.wine_id = s.wine_id
		LEFT JOIN (
			SELECT wine_id, SUM( on_hand ) AS stock_num, AVG( cost ) AS stock_price
			FROM inventory
			GROUP BY wine_id
		) i ON w.wine_id = i.wine_id
		LEFT JOIN (
			SELECT wine_id, GROUP_CONCAT( variety ) AS grape_varieties
			FROM  `wine_variety` wva, grape_variety gva
			WHERE wva.variety_id = gva.variety_id
			GROUP BY wine_id
		) gv ON w.wine_id = gv.wine_id
			
		WHERE w.winery_id = wr.winery_id
		AND r.region_id = wr.region_id
		AND w.wine_id = wv.wine_id
		
		AND w.wine_name LIKE  '%$wine_name%'
		AND wr.winery_name LIKE  '%$winery_name%'
		AND w.year >= '$year_low' 
		AND w.year <= '$year_high'
		$region_cond
		$variety_cond
		
		GROUP BY w.wine_id, YEAR, winery_name, region_name
		
		HAVING 1
		$min_stock_cond
		$min_ordered_cond
		$min_price_cond
		$max_price_cond
		
		ORDER BY wine_name ASC, year ASC
		
		LIMIT 50 
	";

//	print $q;
	$results_q = mysql_query($q);
	$num_results = mysql_num_rows($results_q);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Wine Search - Results</title>
</head>

<body>
<h1>Wine Search Results
</h1>
<?php
	if ($num_results == 0) {
		print "No records match your search criteria. Please refine your search and try again.";
	} else {
?>
<table width="800" border="1" cellspacing="0" cellpadding="2">
	<tr>
		<th align="left" scope="col">Wine Name</th>
		<th align="left" scope="col">Year</th>
		<th align="left" scope="col">Grape Varieties</th>
		<th align="left" scope="col">Winery</th>
		<th align="left" scope="col">Region</th>	
		<th scope="col">Cost</th>
		<th scope="col">Bottles</th>
		<th scope="col">Sales</th>
		<th scope="col">Revenue</th>
	</tr>
	
<?php 
	for ($i=0; $i<$num_results; $i++) {
		$a = mysql_fetch_assoc($results_q);
?>	
	<tr>
		<td><?php print "$a[wine_name]"; ?></td>
		<td><?php print "$a[year]"; ?></td>
		<td><?php print "$a[grape_varieties]"; ?></td>
		<td><?php print "$a[winery_name]"; ?></td>
		<td><?php print "$a[region_name]"; ?></td>
		<td><?php print "$".number_format($a["stock_price"],2); ?></td>
		<td><?php print number_format($a["stock_num"]); ?></td>
		<td><?php print number_format($a["sales_num"]); ?></td>
		<td><?php print "$".number_format($a["sales_value"],2); ?></td>
	</tr>
<?php
}
?>
</table>
<?php
	}
?>

</body>
</html>
