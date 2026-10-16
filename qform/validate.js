

function addcity(id)
{
	if(document.getElementById('LL_destination').value==document.getElementById('LL_destination').defaultValue)
		document.getElementById('LL_destination').value='';
	var cid = document.getElementById(id);
	if(cid.checked)
	{
		document.getElementById('LL_destination').value += cid.value + ',';
	}
	else
	{
		//alert("unchecked");
		var totval=document.getElementById('LL_destination').value;
		document.getElementById('LL_destination').value = totval.replace((cid.value + ','),"");
	}
 //if(this.checked) {javascript: document.getElementById('LL_destination').value += this.value+','; } else { }
}

var datePickerDivID = "datepicker";
var iFrameDivID = "datepickeriframe";
var dayArrayShort = new Array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
var dayArrayMed = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var dayArrayLong = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthArrayShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var monthArrayMed = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
var monthArrayLong = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
var defaultDateSeparator = "/";        // common values would be "/" or "."
var defaultDateFormat = "mdy"    // valid values are "mdy", "dmy", and "ymd"
var dateSeparator = defaultDateSeparator;
var dateFormat = defaultDateFormat;


function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
  if (!displayBelowThisObject)
    displayBelowThisObject = targetDateField;
   // if a date separator character was given, update the dateSeparator variable
  if (dtSep)
    dateSeparator = dtSep;
  else
    dateSeparator = defaultDateSeparator;
 
  // if a date format was given, update the dateFormat variable
  if (dtFormat)
    dateFormat = dtFormat;
  else
    dateFormat = defaultDateFormat;
 
  var x = displayBelowThisObject.offsetLeft;
  var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;
 
  
  var parent = displayBelowThisObject;
  while (parent.offsetParent) {
    parent = parent.offsetParent;
    x += parent.offsetLeft;
    y += parent.offsetTop ;
  }
 
  drawDatePicker(targetDateField, x, y);
}


function drawDatePicker(targetDateField, x, y)
{
  var dt = getFieldDate(targetDateField.value );
 
  
  if (!document.getElementById(datePickerDivID)) {
    
    var newNode = document.createElement("div");
    newNode.setAttribute("id", datePickerDivID);
    newNode.setAttribute("class", "dpDiv");
    newNode.setAttribute("style", "visibility: hidden;");
    document.body.appendChild(newNode);
  }
 
  // move the datepicker div to the proper x,y coordinate and toggle the visiblity
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.position = "absolute";
  pickerDiv.style.left = x-20 + "px";
  pickerDiv.style.top = y + "px";
  pickerDiv.style.visibility = (pickerDiv.style.visibility == "visible" ? "hidden" : "visible");
  pickerDiv.style.display = (pickerDiv.style.display == "block" ? "none" : "block");
  pickerDiv.style.zIndex = 10000;
 
  // draw the datepicker table
  refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());
}

