<?php
/**
 * File contains Directory class
 * @category Persistence
 * @package Simope
 * @subpackage Filesystem
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @version GIT: <git_id>
 * @link https://github.com/factorlabs/Simope
 */
namespace Simope\Util\Filesystem;
/**
 * Class handles basic directory functionalities
 * @category Persistence
 * @package Simope
 * @subpackage Filesystem
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 */
class Directory
{
    /**
     * Constructor sets internal directory
     * @todo Maby delegation of some Spl will be better...
     * @param string $directory directory name 
     * @return null
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }
    /**
     * Clears directory
     * @see http://stackoverflow.com/a/3352564/1406036
     */
    public function clear()
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $this->directory,
                \RecursiveDirectoryIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }  
    }
}
