<?php

namespace CleverAge\Orchestrator\Ticketing\Trac;

use CleverAge\Orchestrator\Ticketing\CachedTicketing;
use CleverAge\Orchestrator\Ticketing\Model;
use CleverAge\Trac\TracApi;

class Trac extends CachedTicketing
{
    /**
     * @var TracApi
     */
    protected $trac;

    public function __construct(TracApi $trac)
    {
        $this->trac = $trac;
    }

    protected function getCachePrefix()
    {
        return 'trac';
    }

    public function getUrlFor($object)
    {
        if ($object instanceof Model\Ticket) {
            return $object->getUrl();
        }

        return '';
    }

    protected function doGetTicketById($id)
    {
        $ticketApi = $this->trac->getTicketById($id);

        return Converters::convertTicketFromTrac($ticketApi);
    }

    protected function doGetTicketListByStatus($status, $limit = 20)
    {
        $ticketsApi = $this->trac->getTicketListByStatus($status, $limit);
        $tickets = array();

        foreach ($ticketsApi as $ticketApi) {
            $tickets[] = Converters::convertTicketFromTrac($ticketApi);
        }

        return $tickets;
    }
}
