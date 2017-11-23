<?php

use Cache\Cache;

$html_detail_body = '';
foreach ($results[$requested_locale] as $module_name => $data) {
    $data['percentage'] = $data['total'] != 0
        ? round($data['translated'] / $data['total'] * 100, 0)
        : 0;
    if ($data['percentage'] == 100) {
        $class = 'success';
    } elseif ($data['percentage'] > 50) {
        $class = 'warning';
    } else {
        $class = 'danger';
    }
    $html_detail_body .= "
	<tr class=\"{$class}\">
		<td>{$module_name}</td>
		<td>{$data['total']}</td>
		<td>{$data['percentage']}&nbsp;%</td>
		<td>{$data['translated']}</td>
        <td>";

    // Link to Diff view only if there are missing strings
    if ($data['missing'] > 0) {
        $html_detail_body .= "<a href=\"diff.php?locale={$requested_locale}&amp;module={$module_name}\">{$data['missing']}</a>";
    } else {
        $html_detail_body .= $data['missing'];
    }

    $html_detail_body .= "</td>
        <td>{$data['identical']}</td>
	</tr>
	";
}

$page_title = 'Locale View';
$selectors_enabled = true;
$sub_template = 'locale.php';
