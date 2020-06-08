<?php

namespace LQL;

/**
 * Class ColumnTranslator
 *
 */
class ColumnTranslator
{
    /**
     * Translate query column names to mklivestatus
     *
     * @return string Translated query
     */
    public function translateIn($str, array $aliases)
    {
        return preg_replace(
            array_map(function ($word) {
                return "/\b$word\b/";
            }, array_keys($aliases)),
            array_values($aliases),
            $str
        );
    }

    /**
     * Translate query column names from mklivestatus to user alias
     *
     * @return string Translated query
     */
    public function translateOut($str, array $aliases)
    {
        return str_replace(array_values($aliases), array_keys($aliases), $str);
    }

    /**
     * Translate custom variable names and extract it to be used after
     *
     * @return array {
     *      @var string Translated Query
     *      @var string[] Custom Variable Names
     * }
     */
    public function translateCustomVariables($str)
    {
        $lines = explode("\n", $str);

        list($index, $line) = $this->findColumnLine($lines);

        $pattern = '([A-Z]{2,}[_0-9]?)+';
        if (! preg_match_all("/(?<custom_vars>$pattern)/", $line, $matchs)) {
            return array($str, array());
        }

        $lines[$index] = preg_replace(
            array("/$pattern/", '/(Columns: .*)/'),
            array('', '${1} custom_variables'),
            $line
        );

        return array(implode("\n", $lines), $matchs['custom_vars']);
    }

    private function findColumnLine($lines)
    {
        $pattern = '/^Columns:/';
        foreach (preg_grep($pattern, $lines) as $index => $line) {
            break;
        }

        return array($index, $line);
    }
}
