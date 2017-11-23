<?php
namespace Dashboard;

use Cache\Cache;

include realpath(__DIR__ . '/../app/inc/init.php');
include "{$root_folder}/app/inc/query_params.php";

// This view is valid only for one locale and one module.

if ($requested_locale == 'all' || $requested_module == 'all') {
    die('This view is available only for one locale and one module.');
}

// Load en-US cache, clean up unwanted strings.
$cache_file = "{$path}en-US/cache_en-US_gecko_strings.php";
if (! file_exists($cache_file)) {
    exit("File {$cache_file} does not exist.");
}
include $cache_file;
$tmx_reference = $tmx;
unset($tmx);

/*
    Store the list of files to analyze in $relevant_files.
    Also store which module is associated to each file for later.
*/
$relevant_files = [];
$file_information = [];
foreach ($list_data as $module => $list) {
    foreach ($list as $file_name) {
        $relevant_files[] = $file_name;
        $file_information[$file_name] = $module;
    }
}
$relevant_files = array_unique($relevant_files);
asort($relevant_files);
asort($file_information);
foreach ($tmx_reference as $entity => $translation) {
    if (! Utils::startsWith($entity, $relevant_files)) {
        unset($tmx_reference[$entity]);
    }
}

$missing_strings = [];
$cache_id = "missing_locale_{$requested_locale}";
if (! $missing_strings = Cache::getKey($cache_id)) {
    // Include locale cache
    $cache_file = "{$path}{$requested_locale}/cache_{$requested_locale}_gecko_strings.php";
    if (! file_exists($cache_file)) {
        exit("File {$cache_file} does not exist.");
    }
    include $cache_file;
    $tmx_locale = $tmx;
    unset($tmx);

    // Store stats for this locale
    foreach ($tmx_reference as $reference_id => $reference_translation) {
        $file_name = explode(':', $reference_id)[0];
        $string_id = explode(':', $reference_id)[1];
        $module = $file_information[$file_name];
        if (! isset($tmx_locale[$reference_id])) {
            if (! isset($missing_strings[$module][$file_name])) {
                $missing_strings[$module][$file_name] = [$string_id];
            } else {
                $missing_strings[$module][$file_name][] = $string_id;
            }
        }
    }
    unset($tmx_locale);
    Cache::setKey($cache_id, $missing_strings);
}

include "{$root_folder}/app/controllers/diff.php";
include "{$root_folder}/app/templates/base.php";
