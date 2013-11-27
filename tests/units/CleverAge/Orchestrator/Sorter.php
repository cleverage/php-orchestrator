<?php

namespace CleverAge\Orchestrator\tests\units;

use CleverAge\Orchestrator\Sorter as BaseSorter;
use CleverAge\Orchestrator\Feature;
use CleverAge\Orchestrator\Ticketing\Model\Ticket;

class Sorter extends \atoum
{
    public function testSortByBlocking()
    {
        $blockingStatus = array('s');

        $features = array();

        /**
         * 8 blocking 3 and 5
         * 5 blocking 3 and 4
         */
        $t1_3 = new Ticket();
        $t1_3->setId('1_3')->setStatus('s');
        $t1_4 = new Ticket();
        $t1_4->setId('1_4')->setStatus('s');
        $t1_5 = new Ticket();
        $t1_5->setId('1_5')->setStatus('s');
        $t1_8 = new Ticket();
        $t1_8->setId('1_8')->setStatus('a');

        $t1_5->setBlocking(array($t1_3, $t1_4));
        $t1_8->setBlocking(array($t1_3, $t1_5));
        $features[] = $feature_1_5 = new Feature($t1_5);
        $features[] = $feature_1_3 = new Feature($t1_3);

        /**
         * 3 blocking 5
         */
        $t2_3 = new Ticket();
        $t2_3->setId('2_3')->setStatus('s');
        $t2_5 = new Ticket();
        $t2_5->setId('2_5')->setStatus('s');

        $t2_3->setBlocking(array($t2_5));
        $features[] = $feature_2_5 = new Feature($t2_5);
        $features[] = $feature_2_3 = new Feature($t2_3);

        /**
         * No blocking
         */
        $t3_1 = new Ticket();
        $t3_1->setId('3_1')->setStatus(1);
        $features[] = $feature_3_1 = new Feature($t3_1);

        $sorted = BaseSorter::sortByBlockingTickets($features, $blockingStatus);

        $this
            ->array($sorted)
                ->hasSize(5)
                ->hasKeys(array(0, 1, 2, 3, 4))
            ->object($sorted[0])
                ->isEqualTo($feature_3_1)
            ->object($sorted[1])
                ->isEqualTo($feature_2_3)
            ->object($sorted[2])
                ->isEqualTo($feature_2_5)
            ->object($sorted[3])
                ->isEqualTo($feature_1_5)
            ->object($sorted[4])
                ->isEqualTo($feature_1_3)
        ;
    }
}
