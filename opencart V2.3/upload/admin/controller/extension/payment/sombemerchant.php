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
        
        $titles = array('heading_title','text_sombemerchant','text_extension','text_success','text_test_mode_on','text_test_mode_off','entry_status','entry_api_auth_token','entry_api_secret','entry_total','entry_geo_zone','entry_sort_order','entry_pending_status','entry_paid_status','entry_invalid_status','entry_expired_status','entry_canceled_status','entry_refunded_status','help_total','error_permission','error_composer','edit_text','text_all_zones','text_disabled','text_enabled');
        
        foreach($titles as $title){
            $data[$title] = $this->language->get($title);
        }
        
        if($this->request->server['REQUEST_METHOD'] == "POST" && $this->validate()){
            $this->model_setting_setting->editSetting('sombemerchant', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', $this->config->get('config_secure')));
        }
        
        $data['action']             = $this->url->link('extension/payment/sombemerchant', 'token=' . $this->session->data['token'], $this->config->get('config_secure'));
        $data['cancel']             = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', $this->config->get('config_secure'));
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
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], $this->config->get('config_secure'))
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=payment', $this->config->get('config_secure'))
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/sombemerchant', 'token=' . $this->session->data['token'], $this->config->get('config_secure'))
        );

        $fields = array(
            'sombemerchant_status', 
            'sombemerchant_api_auth_token',
            'sombemerchant_api_secret',
            'sombemerchant_pending_status_id',
            'sombemerchant_paid_status_id',
            'sombemerchant_invalid_status_id',
            'sombemerchant_expired_status_id',
            'sombemerchant_canceled_status_id',
            'sombemerchant_refunded_status_id',
            'sombemerchant_total',
            'sombemerchant_geo_zone_id',
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
                'auth_token'    => $this->request->post['sombemerchant_api_auth_token'],
                ),
                ['enc_key'    => $this->request->post['sombemerchant_api_secret']]
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
