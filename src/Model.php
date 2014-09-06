<?php
namespace LVAC;

class Model
{
    protected $id;

    public function setId($id)
    {
        if (!$this->id) {
            $this->id = $id;
        }
    }

    public function __call($name, $args)
    {
        if (preg_match('/^(get|set)(\w+)/', $name, $match)) {
            $attribute = $this->validateAttribute($match[2]);

            if ($match[1] == 'get') {
                return $this->{$attribute};
            } else {
                $this->{$attribute} = $args[0];
            }
        } else {
            throw new \Exception(
                'Call to undefined ' . 
                __CLASS__ . 
                '::' . 
                $name . 
                '()'
            ); 
        }
    }

    public function validateAttribute($name)
    {
        $name[0] = strtolower($name[0]);

        // We don't use __CLASS__ here because there are scope problems
        $currentClass = get_class($this);

        if (in_array($name, array_keys(get_class_vars($currentClass)))) {
            return $name; 
        }

        return false;
    }
}
