<?php

namespace CleverAge\Orchestrator\Ticketing\Trac;

use CleverAge\Trac\Model\Ticket;
use CleverAge\Trac\Model\Milestone;
use CleverAge\Trac\TracApi;
use CleverAge\Orchestrator\Ticketing\Model;
use CleverAge\Orchestrator\Ticketing\ConverterInterface;

class Converter implements ConverterInterface
{
    public function convertTicketFromApi($ticket)
    {
        if (!$ticket instanceof Ticket) {
            return null;
        }

        $t = new Model\Ticket();
        $t
            ->setRaw($ticket)
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
            ->setIsClosed($ticket->get('status') === TracApi::STATUS_CLOSED)
            ->setProviderSpecific(array(
                '_ts' => $ticket->get('_ts'),
            ))
        ;

        $blocking = $ticket->get('blocking');
        if (!empty($blocking)) {
            $t->setBlocking(explode(', ', $blocking));
        }
        $blockedBy = $ticket->get('blockedby');
        if (!empty($blockedBy)) {
            $t->setBlockedBy(explode(', ', $blockedBy));
        }
        return $t;
    }

    public function convertMilestoneFromApi($milestone)
    {
        if (!$milestone instanceof Milestone) {
            return null;
        }

        $m = new Model\Milestone();
        $m
            ->setCompletedAt($milestone->get('completed') instanceof \DateTime ? $milestone->get('completed') : null)
            ->setDescription($milestone->get('description'))
            ->setName($milestone->get('name'))
            ->setEndAt($milestone->get('due') instanceof \DateTime ? $milestone->get('due') : null)
            ->setRaw($milestone)
        ;

        return $m;
    }
}
