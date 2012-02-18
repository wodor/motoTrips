<?php

namespace WodorNet\MotoTripBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;


class MotoTripExtension extends \Twig_Extension
{

    public function getName()
    {
        return 'mototrip';
    }

    public function getFilters()
    {
        return array(
            'intldate' => new \Twig_Filter_Method($this, 'intldate'),
            'intlinterval' => new \Twig_Filter_Method($this, 'intlinterval'),
        );
    }


    public function intldate(\DateTime $str, $format = "eeee d MMMM yyyy hh:mm")
    {

        $formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::SHORT,
            null,
            null,
            $format
        );

        return $formatter->format($str);
    }

    public function intlinterval(\DateInterval $str) {
        if(  $str->format("%a") != "0"){
            return $str->format("%a")." d";
        }
        else {
            return $str->format("%h")." h";
        }
    }

}