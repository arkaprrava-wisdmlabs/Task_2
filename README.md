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
	1. Check for whether woocommerce plugin installed activated or not
	2. add order item meta to the order
	3. Check if the User is logged in or not
	4. Hook into the “woocommerce_review_order_before_submit” to add input fields in checkout page
	5. Use woocommerce_form_field to add those fields, using the default value as the latest
	value of the respective usermeta given.
	6. Hook into the “woocommerce_checkout_process” to get the values given, when the user 
	completes the checkout process and update the respective usermeta of the currently logged in user accordingly
	7. Hook into the: 
		a. “woocommerce_thankyou” in order received thank you page
		b. “woocommerce_admin_order_details_after_order_details” in order edit page in admin panel
		c. “woocommerce_after_cart_table” in woocommerce cart page
		d. “woocommerce_view_order” in single order page in my account page of woocommerce
	to show the usermeta values in the front end.

