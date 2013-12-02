<?php

namespace CleverAge\Orchestrator\Ticketing;

use CleverAge\Orchestrator\Model\Urlisable;
use CleverAge\Orchestrator\Request\Request;
use CleverAge\Orchestrator\Ticketing\Model\Ticket;
use CleverAge\Orchestrator\Ticketing\Model\TicketUpdate;

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

    /**
     * @return array<string>
     */
    public function getMilestones();

    /**
     * Update a ticket from TicketUpdate data
     *
     * @param \CleverAge\Orchestrator\Ticketing\Model\Ticket $ticket
     * @param \CleverAge\Orchestrator\Ticketing\Model\TicketUpdate $update
     * @return \CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    public function updateTicket(Ticket $ticket, TicketUpdate $update);
}
