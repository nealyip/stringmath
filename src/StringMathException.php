<?php
/**
 * Created by PhpStorm.
 * User: Neal
 * Date: 1/5/2017
 * Time: 2:17 AM
 */

namespace Nealyip\StringMath;


class StringMathException extends \Exception
{

    /**
     * StringMathException constructor.
     *
     * @param string $formula
     */
    public function __construct($formula)
    {
        parent::__construct(sprintf('formula %s is wrong', $formula));
    }
}