<?php
namespace app\common\utils;

/**
 * 陌生刘：💻
 * Created by PhpStorm.
 * User: StubbornGrass - liu
 * Date: 2019/6/13
 * Time: 15:44
 */
class HHtml
{

    public static function radioList ($name, $selected = null, $items = [], $options = [])
    {
        $html = '';

        if (!$items) {
            return $html;
        }

        foreach ($items as $key => $val) {
            $checked = $key == $selected ? 'checked' : '';
            $html .= "<label class='checkbox-inline' style='padding: 0'><input type='radio' name='{$name}' value='{$key}' {$checked} class='i-checks'> {$val}</label>";
        }
        return $html;
    }

    public static function checkboxList ($name, $selected = null, $items = [], $options = [])
    {
        $html = '';

        if (!$items) {
            return $html;
        }

        // 兼容','
        if (is_string($selected) && strpos($selected, ',') !== false) {
            $selected = explode(',', $selected);
        }

        foreach ($items as $key => $val) {
            $opt = ' ';
            if (!empty($options)) {
                foreach ($options as $k => $v) {
                    if ($k == 'disabled') {
                        $v && $opt .= ' disabled';
                    } else {
                        $opt .= " {$k}='{$v}'  ";
                    }

                }
            }

            $checked = in_array($key, (array)$selected) ? 'checked' : '';
            $html .= "<label class='checkbox-inline' style='padding: 0'><input type='checkbox'  {$opt} name='{$name}' value='{$key}' {$checked} class='i-checks' > {$val}</label>";
        }
        return $html;
    }

    public static function showMoreLabel ($value, $items = [])
    {
        $label = '';

        if (empty($value) || empty($items)) {
            return $label;
        }
        // 兼容','
        if (is_string($value) && strpos($value, ',') !== false) {
            $value = explode(',', $value);
        }

        foreach ((array)$value as $v) {
            $label .= $items[$v] . ',';
        }

        return trim($label, ',');
    }

    public static function assocUnique (&$arr, $key)
    {
        $rAr = [];
        for ($i = 0; $i < count($arr); $i++) {
            if (!isset($rAr[$arr[$i][$key]])) {
                $rAr[$arr[$i][$key]] = $arr[$i];
            }
        }
        $arr = array_values($rAr);
        return $arr;
    }

}