function refreshDatePicker(dateFieldName, year, month, day)
{
  // if no arguments are passed, use today's date; otherwise, month and year
  // are required (if a day is passed, it will be highlighted later)
  var thisDay = new Date();
 
  if ((month >= 0) && (year > 0)) {
    thisDay = new Date(year, month, 1);
  } else {
    day = thisDay.getDate();
    thisDay.setDate(1);
  }
 
 
  var crlf = "\r\n";
  var TABLE = "<table cols=7 class='dpTable'>" + crlf;
  var xTABLE = "</table>" + crlf;
  var TR = "<tr class='dpTR'>";
  var TR_title = "<tr class='dpTitleTR'>";
  var TR_days = "<tr class='dpDayTR'>";
  var TR_todaybutton = "<tr class='dpTodayButtonTR'>";
  var xTR = "</tr>" + crlf;
  var TD = "<td class='dpTD' onMouseOut='this.className=\"dpTD\";' onMouseOver=' this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var TD_title = "<td colspan=5 class='dpTitleTD'>";
  var TD_buttons = "<td class='dpButtonTD'>";
  var TD_todaybutton = "<td colspan=7 class='dpTodayButtonTD'>";
  var TD_days = "<td class='dpDayTD'>";
  var TD_selected = "<td class='dpDayHighlightTD' onMouseOut='this.className=\"dpDayHighlightTD\";' onMouseOver='this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var xTD = "</td>" + crlf;
  var DIV_title = "<div class='dpTitleText'>";
  var DIV_selected = "<div class='dpDayHighlight'>";
  var xDIV = "</div>";
 
  // start generating the code for the calendar table
  var html = TABLE;

  html += TR_title;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, "&lt;") + xTD;
  html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + " " + thisDay.getFullYear() + xDIV + xTD;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, "&gt;") + xTD;
  html += xTR;
 
  // this is the row that indicates which day of the week we're on
  html += TR_days;
  for(i = 0; i < dayArrayShort.length; i++)
    html += TD_days + dayArrayShort[i] + xTD;
  html += xTR;
 
  // now we'll start populating the table with days of the month
  html += TR;
 
  // first, the leading blanks
  for (i = 0; i < thisDay.getDay(); i++)
    html += TD + "&nbsp;" + xTD;
 
  // now, the days of the month
  do {
    dayNum = thisDay.getDate();
    TD_onclick = " onclick=\"updateDateField('" + dateFieldName + "', '" + getDateString(thisDay) + "');\">";
    
    if (dayNum == day)
      html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;
    else
      html += TD + TD_onclick + dayNum + xTD;
    
    // if this is a Saturday, start a new row
    if (thisDay.getDay() == 6)
      html += xTR + TR;
    
    // increment the day
    thisDay.setDate(thisDay.getDate() + 1);
  } while (thisDay.getDate() > 1)
 
  // fill in any trailing blanks
  if (thisDay.getDay() > 0) {
    for (i = 6; i > thisDay.getDay(); i--)
      html += TD + "&nbsp;" + xTD;
  }
  html += xTR;
 
  // add a button to allow the user to easily return to today, or close the calendar
  var today = new Date();
  var todayString = "Today is " + dayArrayMed[today.getDay()] + ", " + monthArrayMed[ today.getMonth()] + " " + today.getDate();
  html += TR_todaybutton + TD_todaybutton;
  html += "<button class='dpTodayButton' onClick='refreshDatePicker(\"" + dateFieldName + "\");'>this month</button> ";
  html += "<button class='dpTodayButton' onClick='updateDateField(\"" + dateFieldName + "\");'>close</button>";
  html += xTD + xTR;
 
  // and finally, close the table
  html += xTABLE;
 
  document.getElementById(datePickerDivID).innerHTML = html;
  // add an "iFrame shim" to allow the datepicker to display above selection lists
  adjustiFrame();
}



function getButtonCode(dateFieldName, dateVal, adjust, label)
{
  var newMonth = (dateVal.getMonth () + adjust) % 12;
  var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);
  if (newMonth < 0) {
    newMonth += 12;
    newYear += -1;
  }
 
  return "<button class='dpButton' onClick='refreshDatePicker(\"" + dateFieldName + "\", " + newYear + ", " + newMonth + ");'>" + label + "</button>";
}


function getDateString(dateVal)
{
  var dayString = "00" + dateVal.getDate();
  var monthString = "00" + (dateVal.getMonth()+1);
  dayString = dayString.substring(dayString.length - 2);
  monthString = monthString.substring(monthString.length - 2);
 
  switch (dateFormat) {
    case "dmy" :
      return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
    case "ymd" :
      return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;
    case "mdy" :
    default :
      return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();
  }
}


