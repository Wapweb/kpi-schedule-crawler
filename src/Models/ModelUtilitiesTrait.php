<?php
namespace Wapweb\KpiScheduleCrawler\Models;

/**
 * Class ModelUtilitiesTrait
 * @package Wapweb\KpiScheduleCrawler\Models
 */
trait ModelUtilitiesTrait
{
    protected function _fromCamelCaseToUnderscore(string $input): string
    {
        $input = $this->_cleanKey($input);

        if (strpos($input, '_') !== false) {
            return $input;
        }

        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    protected function _fromUnderscoreToCamelCase(string $input): string
    {
        $input = $this->_cleanKey($input);

        if (strpos($input, '_') === false) {
            return $input;
        }

        return lcfirst(implode('', array_map('ucfirst', explode('_', $input))));
    }

    protected function _cleanKey(string $key): string
    {
        return trim($key, '_');
    }
}