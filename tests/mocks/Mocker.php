<?php

namespace skyline\tests\mocks;

use yii\base\Component;
use skyline\tests\mocks\Metadata;

/**
 * This class allows the dynamic mocking of an object instance through configuration.
 *
 * Within a configuration file (i.e.: web.ph) you may mock a module by using this
 * class as a base. Pass in this class as the 'class' key to a definition.
 *
 * Example:
 * 'db' => [
 *    'class' => 'skyline\tests\mocks',
 * ]
 *
 * Next provide a key 'origninalClass'. This is the class that is being mocked.
 * Next you provide a 'mocks' key which should be an associative array with
 * keys indicating which methods you wish to mock and what you with to return for each.
 *
 * If you want to return another Mock of a class, simply add an associative array
 * as the value for the key with the keys 'class', 'originalClass', and 'mocks'. The
 * All child Mocks will be created during the creation of the original Mock.
 *
 * If you require that the original object instance populate some parameters / properties
 * you may pass a 'originalClassArgs' key which should have an associative array of
 * original object properties and values to assign to them.
 *
 * Currently the properties must be public to be assigned.
 */
class Mocker extends Component
{
    /**
     * @var $metadata - a Metadata object to store dynamic properties
     */
    private Metadata $metadata;

    /**
     * Executes the provided function against the passed object, or the original
     * object object if an object is not provided.
     *
     * @param string $function - the name of the function
     * @param array $args - an associative array containing arguments to pass to the function
     * @param * [$object] - the object to call the function
     *
     * @returns *
     */
    private function callOriginalObjectFunction(string $function, array $args, ?object $object)
    {
        if (empty($object)) {
            $object = $this->metadata->originalObject;
        }
        return call_user_func_array(array($object, $function), $args);
    }

    private function parseMocks(array $mocks)
    {
        foreach ($mocks as $name => $mock) {
            if (!is_array($mock)) {
                continue;
            }

            if (\array_key_exists('class', $mock)) {
                if ($mock['class'] === __CLASS__) {
                    $newMock = new Mocker();
                    $newMock->originalClass = $mock['originalClass'];
                    $newMock->originalClassArgs = $mock['originalClassArgs'] ?? null;
                    if (\array_key_exists('mocks', $mock)) {
                        $newMock->mocks = $mock['mocks'];
                    }
                    $this->metadata->mocks[$name] = $newMock;
                }
                continue;
            }
        }
    }

    public function __construct()
    {
        $this->metadata = new Metadata();
        return parent::__construct();
    }

    public function __get($name)
    {
        if ($this->metadata->hasAttribute($name)) {
            return $this->metadata->$name;
        }

        try {
            return $this->metadata->originalObject->$name;
        } catch (Exception $exception) {
            return null;
        }
    }

    public function __set($name, $value)
    {
        if ($name === 'mocks') {
            $this->metadata->$name = $value;
            return $this->parseMocks($value);
        }

        if ($name === 'originalClass') {
            $this->metadata->originalObject = new $value();
            return $this->metadata->$name = $value;
        }

        if ($name === 'originalClassArgs') {
            if (!is_array($value)) {
                return;
            }
            if (empty($this->metadata->originalObject)) {
                return;
            }
            $object = $this->metadata->originalObject;

            foreach ($value as $property => $value) {
                $object->$property = $value;
            }
            return $this->metadata->$name = $value;
        }

        if (empty($this->metadata->originalObject)) {
            return;
        }

        return $this->metadata->originalObject->$name = $value;
    }

    public function __call($function, $args)
    {
        if (in_array($function, get_class_methods(__CLASS__))) {
            return $this->callOriginalObjectFunction($function, $args, $this);
        }

        if ($this->metadata->hasAttribute('mocks')) {
            $mocks = $this->metadata->mocks;
            if (!is_array($mocks)) {
                return $mocks;
            }
            if (\array_key_exists($function, $mocks)) {
                return $mocks[$function];
            }
        }
        return $this->callOriginalObjectFunction($function, $args);
    }
}
