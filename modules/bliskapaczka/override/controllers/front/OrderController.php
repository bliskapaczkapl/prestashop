<?php

// @codingStandardsIgnoreStart

/*
 * With this override, you have a new Smarty variable called "currentController" available in header.tpl
 * This allows you to use a different header if you are on a product page, category page or home.
 */
class OrderController extends OrderControllerCore
{
    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    /*
    * module: bliskapaczka
    * date: 2017-06-22 15:22:26
    * version: 1.0.0
    */
    public function initContent()
    {
        parent::initContent();
        switch ((int)$this->step) {
            case OrderController::STEP_DELIVERY:
                $bliskapaczkaHelper = new Bliskapaczka\Prestashop\Core\Hepler();
                $widgetPrices = $bliskapaczkaHelper->getPricesForWidget();
                $widgetDisabledOperators = $bliskapaczkaHelper->getDisabledOperatorsForWidget();

                $this->context->smarty->assign('widget_prices', $widgetPrices);
                $this->context->smarty->assign('widget_disabled_operators', $widgetDisabledOperators);

                $this->setTemplate(_PS_MODULE_DIR_ . 'bliskapaczka/override/views/front/order-carrier.tpl');
                break;
        }
    }
}

// @codingStandardsIgnoreStart
