<span class="anchor" id="reports"></span>
<div class="block_title"><a href="#reports">Reports</a></div>

<div class="graph_block">
<?php
$graph_args = "env=$env&c=$c";
if (isset($dn)) { $graph_args = "$graph_args&dn=$dn"; }

if (isset($g)) { $graph_reports = array($g); }
else { $graph_reports = find_dashboards($env, $c); }

foreach ($graph_reports as $graph_report) {
    $current_graph_args = $graph_args . "&h=$h";
    print print_zoom_graph($current_graph_args, "g=$graph_report", $z, $from, $until);
}
?>
</div>
<span class="anchor" id="metrics"></span>
<div class="block_title"><a href="#metrics">Metrics</a></div>

<?php
if ( $config["metric_groups_enable"] ) {
	$group_config = json_decode(file_get_contents($conf['metric_group_config']), TRUE);
	
}
$metrics = find_metrics("$env.$c.$h", $conf['host_metric_group_depth']);
foreach ($metrics as $metric_group => $metric_array) {
    print "<span class=\"anchor\" id=\"$metric_group\"></span>";
    print "<a href=\"#$metric_group\"><div class=\"banner_text\">$metric_group</div></a>";
    print "<div class=\"graph_block\">";
    if ( isset( $group_config ) ) {
	foreach ( $group_config["metric_groups"] as $group ) {
    	    if ( preg_match( "/^${group["metric_group"]}\.([^.]*)/", $metric_group, $matches ) ) {
		print print_zoom_graph( $graph_args . 
				"&p1=${group["metric_group"]}&p2=${matches[1]}&h=$h&dn=",
				"g=${group["graph"]}", $z, $from, $until);
	    }
	}
    }

    foreach ($metric_array as $metric) {
        $current_graph_args = $graph_args . "&h=$h&dn=";
        print print_zoom_graph($current_graph_args, "m=$metric", $z, $from, $until);
    }
    print "</div>";
}

?>
