<?php

namespace CleverAge\Orchestrator\Ticketing\Trac;

use CleverAge\Orchestrator\Ticketing\TicketingInterface;
use CleverAge\Orchestrator\Ticketing\Model;
use CleverAge\Trac\TracApi;

class Trac implements TicketingInterface
{
    /**
     * @var TracApi
     */
    protected $trac;

    public function __construct(TracApi $trac)
    {
        $this->trac = $trac;
    }

    public function getUrlFor($object)
    {
        if ($object instanceof Model\Ticket) {
            return $object->getUrl();
        }

        return '';
    }

    public function getTicketById($id)
    {
        $ticketApi = $this->trac->getTicketById($id);

        return Converters::convertTicketFromTrac($ticketApi);
    }

    public function getTicketListByStatus($status, $limit = 20)
    {
        $ticketsApi = $this->trac->getTicketListByStatus($status, $limit);
        $tickets = array();

        foreach ($ticketsApi as $ticketApi) {
            $tickets[] = Converters::convertTicketFromTrac($ticketApi);
        }

        return $tickets;
    }
}
