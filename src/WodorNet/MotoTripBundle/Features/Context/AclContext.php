<?php

namespace WodorNet\MotoTripBundle\Features\Context;

use Behat\BehatBundle\Context\BehatContext;
use Symfony\Bundle\SecurityBundle\Command\InitAclCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * Feature context.
 */
class AclContext extends BehatContext
{

    /**
     * TODO
     * this does not checks $this->aclProvider->updateAcl($acl)
     * this test shoul use user as from a parameter not taken from inside of the object
     * this test shoul use user as from a parameter not taken from inside of the object
     *
     * @Then /^I have "([^"]*)" permission for "([^"]*)" trip$/
     */
    public function iHavePermissionForTrip($permission, $tripTitle)
    {
        /** @var $trip \WodorNet\MotoTripBundle\Entity\Trip */
        $trip = current($this->getEntityManager()->getRepository('WodorNetMotoTripBundle:Trip')->findByTitle($tripTitle));

        if (!$trip instanceof \WodorNet\MotoTripBundle\Entity\Trip) {
            throw new \InvalidArgumentException('Trip titled "' . $tripTitle . '" does not exist');
        }

        $aclProvider = $this->getContainer()->get('security.acl.provider');
        foreach ($aclProvider->findAcl($trip->getObjectIdentity(), array($trip->getObjectIdentity()))->getObjectAces() as $entry) {
            /** @var $entry \Symfony\Component\Security\Acl\Domain\Entry */
            $granted = $entry->getAcl()->isGranted(array(MaskBuilder::MASK_OWNER), array($trip->getSecurityIdentity()));

            if ($granted) {
                return true;
            }
        }

        throw new \RuntimeException("OWNER of trip $tripTitle is not set properly");
    }

    /**
     * @param \Behat\Behat\Event\ScenarioEvent|\Behat\Behat\Event\OutlineExampleEvent $event
     *
     * @BeforeScenario
     *
     * @return null
     */
    public function setUp($event)
    {

        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $this->_application->setAutoExit(false);

        // I use drop and create to avoid need for separate entity manager
        // i dont want to complicate config only for this purpose
        $this->runConsole("doctrine:database:drop", array("--force" => true, '--connection' => 'acl'));
        $this->runConsole("doctrine:database:create", array('--connection' => 'acl'));
        $this->runConsole("cache:warmup");

        // franca jebana gubi polaczenie do bazy i jest  SQLSTATE[3D000]: Invalid catalog name: 1046 No database selected
        // let's reconnect to db as it was recreated
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $this->_application->setAutoExit(false);

        $this->runConsole("init:acl");
    }

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options = array_merge($options, array('command' => $command));

        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options), new \Symfony\Component\Console\Output\ConsoleOutput());
    }

    /**
     * Returns entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }


}
