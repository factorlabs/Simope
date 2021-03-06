<?php
/**
 * File contains Generable interface
 * @category Persistence
 * @package Simope
 * @subpackage Generator
 * @author Factorlabs Development <info@factorlabs.pl>
 * @license The MIT License (MIT)
 * @version GIT: <git_id>
 * @link https://github.com/factorlabs/Simope
 */
namespace Simope\Util\Generator;
/**
 * Interface for unique generators
 * @category Persistence
 * @package Simope
 * @subpackage Generator
 * @author Factorlabs Development <info@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 */
interface GeneratorInterface
{
    /**
     * Generates unique identificators
     * @return mixed
     */
    public static function generate();
}
