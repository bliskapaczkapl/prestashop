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

                // Manage Free Shipping
                // Ligic coppied from class ParentOrderControllerCore method _assignWrappingAndTOS
                $free_shipping = false;
                foreach ($this->context->cart->getCartRules() as $rule) {
                    if ($rule['free_shipping'] && !$rule['carrier_restriction']) {
                        $free_shipping = true;
                        break;
                    }
                }

                $bliskapaczkaFreeShipping = $bliskapaczkaHelper->freeShipping($free_shipping, $this->context->cart);

                $widgetGoogleMapApiKey = $bliskapaczkaHelper->getGoogleMapApiKey();
                $widgetOperators = $bliskapaczkaHelper->getOperatorsForWidget(array(), $bliskapaczkaFreeShipping);
                $testMode = Configuration::get($bliskapaczkaHelper::TEST_MODE) ? 'true' : 'false';
                $this->context->smarty->assign('widget_operators', $widgetOperators);
                $this->context->smarty->assign('widget_google_map_api_key', $widgetGoogleMapApiKey);
                $this->context->smarty->assign('test_mode', $testMode);
                $this->context->smarty->assign(
                    'id_carrier_bliskapaczka',
                    \Configuration::get($bliskapaczkaHelper::BLISKAPACZKA_CARRIER_ID)
                );
                $this->setTemplate(_PS_MODULE_DIR_ . 'bliskapaczka/override/views/front/order-carrier.tpl');
                break;
        }
    }
}

// @codingStandardsIgnoreStart
