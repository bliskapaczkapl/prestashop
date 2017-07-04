<?php
require_once 'Bliskapaczka/Prestashop/Core.php';

use Bliskapaczka\Prestashop\Core\Config;

/**
 * Bliskapaczka shipping module
 */
class Bliskapaczka extends CarrierModule
{
    public $id_carrier;
    protected $html = '';
    private $config = null;

    public function __construct()
    {
        $this->config = new Bliskapaczka\Prestashop\Core\Config();

        $this->name = $this->config->name;
        $this->tab = $this->config->tab;
        $this->version = $this->config->version;
        $this->author = 'Mateusz Koszutowski';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l($this->config->displayName);
        $this->description = $this->l($this->config->description);

        $this->confirmUninstall = $this->l($this->config->confirmUninstall);
        $this->limited_countries =$this->config->limitedCountries;

        if (!Configuration::get('bliskapaczka')) {
            $this->warning = $this->l('No name provided');
        }
    }

    /**
     * Instalation proccess
     */
    public function install()
    {
        $installer = new Bliskapaczka\Prestashop\Core\Installer($this->config);

        if (parent::install() == false ||
            $installer->install() == false ||
            $this->registerHook('actionCarrierUpdate') == false ||
            $this->registerHook('header') == false ||
            $this->registerHook('actionCarrierProcess') == false ||
            $this->registerHook('actionValidateOrder') == false
        ) {
            return false;
        }

        return true;
    }

    /**
     * Uninstalation proccess
     */
    public function uninstall()
    {
        $installer = new Bliskapaczka\Prestashop\Core\Installer($this->config);

        if (parent::uninstall() == false ||
            $installer->uninstall($this->id_carrier) == false
        ) {
            return false;
        }
    }

    /**
     * Add javascripts and csss to header
     *
     * @param array $params
     */
    public function hookHeader($params)
    {
        if (get_class($this->context->controller) == 'OrderController') {
            $this->context->controller->addJs('http://widget.bliskapaczka.pl/v1.1/main.js');
            $this->context->controller->addCSS('http://widget.bliskapaczka.pl/v1.1/main.css');
            $this->context->controller->addJs($this->_path . 'views/js/' . $this->name . '.js');
        }
    }

    public function hookActionCarrierUpdate($params)
    {
        $id_carrier_old = (int)($params['id_carrier']);
        $id_carrier_new = (int)($params['carrier']->id);
    }

    /**
     * Update cart if setted carrier is bliskapaczka
     *
     * @param array $params
     */
    public function hookActionCarrierProcess($params)
    {
        $saveCart = false;
        $cart = $params['cart'];
        $posCode = Tools::getValue('bliskapaczka_posCode');
        $posOperator = Tools::getValue('bliskapaczka_posOperator');

        if ($cart->pos_code != $posCode) {
            $cart->pos_code = $posCode;
            $saveCart = true;
        }

        if ($cart->pos_operator != $posOperator) {
            $cart->pos_operator = $posOperator;
            $saveCart = true;
        }
        
        if ($saveCart == true) {
            $cart->save();
        }
    }

    /**
     * Update cart if setted carrier is bliskapaczka
     *
     * @param array $params
     */
    public function hookActionValidateOrder($params)
    {
        $saveOrder = false;
        $cart = $params['cart'];
        $order = $params['order'];

        if ($cart->pos_code != $order->pos_code) {
            $order->pos_code = $cart->pos_code;
            $saveOrder = true;
        }

        if ($cart->pos_operator != $order->pos_operator) {
            $order->pos_operator = $cart->pos_operator;
            $saveOrder = true;
        }
        
        if ($saveOrder == true) {
            $order->save();
        }

        /* @var Sendit_Bliskapaczka_Helper_Data $mapper */
        $mapper = new Bliskapaczka\Prestashop\Core\Mapper\Order();
        $data = $mapper->getData($order);

        /* @var $senditHelper Sendit_Bliskapaczka_Helper_Data */
        $bliskapaczkaHelper = new Bliskapaczka\Prestashop\Core\Hepler();
        /* @var $apiClient \Bliskapaczka\ApiClient\Bliskapaczka */
        $apiClient = $bliskapaczkaHelper->getApiClient();
        $apiClient->createOrder($data);
    }

