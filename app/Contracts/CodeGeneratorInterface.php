<?php

namespace App\Services\Contracts;

interface CodeGeneratorInterface
{
    /**
     * Generate code based on the given parameters.
     *
     * @param  array  $parameters
     */
    public function generate(int $length): string;
}
