<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Getunik\BleedHd\AssessmentDataBundle\Entity\ClientLogEntry;


/**
 * ClientLogEntryHandler
 */
class ClientLogEntryHandler
{
    public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\ClientLogEntry';

    private $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->repository = $managerRegistry
                            ->getManagerForClass(self::$entityType)
                            ->getRepository(self::$entityType);
    }

    public function save(ClientLogEntry $logEntry)
    {
        $this->repository->save($logEntry, true);
    }

    public function batchInsert(array $logEntries)
    {
        foreach ($logEntries as $entry)
        {
            $this->repository->save($entry, false);
        }
    }
}
