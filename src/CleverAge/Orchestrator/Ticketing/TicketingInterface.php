<?php

namespace CleverAge\Orchestrator\Ticketing;

use CleverAge\Orchestrator\Model\Urlisable;

interface TicketingInterface
{
    /**
     * @param CleverAge\Orchestrator\Model\Urlisable $object
     * @return string
     */
    public function getUrlFor(Urlisable $object);

    public function getTicketById($id);

    public function getTicketListByStatus($status, $limit);
}
