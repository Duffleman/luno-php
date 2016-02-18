<?php

if (!function_exists('array_dot')) {
    /**
     * Flatten a multi-dimensional associative array with dots.
     * Written by Taylor Otwell, and shamefully stolen from Illuminate\Support.
     * https://github.com/illuminate/support/blob/master/Arr.php#L79
     *
     * @param array  $array
     * @param string $prepend
     * @return array
     */
    function array_dot(array $array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, array_dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }
}
