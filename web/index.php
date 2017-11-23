<?php
namespace Dashboard;

use Cache\Cache;

include realpath(__DIR__ . '/../app/inc/init.php');
include "{$root_folder}/app/inc/query_params.php";

// Load en-US cache, only keep relevant strings
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

$identical_exclusions = [
    '.key',
    '.accesskey',
    '.commandkey',
];

$controller = $requested_module != 'all'
    ? 'module'
    : 'locale';

/*
    If I'm only checking one locale, create results for this locale to reduce
    the time needed to generate the page.
*/
$locales_list = $requested_module != 'all'
    ? $supported_locales
    : [$requested_locale];

$results = [];
foreach ($locales_list as $supported_locale) {
    $cache_id = "results_locale_{$supported_locale}";
    if (! $results[$supported_locale] = Cache::getKey($cache_id)) {
        // Include locale cache
        $cache_file = "{$path}{$supported_locale}/cache_{$supported_locale}_gecko_strings.php";
        if (! file_exists($cache_file)) {
            exit("File {$cache_file} does not exist.");
        }
        include $cache_file;
        $tmx_locale = $tmx;
        unset($tmx);

        // Store stats for this locale
        foreach ($tmx_reference as $reference_id => $reference_translation) {
            $file_name = explode(':', $reference_id)[0];
            $module = $file_information[$file_name];
            if (! isset($results[$supported_locale][$module])) {
                $results[$supported_locale][$module] = [
                    'translated' => 0,
                    'missing'    => 0,
                    'total'      => 0,
                    'identical'  => 0,
                    'percentage' => 0,
                ];
            }

            // Add to total strings
            $results[$supported_locale][$module]['total'] += 1;

            if (! isset($tmx_locale[$reference_id])) {
                $results[$supported_locale][$module]['missing'] += 1;
            } else {
                $results[$supported_locale][$module]['translated'] += 1;
                if ($tmx_locale[$reference_id] == $reference_translation && ! Utils::inString($reference_id, $identical_exclusions)) {
                    $results[$supported_locale][$module]['identical'] += 1;
                }
            }
        }
        unset($tmx_locale);
        Cache::setKey($cache_id, $results[$supported_locale]);
    }
}

include "{$root_folder}/app/controllers/{$controller}.php";
include "{$root_folder}/app/templates/base.php";
