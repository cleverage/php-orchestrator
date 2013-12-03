<?php

namespace CleverAge\Orchestrator\Ticketing;

interface ConverterInterface
{
    /**
     * @param mixed $ticket
     * @return \CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    public function convertTicketFromApi($ticket);

    /**
     * @param mixed $milestone
     * @return \CleverAge\Orchestrator\Ticketing\Model\Milestone
     */
    public function convertMilestoneFromApi($milestone);
}
