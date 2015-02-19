<?php

namespace custom_twig;

use general\Tools;
use Silex\Application;

class DueRaffles extends \Twig_Extension
{
    public function getName()
    {
        return "due";
    }

    public function getFilters()
    {
        return array(
            "due" => new \Twig_Filter_Method($this, "due"),
        );
    }

    public function due(Application $app)
    {
        $now = new \DateTime('now');
        $dql = "SELECT r.id, r.raffle_title FROM models\Raffles r WHERE r.view_status = 5 AND r.raffle_status = 1 AND r.end_date = :now";
    
        $dql = $app['orm.em']->createQuery($dql);
        $dql->setParameter("now", $now->format('Y-m-d'));
        $dues = $dql->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $dues;
    }
}

/* End of file */