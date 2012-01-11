<?php
namespace WodorNet\MotoTripBundle\Mailing;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use WodorNet\MotoTripBundle\Entity\TripSignup;

class Sender
{
    /**
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * @var \Symfony\Bundle\TwigBundle\TwigEngine
     */
    public $templating;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    public $translator;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @param \Symfony\Bundle\FrameworkBundle\Translation\Translator $translator
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $templating, Translator $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * @param \WodorNet\MotoTripBundle\Entity\TripSignup $tripSignup
     * @return int
     */
    public function sendSingnupApprove(TripSignup $tripSignup)
    {
        $tripSignup->getTrip();

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('mail.subject.trip_signup.approve'))
            ->setFrom('wodor@wodor.net')
            ->setTo('recipient@example.com')
            ->setBody($this->templating->render('WodorNetMotoTripBundle:Email:tripSignup.html.twig', array('name' => 'dupa')), 'text/html');
        return $this->mailer->send($message);
    }

    public function sendSingnupResign(TripSignup $tripSignup)
    {
        $tripSignup->getTrip();

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('mail.subject.trip_signup.resign'))
            ->setFrom('wodor@wodor.net')
            ->setTo('recipient@example.com')
            ->setBody($this->templating->render('WodorNetMotoTripBundle:Email:tripSignup.html.twig', array('name' => 'dupa')), 'text/html');
        return $this->mailer->send($message);
    }


    public function sendSingnupReject(TripSignup $tripSignup)
    {
        $tripSignup->getTrip();

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('mail.subject.trip_signup.reject'))
            ->setFrom('wodor@wodor.net')
            ->setTo('recipient@example.com')
            ->setBody($this->templating->render('WodorNetMotoTripBundle:Email:tripSignup.html.twig', array('name' => 'dupa')), 'text/html');
        return $this->mailer->send($message);
    }

}
