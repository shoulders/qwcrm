<!-- Add New Work Order tpl -->{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
mode : "specific_textareas",
theme : "advanced",
plugins : "advlink,iespell,insertdatetime,preview",
theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
theme_advanced_buttons2_add_before: "cut,copy,paste",
theme_advanced_toolbar_location : "bottom",
theme_advanced_toolbar_align : "center", 
plugin_insertdate_dateFormat : "%Y-%m-%d",
plugin_insertdate_timeFormat : "%H:%M:%S",
extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
width : "100%"
}); 
</script>
<script type="text/javascript">
//<![CDATA[
function validate_new_workorder(frm) {
var value = '';
var errFlag = new Array();
var _qfGroups = {};
_qfMsg = '';
value = frm.elements['scope'].value;
if (value == '' && !errFlag['scope']) {
errFlag['scope'] = true;
_qfMsg = _qfMsg + '\n - Please enter the  Work Order Scope';
frm.elements['scope'].className = 'error';
}
value = frm.elements['scope'].value;
if (value != '' && value.length > 40 && !errFlag['scope']) {
errFlag['scope'] = true;
_qfMsg = _qfMsg + '\n - The Work Order Scope cannot be more than 20 characters';
frm.elements['scope'].className = 'error';
}
if (_qfMsg != '') {
_qfMsg = 'Invalid information entered.' + _qfMsg;
_qfMsg = _qfMsg + '\nPlease correct these fields.';
alert(_qfMsg);
return false;
}
return true;
}
//]]>
</script>
{/literal}
<table width="100%" border="0" cellpadding="20" cellspacing="0">	
<tr>		<td>
<!-- Begin Page -->			
<table width="700" cellpadding="5" cellspacing="0" border="0" >				
<tr>					
<td class="menuhead2" width="80%">{$translate_workorder_new_title}</td>					
<td class="menuhead2" size="10%" align="right">

