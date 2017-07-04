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
