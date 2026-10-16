function validation(frm,v)
{	
		var str=frm.email.value;
		var regex = /^(\w+(?:\.\w+)*)@((?:\w+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		
		var theurl=frm.comment.value;
  		var tomatch= /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/;
	
		if(frm.name.value=="")
		{
		alert("Please Enter Name");
		frm.name.focus();
		return false;
		}
		
		if(!regex.test(str))
  		{
    	alert( "Please Enter Valid Email.");
    	frm.email.focus();
    	return false ;
  		}
		
		if(frm.country.selectedIndex==0) 
  		{
    	alert( "Please Select Country." );
    	frm.country.focus();
    	return false ;
  		}
		
		if(!isValidPhoneNumber(frm.phone.value))
		{
		alert( "Enter the Valid Phone No" );
		frm.phone.focus();
		return false;
		}
		
		if(frm.testinput.value=="")
		{
		alert("Please Enter Check In Date");
		frm.testinput.focus();
		return false;
		}
		
		if(frm.testinput2.value=="")
		{
		alert("Please Enter Check Out Date");
		frm.testinput2.focus();
		return false;
		}
		
		if(frm.rooms.selectedIndex==0) 
  		{
    	alert( "Please Select No. of Rooms." );
    	frm.rooms.focus();
    	return false ;
  		}
		
		if(frm.comment.value=="")
		{
		alert("Please Enter Comment");
		frm.comment.focus();
		return false;
		}
		
		if(tomatch.test(theurl))
     	{
		alert( "Please Enter Correct Comments" );
		frm.comment.focus();
		return false ;
     	}
		if(frm.imgcode)
		if(frm.imgcode.value=="")
		{
			alert("Please enter image code");
			frm.imgcode.focus();
			return false;
		}
		
return true;	
	

}

function isValidPhoneNumber(TheNumber) 
	{
		var valid = 1
		var GoodChars = "0123456789()-+ "
		var i = 0
		if (TheNumber=="") {
			// Return false if number is empty
			valid = 0
		}
		for (i =0; i <= TheNumber.length -1; i++) {
			if (GoodChars.indexOf(TheNumber.charAt(i)) == -1) {
				valid = 0
			} // End if statement
		} // End for loop
		return valid
	}