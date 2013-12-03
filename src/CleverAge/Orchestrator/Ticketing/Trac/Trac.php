<?php

namespace CleverAge\Orchestrator\Ticketing\Trac;

use CleverAge\Orchestrator\Request\Request;
use CleverAge\Orchestrator\Model\Urlisable;
use CleverAge\Orchestrator\Ticketing\CachedTicketing;
use CleverAge\Orchestrator\Ticketing\Model;
use CleverAge\Trac\TracApi;
use CleverAge\Orchestrator\Ticketing\ConverterInterface;

class Trac extends CachedTicketing
{
    /**
     * @var TracApi
     */
    protected $trac;

    /**
     * @var \CleverAge\Orchestrator\Ticketing\ConverterInterface
     */
    protected $converter;

    public function __construct(TracApi $trac, ConverterInterface $converter = null)
    {
        $this->trac = $trac;
        $this->converter = $converter ?: new Converter();
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

        $populated = array();
        $this->populateDependencies($ticket, $populated);

        return $ticket;
    }

    protected function getPopulatedDependenciesById($id, array &$populated = array())
    {
        $ticket = parent::getTicketById($id);

        $this->populateDependencies($ticket, $populated);

        return $ticket;
    }

    protected function doGetTicketById($id)
    {
        if (empty($id)) {
            return null;
        }
        $ticketApi = $this->trac->getTicketById($id);

        return $this->converter->convertTicketFromApi($ticketApi);
    }

    protected function doUpdateTicket(Model\Ticket $ticket, Model\TicketUpdate $update)
    {
        $ticketApi = $this->trac->updateTicket(
            $ticket->getId(),
            $update->getUpdatedData(),
            $update->getComment(),
            $update->getNotify(),
            $update->getAuthor(),
            $update->getUpdatedAt()
        );

        return $this->converter->convertTicketFromApi($ticketApi);
    }

    public function getTicketList(Request $request)
    {
        $tickets = parent::getTicketList($request);

        $populated = array();
        foreach ($tickets as $ticket) {
            $this->populateDependencies($ticket, $populated);
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
            $tickets[] = $this->converter->convertTicketFromApi($ticketApi);
        }

        return $tickets;
    }

    protected function doGetMilstones($completed = null)
    {
        $milestonesApi = $this->trac->getMilestones($completed);
        $milestones = array();

        foreach ($milestonesApi as $milestoneApi) {
            $milestones[] = $this->converter->convertMilestoneFromApi($milestoneApi);
        }

        return $milestones;
    }

    protected function populateDependencies(Model\Ticket $ticket = null, array &$populated = array())
    {
        if ($ticket) {
            if (!isset($populated[$ticket->getId()])) {

                $populated[$ticket->getId()] = true;

                $blocking = array();
                foreach ($ticket->getBlocking() as $id) {
                    if (!empty($id)) {
                        $blocking[] = $this->getPopulatedDependenciesById($id, $populated);
                    }
                }
                $ticket->setBlocking($blocking);

                $blockedBy = array();
                foreach ($ticket->getBlockedBy() as $id) {
                    if (!empty($id)) {
                        $blockedBy[] = $this->getPopulatedDependenciesById($id, $populated);
                    }
                }
                $ticket->setBlockedBy($blockedBy);
            }
        }
    }
}
