<?php

namespace CleverAge\Orchestrator;

use CleverAge\Orchestrator\Ticketing\Model\Ticket;

class Sorter
{
    /**
     * Sort features, depending of blocking tickets
     *
     * http://en.wikipedia.org/wiki/Topological_sorting
     *
     * @param array<CleverAge\Orchestrator\Feature> $features
     * @param array<mixed> $blockingStatus List of status considering ticket as blocker
     * @return array
     */
    public static function sortByBlockingTickets(array $features, array $blockingStatus)
    {
        $sortedTickets = array();
        $keyedFeatures = array();
        $flattened = array();

        foreach ($features as $feature) {
            $keyedFeatures[$feature->getTicket()->getId()] = $feature;

            //flatten all tickets, parsing dependencies
            self::flattenByBlock($feature->getTicket(), $flattened);
        }

        // return only sorted tickets
        foreach ($flattened as $ticket) {
            self::visitSortByBlockingTickets($ticket, $sortedTickets, $blockingStatus, $flattened);
        }

        // extract features in order of tickets
        $sortedFeatures = array();
        foreach ($sortedTickets as $ticket) {
            if (isset($keyedFeatures[$ticket->getId()])) {
                $sortedFeatures[] = $keyedFeatures[$ticket->getId()];
            }
        }

        return $sortedFeatures;
    }

    private static function flattenByBlock(Ticket $ticket, array &$flattened)
    {
        if (!isset($flattened[$ticket->getId()])) {
            $flattened[$ticket->getId()] = $ticket;
            foreach ($ticket->getBlocking() as $blocking) {
                self::flattenByBlock($blocking, $flattened);
            }
            foreach ($ticket->getBlockedBy() as $blockedBy) {
                self::flattenByBlock($blockedBy, $flattened);
            }
        }
    }

    private static function visitSortByBlockingTickets(Ticket $ticket, array &$sorted, array $blockingStatus, array $tickets)
    {
        if (!in_array($ticket, $sorted)) {
            foreach ($ticket->getBlocking() as $blocking) {
                if ($blocking->hasBlockingStatus($blockingStatus)) {
                    self::visitSortByBlockingTickets($blocking, $sorted, $blockingStatus, $tickets);
                }
            }

            array_unshift($sorted, $ticket);
        }
    }
}
