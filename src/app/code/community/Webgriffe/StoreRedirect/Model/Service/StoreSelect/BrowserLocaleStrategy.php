<?php
/**
 * Created by PhpStorm.
 * User: manuele
 * Date: 29/04/15
 * Time: 20:32
 */
class Webgriffe_StoreRedirect_Model_Service_StoreSelect_BrowserLocaleStrategy
    implements Webgriffe_StoreRedirect_Model_Service_StoreSelect_StrategyInterface
{

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @return Mage_Core_Model_Store
     */
    public function select(Mage_Core_Controller_Request_Http $request)
    {
        $accpetedLocales = $this->__getSortedAcceptedLocales($request);
        $stores = Mage::app()->getStores(false, true);
        $storesByLocale = array();
        $url = NULL;
        // collect store codes
        /** @var Mage_Core_Model_Store $store */
        foreach ($stores as $store) {
            if (!$store->getIsActive()) {
                continue;
            }
            $storesByLocale[$this->__getDefaultLanguage($store)] = $store;
        }

        // loop available locales and find sutable for the browser
        foreach ($accpetedLocales as $locale => $code) {
            $localeCode = substr($locale, 0, 2);
            // if locale was found in store languages — prepare redirect url
            if (isset($storesByLocale[$localeCode])) {
                return $storesByLocale[$localeCode];
            }
        }
        return Mage::app()->getDefaultStoreView();
    }

    /**
     * Check HTTP_ACCEPT_LANGUAGE header, split locales according to the quality
     * @return array
     */
    private function __getSortedAcceptedLocales(Mage_Core_Controller_Request_Http $request)
    {
        $locales = array();
        if ($acceptLanguage = $this->__getCleanHttpAcceptLanguage($request)) {
            // break up string into pieces (languages and q factors)
            $pattern = '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i';
            preg_match_all($pattern, $acceptLanguage, $parsedLocales);
            if (count($parsedLocales[1])) {
                // create a list like "en" => 0.8
                $locales = array_combine($parsedLocales[1], $parsedLocales[4]);
                // set default to 1 for any without q factor
                foreach ($locales as $locale => $val) {
                    if ($val === '') {
                        $locales[$locale] = floatval(1);
                    } elseif (is_numeric($val)) {
                        $locales[$locale] = floatval($val);
                    } else {
                        $locales[$locale] = 0.01;
                    }
                }
            }
            if (!isset($locales[$this->__getDefaultLanguage()])) {
                $locales[$this->__getDefaultLanguage()] = 0.01;
            }
        } else {
            $locales[$this->__getDefaultLanguage()] = 1;
        }
        // sort list based on value
        arsort($locales, SORT_NUMERIC);
        return $locales;
    }

    /**
     * Return language for the store from configuration
     *
     * @param mixed $store
     * @return string
     */
    private function __getDefaultLanguage($store = null)
    {
        $locale = Mage::getStoreConfig('general/locale/code', $store);
        $lang = substr($locale, 0, 2);
        return strtolower($lang);
    }

    private function __getCleanHttpAcceptLanguage(Mage_Core_Controller_Request_Http $request)
    {
        return Mage::helper('core/string')->cleanString($request->getServer('HTTP_ACCEPT_LANGUAGE', ''));
    }
}
