$(function() {
		
		//bind to the form's submit event 
		$('#textedit').click(function() { 
			$.post("/trms-content/ajax/contenttext_edit.php", $("#editcontenttext").serialize());
			$('#' + $('#textid').val()).html($('#textcontent').val());
		});
		
		$('#textcontent').focusout(function(){
			saveSelection("textcontent");
		});
		
		$("#tags").change(function(){
			replaceText("textcontent", $(this).val());
			$(this).attr("selectedIndex", 0);
		});
});

var len;
var start;
var end;
var sel;

function saveSelection(el){
	//alert("as");
   var textarea = document.getElementById(el);
   var replace = "";
  len = textarea.value.length;
   start = textarea.selectionStart;
   end = textarea.selectionEnd;
   sel = textarea.value.substring(start, end);
  
  //alert($("#" + el).html().selectionStart);
  // alert(start + " " + end);
}

// code for Mozilla
function replaceText(el, index){
   var textarea = document.getElementById(el);
   var replacementtext = "";
   
   switch(index){
  	case "1":
  	replacementtext = '<p>' + sel + '</p>';
  	break;
  	case "2":
  	replacementtext = '<i>' + sel + '</i>';
  	break;
  	case "3":
  	replacementtext = '<span class="cerise-anfang">' + sel + '</span>';
  	break;
  	case "4":
  	replacementtext = '<h3>' + sel + '</h3>';
  	break;
  	case "5":
  	replacementtext = '<h4 class="pino-artikelrubrik">' + sel + '</h4>';
  	break;
  	case "6":
  	replacementtext = '<a href="">' + sel + '</a>';
  	break;
  	case "7":
  	replacementtext = '<div class="topingress">' + sel + '</div>';
  	break;
  	case "8":
  	replacementtext = '<br/>';
  	break;
  	case "9":
  	replacementtext = '<span class="navyheader">' + sel + '</span>';
  	break;
  	case "10":
  	replacementtext = sel.replace(/<\/?[^>]+(>|$)/g, "");
  	break;
  
  }
  // Here we are replacing the selected text with this one
  textarea.value =  textarea.value.substring(0,start) + replacementtext + textarea.value.substring(end,len);
 }



function getInputSelection(el) {
    var start = 0, end = 0, normalizedValue, range,
        textInputRange, len, endRange;
        
    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        start = el.selectionStart;
        end = el.selectionEnd;
    } else {
    	
        range = document.selection.createRange();
      

        if (range && range.parentElement() == el) {
            len = el.value.length;
            normalizedValue = el.value.replace(/\r\n/g, "\n");

            // Create a working TextRange that lives only in the input
            textInputRange = el.createTextRange();
            textInputRange.moveToBookmark(range.getBookmark());

            // Check if the start and end of the selection are at the very end
            // of the input, since moveStart/moveEnd doesn't return what we want
            // in those cases
            endRange = el.createTextRange();
            endRange.collapse(false);

            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                start = end = len;
            } else {
                start = -textInputRange.moveStart("character", -len);
                start += normalizedValue.slice(0, start).split("\n").length - 1;

                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    end = len;
                } else {
                    end = -textInputRange.moveEnd("character", -len);
                    end += normalizedValue.slice(0, end).split("\n").length - 1;
                }
            }
        }
    }

    return {
        start: start,
        end: end
    };
}

function replaceSelectedText(el, text) {
  //alert(el + " " + text);
  
    var sel = getInputSelection(el); //val = el.value;
    
   // alert(sel.start);
    //el.value = val.slice(0, sel.start) + text + val.slice(sel.end);
}

var el = document.getElementById("textcontent");
//replaceSelectedText(el, "[NEW TEXT]");
