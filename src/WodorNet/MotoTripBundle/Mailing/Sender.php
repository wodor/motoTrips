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
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('mail.subject.trip_signup.approve'))
            ->setFrom($tripSignup->getTrip()->getCreator()->getEmail())
            ->setReplyTo($tripSignup->getTrip()->getCreator()->getEmail())
            ->setTo($tripSignup->getUser()->getEmail())
            ->setBody($this->templating->render('WodorNetMotoTripBundle:Email:tripSignupApprove.html.twig', array('name' => 'dupa')), 'text/html');
        return $this->mailer->send($message);
    }

    public function sendSingnupResign(TripSignup $tripSignup)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('mail.subject.trip_signup.resign'))
            ->setFrom('wodor@wodor.net')
            ->setTo($tripSignup->getTrip()->getUser()->getEmail())
            ->setBody($this->templating->render('WodorNetMotoTripBundle:Email:tripSignup.html.twig', array('name' => 'dupa')), 'text/html');
        return $this->mailer->send($message);
    }


    public function sendSingnupReject(TripSignup $tripSignup)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('mail.subject.trip_signup.reject'))
            ->setFrom('wodor@wodor.net')
            ->setTo($tripSignup->getUser()->getEmail())
            ->setBody($this->templating->render('WodorNetMotoTripBundle:Email:tripSignup.html.twig', array('name' => 'dupa')), 'text/html');
        return $this->mailer->send($message);
    }

    public function sendSingnupCreate(TripSignup $tripSignup)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('mail.subject.trip_signup.create'))
            ->setFrom($tripSignup->getUser()->getEmail())
            ->setReplyTo($tripSignup->getUser()->getEmail())
            ->setTo($tripSignup->getTrip()->getCreator()->getEmail())
            ->setBody($this->templating->render('WodorNetMotoTripBundle:Email:tripSignupCreate.html.twig', array('name' => 'dupa')), 'text/html');
        return $this->mailer->send($message);

    }

}
