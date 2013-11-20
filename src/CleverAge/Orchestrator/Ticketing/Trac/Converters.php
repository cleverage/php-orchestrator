<?php

namespace CleverAge\Orchestrator\Ticketing\Trac;

use CleverAge\Trac\Ticket;
use CleverAge\Orchestrator\Ticketing\Model;

class Converters
{
    public static function convertTicketFromTrac(Ticket $ticket)
    {
        $t = new Model\Ticket();
        $t
            ->setId($ticket->get('id'))
            ->setTitle($ticket->get('summary'))
            ->setDescription($ticket->get('description'))
            ->setStatus($ticket->get('status'))
            ->setReporter($ticket->get('reporter'))
            ->setMilestone($ticket->get('milestone'))
            ->setType($ticket->get('type'))
            ->setOwner($ticket->get('owner'))
            ->setPriority($ticket->get('priority'))
            ->setUrl($ticket->getUrl())
        ;

        $blocking = $ticket->get('blocking');
        if (!empty($blocking)) {
            $t->setBlocking(explode(', ', $blocking));
        }
        $blockedBy = $ticket->get('blockedby');
        if (!empty($blocking)) {
            $t->setBlockedBy(explode(', ', $blockedBy));
        }
        return $t;
    }
}
