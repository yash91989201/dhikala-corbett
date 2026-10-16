<?php
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$booking_ref_no = $_POST['booking_ref_no'];
$check_in_date = $_POST['check_in_date'];
$check_out_date = $_POST['check_out_date'];
$safari_date = $_POST['safari_date'];
$slot_of_safari = $_POST['slot_of_safari'];

$sub = "Member Details Request From Dhikala-corbett.com";

$msg .= "Name: ".$name."\r\n\r\n";
$msg .= "Email: ".$email."\r\n\r\n";
$msg .= "Contact No.: ".$phone."\r\n\r\n";
$msg .= "Sales Person Name: ".$booking_ref_no."\r\n\r\n";
/*$msg .= "Check in Date: ".$check_in_date."\r\n\r\n";
$msg .= "Check out Date: ".$check_out_date."\r\n\r\n";
$msg .= "Safari Date: ".$safari_date."\r\n\r\n";
$msg .= "Slot of Safari: ".$slot_of_safari."\r\n\r\n";
*/
$msg .= "------------------------- \r\n\r\n";
$msg .= "Visitor Details \r\n\r\n";
$msg .= "------------------------- \r\n\r\n";
//Visitor Detail 1
$msg .= "Visitor Detail - 1 \r\n\r\n";
$msg .= "Name: ".$_POST['v1_name']."\r\n\r\n";
$msg .= "Gender: ".$_POST['v1_gender']."\r\n\r\n";
$msg .= "Age: ".$_POST['v1_age']."\r\n\r\n";
$msg .= "Nationality: ".$_POST['v1_nationality']."\r\n\r\n";
$msg .= "Id Proof: ".$_POST['v1_id_proof_select']." - ".$_POST['v1_id_proof']."\r\n\r\n";

if($_POST['v2_name'] != "")
{
	$msg .= "Visitor Detail - 2 \r\n\r\n";
	$msg .= "Name: ".$_POST['v2_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v2_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v2_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v2_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v2_id_proof_select']." - ".$_POST['v2_id_proof']."\r\n\r\n";
}
if($_POST['v3_name'] != "")
{
	$msg .= "Visitor Detail - 3 \r\n\r\n";
	$msg .= "Name: ".$_POST['v3_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v3_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v3_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v3_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v3_id_proof_select']." - ".$_POST['v3_id_proof']."\r\n\r\n";
}
if($_POST['v4_name'] != "")
{
	$msg .= "Visitor Detail - 4 \r\n\r\n";
	$msg .= "Name: ".$_POST['v4_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v4_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v4_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v4_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v4_id_proof_select']." - ".$_POST['v4_id_proof']."\r\n\r\n";
}
if($_POST['v5_name'] != "")
{
	$msg .= "Visitor Detail - 5 \r\n\r\n";
	$msg .= "Name: ".$_POST['v5_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v5_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v5_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v5_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v5_id_proof_select']." - ".$_POST['v5_id_proof']."\r\n\r\n";
}
if($_POST['v6_name'] != "")
{
	$msg .= "Visitor Detail - 6 \r\n\r\n";
	$msg .= "Name: ".$_POST['v6_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v6_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v6_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v6_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v6_id_proof_select']." - ".$_POST['v6_id_proof']."\r\n\r\n";
}
if($_POST['v7_name'] != "")
{
	$msg .= "Visitor Detail - 7 \r\n\r\n";
	$msg .= "Name: ".$_POST['v7_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v7_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v7_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v7_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v7_id_proof_select']." - ".$_POST['v7_id_proof']."\r\n\r\n";
}
if($_POST['v8_name'] != "")
{
	$msg .= "Visitor Detail - 8 \r\n\r\n";
	$msg .= "Name: ".$_POST['v8_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v8_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v8_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v8_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v8_id_proof_select']." - ".$_POST['v8_id_proof']."\r\n\r\n";
}

if($_POST['v9_name'] != "")
{
	$msg .= "Visitor Detail - 9 \r\n\r\n";
	$msg .= "Name: ".$_POST['v9_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v9_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v9_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v9_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v9_id_proof_select']." - ".$_POST['v9_id_proof']."\r\n\r\n";
}
if($_POST['v10_name'] != "")
{
	$msg .= "Visitor Detail - 10 \r\n\r\n";
	$msg .= "Name: ".$_POST['v10_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v10_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v10_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v10_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v10_id_proof_select']." - ".$_POST['v10_id_proof']."\r\n\r\n";
}
if($_POST['v11_name'] != "")
{
	$msg .= "Visitor Detail - 11 \r\n\r\n";
	$msg .= "Name: ".$_POST['v11_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v11_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v11_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v11_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v11_id_proof_select']." - ".$_POST['v11_id_proof']."\r\n\r\n";
}
if($_POST['v12_name'] != "")
{
	$msg .= "Visitor Detail - 12 \r\n\r\n";
	$msg .= "Name: ".$_POST['v12_name']."\r\n\r\n";
	$msg .= "Gender: ".$_POST['v12_gender']."\r\n\r\n";
	$msg .= "Age: ".$_POST['v12_age']."\r\n\r\n";
	$msg .= "Nationality: ".$_POST['v12_nationality']."\r\n\r\n";
	$msg .= "Id Proof: ".$_POST['v12_id_proof_select']." - ".$_POST['v12_id_proof']."\r\n\r\n";
}
/*if($_POST['v1_childname'] != "")
{
	$msg .= "Children Detail - 1 \r\n\r\n";
	$msg .= "Child Name: ".$_POST['v1_childname']."\r\n\r\n";
	$msg .= "Child Age: ".$_POST['v1_childage']."\r\n\r\n";
	$msg .= "Child Nationality: ".$_POST['v1_childnationality']."\r\n\r\n";
}
if($_POST['v2_childname'] != "")
{
	$msg .= "Children Detail - 2 \r\n\r\n";
	$msg .= "Child Name: ".$_POST['v2_childname']."\r\n\r\n";
	$msg .= "Child Age: ".$_POST['v2_childage']."\r\n\r\n";
	$msg .= "Child Nationality: ".$_POST['v2_childnationality']."\r\n\r\n";
}
*/
$to = "tours@dhikala-corbett.com";
$headers = "From: ${email}\nReply-To: ${email}"."\r\n";
$headers .= "Bcc: "."\r\n";
mail($to, $sub, $msg, $headers);
header("Location: thankyou.html");
exit;

?>
