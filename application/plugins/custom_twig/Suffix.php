<?php

namespace custom_twig;

class Suffix extends \Twig_Extension
{
    public function getName()
    {
        return "suffix";
    }

    public function getFilters()
    {
        return array(
            "suffix" => new \Twig_Filter_Method($this, "suffix"),
        );
    }

    public function suffix($i)
    {
        $j = $i % 10;
        $k = $i % 100;
        if ($j == 1 && $k != 11) {
            return $i . "st";
        }
        if ($j == 2 && $k != 12) {
            return $i . "nd";
        }
        if ($j == 3 && $k != 13) {
            return $i . "rd";
        }

        return $i . "th";
    }
}

/* End of file */