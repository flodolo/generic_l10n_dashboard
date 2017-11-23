<?php

$html_detail_body = '';
foreach ($supported_locales as $supported_locale) {
    $data = $results[$supported_locale][$requested_module];
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
		<td><a href=\"?locale={$supported_locale}\">{$supported_locale}</a></td>
		<td>{$data['total']}</td>
		<td>{$data['percentage']}&nbsp;%</td>
		<td>{$data['translated']}</td>
		<td>";

    // Link to Diff view only if there are missing strings
    if ($data['missing'] > 0) {
        $html_detail_body .= "<a href=\"diff.php?locale={$supported_locale}&amp;module={$requested_module}\">{$data['missing']}</a>";
    } else {
        $html_detail_body .= $data['missing'];
    }

    $html_detail_body .= "</td>
		<td>{$data['identical']}</td>
	</tr>
	";
}

$page_title = 'Module View';
$selectors_enabled = true;
$sub_template = 'module.php';
