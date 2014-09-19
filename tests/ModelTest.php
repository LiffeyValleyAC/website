<?php
namespace LVAC\Test\Model;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    public function testCallThrowsAnExceptionWhenCalledWithAnInvalidFunction()
    {
        $model = new \LVAC\Model();
        $model->invalid();
    }

    public function testValidateAtributesReturnsFalseWhenTheAttributeDoesNotValidate()
    {
        $model = new \LVAC\Model();
        $result = $model->validateAttribute('invalid');

        $this->assertFalse($result);
    }
}
