<?php
/**
 * Created by PhpStorm.
 * User: manuele
 * Date: 03/05/15
 * Time: 11:27
 */

class Webgriffe_StoreRedirect_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @loadFixture testRedirect.yaml
     */
    public function testShouldNotRedirectDueToLocaleAlreadyChecked()
    {
        $this->assertEquals('en', Mage::getStoreConfig('general/locale/code', 'default'));
        $this->assertEquals('fr', Mage::getStoreConfig('general/locale/code', 'fr'));
        $this->assertEquals('de', Mage::getStoreConfig('general/locale/code', 'de'));

        $customerSessionMock = $this->getMockBuilder('Mage_Customer_Model_Session')
            ->disableOriginalConstructor()
            ->setMethods(array('hasLanguageChecked'))
            ->getMock();
        $customerSessionMock
            ->expects($this->any())
            ->method('hasLanguageChecked')
            ->will($this->returnValue(true));

        $this->replaceByMock('singleton', 'customer/session', $customerSessionMock);

        $request = new Mage_Core_Controller_Request_Http();
        $response = new Mage_Core_Controller_Response_Http();
        $action = new Mage_Core_Controller_Front_Action($request, $response);
        $event = new Varien_Event_Observer();
        $event->setControllerAction($action);

        $observer = Mage::getModel('webgriffe_storeredirect/observer');
        $observer->redirectToProperStoreView($event);

        $this->assertNoRedirect($response);
    }

    private function assertNoRedirect(Mage_Core_Controller_Response_Http $response)
    {
        $this->assertFalse($this->getLocationHeader($response));
    }

    /**
     * @param Mage_Core_Controller_Response_Http $response
     * @return array|false
     */
    private function getLocationHeader(Mage_Core_Controller_Response_Http $response)
    {
        foreach ($response->getHeaders() as $header) {
            if ($header['name'] === 'Location') {
                return $header;
            }
        }
        return false;
    }

}
