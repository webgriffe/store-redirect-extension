<?php
/**
 * Created by PhpStorm.
 * User: manuele
 * Date: 29/04/15
 * Time: 20:16
 */

class Webgriffe_StoreRedirect_Model_Observer
{
    const BROWSER_LOCALE_STRATEGY = 'webgriffe_storeredirect/service_storeSelect_browserLocaleStrategy';

    /**
     * Observed event: controller_action_predispatch
     *
     * @param Varien_Event_Observer $event
     */
    public function redirectToProperStoreView(Varien_Event_Observer $event)
    {
        $helper = Mage::helper('webgriffe_storeredirect');

        if ($helper->isLocaleAlreadyChecked() || $helper->isStoreCookieSet() || $helper->isSkippableUserAgent()) {
            return;
        }

        /** @var Webgriffe_StoreRedirect_Model_Service_StoreSelect_StrategyInterface $storeSelectStrategy */
        $storeSelectStrategy = Mage::getModel(self::BROWSER_LOCALE_STRATEGY);

        /** @var Mage_Core_Controller_Front_Action $action */
        $action = $event->getControllerAction();
        $storeView = $storeSelectStrategy->select($action->getRequest());
        $helper->setLocaleChecked();
        // generate redirect url
        $action->getResponse()->setRedirect(
            Mage::getUrl(
                '',
                array (
                    '_current' => true,
                    '_use_rewrite' => true,
                    '_store_to_url' => true,
                    '_store' => $storeView->getId()
                )
            )
        );
        $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
    }
}
