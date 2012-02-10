<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use WodorNet\MotoTripBundle\Controller\MotoTripController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WodorNet\MotoTripBundle\Entity\TripSignup;
use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Form\TripSignupType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TripSignup controller.
 *
 * @Route("")
 */
class DashboardController extends Controller
{
    /**
     * Shows user's control panel
     *
     * @Route("/dashboard", name="dashboard")
     * @Template()
     * @PreAuthorize("isFullyAuthenticated()")
     */
    public function indexAction() {

        $user = $this->get('security.context')->getToken()->getUser();

        return array(
            'user'=>$user,
        );
    }

    /**
     * Shows user's control panel
     *
     * @Route("/user/{id}", name="userShow")
     * @Template()
     * @ParamConverter("user", class="WodorNetMotoTripBundle:User")
     */
    public function userShowAction($user) {
        return array(
            'user' => $user,
            'isOwner' =>$user === $this->get('security.context')->getToken()->getUser()
        );
    }

    /**
     * Shows user's control panel
     *
     * @Route("/description/edit", name="descriptionEdit")
     * @Template()
     * @PreAuthorize("isFullyAuthenticated()")
     */
    public function descriptionEditAction() {

        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->createForm(new \WodorNet\MotoTripBundle\Form\UserDescriptionType(), $user);

        if("POST" === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('userShow', array('id'=>$user->getId())));
            }
        }

        return array(
            'form' => $form->createView()
        );

    }

}