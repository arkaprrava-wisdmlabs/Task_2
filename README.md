	Problem Statement:
		Create a plugin dependent on WooCommerce that will achieve the following:
			Add the following fields to the checkout page:
				How did you hear about us? - Textbox
				Preferable mode of communication - Dropdown with "Email", "Call" and "Message" 
				as options
				The same fields with the selected values during checkout should show up on the 
				following pages:
					Order Thank you page for the user
					Single order page under "My Account" for the user
					The Edit order page - For the admin under the billing section


	Approach:
		1. Check for whether woocommerce plugin installed activated or not
		2. Add user meta for hear about us and mode of communication fields, before that 
		check for the user meta already exists or not in the activation hook
		3. Check if the User is logged in or not
		4. Hook into the “woocommerce_review_order_before_submit” to add input fields in checkout page
		5. Use woocommerce_form_field to add those fields, using the default value as the latest
		value of the respective usermeta given.
		6. Hook into the “woocommerce_checkout_process” to get the values given, when the user 
		completes the checkout process and
		update the respective usermeta of the currently logged in user accordingly
		7. Hook into the: 
		    a. “woocommerce_thankyou” in order received thank you page
		    b. “woocommerce_admin_order_details_after_order_details” in order edit page in admin panel
		    c. “woocommerce_after_cart_table” in woocommerce cart page
		    d. “woocommerce_view_order” in single order page in my account page of woocommerce
		to show the usermeta values in the front end.

