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
            'crop' => new \Twig_Filter_Method($this, 'crop'),
            'intldate' => new \Twig_Filter_Method($this, 'intldate'),
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

    public function crop($str, $len)
    {
        if (strlen($str) <= $len) {
            return $str;
        }

        // find the longest possible match
        $pos = 0;
        foreach (array('. ', '? ', '! ') as $punct) {
            $npos = strpos($str, $punct);
            if ($npos > $pos && $npos < $len) {
                $pos = $npos;
            }
        }

        if (!$pos) {
            // substr $len-3, because the ellipsis adds 3 chars
            return substr($str, 0, $len - 3) . '...';
        }
        else {
            // $pos+1 to grab punctuation mark
            return substr($str, 0, $pos + 1);
        }
    }

}