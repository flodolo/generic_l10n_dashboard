<?php

$html_detail_body = '';
foreach ($missing_strings[$requested_module] as $file_name => $missings_in_file) {
    $id = str_replace(['/', '.'], '_', strtolower(basename($file_name)));
    $html_detail_body .= '
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#' . $id . '"> ' . $file_name . '</a>
                    <span class="badge">' . count($missings_in_file) . '</span>
                </h4>
            </div>
            <div id="' . $id . '" class="panel-collapse collapse in">
                <ul class="list-group">
    ';

    foreach ($missings_in_file as $string_id) {
        $html_detail_body .= '<li class="list-group-item">' . $string_id . "</li>\n";
    }

    $html_detail_body .= '
                </ul>
            </div>
        </div>
    </div>
    ';
}

$page_title = 'Diff View';
$selectors_enabled = false;
$sub_template = 'diff.php';
