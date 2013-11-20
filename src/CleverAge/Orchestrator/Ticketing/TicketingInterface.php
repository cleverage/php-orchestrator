<?php

namespace CleverAge\Orchestrator\Ticketing;

interface TicketingInterface
{
    /**
     * @return string
     */
    public function getUrlFor($object);

    public function getTicketById($id);

    public function getTicketListByStatus($status, $limit);
}
