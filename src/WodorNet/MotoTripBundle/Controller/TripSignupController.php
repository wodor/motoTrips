<?php

namespace WodorNet\MotoTripBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WodorNet\MotoTripBundle\Entity\TripSignup;
use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Form\TripSignupType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TripSignup controller.
 *
 * @Route("/tripsignup")
 */
class TripSignupController extends Controller
{

    /**
     * Approve the signup, ad-hoc
     *
     * @Route("/{id}/Approve/", name="tripsignup_approve", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:singupList.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:TripSignup")
     */
    public function approveAction(TripSignup $tripSignup) {
        // only owner of the trip which is binded to tripsignup
        // is allowed to appprove

        $securityContext = $this->get('security.context');

        if (false === $securityContext->isGranted('EDIT', $tripSignup->getTrip()) && !$securityContext->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $tripSignupsService = $this->get('wodor_net_moto_trip.tripsignups');
        $tripSignupsService->approve($tripSignup);

    }

    /**
     * List of candidates to approve
     *
     * @Route("/{id}/Candidates/", name="candidateTripSignups", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:singupList.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     */
    public function signupCandidatesListAction(Trip $trip) {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->findCandidatesByTrip($trip);

        $paginator = $this->get('wodor_net_moto_trip.datatable_paginator');

        $paginator->setItemTemplate('WodorNetMotoTripBundle:TripSignup:candidateItem.html.twig');
        $output = $paginator->paginate($qb);

        return new Response(json_encode($output));

    }


    /**
     * List of approved candidates
     *
     * @Route("/{id}/Approved/", name="approvedTripSignups", options={"expose"=true})
     * @Template("WodorNetMotoTripBundle:TripSignup:singupList.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     */
    public function signupListAction(Trip $trip) {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->findApprovedByTrip($trip);

        $paginator = $this->get('wodor_net_moto_trip.datatable_paginator');

        $paginator->setItemTemplate('WodorNetMotoTripBundle:TripSignup:approvedItem.html.twig');
        $output = $paginator->paginate($qb);

        return new Response(json_encode($output));
    }


    /**
     * Creates a new TripSignup entity.
     *
     * @Secure("ROLE_ADMIN")
     * @Route("/signup/{id}", name="signup")
     * @Template("WodorNetMotoTripBundle:TripSignup:usersignup.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     */
    public function signupAction(Trip $trip)
    {
        $signup = new TripSignup();

        $user = $this->get('security.context')->getToken()->getUser();

        $signup->setTrip($trip);
        $signup->setUser($user);
        $signup->setSignupType('join');

        $request = $this->getRequest();
        $form = $this->createForm(new TripSignupType(), $signup);

        if ($request->getMethod() === "POST") {
            $form->bindRequest($request);

            if ($form->isValid()) {

                $signup->setSignupDate(new \DateTime());

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($signup);
                $em->flush();

                return $this->redirect($this->generateUrl('trip_show', array('id' => $trip->getId())));

            }
        }

        return array(
            'entity' => $signup,
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
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TripSignup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),);
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