    /**
     * Get shipping cost for order. Shipping cost depends on operator. If operator isn't setted method retur lowest cost
     *
     * @param Cart $cart
     * @param float shipping_cost
     * @return float
     */
    public function getOrderShippingCost($cart, $shipping_cost)
    {
        $bliskapaczkaHelper = new Bliskapaczka\Prestashop\Core\Hepler();
        /* @var $apiClient \Bliskapaczka\ApiClient\Bliskapaczka */
        $apiClient = $bliskapaczkaHelper->getApiClient();
        $priceList = $apiClient->getPricing(
            array("parcel" => array('dimensions' => $bliskapaczkaHelper->getParcelDimensions()))
        );

        // Fix for shipping price on step payment on checkout
        $posOperator = Tools::getValue('bliskapaczka_posOperator');
        if ($posOperator) {
            $cart->pos_operator = $posOperator;
        }

        if ($cart->pos_operator) {
            $shippingPrice = round(
                $bliskapaczkaHelper->getPriceForCarrier(
                    json_decode($priceList),
                    $cart->pos_operator,
                    false
                ),
                2
            );
        } else {
            $shippingPrice = round($bliskapaczkaHelper->getLowestPrice(json_decode($priceList), false), 2);
        }

        return $shippingPrice;
    }

    public function getOrderShippingCostExternal($params)
    {
        // return 7.0;
    }

    protected function postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue(
                Bliskapaczka\Prestashop\Core\Hepler::API_KEY,
                Tools::getValue(Bliskapaczka\Prestashop\Core\Hepler::API_KEY)
            );
            Configuration::updateValue(
                Bliskapaczka\Prestashop\Core\Hepler::TEST_MODE,
                Tools::getValue(Bliskapaczka\Prestashop\Core\Hepler::TEST_MODE)
            );
            Configuration::updateValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_X,
                Tools::getValue(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_X)
            );
            Configuration::updateValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Y,
                Tools::getValue(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Y)
            );
            Configuration::updateValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Z,
                Tools::getValue(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Z)
            );
            Configuration::updateValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_WEIGHT,
                Tools::getValue(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_WEIGHT)
            );
        }

        $this->html .= $this->displayConfirmation($this->l('Settings updated'));
    }

    public function getContent()
    {
        $this->html = '';

        if (Tools::isSubmit('btnSubmit'))
        {
            $this->postProcess();
        } else {
            $this->html .= '<br />';
        }

        $this->html .= $this->renderForm();

        return $this->html;
    }

    public function renderForm()
    {
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fieldsForm = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configure'),
                    'icon' => 'icon-user'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('API Key'),
                        'name' => 'BLISKAPACZKA_API_KEY',
                        'required' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Test mode enabled'),
                        'name' => Bliskapaczka\Prestashop\Core\Hepler::TEST_MODE,
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Fixed parce type size X (cm)'),
                        'name' => Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_X
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Fixed parce type size Y (cm)'),
                        'name' => Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Y
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Fixed parce type size Z (cm)'),
                        'name' => Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Z
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Fixed parce type weight (kg)'),
                        'name' => Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_WEIGHT
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        
        // Land
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) .
            '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fieldsForm));
    }

    public function getConfigFieldsValues()
    {
        return array(
            Bliskapaczka\Prestashop\Core\Hepler::API_KEY => Tools::getValue(
                Bliskapaczka\Prestashop\Core\Hepler::API_KEY,
                Configuration::get(Bliskapaczka\Prestashop\Core\Hepler::API_KEY)
            ),
            Bliskapaczka\Prestashop\Core\Hepler::TEST_MODE => Tools::getValue(
                Bliskapaczka\Prestashop\Core\Hepler::TEST_MODE,
                Configuration::get(Bliskapaczka\Prestashop\Core\Hepler::TEST_MODE)
            ),
            Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_X => Tools::getValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_X,
                Configuration::get(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_X)
            ),
            Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Y => Tools::getValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Y,
                Configuration::get(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Y)
            ),
            Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Z => Tools::getValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Z,
                Configuration::get(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_Z)
            ),
            Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_WEIGHT => Tools::getValue(
                Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_WEIGHT,
                Configuration::get(Bliskapaczka\Prestashop\Core\Hepler::SIZE_TYPE_FIXED_SIZE_WEIGHT)
            ),
        );
    }
}
