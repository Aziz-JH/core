<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2012 Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://www.isotopeecommerce.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

namespace Isotope\CheckoutStep;

use Isotope\Isotope;
use Isotope\Interfaces\IsotopeCheckoutStep;
use Isotope\Interfaces\IsotopeProductCollection;


class OrderProducts extends CheckoutStep implements IsotopeCheckoutStep
{

    /**
     * Returns true to enable the module
     * @return  bool
     */
    public function isAvailable()
    {
        return true;
    }

    /**
     * Generate the checkout step
     * @return  string
     */
    public function generate()
    {
        $objTemplate = new \Isotope\Template($this->objModule->iso_collectionTpl);

        Isotope::getCart()->addToTemplate($objTemplate, $this->objModule->getProductCollectionItemsSortingCallable());

        return $objTemplate->parse();
    }

    /**
     * Cart product view does not have review information
     * @return  string
     */
    public function review()
    {
        return '';
    }

    /**
     * Return array of tokens for email templates
     * @param   IsotopeProductCollection
     * @param   \Module
     * @return  array
     */
    public function getEmailTokens(IsotopeProductCollection $objCollection, \Module $objModule)
    {
        return array(
            'items'         => $objCollection->sumItemsQuantity(),
            'products'      => $objCollection->countItems(),
            'subTotal'      => Isotope::formatPriceWithCurrency($objCollection->getSubtotal(), false),
            'grandTotal'    => Isotope::formatPriceWithCurrency($objCollection->getTotal(), false),
// @todo implement these tags using collection templates
//            'cart_text'     => strip_tags(Isotope::getInstance()->call('replaceInsertTags', $this->getProducts('iso_products_text'))),
//            'cart_html'     => Isotope::getInstance()->call('replaceInsertTags', $this->getProducts('iso_products_html'))
        );
    }
}
