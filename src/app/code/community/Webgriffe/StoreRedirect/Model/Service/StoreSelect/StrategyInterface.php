<?php
/**
 * Created by PhpStorm.
 * User: manuele
 * Date: 29/04/15
 * Time: 20:29
 */

interface Webgriffe_StoreRedirect_Model_Service_StoreSelect_StrategyInterface
{
    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @return Mage_Core_Model_Store
     */
    public function select(Mage_Core_Controller_Request_Http $request);
}
