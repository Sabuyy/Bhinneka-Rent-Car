<?php


if (!function_exists('array_group_by')) {
    /**
     * Groups an array by a given key.
     *
     * Groups an array into arrays by a given key, or set of keys, shared between all array members.
     *
     * Based on {@author Jake Zatecky}'s {@link https://github.com/jakezatecky/array_group_by array_group_by()} function.
     * This variant allows $key to be closures.
     *
     * @param array $array   The array to have grouping performed on.
     * @param mixed $key,... The key to group or split by. Can be a _string_,
     *                       an _integer_, a _float_, or a _callable_.
     *
     *                       If the key is a callback, it must return
     *                       a valid key from the array.
     *
     *                       If the key is _NULL_, the iterated element is skipped.
     *
     *                       ```
     *                       string|int callback ( mixed $item )
     *                       ```
     *
     * @return array|null Returns a multidimensional array or `null` if `$key` is invalid.
     */
    function array_group_by(array $array, $key)
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
            return null;
        }

        $func = (!is_string($key) && is_callable($key) ? $key : null);
        $_key = $key;

        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($array as $value) {
            $key = null;

            if (is_callable($func)) {
                $key = call_user_func($func, $value);
            } elseif (is_object($value) && property_exists($value, $_key)) {
                $key = $value->{$_key};
            } elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }

            if ($key === null) {
                continue;
            }

            $grouped[$key][] = $value;
        }

        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();

            foreach ($grouped as $key => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $params);
            }
        }

        return $grouped;
    }
}

if (!function_exists('format_rupiah')) {
    /**
     * Mengubah format angka menjadi rupiah
     * 
     * @param string|int|float $number
     * @param bool|string      $withPrefix
     */
    function format_rupiah($number, $withPrefix = false)
    {
        if ($withPrefix === true) $prefix = 'Rp';
        elseif (is_string($withPrefix)) $prefix = $withPrefix;

        return ($prefix ?? '') . number_format(floatval($number), 0, ',', '.');
    }
}

if (!function_exists('clean_number')) {
    /**
     * Mengubah format rupiah menjadi float
     * 
     * @param string $number
     * @param bool   $swapSeparator true jika pemisah desimal dari number adalah koma
     */
    function clean_number($number, $swapSeparator = false)
    {
        $number = preg_replace('/[^\.\,\d\-]/', '', $number);
        if ($swapSeparator) $number = str_replace([',', '.'], ['.', ','], $number);

        $number = str_replace(',', '', $number);
        $number = rtrim($number, '.');
        $number = ltrim($number, '0');
        if (str_starts_with($number, '.')) {
            $number = '0' . $number;
        }

        if (str_contains($number, '.')) {
            $number = rtrim($number, '0');
            $number = rtrim($number, '.');
        }
        return $number;
    }
}

if (!function_exists('format_number')) {
    function format_number($number, $decimals = 2)
    {
        $format = number_format($number, $decimals, ',', '.');
        if (strpos($format, ',') !== FALSE) {
            $format = rtrim($format, '0');
            $format = rtrim($format, ',');
        }
        return $format;
    }
}

if (!function_exists('generateRandomstring')) {
    function generateRandomstring($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {

        $jadi = number_format($angka, 0, ',', '.');
        return $jadi;
    }
}