/**
Convert a string to a JavaScript Date object.
*/
function getFieldDate(dateString)
{
  var dateVal;
  var dArray;
  var d, m, y;
 
  try {
    dArray = splitDateString(dateString);
    if (dArray) {
      switch (dateFormat) {
        case "dmy" :
          d = parseInt(dArray[0], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
        case "ymd" :
          d = parseInt(dArray[2], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[0], 10);
          break;
        case "mdy" :
        default :
          d = parseInt(dArray[1], 10);
          m = parseInt(dArray[0], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
      }
      dateVal = new Date(y, m, d);
    } else if (dateString) {
      dateVal = new Date(dateString);
    } else {
      dateVal = new Date();
    }
  } catch(e) {
    dateVal = new Date();
  }
 
  return dateVal;
}


function splitDateString(dateString)
{
  var dArray;
  if (dateString.indexOf("/") >= 0)
    dArray = dateString.split("/");
  else if (dateString.indexOf(".") >= 0)
    dArray = dateString.split(".");
  else if (dateString.indexOf("-") >= 0)
    dArray = dateString.split("-");
  else if (dateString.indexOf("\\") >= 0)
    dArray = dateString.split("\\");
  else
    dArray = false;
 
  return dArray;
}

function updateDateField(dateFieldName, dateString)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
  if (dateString)
    targetDateField.value = dateString;
 
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.visibility = "hidden";
  pickerDiv.style.display = "none";
 
  adjustiFrame();
  targetDateField.focus();
 
  if ((dateString) && (typeof(datePickerClosed) == "function"))
    datePickerClosed(targetDateField);
}


function adjustiFrame(pickerDiv, iFrameDiv)
{
  
  var is_opera = (navigator.userAgent.toLowerCase().indexOf("opera") != -1);
  if (is_opera)
    return;
  
 
  try {
    if (!document.getElementById(iFrameDivID)) {
      
      var newNode = document.createElement("iFrame");
      newNode.setAttribute("id", iFrameDivID);
      newNode.setAttribute("src", "javascript:false;");
      newNode.setAttribute("scrolling", "no");
      newNode.setAttribute ("frameborder", "0");
      document.body.appendChild(newNode);
    }
    
    if (!pickerDiv)
      pickerDiv = document.getElementById(datePickerDivID);
    if (!iFrameDiv)
      iFrameDiv = document.getElementById(iFrameDivID);
    
    try {
      iFrameDiv.style.position = "absolute";
      iFrameDiv.style.width = pickerDiv.offsetWidth;
      iFrameDiv.style.height = pickerDiv.offsetHeight ;
      iFrameDiv.style.top = pickerDiv.style.top;
      iFrameDiv.style.left = pickerDiv.style.left;
      iFrameDiv.style.zIndex = pickerDiv.style.zIndex - 1;
      iFrameDiv.style.visibility = pickerDiv.style.visibility ;
      iFrameDiv.style.display = pickerDiv.style.display;
    } catch(e) {
    }
 
  } catch (ee) {
  }
 
}
function code(val)
{
 var ccode=val.split("_");
 document.getElementById('ccode').value="+"+ ccode[1];
}
// function validation()
// {
//     var LL_duration=document.getElementById("LL_duration");
//     var LL_arrival=document.getElementById("LL_arrival");
//     //var LL_destination=document.getElementById("LL_destination");
//     var LL_adult=document.getElementById("LL_adult");
//     //var LL_tourstyle=document.getElementById("LL_tourstyle");
//     var LL_requirements=document.getElementById("LL_requirements");
//     var LL_name=document.getElementById("LL_name");
//     var LL_fromcountry=document.getElementById("LL_fromcountry");
//     var LL_email=document.getElementById("LL_email");
//     //var LL_yourcity=document.getElementById("LL_yourcity");
//     //var LL_phone=document.getElementById("LL_phone");
       
//     if(LL_duration.value==LL_duration.defaultValue || LL_duration.value=="")
//     {
//         alert("No. of Days should not be left blank");
//         return false;
//     }
//     if(LL_arrival.value==LL_arrival.defaultValue || LL_arrival.value=="")
//     {
//         alert("Arrival should not be left blank");
//         return false;
//     }
//     /*if(LL_destination.value==LL_destination.defaultValue || LL_destination.value=="")
//     {
//         alert("Destination should not be left blank");
//         return false;
//     }*/
//     if(LL_adult.value==LL_adult.defaultValue || LL_adult.value=="")
//     {
//         alert("How many People");
//         return false;
//     }
//     /*if(LL_tourstyle.value==LL_tourstyle.defaultValue || LL_tourstyle.value=="")
//     {
//         alert("Tour style should not be left blank");
//         return false;
//     }*/
//     if(LL_requirements.value==LL_requirements.defaultValue || LL_requirements.value=="")
//     {
//         alert("Your requirement please");
//         return false;
//     }
//     if(LL_name.value==LL_name.defaultValue || LL_name.value=="")
//     {
//         alert("Your name please");
//         return false;
//     }
//     if(LL_fromcountry.value==LL_fromcountry.defaultValue || LL_fromcountry.value=="")
//     {
//         alert("Please select Your Country");
//         return false;
//     }
//     if(LL_email.value==LL_email.defaultValue || LL_email.value=="")
//     {
//         alert("Email should not be left blank");
//         return false;
//     }
   
    
//}
function validation()
{
    // Clear previous error messages
    var errorContainer = document.getElementById("form-error-msg");
    if(errorContainer) {
        errorContainer.remove();
    }
    
    var errors = [];
    
    // Get form fields
    var LL_duration = document.getElementById("LL_duration");
    var LL_arrival = document.getElementById("LL_arrival");
    var LL_adult = document.getElementById("LL_adult");
    var LL_requirements = document.getElementById("LL_requirements");
    var LL_name = document.getElementById("LL_name");
    var LL_fromcountry = document.getElementById("LL_fromcountry");
    var LL_email = document.getElementById("LL_email");
    
    // Reset borders
    var allFields = [LL_duration, LL_arrival, LL_adult, LL_requirements, LL_name, LL_fromcountry, LL_email];
    allFields.forEach(function(field) {
        if(field) field.style.border = "";
    });
    
    // Validate Duration
    if(!LL_duration || LL_duration.value == LL_duration.defaultValue || LL_duration.value == "") {
        errors.push("No. of Days should not be left blank.");
        if(LL_duration) LL_duration.style.border = "1px solid #dc3545";
    }
    
    // Validate Arrival Date
    if(!LL_arrival || LL_arrival.value == LL_arrival.defaultValue || LL_arrival.value == "") {
        errors.push("Arrival date should not be left blank.");
        if(LL_arrival) LL_arrival.style.border = "1px solid #dc3545";
    }
    
    // Validate Adults
    if(!LL_adult || LL_adult.value == LL_adult.defaultValue || LL_adult.value == "") {
        errors.push("Please select number of adults.");
        if(LL_adult) LL_adult.style.border = "1px solid #dc3545";
    }
    
    // Validate Requirements
    if(!LL_requirements || LL_requirements.value == LL_requirements.defaultValue || LL_requirements.value == "") {
        errors.push("Your requirement please.");
        if(LL_requirements) LL_requirements.style.border = "1px solid #dc3545";
    }
    
    // Validate Name
    if(!LL_name || LL_name.value == LL_name.defaultValue || LL_name.value == "") {
        errors.push("Your name please.");
        if(LL_name) LL_name.style.border = "1px solid #dc3545";
    }
    
    // Validate Country
    if(!LL_fromcountry || LL_fromcountry.value == LL_fromcountry.defaultValue || LL_fromcountry.value == "") {
        errors.push("Please select your country.");
        if(LL_fromcountry) LL_fromcountry.style.border = "1px solid #dc3545";
    }
    
    // Validate Email
    if(!LL_email || LL_email.value == LL_email.defaultValue || LL_email.value == "") {
        errors.push("Email should not be left blank.");
        if(LL_email) LL_email.style.border = "1px solid #dc3545";
    } else if(LL_email && !isValidEmail(LL_email.value)) {
        errors.push("Please enter a valid email address.");
        if(LL_email) LL_email.style.border = "1px solid #dc3545";
    }
    
    // Check Google reCAPTCHA
    if(typeof grecaptcha !== 'undefined') {
        var recaptcha_response = grecaptcha.getResponse();
        if(recaptcha_response.length === 0) {
            errors.push("Please verify that you are not a robot.");
            var recaptchaDiv = document.querySelector('.g-recaptcha');
            if(recaptchaDiv) {
                recaptchaDiv.style.border = "1px solid #dc3545";
                recaptchaDiv.style.padding = "2px";
                recaptchaDiv.style.borderRadius = "4px";
            }
        } else {
            var recaptchaDiv = document.querySelector('.g-recaptcha');
            if(recaptchaDiv) recaptchaDiv.style.border = "";
        }
    }
    
    // If errors exist, show them
    if(errors.length > 0) {
        // Find the form container
        var formContainer = document.querySelector('.holidayz, .appointmentform, form');
        if(formContainer) {
            var errorDiv = document.createElement('div');
            errorDiv.id = "form-error-msg";
            errorDiv.style.cssText = "background:#f8d7da; color:#721c24; padding:12px; margin-bottom:15px; border-radius:5px; border-left:4px solid #dc3545; font-size:14px;";
            errorDiv.innerHTML = "❌ Please fix the following errors:<br><br> • " + errors.join("<br> • ");
            formContainer.insertBefore(errorDiv, formContainer.firstChild);
            
            // Scroll to error message
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        return false;
    }
    
    return true;
}

// Email validation helper function
function isValidEmail(email) {
    var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return pattern.test(email);
}

// Clear red border when user starts typing
function setupErrorClear() {
    var fieldIds = ["LL_duration", "LL_arrival", "LL_adult", "LL_requirements", "LL_name", "LL_fromcountry", "LL_email"];
    fieldIds.forEach(function(id) {
        var field = document.getElementById(id);
        if(field) {
            field.addEventListener("input", function() {
                this.style.border = "";
                var errorContainer = document.getElementById("form-error-msg");
                if(errorContainer) {
                    errorContainer.style.display = "none";
                }
            });
            field.addEventListener("focus", function() {
                this.style.border = "";
            });
            field.addEventListener("change", function() {
                this.style.border = "";
            });
        }
    });
    
    // Clear reCAPTCHA border on click
    var recaptchaDiv = document.querySelector('.g-recaptcha');
    if(recaptchaDiv) {
        recaptchaDiv.addEventListener("click", function() {
            this.style.border = "";
            this.style.padding = "";
        });
    }
}

// Call setup after form loads
setTimeout(setupErrorClear, 100);