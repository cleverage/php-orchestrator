<?php

namespace CleverAge\Orchestrator\Ticketing;

use CleverAge\Orchestrator\Cache\CacheCapable;
use CleverAge\Orchestrator\Request\Request;

abstract class CachedTicketing extends CacheCapable implements TicketingInterface
{
    /**
     * @var array
     */
    protected $cacheLifetime = array(
        'ticket'        => 120,
    );

    /**
     * @param string $id
     * @return CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    public function getTicketById($id)
    {
        return $this->getCachedRessource('ticket_'.$id, 'doGetTicketById', func_get_args(), 'ticket');
    }

    /**
     * @param string $id
     * @return CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    abstract protected function doGetTicketById($id);

    /**
     * @param CleverAge\Orchestrator\Request\Request                $request
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Ticket>
     */
    public function getTicketList(Request $request)
    {
        return $this->getCachedRessource('tickets_'.$request->getHash(), 'doGetTicketList', func_get_args(), 'ticket');
    }

    /**
     * @param CleverAge\Orchestrator\Request\Request                $request
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Ticket>
     */
    abstract protected function doGetTicketList(Request $request);
}
