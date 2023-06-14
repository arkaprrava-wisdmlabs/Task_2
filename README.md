## Problem Statement:
<p>Create a plugin dependent on WooCommerce that will achieve the following:</p>
<p>Add the following fields to the checkout page:</p>
	<ol>
		<li>How did you hear about us? - Textbox
		<li>Preferable mode of communication - Dropdown with 
			<ol>
				<li>"Email",</li> <li>"Call",</li><li>"Message"</li>
			</ol>
			as options
		</li>
	</ol>
	<p>The same fields with the selected values during checkout should show up on the following pages:<p>
		<ol>
			<li>Order Thank you page for the user</li>
			<li>Single order page under "My Account" for the user</li>
			<li>The Edit order page - For the admin under the billing section</li>
		</ol>


## Approach:
<ol>
	<li>Check for whether woocommerce plugin installed activated or not</li>
	<li>Hook into <strong>'admin_init'</strong> to show notice when dependency is no active and the plugin tries to activate</li>
	<li>Hook into <strong>'woocommerce_review_order_before_submit'</strong> to add input fields for order item meta</li>
	<li>Use <strong>woocommerce_form_field</strong> to add input fields</li>
	<li>Check if the user logged in or not</li>
	<li>If logged in, Check for the last order item meta field values</li>
	<li>If order meta present, put it as default value of the fields</li>
	<li>Hook into <strong>'woocommerce_checkout_order_processed'</strong> to get input fields and save order item meta</li>
	<li>Hook into <strong>'woocommerce_after_cart_table'</strong> to show the last order item meta in WooCommerce Cart Page, if the user<br> is logged in, otherwise, shows nothing</li>
	<li>Hook into <strong>'woocommerce_order_details_after_customer_details'</strong> to show the last order item meta in WooCommerce order recieved thankyou page and in Woocommerce single order page in Woocommerce My Account page, if the user is logged in, otherwise, shows nothing</li>
	<li>Hook into <strong>'woocommerce_admin_order_data_after_billing_address'</strong> to show every order item meta in the edit order page of admin panel backend</li>
</ol>