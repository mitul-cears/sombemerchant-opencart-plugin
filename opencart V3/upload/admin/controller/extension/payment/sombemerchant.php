<?php

require_once(DIR_SYSTEM . 'library/sombemerchant/sombemerchant-php/init.php');

class ControllerExtensionPaymentSombeMerchant extends Controller
{
    private $error = array();

    public function index(){
        $this->load->language('extension/payment/sombemerchant');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');

        if($this->request->server['REQUEST_METHOD'] == "POST" && $this->validate()){
            $this->model_setting_setting->editSetting('payment_sombemerchant', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        $data['action']             = $this->url->link('extension/payment/sombemerchant', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel']             = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
        $data['order_statuses']     = $this->model_localisation_order_status->getOrderStatuses();
        $data['geo_zones']          = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/sombemerchant', 'user_token=' . $this->session->data['user_token'], true)
        );

        $fields = array(
            'payment_sombemerchant_status', 
            'payment_sombemerchant_api_auth_token',
            'payment_sombemerchant_api_secret',
            'payment_sombemerchant_pending_status_id',
            'payment_sombemerchant_paid_status_id',
            'payment_sombemerchant_invalid_status_id',
            'payment_sombemerchant_expired_status_id',
            'payment_sombemerchant_canceled_status_id',
            'payment_sombemerchant_refunded_status_id',
            'payment_sombemerchant_total',
            'payment_sombemerchant_geo_zone_id',
        );


        foreach ($fields as $field) {
          if (isset($this->request->post[$field])) {
                $data[$field] = $this->request->post[$field];
            } else {
                $data[$field] = $this->config->get($field);
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/sombemerchant', $data));
    }

    public function validate(){
        if (!$this->user->hasPermission('modify', 'extension/payment/sombemerchant')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!class_exists('\SombeMerchant\SombeMerchant')) {
          $this->error['warning'] = $this->language->get('error_composer');
        }

        if (!$this->error) {
            $testConnection = \SombeMerchant\SombeMerchant::testConnection(array(
                'auth_token'    => $this->request->post['payment_sombemerchant_api_auth_token'],
                ),
                ['enc_key'    => $this->request->post['payment_sombemerchant_api_secret']]
            );

          if ($testConnection !== true) {
            $this->error['warning'] = $testConnection;
        }
    }

    return !$this->error;
    }

    public function install(){
        $this->load->model('extension/payment/sombemerchant');
        $this->model_extension_payment_sombemerchant->install();
    }
    
    public function uninstall(){
        $this->load->model('extension/payment/sombemerchant');
        $this->model_extension_payment_sombemerchant->uninstall();
    }
}
