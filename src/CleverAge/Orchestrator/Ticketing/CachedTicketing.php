<?php

namespace CleverAge\Orchestrator\Ticketing;

use CleverAge\Orchestrator\Service\CachedService;
use CleverAge\Orchestrator\Request\Request;

abstract class CachedTicketing extends CachedService implements TicketingInterface
{
    /**
     * @var array
     */
    protected $cacheLifetime = array(
        'ticket'        => 120,
        'milestone'     => 86400,
    );

    protected $tickets = array();

    /**
     * @param string $id
     * @return CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    public function getTicketById($id)
    {
        if (isset($this->tickets[$id])) {
            return $this->tickets[$id];
        }

        $ticket = $this->getResource('doGetTicketById', func_get_args(), array(
            'cache_key'      => 'ticket_'.$id,
            'cache_lifetime' => $this->cacheLifetime['ticket']
        ));

        $this->tickets[$id] = $ticket;

        return $ticket;
    }

    /**
     * @param string $id
     * @return CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    abstract protected function doGetTicketById($id);

    /**
     * Update a ticket from TicketUpdate data
     *
     * @param \CleverAge\Orchestrator\Ticketing\Model\Ticket $ticket
     * @param \CleverAge\Orchestrator\Ticketing\Model\TicketUpdate $update
     * @return \CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    public function updateTicket(Model\Ticket $ticket, Model\TicketUpdate $update)
    {
        return $this->getResource('doUpdateTicket', func_get_args(), array(
            'cache_key'      => 'ticket_' . $ticket->getId(),
            'cache_lifetime' => $this->cacheLifetime['ticket'],
            'cache_no_get'   => true,
        ));
    }

    /**
     * Update a ticket from TicketUpdate data
     *
     * @param \CleverAge\Orchestrator\Ticketing\Model\Ticket $ticket
     * @param \CleverAge\Orchestrator\Ticketing\Model\TicketUpdate $update
     * @return \CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    abstract protected function doUpdateTicket(Model\Ticket $ticket, Model\TicketUpdate $update);

    /**
     * @param CleverAge\Orchestrator\Request\Request                $request
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Ticket>
     */
    public function getTicketList(Request $request)
    {
        $tickets = $this->getResource('doGetTicketList', func_get_args(), array(
            'cache_key'      => 'tickets_'.$request->getHash(),
            'cache_lifetime' => $this->cacheLifetime['ticket']
        ));

        foreach ($tickets as $k => $ticket) {
            if (isset($this->tickets[$ticket->getId()])) {
                $tickets[$k] = $this->tickets[$ticket->getId()];
            } else {
                $this->tickets[$ticket->getId()] = $tickets[$k];
            }
        }

        return $tickets;
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
        return $this->getResource('doGetMilstones', func_get_args(), array(
            'cache_key'      => 'milestones' . $c,
            'cache_lifetime' => $this->cacheLifetime['milestone']
        ));
    }

    /**
     * @param boolean\null $completed if null, all milestones, if false, actives, finished otherwise
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Milestone>
     */
    abstract protected function doGetMilstones($completed = null);

    /**
     * @inheritdoc
     */
    protected function fetchResource($method, $arguments)
    {
        return call_user_func_array(array($this, $method), $arguments);
    }
}
