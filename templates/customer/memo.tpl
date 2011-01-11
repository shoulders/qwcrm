<!-- template name -->
{literal}
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
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		flash_external_list_url : "example_flash_list.js",
		file_browser_callback : "fileBrowserCallBack",
		width : "100%"
	});
</script>
{/literal}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">New Memo</td>
				</tr><tr>
					<td class="menutd2">
						{if $error_msg != ""}
							{include file="core/error.tpl"}
						{/if}
						<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
							<tr>
								<td width="100%" valign="top" >
									<!-- Work Order Notes -->
									
									<form  action="index.php?page=customer:memo" method="POST" >
									
									<input type="hidden" name="customer_id" value="{$customer_id}">
									<table class="olotable" width="100%" border="0" >
										<tr>
											<td class="olohead"></td>
										</tr><tr>
											<td class="olotd"><textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="memo"></textarea></td>
										</tr>
									</table>
									<br>
									<input class="olotd4" name="submit" value="submit" type="submit" />	
									</form>
									<br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
	