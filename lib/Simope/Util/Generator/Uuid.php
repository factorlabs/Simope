<?php
/**
 * File contains UUID generator class
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
 * Class for generating UUID for entity identyfication
 * @category Persistence
 * @package Simope
 * @subpackage Generator
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 * @see http://stackoverflow.com/a/2040279/1406036
 */
class Uuid implements GeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public static function generate()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), 
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
