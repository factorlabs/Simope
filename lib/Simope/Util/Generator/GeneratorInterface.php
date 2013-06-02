<?php
/**
 * File contains Generable interface
 * @category Persistence
 * @package Simope
 * @subpackage Generator
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
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
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 */
interface Generable
{
    /**
     * Generates unique identificators
     * @return mixed
     */
    public static function generate();
}
