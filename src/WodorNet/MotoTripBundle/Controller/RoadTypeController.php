<?php

namespace WodorNet\MotoTripBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WodorNet\MotoTripBundle\Entity\RoadType;
use WodorNet\MotoTripBundle\Form\RoadTypeType;

/**
 * RoadType controller.
 *
 * @Route("/roadtype")
 */
class RoadTypeController extends Controller
{
    /**
     * Lists all RoadType entities.
     *
     * @Route("/", name="roadtype")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('WodorNetMotoTripBundle:RoadType')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a RoadType entity.
     *
     * @Route("/{id}/show", name="roadtype_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:RoadType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RoadType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new RoadType entity.
     *
     * @Route("/new", name="roadtype_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new RoadType();
        $form   = $this->createForm(new RoadTypeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new RoadType entity.
     *
     * @Route("/create", name="roadtype_create")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:RoadType:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new RoadType();
        $request = $this->getRequest();
        $form    = $this->createForm(new RoadTypeType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('roadtype_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing RoadType entity.
     *
     * @Route("/{id}/edit", name="roadtype_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:RoadType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RoadType entity.');
        }

        $editForm = $this->createForm(new RoadTypeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing RoadType entity.
     *
     * @Route("/{id}/update", name="roadtype_update")
     * @Method("post")
     * @Template("WodorNetMotoTripBundle:RoadType:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WodorNetMotoTripBundle:RoadType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RoadType entity.');
        }

        $editForm   = $this->createForm(new RoadTypeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('roadtype_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a RoadType entity.
     *
     * @Route("/{id}/delete", name="roadtype_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('WodorNetMotoTripBundle:RoadType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RoadType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('roadtype'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
