<?php
/**
 * Created by PhpStorm.
 * User: Neal
 * Date: 1/5/2017
 * Time: 2:00 AM
 */

namespace Nealyip\StringMath;

class StringMath
{
    const PATTERN = '/(?:\-?\d+(?:\.?\d+)?[\+\-\*\/])+\-?\d+(?:\.?\d+)?/';

    const ACCEPTCHARS = '#^[\d\-\+*/\.\(\)]+$#';

    const PARENTHESIS_DEPTH = 10;

    /**
     * @param string $input
     *
     * @return int|mixed
     * @throws StringMathException
     */
    public function calculate($input)
    {
        if (!preg_match(self::ACCEPTCHARS, $input, $match)){
            throw new StringMathException($input);
        }
        if (strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null) {
            //  Remove white spaces and invalid math chars
            $input = str_replace(',', '.', $input);
            $input = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $input);

            //  Calculate each of the parenthesis from the top
            $i = 0;
            while (strpos($input, '(') || strpos($input, ')')) {
                $input = preg_replace_callback('/\(([^\(\)]+)\)/', 'self::callback', $input);

                $i++;
                if ($i > self::PARENTHESIS_DEPTH) {
                    break;
                }
            }

            //  Calculate the result
            if (preg_match(self::PATTERN, $input, $match)) {
                return $this->_compute($match[0]);
            }

            throw new StringMathException($input);
        }

        return $input;
    }

    /**
     * @param string $input
     *
     * @return int
     */
    private function _compute($input)
    {
        $compute = create_function('', 'return ' . $input . ';');

        return 0 + $compute();
    }

    /**
     * @param string $input
     *
     * @return int|string
     */
    private function callback($input)
    {
        if (is_numeric($input[1])) {
            return $input[1];
        } elseif (preg_match(self::PATTERN, $input[1], $match)) {
            return $this->_compute($match[0]);
        }

        return 0;
    }
}