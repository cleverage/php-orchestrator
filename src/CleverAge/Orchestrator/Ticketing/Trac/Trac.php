<?php

namespace CleverAge\Orchestrator\Ticketing\Trac;

use CleverAge\Orchestrator\Request\Request;
use CleverAge\Orchestrator\Model\Urlisable;
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

    public function getName()
    {
        return 'trac';
    }

    public function getUrlFor(Urlisable $object)
    {
        if ($object instanceof Model\Ticket) {
            return $object->getUrl();
        }

        return '';
    }

    protected function convertRequestToFilters(Request $request)
    {
        $filters = array();

        if ($request->getStatus()) {
            $filters['status'] = $request->getStatus();
        }
        if ($request->getMilestone()) {
            $filters['milestone'] = $request->getMilestone()->getName();
        }

        return $filters;
    }

    public function getTicketById($id)
    {
        $ticket = parent::getTicketById($id);

        $this->populateBlocking($ticket);

        return $ticket;
    }

    protected function doGetTicketById($id)
    {
        if (empty($id)) {
            return null;
        }
        $ticketApi = $this->trac->getTicketById($id);

        return Converters::convertTicketFromTrac($ticketApi);
    }

    public function getTicketList(Request $request)
    {
        $tickets = parent::getTicketList($request);

        foreach ($tickets as $ticket) {
            $this->populateBlocking($ticket);
        }

        return $tickets;
    }

    protected function doGetTicketList(Request $request)
    {
        $ticketsApi = $this->trac->getTicketListBy(
            $this->convertRequestToFilters($request),
            $request->getLimit(),
            $request->getOffset()
        );
        $tickets = array();

        foreach ($ticketsApi as $ticketApi) {
            $tickets[] = Converters::convertTicketFromTrac($ticketApi);
        }

        return $tickets;
    }

    protected function doGetMilstones($completed = null)
    {
        $milestonesApi = $this->trac->getMilestones($completed);
        $milestones = array();

        foreach ($milestonesApi as $milestoneApi) {
            $milestones[] = Converters::convertMilestoneFromTrac($milestoneApi);
        }

        return $milestones;
    }

    protected function populateBlocking(Model\Ticket $ticket = null)
    {
        if ($ticket) {
            $blocking = array();
            foreach ($ticket->getBlocking() as $id) {
                if (!empty($id)) {
                    $blocking[] = $this->getTicketById($id);
                }
            }

            $ticket->setBlocking($blocking);
        }
    }
}
