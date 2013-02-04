<?php
namespace Simope\Util\Generator;
class Uuid
{
    public static function generate()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),

            mt_rand( 0, 0x0fff ) | 0x4000,

            mt_rand( 0, 0x3fff ) | 0x8000,

            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}
