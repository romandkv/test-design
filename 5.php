<?php

abstract class BaseCalculator {
    public function calculate($val1, $val2) { return ($val1 + $val2) * 2; }
}

trait CalculatorTrait {
    public function calculate($val1, $val2) { return $val1 + $val2; }
}

class Calculator1 extends BaseCalculator {
    use CalculatorTrait;

    public function calculate($val1, $val2) { return ($val1 + $val2) / 2; }
}

class Calculator2 extends BaseCalculator {
    use CalculatorTrait;
}

class Calculator3 extends BaseCalculator { }


$val1 = 3;
$val2 = 7;

$calc1 = new Calculator1();
$calc2 = new Calculator2();
$calc3 = new Calculator3();

echo $calc1->calculate($val1, $val2) * ($calc2->calculate($val1, $val2) + $calc3->calculate($val1, $val2));