<?php
atom('cond');
atom('elsedo');
atom('isinstanceof');
atom('add');
atom('subtract');
atom('multiply');
atom('divide');
atom('mod');
atom('lt');
atom('lteq');
atom('gt');
atom('gteq');
atom('noteq');
atom('not');
atom('andx');
atom('orx');
atom('exponent');

function isinstanceof($object, $classname) { return $object instanceof $classname; }
function add($x, $y) { return $x + $y; }
function subtract($a, $b) { return $a -   $b; };
function multiply($x, $y) { return $x * $y; }
function divide ($a, $b) { return $a /   $b; };
function mod($a, $b) { return $a %   $b; };
function exponent($a, $b) { return $a ** $b; };
function lt($a, $b) { return $a <   $b; };
function lteq($a, $b) { return $a <=  $b; };
function gt($a, $b) { return $a >   $b; };
function gteq($a, $b) { return $a >=  $b; };
function noteq($a, $b) { return !isequal($a, $b); };
function not(callable $function) { return function(...$arguments) use($function) { return !call($function, $arguments);}; }
function andx($a, $b) { return $a &&  $b; };
function orx($a, $b) { return $a ||  $b; };

/**
 * Returns the last element of the first pair. The last pair should always be pair(elsedo, expression)
 * @param $conds
 * @throws CondExpectsAtLeastOneCondition
 * @throws CondExpectsPairsAsArguments
 * @throws CondExpectsTheFinalExpressionToBeElsedo
 * @return mixed
 */
function cond(...$conds)
{
    if(count(filter(not(ispair), $conds))) throw new CondExpectsPairsAsArguments;
    if(isempty($conds)) throw new CondExpectsAtLeastOneCondition;

    if(contains1($conds)) {
        if(!isequal(elsedo, car($conds)->first())) throw new CondExpectsTheFinalExpressionToBeElsedo;
        return evaluate(car($conds)->last());
    }

    return car($conds)->first() ? car($conds)->last() : call(cond, cdr($conds));
}
final class CondExpectsTheFinalExpressionToBeElsedo extends \Exception {}
final class CondExpectsPairsAsArguments extends \Exception {}
final class CondExpectsAtLeastOneCondition extends \Exception {}