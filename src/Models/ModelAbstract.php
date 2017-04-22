<?php
namespace Wapweb\KpiScheduleCrawler\Models;


abstract class ModelAbstract
{
    use ModelUtilitiesTrait;

    public const SNAKE_CASE = 1;
    public const CAMEL_CASE = 2;

    /**
     * ModelAbstract constructor.
     * @param array|null $params
     */
    public function __construct(array $params = null)
    {
        if(!empty($params)) {
            $this->unpack($params);
        }
    }

    public function unpack(array $params) : self {
        foreach ($params as $key => $value) {
            $newKey = $this->_fromUnderscoreToCamelCase($key);
            $setterName = 'set'.ucfirst($newKey);
            if(method_exists($this, $setterName)) {
                $this->$setterName($value);
            }
        }

        return $this;
    }

    public function toArray(int $keyCase = self::SNAKE_CASE) : array {
        $data = get_object_vars($this);
        $res = [];

        foreach ($data as $key => $value) {
            $newKey = $keyCase == self::SNAKE_CASE ?
                $this->_fromCamelCaseToUnderscore($key) :
                $this->_fromUnderscoreToCamelCase($key);

            $res[$newKey] = $value;
        }

        return $res;
    }
}