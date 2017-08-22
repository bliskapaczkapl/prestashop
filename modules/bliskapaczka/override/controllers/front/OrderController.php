<?php

// @codingStandardsIgnoreStart
/**
 * Override OrderController
 */
class OrderController extends OrderControllerCore
{
    /**
    * Assign new variables to temlate. Overrive tempalte for carrier page on checkout
    */
    public function initContent()
    {
        parent::initContent();
        switch ((int)$this->step) {
            case OrderController::STEP_DELIVERY:
                $bliskapaczkaHelper = new Bliskapaczka\Prestashop\Core\Helper();
                $widgetGoogleMapApiKey = $bliskapaczkaHelper->getGoogleMapApiKey();
                $widgetOperators = $bliskapaczkaHelper->getOperatorsForWidget();

                $this->context->smarty->assign('widget_operators', $widgetOperators);
                $this->context->smarty->assign('widget_google_map_api_key', $widgetGoogleMapApiKey);

                $this->setTemplate(_PS_MODULE_DIR_ . 'bliskapaczka/override/views/front/order-carrier.tpl');
                break;
        }
    }
}

// @codingStandardsIgnoreStart
