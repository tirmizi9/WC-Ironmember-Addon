<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-04-01
 * Time: 2:10 PM
 */
if (class_exists('WC_Payment_Order_Inquiry')) return;
class WC_Payment_Order_Inquiry extends WC_Payment_Gateway
{
	public function __construct()
	{
		$this->id = 'wc_order_inquiry';
		$this->method_title = 'Order Inquiry';
		$this->title = 'Order Inquiry';
		$this->has_fields = false;
		$this->method_description = 'Allows customers to submit an order inquiry';

		//load the settings
		$this->init_form_fields();
		$this->init_settings();
		$this->enabled = $this->get_option('enabled');
		$this->title = $this->get_option( 'title' );
		$this->description = $this->get_option('description');

		//process settings with parent method
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	public function init_form_fields(){
		$this->form_fields = array(
			'enabled' => array(
				'title'         => 'Enable/Disable',
				'type'          => 'checkbox',
				'label'         => 'Enable Order Inquiry',
				'default'       => 'yes'
			),
			'title' => array(
				'title'         => 'Method Title',
				'type'          => 'text',
				'description'   => 'This controls the payment method title',
				'default'       => 'Order Inquiry',
				'desc_tip'      => true,
			),
			'description' => array(
				'title'         => 'Customer Message',
				'type'          => 'textarea',
				'css'           => 'width:500px;',
				'default'       => 'Submit an order inquiry and we will contact you about your order.  No payment is nessecary.',
				'description'   => 'The message which you want it to appear to the customer in the checkout page.',
			)
		);
	}

	function process_payment( $order_id ) {
		global $woocommerce;

		$order = new WC_Order( $order_id );

		$order->update_status('order-inquiry');

		//once the order is updated clear the cart and reduce the stock
		$woocommerce->cart->empty_cart();

		//if the payment processing was successful, return an array with result as success and redirect to the order-received/thank you page.
		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		);
	}

	//this function lets you add fields that can collect payment information in the checkout page like card details and pass it on to your payment gateway API through the process_payment function defined above.

	public function payment_fields(){
		?>
		<fieldset>
			<p class="form-row form-row-wide">
				<?php echo esc_attr($this->description); ?>
			</p>
			<div class="clear"></div>
		</fieldset>
		<?php
	}
}