</tr>
<tr>					
<td class="menutd2" colspan="2">					{if $error_msg != ""}						{include file="core/error.tpl"}					{/if} 					
<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >						
<tr>							
<td width="100%" valign="top" >								
<!-- Content Here -->								
<!-- Display Customer Contact Infromation -->								{section name=i loop=$customer_details}					 								
<table class="olotable" border="0" cellpadding="2" cellspacing="0" width="100%" summary="Customer Contact">									
<tr>										
<td class="olohead" colspan="4">											
<table width="100%">												
<tr>													
<td class="menuhead2" width="80%">{$translate_workorder_cutomer_contact_title}</td>													
<td class="menuhead2" width="20%" align="right">														
<a href="?page=customer:edit&customer_id={$customer_details[i].CUSTOMER_ID}"<img src="images/icons/16x16/small_edit.gif" border="0" ></a>														</a>													</td>
</tr>											
</table>										</td>									
</tr>
<tr>						 										
<td class="menutd"><b>{$translate_workorder_contact}</b></td>										
<td class="menutd"> {$customer_details[i].CUSTOMER_FIRST_NAME} {$customer_details[i].CUSTOMER_LAST_NAME}</td>										
<td class="menutd"><b>{$translate_workorder_email}</b></td>										
<td class="menutd"> {$customer_details[i].CUSTOMER_EMAIL}</td>									
</tr>
<tr>										
<td class="menutd"><b>{$translate_workorder_customer_first}</b></td>										
<td class="menutd">{$customer_details[i].CUSTOMER_FIRST_NAME}</td>										
<td class="menutd"><b>{$translate_workorder_customer_last}</b>										
<td class="menutd">{$customer_details[i].CUSTOMER_LAST_NAME}</td>									
</tr>
<tr>										
<td class="row2" colspan="4">&nbsp;</td>									
</tr>
<tr>										
<td class="menutd"><b>{$translate_workorder_address}</b></td>										
<td class="menutd"></td>										
<td class="menutd"><b>{$translate_workorder_phone_1}</b></td>										
<td class="menutd">{$customer_details[i].CUSTOMER_PHONE}</td>									
</tr>
<tr>								 										
<td class="menutd" colspan="2">{$customer_details[i].CUSTOMER_ADDRESS}</td>			 										
<td class="menutd"><b>{$translate_workorder_phone_2}</b></td>										
<td class="menutd"> {$customer_details[i].CUSTOMER_WORK_PHONE}</td>									
</tr>
<tr>										
<td class="menutd"> {$customer_details[i].CUSTOMER_CITY},</td>										
<td class="menutd">{$customer_details[i].CUSTOMER_STATE} {$customer_details[i].CUSTOMER_ZIP}</td>										
<td class="menutd"><b>{$translate_workorder_phone_3}</b></td>										
<td class="menutd"> {$customer_details[i].CUSTOMER_MOBILE_PHONE}</td>									
</tr>
<tr>										
<td class="row2" colspan="4">&nbsp;</td>									
</tr>
<tr>										
<td class="menutd"><b>{$translate_workorder_type}</b></td>										
<td class="menutd">											 {if $customer_details[i].CUSTOMER_TYPE ==1}													{$translate_workorder_type_1}												{/if}												{if $customer_details[i].CUSTOMER_TYPE ==2}													{$translate_workorder_type_2}												{/if}												{if $customer_details[i].CUSTOMER_TYPE ==3}													{$translate_workorder_type_3}												{/if}												{if $customer_details[i].CUSTOMER_TYPE ==4}													{$translate_workorder_type_4}												{/if} 										</td>										
<td class="menutd"></td>										
<td class="menutd"></td>									
</tr>
<tr>										
<td class="row2" colspan="4">&nbsp;</td>									
</tr>	 								
</table>	 								
<br>								{$form.javascript} 								
<!-- New Work Order Form -->								{literal} 								
<form method="POST"  action="index.php?page=workorder:new" name="new_workorder" id="new_workorder" onsubmit="try { var myValidator = validate_new_workorder; } catch(e) { return true; } return myValidator(this);">								{/literal} 								
<input type="hidden" name="customer_ID" value="{$customer_details[i].CUSTOMER_ID}">								
<input type="hidden" name="page" value="workorder:new">								
<input type="hidden" name="create_by" value="{$login_id}">								
<table class="olotable" width="100%" border="0"  cellpadding="4" cellspacing="0" summary="Work order display">							
<tr>								
<td class="olohead">{$translate_workorder_opened}</td>								
<td class="olohead">{$translate_workorder_customer}</td>								
<td class="olohead">{$translate_workorder_scope}</td>								
<td class="olohead">{$translate_workorder_status}</td>								
<td class="olohead">{$translate_workorder_tech}</td>							
</tr>
<tr>								
<td class="olotd4">{$smarty.now|date_format:"$date_format"}</td>
<input type="hidden" name="date"  id="date" value="{$smarty.now|date_format:"$date_format"}"/>								</td>
<td class="olotd4">{$customer_details[i].CUSTOMER_DISPLAY_NAME}</td>								
<td class="olotd4">
<input class="olotd4" size="30" name="scope" type="text"></td>
<td class="olotd4">{$translate_workorder_created}</td>								
<td class="olotd4">{$display_login}</td>								 							
</tr>						
</table>						
<br>						
<!-- Display Work Order Discription -->						
<table class="olotable" width="100%" border="0" summary="Work order display">							
<tr>								
<td class="olohead">{$translate_workorder_description_title}</td>							
</tr>
<tr>								
<td class="olotd">
<textarea  class="olotd4" rows="15" cols="70" mce_editable="true" name="work_order_discription"></textarea></td>							
</tr>						
</table>						
<br>						
<input type="submit" name="submit" value="{$translate_workorder_submit}"/>
<!--<input type="submit" name="email" value="Email"/> -->
<br>            
<br>						
<table class="olotable" width="100%" border="0" summary="Work order display">							
<tr>								
<td class="olohead">{$translate_workorder_comments_title}</td>							
</tr>
<tr>								
<td class="olotd">
<textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="work_order_comments"></textarea></td>							
</tr>						
</table>	 						
<br>						
<input type="submit" name="submit" value="{$translate_workorder_submit}"/>            
<br>            
<br>						
<!-- Work Order Notes -->						
<table class="olotable" width="100%" border="0" summary="Work order display">							
<tr>								
<td class="olohead">{$translate_workorder_notes}</td>							
</tr>
<tr>								
<td class="olotd">
<textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="work_order_notes"></textarea></td>							
</tr>						
</table>						
<br>						
<input type="submit" name="submit" value="{$translate_workorder_submit}"/>

</form>						{/section}	 						
<br>					</td>				
</tr>			
</table>		</td>	
</tr>
</table>							</td>						
</tr>					
</table>					</td>				
</tr>			
</table>		</td>	
</tr>
</table></td>																															