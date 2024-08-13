<?php

namespace AndreInocenti\PhpSocialScrapper\Handler\Traits;

trait GetNumberFromText
{
    function getNumberFromText($sufix, $text)
    {
        if (preg_match('#([\d,]+)\s+' . $sufix . '#', $text, $matches)) {
            return (int) preg_replace('#,#', '', $matches[1]);
        }
        if (preg_match('#([\d\.]+)([MK])\s+' . $sufix . '#', $text, $matches)) {
            $value = (int) preg_replace('#,#', '', $matches[1]);
            switch ($matches[2]) {
                case 'M':
                    return $value * 1000000;
                case 'K':
                    return $value * 1000;
            }
            return $value;
        }
        return null;
    }
}