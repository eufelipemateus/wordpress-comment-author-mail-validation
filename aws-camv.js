/**
 * Awebsome! Comment Author Mail Validation
 * 
 * Basically moves the plugin option to the "Before a comment appears" fields section
 */
jQuery().ready(function()
{	
	var label = jQuery('.aws_camv');
	var camv = label.parent().parent();
	var aws = camv.prev().prev().prev().children('td').children('fieldset');
	
	aws.append('<br />' + label.parent().html());
	camv.remove(); 
});