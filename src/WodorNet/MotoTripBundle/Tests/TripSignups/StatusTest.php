<?php
namespace WodorNet\MotoTripBundle\Tests\TripSignups;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $securityContext;
    /**
     * @var \WodorNet\MotoTripBundle\TripSignups\Manager
     */
    protected $statusService;

    /**
     * @var \WodorNet\MotoTripBundle\Entity\TripSignupRepository
     */
    protected $tripSignupRepository;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->tripSignupRepository = $this
            ->getMockBuilder('WodorNet\MotoTripBundle\Entity\TripSignupRepository')
            ->disableOriginalConstructor()
        ->getMock();

        $this->securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()->getMock();

        $this->statusService = new \WodorNet\MotoTripBundle\TripSignups\Status($this->tripSignupRepository, $this->securityContext);
    }

    /**
     *
     */
    public function testGetRelationInfoOwner()
    {
        $user = $this->getMock('WodorNet\MotoTripBundle\Entity\User');

        $trip = $this->getMock('WodorNet\MotoTripBundle\Entity\Trip');
        $trip->expects($this->any())
            ->method('getCreator')
            ->will($this->returnValue($user));

        $this->assertEquals('trip.userrelation.owner',$this->statusService->getRelationInfo($trip, $user));
    }

    /**
     *
     */
    public function testGetRelationInfoCandidate()
    {
        $user = $this->getMock('WodorNet\MotoTripBundle\Entity\User');
        //$user->expects($this-any())

        $trip = $this->getMock('WodorNet\MotoTripBundle\Entity\Trip');

        $signup = $this->getMock('WodorNet\MotoTripBundle\Entity\TripSignup');

        $this->tripSignupRepository->expects($this->once())
            ->method('getByTripAndUser')
            ->with($trip, $user)
            ->will($this->returnValue($signup));

        $signup->expects($this->any())
            ->method('getStatus')
            ->will($this->returnValue(\WodorNet\MotoTripBundle\Entity\TripSignup::STATUS_NEW));

        $this->assertEquals('trip.userrelation.candidate',$this->statusService->getRelationInfo($trip, $user));
    }

    /**
     *
     */
    public function testGetRelationInfoAttendee()
    {
        $user = $this->getMock('WodorNet\MotoTripBundle\Entity\User');

        $trip = $this->getMock('WodorNet\MotoTripBundle\Entity\Trip');
        $signup = $this->getMock('WodorNet\MotoTripBundle\Entity\TripSignup');

        $this->tripSignupRepository->expects($this->once())
            ->method('getByTripAndUser')
            ->with($trip, $user)
            ->will($this->returnValue($signup));

        $signup->expects($this->any())
            ->method('getStatus')
            ->will($this->returnValue(\WodorNet\MotoTripBundle\Entity\TripSignup::STATUS_APPROVED));

        $this->assertEquals('trip.userrelation.attendee',$this->statusService->getRelationInfo($trip, $user));
    }

    /**
     *
     */
    public function testGetRelationInfoUnrelated()
    {
        $user = $this->getMock('WodorNet\MotoTripBundle\Entity\User');
        $trip = $this->getMock('WodorNet\MotoTripBundle\Entity\Trip');

        $this->tripSignupRepository->expects($this->once())
            ->method('getByTripAndUser')
            ->with($trip, $user)
            ->will($this->returnValue(null));

        $this->assertEquals('trip.userrelation.unrelated',$this->statusService->getRelationInfo($trip, $user));

    }


    public function testGetRelationInfoNotLoggedIn() {

        $token = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token\AnonymousToken')
            ->disableOriginalConstructor()
            ->getMock();

        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(''));

        $this->securityContext->expects($this->any())
        ->method('getToken')
        ->will($this->returnValue($token));

        $trip = $this->getMock('WodorNet\MotoTripBundle\Entity\Trip');

        $this->assertEquals('trip.userrelation.unrelated',$this->statusService->getRelationInfo($trip));
    }
}
