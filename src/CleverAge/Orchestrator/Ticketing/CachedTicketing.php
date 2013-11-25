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
        'milestone'    => 86400,
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

    /**
     * @param boolean\null $completed if null, all milestones, if false, actives, finished otherwise
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Milestone>
     */
    public function getMilestones($completed = null)
    {
        $c = '';
        if (!is_null($completed)) {
            $c = $completed ? '_completed' : '_not_completed';
        }
        return $this->getCachedRessource('milestones' . $c, 'doGetMilstones', func_get_args(), 'milestone');
    }

    /**
     * @param boolean\null $completed if null, all milestones, if false, actives, finished otherwise
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Milestone>
     */
    abstract protected function doGetMilstones($completed = null);
}
