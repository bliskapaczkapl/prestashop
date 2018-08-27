<?php

// @codingStandardsIgnoreFile

/**
 * @property Order $object
 * @SuppressWarnings(PHPMD)
 */
class AdminOrdersController extends AdminOrdersControllerCore
{
    public $toolbar_title;

    protected $statuses_array = array();

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->_where = 'AND number is not null AND number != \'\'';

        $this->bulk_actions = array(
            'getReport' => array('text' => $this->l('Get Report'), 'icon' => 'icon-download-alt')
        );

        parent::__construct();
    }

    /**
     * Init page header yoolbar
     */
    public function initPageHeaderToolbar()
    {
        AdminController::initPageHeaderToolbar();

        unset($this->toolbar_btn['new']);
    }

    /**
     * Mass action get report for choosen orders
     */
    public function processBulkGetReport()
    {
        if (Tools::isSubmit('submitBulkgetReportorder')) {
            /* We need call autoloader */
            Module::getInstanceByName('bliskapaczka');

            $numbers = '';

            foreach (Tools::getValue('orderBox') as $id_order) {
                $order = new Order($id_order);

                if ($numbers && $order->number) {
                    $numbers .= ',' . $order->number;
                } else {
                    $numbers = $order->number;
                }
            }

            /* @var Bliskapaczka\Prestashop\Core\Helper $bliskapaczkaHelper */
            $bliskapaczkaHelper = new Bliskapaczka\Prestashop\Core\Helper();

            /* @var $apiClient \Bliskapaczka\ApiClient\Bliskapaczka\Order */
            $apiClient = $bliskapaczkaHelper->getApiClientReport();
            $apiClient->setNumbers($numbers);

            try {
                $content = $apiClient->get();
            } catch (Exception $e) {
                $this->errors[] = Tools::displayError('The report file has not been downloaded. ' . $e->getMessage());
            }

            if ($content) {
                header("Content-Type: application/pdf");
                header("Content-Disposition: inline; filename=filename.pdf");
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                echo $content;
                exit();
            }
        }

    }

    /**
     * Render orders list
     */
    public function renderView()
    {
        parent::renderView();

        $tpl_file = _PS_MODULE_DIR_.'bliskapaczka/override/views/admin/bliskaorders/view.tpl';

        $tpl = $this->context->smarty->createTemplate($tpl_file, $this->context->smarty);

        $tpl->assign($this->tpl_view_vars);

        return $tpl->fetch();
    }

    /**
     * Post process
     */
    public function postProcess()
    {
        // If id_order is sent, we instanciate a new Order object
        if (Tools::isSubmit('id_order') && Tools::getValue('id_order') > 0) {
            $order = new Order(Tools::getValue('id_order'));
            if (!Validate::isLoadedObject($order)) {
                $this->errors[] = Tools::displayError('The order cannot be found within your database.');
            }
            ShopUrl::cacheMainDomainForShop((int)$order->id_shop);
        }

        $adminController = $this->createAdminController();
        if (Tools::isSubmit('bliskaCancel') && isset($order)) {
            try {
                $adminController->bliskaCancelAction();
            } catch (Exception $exception) {
                $this->errors[] = Tools::displayError($exception->getMessage());
            }


        } elseif (Tools::isSubmit('bliskaAdvice') && isset($order)) {
            try {
                $adminController->bliskaAdviceAction();
            } catch (Exception $exception) {
                $this->errors[] = Tools::displayError($exception->getMessage());
            } catch (CarrierNameException $carrierNameException) {
                $this->errors[] = Tools::displayError($carrierNameException->getMessage());
                parent::postProcess();
                return;
            }

        } elseif (Tools::isSubmit('bliskaUpdate') && isset($order)) {
            try {
                $adminController->bliskaUpdateAction();
            } catch (Exception $exception) {
                $this->errors[] = Tools::displayError($exception->getMessage());
            }


        } elseif (Tools::isSubmit('bliskaWaybill') && isset($order)) {
            try {
                $adminController->bliskaWaybillAction();
            } catch (Exception $exception) {
                $this->errors[] = Tools::displayError($exception->getMessage());
            }

        }

        parent::postProcess();
    }

    /**
     * @return \Bliskapaczka\Prestashop\Core\Controller\AdminController
     */
    protected function createAdminController()
    {
        Module::getInstanceByName('bliskapaczka');
        $order = new Order(Tools::getValue('id_order'));

        $commandBus = new \Bliskapaczka\Prestashop\Core\Command\CommandBus();
        $bliskapaczkaHelper = new Bliskapaczka\Prestashop\Core\Helper();
        $restWaybillQuery = new \Bliskapaczka\Prestashop\Core\Query\RestWaybillQuery(
            $bliskapaczkaHelper->getApiClientOrder()
        );
        $adminController = new \Bliskapaczka\Prestashop\Core\Controller\AdminController(
            $order,
            $commandBus,
            $restWaybillQuery,
            $bliskapaczkaHelper
        );
        return $adminController;
    }


}
