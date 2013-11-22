<?php

namespace CleverAge\Orchestrator\Ticketing;

use CleverAge\Orchestrator\Model\Urlisable;
use CleverAge\Orchestrator\Request\Request;

interface TicketingInterface
{
    /**
     * @param CleverAge\Orchestrator\Model\Urlisable $object
     * @return string
     */
    public function getUrlFor(Urlisable $object);

    /**
     * @param int $id
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Ticket>
     */
    public function getTicketById($id);

    /**
     * @param CleverAge\Orchestrator\Request\Request $request
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Ticket>
     */
    public function getTicketList(Request $request);
}
