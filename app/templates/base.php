<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset=utf-8>
	<title>L10N Dashboard - <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="assets/npm/css/bootstrap.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="assets/npm/css/bootstrap-theme.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="assets/npm/css/dataTables.bootstrap.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="assets/css/main.css" type="text/css" media="all" />
    <script src="assets/npm/js/jquery.min.js"></script>
	<script src="assets/npm/js/bootstrap.min.js"></script>
    <script src="assets/npm/js/jquery.dataTables.min.js"></script>
    <script src="assets/npm/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</head>
<body>
  <div class="container">
	<?php
        if ($selectors_enabled):
    ?>
	<h1>Generic L10N Dashboard</h1>
	<p>See the <a href="https://github.com/flodolo/generic_l10n_dashboard/">GitHub repository</a> for background information.</p>
    <h2>Locale: <?php echo $requested_locale; ?></h2>
	<div class="list locale_list">
      <p>
        Display localization status for a specific locale<br/>
        <?php echo $html_supported_locales; ?>
      </p>
    </div>
	<h2>Module: <?php echo $requested_module; ?></h2>
	<div class="list module_list">
      <p>
        Display localization status for a specific module<br/>
        <?php echo $html_supported_modules; ?>
      </p>
    </div>
	<?php
        endif;
    ?>

	<?php include "{$root_folder}/app/templates/{$sub_template}"; ?>

  </div>
</body>
