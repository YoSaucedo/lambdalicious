<?php
atom(@partial);
atom(@compose);
atom(@pipe);
atom(@memoize);
atom(@__); // Argument placeholder for partials

/**
 * The compose function returns a closure that calls each of the functions from last to first, passing on the result of
 * each function call to the next.
 * @param $functions
 * @return callable
 */
function compose(...$functions)
{
    return reduce(
        function (callable $carry, $function) {
            return function ($argument) use ($carry, $function) {
                return $carry($function($argument));
            };
        },
        $functions,
        identity
    );
}

/**
 * Pipe is like compose, but the first function is applied first. Think Unix pipes.
 *
 * @param $functions
 * @return callable
 */
function pipe(...$functions)
{
    return call(compose, reverse($functions));
}

/**
 * Fixes the $arguments to $function, producing another function with the leftover arguments.
 *
 * @param $function
 * @param $partialArgs
 * @return callable
 *
 */
function partial($function, ...$partialArgs)
{
    $replacePlaceholders = function (array $partialArgs, array $finalArgs, array $carry = []) use (&$replacePlaceholders)
    {
        if (isempty($partialArgs)) {
            return concat(reverse($carry), $finalArgs);
        } elseif (head($partialArgs) === __) {
            return $replacePlaceholders(tail($partialArgs), tail($finalArgs), cons(head($finalArgs), $carry));
        } else {
            return $replacePlaceholders(tail($partialArgs), $finalArgs, cons(head($partialArgs), $carry));
        }
    };

    return function (...$finalArgs) use ($function, $partialArgs, $replacePlaceholders) {
        return call_user_func_array($function, $replacePlaceholders($partialArgs, $finalArgs));
    };
}

/**
 * True if $list has placeholder aka __ arguments
 * @param array $list
 * @return mixed
 */
function hasplaceholders(array $list)
{
    return
        isempty($list) ? false :
        (head($list) === __ ? true :
        hasplaceholders(tail($list)));
}

/**
 * Stores the results of expensive function calls and returns the cached result when the same function is called with
 * the same inputs again.
 *
 * @param callable $function
 * @return callable
 */
function memoize(callable $function)
{
    // We can't get a unique id for a closure so we have to do it using SplObjectStorage.
    // spl_object_hash() recycles used ids when objects are trashed.
    static $cache;
    if(!isset($cache)) $cache = new SplObjectStorage;
    if(!$cache->contains($function)) {
        $cache->attach($function, new ArrayObject());
    }

    return function(...$arguments) use($cache, $function) {
        $key = serialize($arguments);
        if(!array_key_exists($key, $cache[$function])) {
            $cache[$function][$key] = call($function, $arguments);
        }
        return $cache[$function][$key];
    };
}
