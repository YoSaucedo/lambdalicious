<?php

namespace Verraes\Lambdalicious\Tests;

final class examplesTest extends LambdaliciousTestCase
{
    /**
     * @test
     */
    public function examples_01_atoms_and_lists()
    {
        include __DIR__ . '/../../../../examples/01-atoms-and-lists.php';
    }

    /**
     * @test
     */
    public function examples_02__tuples_and_pairs()
    {
        include __DIR__ . '/../../../../examples/02-tuples_and_pairs.php';
    }

    /**
     * @test
     */
    public function examples_03_functions()
    {
        include __DIR__ . '/../../../../examples/03-functions.php';
    }

    /**
     * @test
     */
    public function examples_04_fibonacci()
    {
        include __DIR__ . '/../../../../examples/04-fibonacci.php';
    }

    /**
     * @test
     */
    public function examples_05_pipes_and_filters()
    {
        include __DIR__ . '/../../../../examples/05-pipes-and-filters.php';
    }

    /**
     * @test
     */
    public function examples_06_average()
    {
        include __DIR__ . '/../../../../examples/06-average.php';
    }
}
 