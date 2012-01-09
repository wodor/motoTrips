<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WodorNet\MotoTripBundle\Entity\TripSignup;
use WodorNet\MotoTripBundle\Entity\Trip;
use WodorNet\MotoTripBundle\Form\TripSignupType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * TripSignup controller.
 *
 * @Route("/tripsignup")
 */
class TripSignupController extends Controller
{


    /**
     * Creates a new TripSignup entity.
     *
     * @Route("/signup/{tripId}", name="signup")
     * @Template("WodorNetMotoTripBundle:TripSignup:usersignup.html.twig")
     */
    public function signupAction($tripId)
    {

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $entity = new TripSignup();

        $user = $this->get('security.context')->getToken()->getUser();
        $trip = current($this->getDoctrine()->getEntitymanager()->getRepository('WodorNetMotoTripBundle:Trip')->findById($tripId));

        if (!$trip instanceof Trip) {
            return $this->redirect($this->generateUrl('tripsignup_new'));
        }

        $entity->setTrip($trip);
        $entity->setUser($user);
        $entity->setSignupType('join');

        $request = $this->getRequest();
        $form = $this->createForm(new TripSignupType(), $entity);

        if ($request->getMethod() === "POST") {
            $form->bindRequest($request);

            if ($form->isValid()) {

                $entity->setSignupDate(new \DateTime());

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('tripsignup_show', array('id' => $entity->getId())));

            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }


    /**
     * Lists all TripSignup entities.
     *
     * @Route("/", name="tripsignup")
     * @Template()
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
