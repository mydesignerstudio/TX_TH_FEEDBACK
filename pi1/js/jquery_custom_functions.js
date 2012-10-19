/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/*
/*   AJAX FEEDBACK FORM
/*   (C) 10/2012 BY AKIN AJEWOLE, TYPOHOSTING.AT
/*   
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/*   AJAX FEEDBACK FORM :: FUNCTIONS     
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(document).ready(function() {

	/** FADE FORM IN **/	
	if (animate_form != 'no') {
		$('.feedback_area').mouseenter(function() {
			$('#feedback_label').fadeOut(0);
			$('#feedback_form').fadeIn(200);
		}); // end of ".mouseenter"
		
		/** FADE FORM OUT **/
		$('.feedback_area').mouseleave(function() {	
			$('#feedback_form').fadeOut(0);
			$('#feedback_label').fadeIn(200);
		}); // end of ".mouseleave"
	} // end if (animate_form)

	/** TOGGLE COMMENT FORM **/
	$('#btn_yesbut').click(function() {	
		$('#comment').fadeToggle(300,'linear');
	}); // end of ".click"	
	
	/** DELETE COMMENTS PRE-TEXT **/
	$('#textfield').focusin(function() {
         $('#textfield').val('');
    }); // end of ".focusin"
	
	/** BTN PRESSED: YES -> AJAX XHTTP SERVER REQUEST **/
	$('#btn_yes').click(function() {
		var comment = $('input[name="textfield"]').val();
		var helpful = 1;
		$.ajax({url:"typo3conf/ext/th_feedback/pi1/db_update.php",data: "comment=" + comment + "&helpful=" + helpful + "&page_id=" + page_id + "&page_title=" + page_title,type: "GET",async:false,success:function(result){
		$('#feedback_form').html(message_thanks + '<input type="button" class="hidden_button" />');
		}}); // ".ajax"
	}); // end of ".click"
	
	/** BTN PRESSED: NO -> AJAX XHTTP SERVER REQUEST **/
	$('#btn_no').click(function() {
		var comment = $('input[name="textfield"]').val();
		var helpful = 0;
		$.ajax({url:"typo3conf/ext/th_feedback/pi1/db_update.php",data: "comment=" + comment + "&helpful=" + helpful + "&page_id=" + page_id + "&page_title=" + page_title,type: "GET",async:false,success:function(result){
		$('#feedback_form').html(message_thanks + '<input type="button" class="hidden_button" />');
		}}); // ".ajax"
	}); // end of ".click"

	/** BTN PRESSED YES, BUT.. (GO!) -> AJAX XHTTP SERVER REQUEST **/
	$('#btn_go').click(function() {
		var comment = $('input[name="textfield"]').val();					
		if (comment == '') {
			$('#textfield').addClass('text_warning');
			$('input[name="textfield"]').val(message_missing);
		}
		else {
			var comment = $('input[name="textfield"]').val();
			var helpful = 2;
			$.ajax({url:"typo3conf/ext/th_feedback/pi1/db_update.php",data: "comment=" + comment + "&helpful=" + helpful + "&page_id=" + page_id + "&page_title=" + page_title,type: "GET",async:false,success:function(result){
			$('#feedback_form').html(message_thanks + '<input type="button" class="hidden_button" />');
			}}); // ".ajax"
		}
	}); // end of ".click"		
}); // end of ".ready"