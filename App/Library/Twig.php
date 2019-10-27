<?php

declare(strict_types=1);

namespace App\Library;

/**
 * Class Twig
 *
 * @package App\Library
 */
final class Twig extends \Twig_Environment
{

    /**
     * example root page
     *
     * @param string $name
     * @param array $context
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($name, array $context = array()): string
    {
        if (\strpos($name, '.twig') === false) {
            $name .= '.twig';
        }

        return parent::render($name, $context);
    }
}
