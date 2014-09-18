<?php
class Nrapps_AdvancedStoreUrl_Model_Template_Filter extends Mage_Widget_Model_Template_Filter
{
    /**
    * Add possibility to cross link between stores and protocols.
    *
    * Additional parameters:
    * <ul>
    * <li>store: store code or store id</li>
    * <li>current_protocol: use https if called from secure context, http otherwise (boolean)</li>
    * <li>secure: whether to use https or not, ignore current_protocol (boolean)</li>
    * </ul>
    *
    * @param array $construction
    * @return string
    */
    public function storeurlDirective($construction)
    {
        $params = $this->_getIncludeParameters($construction[2]);
         
        // the store url as parsed by the default filter
        $url = $this->storeDirective($construction);
         
        // maybe the storeurl directive is only used to get the secure base url
        if (!isset($params['store'])) {
            $params['store'] = Mage::getDesign()->getStore();
        }
         
        $secure = false;
        if (isset($params['current_protocol'])) {
            $useCurrentProtocol = (bool)$params['current_protocol'];
            $secure = $useCurrentProtocol && Mage::app()->getStore($params['store'])->isCurrentlySecure();
        }
         
        if (isset($params['secure'])) {
            $secure = (bool)$params['secure'];
        }
         
        // the base url as used by the default filter
        $currentBaseUrl = Mage::app()->getStore(Mage::getDesign()->getStore())
            ->getBaseUrl();
         
        // replace current base url by store code (secure) base url
        $storeBaseUrl = Mage::app()->getStore($params['store'])
            ->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, $secure);
         
        return str_replace($currentBaseUrl, $storeBaseUrl, $url);
    }
}