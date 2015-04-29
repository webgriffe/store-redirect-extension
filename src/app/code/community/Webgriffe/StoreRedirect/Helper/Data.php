<?php

class Webgriffe_StoreRedirect_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Checks if current request's user-agent is skippable.
     *
     * @return bool
     */
    public function isSkippableUserAgent()
    {
        $currentUserAgent = Mage::helper('core/http')->getHttpUserAgent();

        $userAgents = array(
            'PayPal IPN',
            'Bingbot',
            'Adidxbot',
            'MSNBot',
            'BingPreview',
            'Googlebot',
            'YahooSeeker',
            'Slurp',
            'Dotbot',
            'facebookexternalhit'
        );

        foreach ($userAgents as $userAgent) {
            if (stripos($currentUserAgent, $userAgent) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isStoreCookieSet()
    {
        return (bool)Mage::app()->getCookie()->get(Mage_Core_Model_Store::COOKIE_NAME);
    }

    /**
     * @return bool
     */
    public function isLocaleAlreadyChecked()
    {
        return $this->_getSession()->hasLanguageChecked();
    }

    /**
     * Sets that locale has checked during the current session.
     */
    public function setLocaleChecked()
    {
        $this->_getSession()->setLanguageChecked(true);
    }

    /**
     * Get frontend customer session
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
}
