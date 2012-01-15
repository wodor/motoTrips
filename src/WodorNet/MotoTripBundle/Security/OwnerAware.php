<?php
namespace WodorNet\MotoTripBundle\Security;
/**
 * Created by JetBrains PhpStorm.
 * User: wodor
 * Date: 13.01.12
 * Time: 17:04
 * To change this template use File | Settings | File Templates.
 */
interface OwnerAware
{

    public function getObjectIdentity();

    /**
     * @abstract
     * Identity must be generated from the object which is going to be the owner of the created enity
     */
    public function getSecurityIdentity();

    public function getOwnerFieldName();
}
