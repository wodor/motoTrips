<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Form\TripType;

/**
 * Trip controller.
 *
 * @Route("/trip")
 */
class TripController extends Controller
{
    /**
     * List of latest trips
     *
     * @Route ("/upcomingTrips", name="upcomingTripsSnippet", options={"expose"=true}))
     * @Template()
     */
    public function snippetListAction() {



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

       // $entities = $em->getRepository('WodorNetMotoTripBundle:Trip')->findAll();
        $entities = $em->getRepository('WodorNetMotoTripBundle:TripSignup')->findByTrip(1);

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

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Trip entity.
     *
     * @Route("/new", name="trip_new")
     * @Template()
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

        $form   = $this->createForm(new TripType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Trip entity.
     *
     * @Route("/create", name="trip_create")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:Trip:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Trip();
        
        $entity->setCreationDate(new \DateTime());
        $request = $this->getRequest();
        $form    = $this->createForm(new TripType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $rt = $em->getRepository('WodorNetMotoTripBundle:RoadType')->find(1);
            $entity->addRoadType($rt);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('trip_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Trip entity.
     *
     * @Route("/{id}/edit", name="trip_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('WodorNetMotoTripBundle:Trip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trip entity.');
        }

        $editForm = $this->createForm(new TripType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Trip entity.
     *
     * @Route("/{id}/update", name="trip_update")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:Trip:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:Trip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trip entity.');
        }

        $editForm   = $this->createForm(new TripType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('trip_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Trip entity.
     *
     * @Route("/{id}/delete", name="trip_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('WodorNetMotoTripBundle:Trip')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Trip entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('trip'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
}
