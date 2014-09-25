<?php
atom(@arity);
atom(@call);
atom(@reverseargs);
atom(@evaluate);

/**
 * From http://jasonframe.co.uk/logfile/2009/05/finding-the-arity-of-a-closure-in-php-53/
 * @param $function
 * @return int
 */
function arity($function)
{
    $m = (new ReflectionObject($function))->getMethod('__invoke');
    return $m->getNumberOfParameters();
}

/**
 * Call a $function using $arguments
 *
 * @partial
 * @param callable $function
 * @param array $arguments
 * @return mixed|callable
 */
function call($function, $arguments)
{
    return
        hasplaceholders(func_get_args()) ? call_user_func_array(partial, cons(call, func_get_args())) :
        call_user_func_array($function, $arguments);
}

/**
 * Reverse the arguments of $function
 *
 * @param callable $function
 * @return callable
 */
function reverseargs($function)
{
    return function(...$arguments) use($function) {
        return call($function, reverse($arguments));
    };
}

/**
 * @param $function
 * @return bool
 */
function isfunction($function)
{
    return is_callable($function);
}

/**
 * Return the value of $x
 *
 * @param $x
 * @return mixed
 */
function evaluate($x)
{
    if(isfunction($x)) return $x(); else return $x;
}