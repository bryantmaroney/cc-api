Initial: Save Customer and Make Sale

URL: https://api.onstatic.com/saveCustomer

REQUEST

Header: 
content-type: application/json

body:
first_name //string
last_name //string
address1 //string
city //string
state //string
country //string
zip //string
phone //string
email //string
cc //string
ccexp //string
amount //string
stored_credential_indicator: "stored"  //string

RESPONSE:
customer_id //id 
transaction Response // object

----------------------------------------------------------------

Success Transaction: Make Sale Transaction through customer vault

URL: https://api.onstatic.com/payByCustomerId

REQUEST


Header: 
content-type: application/json

body:
customer_id // string
amount // string

RESPONSE:
customer_id //id 
transaction Response// object


