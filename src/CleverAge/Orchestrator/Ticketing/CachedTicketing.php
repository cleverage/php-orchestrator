<?php

namespace CleverAge\Orchestrator\Ticketing;

use CleverAge\Orchestrator\Cache\CacheCapable;

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
     *
     * @param string $status
     * @param int $limit
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Ticket>
     */
    public function getTicketListByStatus($status, $limit = 20)
    {
        return $this->getCachedRessource('ticketss_'.$status.'_'.$limit, 'doGetTicketListByStatus', func_get_args(), 'ticket');
    }
    /**
     *
     * @param string $status
     * @param int $limit
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Ticket>
     */
    abstract protected function doGetTicketListByStatus($status, $limit = 20);
}
