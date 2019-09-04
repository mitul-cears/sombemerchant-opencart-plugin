<?php

require_once(DIR_SYSTEM . 'library/sombemerchant/sombemerchant-php/init.php');
require_once(DIR_SYSTEM . 'library/sombemerchant/version.php');

class ControllerExtensionPaymentSombeMerchant extends Controller
{
	public function index(){
		$this->load->language('extension/payment/sombemerchant');
        $this->load->model('checkout/order');

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['action'] = $this->url->link('extension/payment/sombemerchant/checkout', '', true);

        return $this->load->view('extension/payment/sombemerchant', $data);
	}

	public function checkout()
    {
        $this->setupSombeMerchantClient();
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/sombemerchant');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $token = md5(uniqid(rand(), true));
        $description = [];

        foreach ($this->cart->getProducts() as $product) {
            $description[] = $product['quantity'] . ' × ' . $product['name'];
        }

        $amount = number_format($order_info['total'] * $this->currency->getvalue($order_info['currency_code']), 8, '.', '');
    
        $sombe_order = \SombeMerchant\Merchant\Order::create(array(
            'OrderId' => $order_info['order_id'],
            'amount' => $amount,
            'currency' => $order_info['currency_code'],
            'cancel_url' => html_entity_decode($this->url->link('extension/payment/sombemerchant/cancel', '', true)),
            'callback_url' => html_entity_decode($this->url->link('extension/payment/sombemerchant/callback', array('token' => $token), true)),
            'success_url' => html_entity_decode($this->url->link('extension/payment/sombemerchant/success', array('token' => $token), true)),
            'title' => $this->config->get('config_meta_title') . ' Order #' . $order_info['order_id'],
            'description' => join($description, ', '),
            'token' => $token
        ));

        if ($sombe_order) {
            $this->model_extension_payment_sombemerchant->addOrder(array(
                'order_id' => $order_info['order_id'],
                'token' => $token,
                'sombe_invoice_id' => $sombe_order->response['id']
            ));

            $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('sombemerchant_order_status_id'));

            $this->response->redirect($sombe_order->response['payment_url']);
        } else {
            $this->log->write("Order #" . $order_info['order_id'] . " is not valid. Please check SombeMerchant API request logs.");
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }
    }

    public function cancel()
    {
        $this->response->redirect($this->url->link('checkout/cart', ''));
    }

    public function success()
    {
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/sombemerchant');

        $order = $this->model_extension_payment_sombemerchant->getOrder($this->session->data['order_id']);
        
        if (empty($order) || strcmp($order['token'], $this->request->get['token']) !== 0) {
            $this->response->redirect($this->url->link('common/home', '', true));
        } else {
            $this->response->redirect($this->url->link('checkout/success', '', true));
        }
    }

    public function callback()
    {
        $this->load->model('checkout/order');
        $this->load->model('extension/payment/sombemerchant');
        $post_data = \SombeMerchant\SombeMerchant::decrypt($this->config->get('sombemerchant_api_secret'),$this->request->post['encData']);
        $post_data = json_decode($post_data,true);

        $order_id = $post_data['OrderId'];
        $order_info = $this->model_checkout_order->getOrder($order_id);
        $ext_order = $this->model_extension_payment_sombemerchant->getOrder($order_id);

        if (!empty($order_info) && !empty($ext_order) && strcmp($ext_order['token'], $post_data['token']) === 0) {
            $this->setupSombeMerchantClient();

            $sombe_order = \SombeMerchant\Merchant\Order::find($ext_order['sombe_invoice_id']);
            if ($sombe_order) {
                switch ($sombe_order->status) {
                    case 'Completed':
                        $sombe_order_status = 'sombemerchant_paid_status_id';
                        break;
                    case 'Invalid':
                        $sombe_order_status = 'sombemerchant_invalid_status_id';
                        break;
                    case 'Expired':
                        $sombe_order_status = 'sombemerchant_expired_status_id';
                        break;
                    case 'Canceled':
                        $sombe_order_status = 'sombemerchant_canceled_status_id';
                        break;
                    case 'Refunded':
                        $sombe_order_status = 'sombemerchant_refunded_status_id';
                        break;
                    default:
                        $sombe_order_status = NULL;
                }

                if (!is_null($sombe_order_status)) {
                    $this->model_checkout_order->addOrderHistory($order_id, $this->config->get($sombe_order_status));
                }
            }
        }

        $this->response->addHeader('HTTP/1.1 200 OK');
    }

    private function setupSombeMerchantClient()
    {
        \SombeMerchant\SombeMerchant::config(array(
            'auth_token' => empty($this->config->get('sombemerchant_api_auth_token')) ? $this->config->get('sombemerchant_api_secret') : $this->config->get('sombemerchant_api_auth_token'),
            'user_agent' => 'SombeMerchant - OpenCart v' . VERSION . ' Extension v' . sombeCOIN_OPENCART_EXTENSION_VERSION
        ));
    }
}