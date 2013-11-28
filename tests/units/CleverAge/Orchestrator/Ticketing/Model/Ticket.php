<?php

namespace CleverAge\Orchestrator\Ticketing\Model\tests\units;

use CleverAge\Orchestrator\Ticketing\Model\Ticket as BaseTicket;

class Ticket extends \atoum
{
    public function testHasBlockingStatus()
    {
        $blockingStatus = array('s');

        $ticket = new BaseTicket();
        $ticket->setStatus('s');
        $this
            ->boolean($ticket->hasBlockingStatus($blockingStatus))
                ->isTrue()
        ;

        $ticket->setStatus('a');
        $this
            ->boolean($ticket->hasBlockingStatus($blockingStatus))
                ->isFalse()
        ;
    }

    public function testIsBlocking()
    {
        $blockingStatus = array('s');

        // blocking status without dependencies
        $ticket = new BaseTicket();
        $ticket->setStatus('s');
        $this
            ->boolean($ticket->isBlocking($blockingStatus))
                ->isFalse()
        ;

        // non blocking status without dependencies
        $ticket->setStatus('a');
        $this
            ->boolean($ticket->isBlocking($blockingStatus))
                ->isFalse()
        ;

        // non blocking status with one non blocking dependency
        $nonBlocking = new BaseTicket();
        $nonBlocking
            ->setStatus('a')
            ->setBlockedBy(array($ticket))
        ;
        $ticket->setBlocking(array($nonBlocking));

        $this
            ->boolean($ticket->isBlocking($blockingStatus))
                ->isFalse()
        ;

        // blocking status with one non blocking dependency
        $ticket->setStatus('s');
        $this
            ->boolean($ticket->isBlocking($blockingStatus))
                ->isFalse()
        ;

        // blocking status with one blocking dependency
        $blocking = new BaseTicket();
        $blocking
            ->setStatus('s')
            ->setBlockedBy(array($ticket))
        ;
        $ticket->setBlocking(array($blocking));

        $this
            ->boolean($ticket->isBlocking($blockingStatus))
                ->isTrue()
        ;

        // blocking status with one non blocking dependency and one blocking dependency
        $ticket->setBlocking(array($nonBlocking, $blocking));

        $this
            ->boolean($ticket->isBlocking($blockingStatus))
                ->isTrue()
        ;
    }
}
