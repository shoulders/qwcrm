<!--
This sample code is designed to connect to Authorize.net using the AIM method.
For API documentation or additional sample code, please visit:
http://developer.authorize.net
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
  "http://www.w3.org/TR/html4/loose.dtd">
<HTML lang='en'>
<HEAD>
	<TITLE> Sample AIM Implementation </TITLE>
</HEAD>
<BODY>
<P> This sample code is designed to generate a post using Authorize.net's
Advanced Integration Method (AIM) and display the results of this post to
the screen. </P>
<P> For details on how this is accomplished, please review the readme file,
the comments in the sample code, and the Authorize.net AIM API documentation
found at http://developer.authorize.net </P>
<HR />

<?PHP

// By default, this sample code is designed to post to our test server for
// developer accounts: https://test.authorize.net/gateway/transact.dll
// for real accounts (even in test mode), please make sure that you are
// posting to: https://secure.authorize.net/gateway/transact.dll
//$post_url = "https://test.authorize.net/gateway/transact.dll?";
$post_url = "https://developer.authorize.net/param_dump.asp";
$post_values = array(
	
	// the API Login ID and Transaction Key must be replaced with valid values
	"x_login"			=> "7h47qLB222F9",
	"x_tran_key"		=> "6rWUm9Z755sz44aR",

	"x_version"			=> "3.1",
	"x_delim_data"		=> "TRUE",
	"x_delim_char"		=> "|",
	"x_relay_response"	=> "TRUE",

	"x_type"			=> "AUTH_CAPTURE",
	"x_method"			=> "CC",
	"x_card_num"		=> "4111111111111111",
	"x_exp_date"		=> "0115",

	"x_amount"			=> "19.99",
	"x_description"		=> "Sample Transaction",

	"x_first_name"		=> "John",
	"x_last_name"		=> "Doe",
	"x_address"			=> "1234 Street",
	"x_state"			=> "WA",
	"x_zip"				=> "98004"
	// Additional fields can be added here as outlined in the AIM integration
	// guide at: http://developer.authorize.net
);

// This section takes the input fields and converts them to the proper format
// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
$post_string = "";
foreach( $post_values as $key => $value )
	{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
$post_string = rtrim( $post_string, "& " );

// This sample code uses the CURL library for php to establish a connection,
// submit the post, and record the response.
// If you receive an error, you may want to ensure that you have the curl
// library enabled in your php configuration
$request = curl_init($post_url); // initiate curl object
	curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
	$post_response = curl_exec($request); // execute curl post and store results in $post_response
	// additional options may be required depending upon your server configuration
	// you can find documentation on curl options at http://www.php.net/curl_setopt
curl_close ($request); // close curl object

// This line takes the response and breaks it into an array using the specified delimiting character
$response_array = explode($post_values["x_delim_char"],$post_response);

// The results are output to the screen in the form of an html numbered list.
echo "<OL>\n";
foreach ($response_array as $value)
{
	echo "<LI>" . $value . "&nbsp;</LI>\n";
	$i++;
}
echo "</OL>\n";
// individual elements of the array could be accessed to read certain response
// fields.  For example, response_array[0] would return the Response Code,
// response_array[2] would return the Response Reason Code.
// for a list of response fields, please review the AIM Implementation Guide
?>
</BODY>
</HTML>