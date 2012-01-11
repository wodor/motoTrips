<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Form\TripType;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * Trip controller.
 *
 * @Route("/trip")
 */
class TripController extends Controller
{
    /**
     * Info window on map
     *
     * @Route ("/{trip}/infoWindow", name="tripInfoWindow", options={"expose"=true}))
     * @Template()
     */
    public function infoWindowAction($trip)
    {
        return array('trip' => $trip);
    }


    /**
     * List of latest trips
     *
     * @Route ("/upcomingTrips", name="upcomingTripsSnippet", options={"expose"=true}))
     * @Template()
     */
    public function snippetListAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('WodorNetMotoTripBundle:Trip')->findUpcomingTrips('10');

        $paginator = $this->get('wodor_net_moto_trip.datatable_paginator');

        $paginator->setItemTemplate('WodorNetMotoTripBundle:Trip:snippetItem.html.php');
        $output = $paginator->paginate($qb);

        return new Response(json_encode($output));
    }


    /**
     * Lists all Trip entities.
     *
     * @Route ("/", name="trip")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('WodorNetMotoTripBundle:Trip')->findAll();


        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Trip entity.
     *
     * @Route("/{id}/show", name="trip_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:Trip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trip entity.');
        }


        return array(
            'trip' => $entity,
        );
    }

    /**
     * Displays a form to create a new Trip entity.
     *
     * @Route("/new", name="trip_new")
     * @Template()
     * @Secure(roles="ROLE_USER")
     *
     */
    public function newAction()
    {
        $entity = new Trip();
        $nextHour = new \DateTime(date("Y-m-d H:00:00"));
        $nextHour->modify('next hour');
        $tomorrow = clone $nextHour;
        $tomorrow->modify('next day');

        $entity->setStartDate($nextHour);
        $entity->setEndDate($tomorrow);

        $form = $this->createForm(new TripType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * gives Trip entity to the admin
     *
     * @Route("/{id}/give", name="trip_give")
     * @Template()
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     * @Secure("ROLE_ADMIN")
     *
     */
    public function giveAction(Trip $trip)
    {

        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($trip);
        $acl = $aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($acl);

        return new Response("ok");
    }

    /**
     * Creates a new Trip entity.
     *
     * @Route("/create", name="trip_create")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:Trip:new.html.twig")
     * @Secure("ROLE_USER")
     */
    public function createAction()
    {
        $trip = new Trip();

        $trip->setCreationDate(new \DateTime());
        $request = $this->getRequest();
        $form = $this->createForm(new TripType(), $trip);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $rt = $em->getRepository('WodorNetMotoTripBundle:RoadType')->find(1);
            $trip->addRoadType($rt);
            $em->persist($trip);
            $em->flush();

            // creating the ACL
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($trip);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            $securityContext = $this->get('security.context');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            // grant owner access
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

            return $this->redirect($this->generateUrl('trip_show', array('id' => $trip->getId())));

        }

        return array(
            'entity' => $trip,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Trip entity.
     *
     * @Route("/{id}/edit", name="trip_edit")
     * @Template()
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     * @PreAuthorize("permitAll()")
     */
    public function editAction(Trip $trip)
    {

        //   $aclProvider = $this->get('security.acl.provider');
        //   $aclProvider->findAcl( ObjectIdentity::fromDomainObject($trip));


        $editForm = $this->createForm(new TripType(), $trip);
        $deleteForm = $this->createDeleteForm($trip->getId());

        return array(
            'entity' => $trip,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Trip entity.
     *
     * @Route("/{id}/update", name="trip_update")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:Trip:edit.html.twig")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     * @PreAuthorize("hasRole(ADMIN) or hasPermission(trip, EDIT)")
     *
     */
    public function updateAction(Trip $trip)
    {

        $editForm = $this->createForm(new TripType(), $trip);
        $deleteForm = $this->createDeleteForm($trip->getId());

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($trip);
            $em->flush();

            return $this->redirect($this->generateUrl('trip_edit', array('id' => $trip->getId())));
        }

        return array(
            'entity' => $trip,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Trip entity.
     *
     * @Route("/{id}/delete", name="trip_delete")
     * @PreAuthorize("hasRole(ADMIN) or hasPermission(trip, DELETE)")
     * @ParamConverter("trip", class="WodorNetMotoTripBundle:Trip")
     */
    public function deleteAction(Trip $trip)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($trip);
        $em->flush();

        return $this->redirect($this->generateUrl('trip'));
    }


    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }


}
