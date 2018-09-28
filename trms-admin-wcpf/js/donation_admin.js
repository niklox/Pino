/* Donation Scripts */

$(function(){
	
	$("img[id^='donation_']").click(function(){
	
		donationid = $(this).attr("id").substring(9, $(this).attr("id").length);
		
		if(confirm("Do you want to remove this row with ID:" + donationid + "?  This action cannot be reversed!")){
			$.post('/trms-admin/ajax/donation_edit.php', {action: "deletedonationrow", donationid: donationid});
			$("#" + donationid).remove();
		}
	});
	
	$("td[id^='donation-opendata_']").click(function(){
	
		donationid = $(this).attr("id").substring(18, $(this).attr("id").length);
		$("#data_" + donationid).toggle();
	});
	
	
});