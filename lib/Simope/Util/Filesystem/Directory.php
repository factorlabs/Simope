<?php
namespace Simope\Util\Filesystem;
class Directory
{
    public function __construct($directory)
    {
        $this->directory = $directory;
    }
    /**
     * @see http://stackoverflow.com/a/3352564/1406036
     */
    public function purge()
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
