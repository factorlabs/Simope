<?php
/**
 * File contains ContainerFactory class
 * @category Persistence
 * @package Simope
 * @subpackage ContainerFactory
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @version GIT: <git_id>
 * @link https://github.com/factorlabs/Simope
 */
namespace Simope;
/**
 * Class making library independent from DIC
 * @category Persistence
 * @package Simope
 * @subpackage ContainerFactory
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 */
class ContainerFactory
{
    /**
     * Creating DIC object
     * @param Config $config configuration object 
     * @return null|object
     */
    public function create(Config $config)
    {
        $containerClass = $config->get('container_class');
        if (!class_exists($containerClass, false)) {
            throw new Exception\ContainerFactoryException(
                'Could not create instance of Container class'
            );
        }
        return new $containerClass();
    }
}
