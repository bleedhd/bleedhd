<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Getunik\BleedHd\AssessmentDataBundle\Handler\ResponseHandler;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Response;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151117164756 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema)
    {
        $this->addSql('SELECT 1');
    }

    public function postUp(Schema $schema)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('GetunikBleedHdAssessmentDataBundle:Response');

        $responses = $repository->findBy(array('questionSlug' => 'gvhd-organ-scoring.overall.immunosuppression-medication.photopheresis'));
        $this->photopheresisUp($em, $responses);

        $responses = $repository->findBy(array('questionSlug' => 'gvhd-activity-assessment.organ-score.overall.immunosuppression-medication.photopheresis'));
        $this->photopheresisUp($em, $responses);

        $em->flush();
    }

    public function down(Schema $schema)
    {
        $this->addSql('SELECT 1');
    }

    public function postDown(Schema $schema)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('GetunikBleedHdAssessmentDataBundle:Response');

        $responses = $repository->findBy(array('questionSlug' => 'gvhd-organ-scoring.overall.immunosuppression-medication.photopheresis'));
        $this->photopheresisDown($em, $responses);

        $responses = $repository->findBy(array('questionSlug' => 'gvhd-activity-assessment.organ-score.overall.immunosuppression-medication.photopheresis'));
        $this->photopheresisDown($em, $responses);

        $em->flush();
    }

    private function photopheresisUp($em, $responses)
    {
        foreach ($responses as $response)
        {
            $result = $response->getResult();
            if ($result['data'])
            {
                $result['data']['supplements']['other'] = $result['data']['value'];
                $result['data']['value'] = 'other';

                $response->setResult($result);
                $em->persist($response);
            }
        }
    }

    private function photopheresisDown($em, $responses)
    {
        foreach ($responses as $response)
        {
            $result = $response->getResult();
            if (!empty($result['data']) && !empty($result['data']['value']))
            {
                if ($result['data']['value'] === 'other')
                {
                    $result['data']['value'] = $result['data']['supplements']['other'];
                }
                unset($result['data']['supplements']);

                $response->setResult($result);
                $em->persist($response);
            }
        }
    }
}
