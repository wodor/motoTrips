<?php


namespace WodorNet\MotoTripBundle\Features\Context;

use Behat\BehatBundle\Context\BehatContext,
Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
Behat\Behat\Context\TranslatedContextInterface,
Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;

use WodorNet\MotoTripBundle\Entity;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext //BehatContext //MinkContext if you want to test web
{

    protected $myLogin;

    public function __construct($kernel)
    {
        $this->useContext('acl', new \WodorNet\MotoTripBundle\Features\Context\AclContext($kernel));
        $this->useContext('symfony_doctrine', new \Behat\CommonContexts\SymfonyDoctrineContext($kernel));
        $this->useContext('web_extra', new \Behat\CommonContexts\MinkExtraContext($kernel));
        $this->useContext('symfony_extra', new \Behat\CommonContexts\SymfonyMailerContext($kernel));
        $this->useContext('redirect', new \Behat\CommonContexts\MinkRedirectContext($kernel));
        $this->useContext('trip', new \WodorNet\MotoTripBundle\Features\Context\TripContext($kernel));
        parent::__construct($kernel);
    }


    /**
     * @Given /^the site has following users:$/
     */
    public function theSiteHasFollowingUsers(TableNode $table)
    {
        $entityManager = $this->getEntityManager();


        $factory = $this->getContainer()->get('security.encoder_factory');


        $hash = $table->getHash();
        foreach ($hash as $row) {
            $user = new Entity\User();
            $encoder = $factory->getEncoder($user);
            $user->setEmail($row['email']);
            $user->setUsername($row['username']);

            $password = $encoder->encodePassword($row['password'], $user->getSalt());
            $user->setPassword($password);
            $user->setEnabled(true);

            $entityManager->persist($user);
        }
        $entityManager->flush();
    }


    /**
     * @Given /^I click randomly on the map in "([^"]*)"$/
     */
    public function iClickOnThe($argument1)
    {
        /** @var $page \Behat\Mink\Element\DocumentElement */
        $page = $this->getSession()->getPage();

        $el = $page->find('css', '#map_canvas div div div');
        $el->click();

    }


    /**
     * @Given /^I am "([^"]*)"$/
     */
    public function iAm($login)
    {
        $this->myLogin = $login;
    }


    /**
     * @Given /^I am logged in as "([^"]*)" with "([^"]*)" password$/
     */
    public function iAmLoggedInAsWithPassword($login, $pass)
    {
        return array(
            new Step\Given('I am "' . $login . '"'),
            new Step\When('I go to "/login"'),
            new Step\When('I fill in "Nazwa użytkownika:" with "' . $login . '"'),
            new Step\When('I fill in "Hasło:" with "' . $pass . '"'),
            new Step\When('I press "Zaloguj"'),
            //  new Step\Then('I should be on "/"'),
        );
    }


    /**
     * Returns entity manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }


    protected $_application;

    public function setUp()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $this->_application->setAutoExit(false);
        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:create");
        $this->runConsole("cache:warmup");
        $this->runConsole("doctrine:fixtures:load", array("--fixtures" => __DIR__ . "/../DataFixtures"));
    }

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

    public function getMyLogin()
    {
        return $this->myLogin;
    }

    public function getMe()
    {
        return current($this->getEntityManager()->getRepository('WodorNetMotoTripBundle:User')->findByUsername($this->getMyLogin()));
    }


}
