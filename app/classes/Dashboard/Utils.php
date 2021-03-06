<?php
namespace Dashboard;

/**
 * Utils class
 *
 * Utility functions like string management.
 *
 *
 * @package Dashboard
 */
class Utils
{
    /**
     * Check if $haystack starts with a string in $needles.
     * $needles can be a string or an array of strings.
     *
     * @param string $haystack String to analyse
     * @param array  $needles  The strings to look for
     *
     * @return boolean True       if the $haystack string starts with a
     *                 string in $needles
     */
    public static function startsWith($haystack, $needles)
    {
        // Lang file common case: reference string
        if ($needles === ';') {
            return $haystack[0] == ';';
        }

        // Lang file common case: comment
        if ($needles === '#') {
            return $haystack[0] == '#';
        }

        foreach ((array) $needles as $needle) {
            if (mb_strpos($haystack, $needle, 0) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if $haystack contain any of $needles.
     * $needles can be a string or an array of strings.
     *
     * @param string $haystack String to analyse
     * @param array  $needles  The strings to look for
     *
     * @return boolean True  if the $haystack string contain any of $needle
     */
    public static function inString($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (mb_strpos($haystack, $needle, $offset = 0, 'UTF-8') !== false) {
                return true;
            }
        }
    }

    /**
     * Try to get the best supported locale from HTTP_ACCEPT_LANGUAGE header
     *
     * @param array  $available_locales Available locales
     * @param string $fallback_locale   Fallback locale
     * @param string $header            HTTP_ACCEPT_LANGUAGE header
     *
     * @return string Best supported locale code
     */
    public static function detectLocale($available_locales = [], $fallback_locale = 'en-US', $header = '')
    {
        $accept_languages = [];
        if ($header == '') {
            // Read the header from the server, if available
            $header = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        }
        // Source: http://www.thefutureoftheweb.com/blog/use-accept-language-header
        if ($header != '') {
            // Break up string into pieces (languages and q factors)
            preg_match_all(
                '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
                $header,
                $lang_parse
            );
            if (count($lang_parse[1])) {
                // Create a list like "en" => 0.8
                $accept_languages = array_combine($lang_parse[1], $lang_parse[4]);
                // Set default to 1 for any without q factor
                foreach ($accept_languages as $accept_locale => $val) {
                    if ($val === '') {
                        $accept_languages[$accept_locale] = 1;
                    }
                }
                // Sort list based on value
                arsort($accept_languages, SORT_NUMERIC);
            }
        }

        // Check if any of the locales is available
        $intersection = array_values(array_intersect(array_keys($accept_languages), $available_locales));
        if (! isset($intersection[0])) {
            // Accept-Language doesn't include any supported locale
            return $fallback_locale;
        } else {
            return $intersection[0];
        }
    }
}
