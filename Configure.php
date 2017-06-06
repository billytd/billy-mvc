<?php
namespace BillyMVC;

class Configure {
    private static $values = [];

    /**
     * Set a configuration value
     * @param  string $key
     * @param  any $new_value
     * @return null
     */
    public static function write($key, $new_value)
    {
        $key_stack = explode('.', $key);

        if (count($key_stack) === 1) {
            self::$values[$key] = $new_value;
        } else if (count($key_stack) > 1) {
            throw new Exception("Setting configuration parameters with keys using dot ('.') depth representation not yet supported.");

            /*
            if (isset(self::$values[$key_stack[0]])) {
                $value = self::$values[$key_stack[0]];
            } else {
                $value = [];
            }

            $i = 1;
            while($i < count($key_stack)) {
                $old_value
                $value[$key_stack[$i]] = ($i == (count($key_stack) - 1)) ? $new_value : $value[$key_stack[$i]];
            }
            */
        }
    }

    public static function read($key)
    {
        if (isset(self::$values[$key])) {
            return self::$values[$key];
        }

        $key_stack = explode('.', $key);

        if (count($key_stack) > 1) {
            if (isset(self::$values[$key_stack[0]])) {
                $value = self::$values[$key_stack[0]];
            }

            $i = 1;
            while ($i < count($key_stack)) {
                if (isset($value[$key_stack[$i]])) {
                    $value = $value[$key_stack[$i]];
                } else {
                    return null;
                }
                ++$i;
            }

            return $value;
        }

        return null;
    }
}
