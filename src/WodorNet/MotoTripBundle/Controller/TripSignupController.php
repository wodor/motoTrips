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
 * @Route("/tripsignup")
 */
class TripSignupController extends Controller
{

    /**
     * resign the signup, ad-hoc
     *
     * @Route("/{id}/disapprove/", name="tripsignup_disapprove", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:singupList.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:TripSignup")
     */
    public function disapproveAction(TripSignup $tripSignup)
    {
        $this->ensureUserEqualsLoggedIn($tripSignup->getTrip()->getCreator());

        $tripSignupsService = $this->get('wodor_net_moto_trip.tripsignups');
        $tripSignupsService->disapprove($tripSignup);

        return $this->redirect($this->generateUrl('trip_show', array('id' => $tripSignup->getTrip()->getId())));
    }

    /**
     * resign the signup, ad-hoc
     *
     * @Route("/{id}/resign/", name="tripsignup_resign", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:singupList.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:TripSignup")
     */
    public function resignAction(TripSignup $tripSignup)
    {
        $this->ensureUserEqualsLoggedIn($tripSignup->getUser());

        $tripSignupsService = $this->get('wodor_net_moto_trip.tripsignups');
        $tripSignupsService->resign($tripSignup);

        return $this->redirect($this->generateUrl('trip_show', array('id' => $tripSignup->getTrip()->getId())));

    }

    /**
     * reject the signup, ad-hoc
     *
     * @Route("/{id}/reject/", name="tripsignup_reject", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:singupList.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:TripSignup")
     */
    public function rejectAction(TripSignup $tripSignup)
    {
        $this->ensureUserEqualsLoggedIn($tripSignup->getTrip()->getCreator());

        $tripSignupsService = $this->get('wodor_net_moto_trip.tripsignups');
        $tripSignupsService->reject($tripSignup);

        return $this->redirect($this->generateUrl('trip_show', array('id' => $tripSignup->getTrip()->getId())));
    }

    /**
     * Approve the signup, ad-hoc
     *
     * @Route("/{id}/Approve/", name="tripsignup_approve", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:singupList.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:TripSignup")
     */
    public function approveAction(TripSignup $tripSignup)
    {
        $this->ensureUserEqualsLoggedIn($tripSignup->getTrip()->getCreator());

        $tripSignupsService = $this->get('wodor_net_moto_trip.tripsignups');
        $tripSignupsService->approve($tripSignup);

        return $this->redirect($this->generateUrl('trip_show', array('id' => $tripSignup->getTrip()->getId())));
    }

    /**
     * List of candidates to approve
     *
     * @Route("/{id}/Candidates/", name="candidateTripSignups", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:candidatesSnippet.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     */
    public function signupCandidatesListAction(Trip $trip)
    {
        $em = $this->getDoctrine()->getEntityManager();
        /** @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->findCandidatesByTrip($trip);

        $tripSignups = $qb->getQuery()->getResult();

        return array('tripSignups' => $tripSignups);
    }

    /**
     * List of approved candidates
     *
     * @Route("/{id}/Approved/", name="approvedTripSignups", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:approvedSnippet.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     */
    public function signupListAction(Trip $trip)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->findApprovedByTrip($trip);

        $tripSignups = $qb->getQuery()->getResult();

        $tripPerm = $this->get('tripPerm');

        return array(
            'tripSignups' => $tripSignups,
            'tripEditAllowed' => $tripPerm->canEdit($trip),
            'signupPerm' => $this->get('signupPerm')
        );
    }


    /**
     * List of approved candidates
     *
     * @Route("/signpus/{id}/{status}", name="usersSignupList")
     * @Template("WodorNetMotoTripBundle:TripSignup:usersSignupList.html.twig")
     * @ParamConverter("user", class="WodorNetMotoTripBundle:User")
     */
    public function usersSignupsListAction(\WodorNet\MotoTripBundle\Entity\User $user, $status) {
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->findByStatusAndUser($user, $status);
        $tripSignups = $qb->getQuery()->getResult();
        return array(
            'tripSignups' => $tripSignups,
        );
    }

    /**
     * Creates a new TripSignup entity.
     *
     * @Route("/signup/{id}", name="signup")
     * @Template("WodorNetMotoTripBundle:TripSignup:usersignup.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     * @PreAuthorize("isAuthenticated()")
     */
    public function signupAction(Trip $trip)
    {
        $tripPerm = $this->get('tripPerm');
        if (!$tripPerm->canJoin($trip)) {
            return $this->redirect($this->generateUrl('trip_show', array('id' => $trip->getId())));
        }

        $tripSignup = new TripSignup();

        $user = $this->get('security.context')->getToken()->getUser();

        $tripSignup->setTrip($trip);
        $tripSignup->setUser($user);
        $tripSignup->setSignupType('join');

        $request = $this->getRequest();
        $form = $this->createForm(new TripSignupType(), $tripSignup);

        if ($request->getMethod() === "POST") {
            $form->bindRequest($request);

            if ($form->isValid()) {

                $tripSignup->setSignupDate(new \DateTime());

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($tripSignup);
                $em->flush();

                $event = new \WodorNet\MotoTripBundle\Event\TripSignupEvent($tripSignup);
                $this->get('event_dispatcher')->dispatch(\WodorNet\MotoTripBundle\MotoTripEvents::onTripSignupCreate, $event);

                return $this->redirect($this->generateUrl('trip_show', array('id' => $trip->getId())));
            }
        }

        return array(
            'entity' => $tripSignup,
            'form' => $form->createView()
        );
    }


    /**
     * Lists all TripSignup entities.
     * only for admin
     *
     * @Route("/", name="tripsignup")
     * @Template()
     * @Secure("ROLE_ADMIN")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a TripSignup entity.
     *
     * @Route("/{id}/show", name="tripsignup_show")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:TripSignup")
     * @Template()
     */
    public function showAction(TripSignup $tripSignup)
    {
        /** @var $tripperm \WodorNet\MotoTripBundle\Security\TripPermissions */
        $tripperm = $this->get('tripperm');

        return array(
            'editAllowed' => $tripperm->canEdit($tripSignup->getTrip()),
            'tripSignup' => $tripSignup,
        );
    }

    /**
     * Displays a form to create a new TripSignup entity.
     *
     * @Route("/new", name="tripsignup_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TripSignup();
        $form = $this->createForm(new TripSignupType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new TripSignup entity.
     *
     * @Route("/create", name="tripsignup_create")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:TripSignup:new.html.twig")
     */
    public function createAction()
    {


        $entity = new TripSignup();
        $request = $this->getRequest();
        $form = $this->createForm(new TripSignupType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('tripsignup_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing TripSignup entity.
     *
     * @Route("/{id}/edit", name="tripsignup_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TripSignup entity.');
        }

        $editForm = $this->createForm(new TripSignupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TripSignup entity.
     *
     * @Route("/{id}/update", name="tripsignup_update")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:TripSignup:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TripSignup entity.');
        }

        $editForm = $this->createForm(new TripSignupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tripsignup_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a TripSignup entity.
     *
     * @Route("/{id}/delete", name="tripsignup_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TripSignup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tripsignup'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }


}