Problem Statement:
Create a plugin dependent on WooCommerce that will achieve the following:


Add the following fields to the checkout page:
How did you hear about us? - Textbox
Preferable mode of communication - Dropdown with "Email", "Call" and "Message" as options
The same fields with the selected values during checkout should show up on the following pages:
Order Thank you page for the user
Single order page under "My Account" for the user
The Edit order page - For the admin under the billing section

Approach:
	
1. Check for whether woocommerce plugin installed activated or not
2. Add user meta for hear about us and mode of communication fields, before that check for the user meta already exists or not in the activation hook
