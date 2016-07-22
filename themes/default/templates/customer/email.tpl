<!-- Customer Email TPL -->
{literal}
<script language="javascript" type="text/javascript" src="includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "style,spellchecker,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",

    // Theme options
    theme_advanced_buttons1 : "mybutton,|,save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,spellchecker",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo",
    theme_advanced_buttons3 : "emotions,iespell,media,advhr,|,print,preview,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime",
    //theme_advanced_buttons4 : "spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    //theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
       // plugins : "advlink,iespell,preview,inlinepopups",
        //theme_advanced_buttons1 : "mybutton",
        //theme_advanced_buttons2_add : "separator,preview,separator,forecolor,backcolor",
        //theme_advanced_buttons2_add_before: "cut,copy,paste",
        //theme_advanced_toolbar_location : "bottom",
        //theme_advanced_toolbar_align : "center",
        //extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        //width : "100%",
        setup : function(ed) {
        // Add a custom button
        ed.addButton('mybutton', {
            title : 'Insert Customer Name',
            //name : 'Customer Name',
            image : '{$theme_images_dir}icons/customers.gif',
            onclick : function() {
                // Add you own code to execute something on click
                ed.focus();
                ed.selection.setContent('{name}');
            }
        });
    }
    });
</script>
{/literal}
<br>
<table width="100%">
       <tr>
        <td>
            <div id="tabs_container">
                <ul class="tabs">
                    <li class="active"><a href="#" rel="#tab_1_contents" class="tab">New Email</a></li>
                    <li><a href="#" rel="#tab_2_contents" class="tab">Past Emails</a></li>
                </ul>

                <!-- This is used so the contents don't appear to the right of the tabs -->
                <div class="clear"></div>

                <!-- This is a div that hold all the tabbed contents -->
                <div class="tab_contents_container">
                    <!-- Tab 1 Contents -->
                    <div id="tab_1_contents" class="tab_contents tab_contents_active">
                        <table width="100%" border="0" cellpadding="5" cellspacing="5">
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>{section name=i loop=$customer_details}
                                            <td class="menuhead2" width="80%">
                                                &nbsp;Send Email to {$customer_details[i].CUSTOMER_DISPLAY_NAME}
                                                                       </td>
                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="?page=customer:edit&customer_id={$customer_details[i].CUSTOMER_ID}&page_title=Edit%20Customer%20Information" target="new"><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0"> Edit</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2" colspan="2">
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="menutd"> {if $error_msg != ""}
                                                            <br> {include file="core/error.tpl"}
                                                            <br> {/if}
                                                            <!-- Content -->
                                                            <form  action="index.php?page=customer:email" method="POST" enctype="multipart/form-data" >
                                                                <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                                                    <tr>
                                                                        <td class="menutd" align="right">
                                                                            <b>From:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="text" name="" value="{$employee_details.EMPLOYEE_FIRST_NAME} {$employee_details.EMPLOYEE_LAST_NAME}  <{$employee_details.EMPLOYEE_EMAIL}>" size="60" readonly>
                                                                            <input type="hidden" name="email_from" value="{$employee_details.EMPLOYEE_EMAIL}" size="60" readonly>
                                                                            <input type="hidden" name="email_server2" value="{$email_server}" size="60" readonly>
                                                                            <input type="hidden" name="email_server_port2" value="{$email_server_port}" size="60" readonly>
                                                                            <input type="hidden" name="c2" value="{$customer_details[i].CUSTOMER_ID}" size="3" readonly>
                                                                            <input type="hidden" name="cus_name" value="{$customer_details[i].CUSTOMER_DISPLAY_NAME}" size="3" readonly>
                                                                            </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd" align="right">
                                                                            <b>To:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="text" name="" value="{$customer_details[i].CUSTOMER_FIRST_NAME} {$customer_details[i].CUSTOMER_LAST_NAME}  <{$customer_details[i].CUSTOMER_EMAIL}>" size="60" readonly>
                                                                            <input type="hidden" name="email_to" value="{$customer_details[i].CUSTOMER_EMAIL}" size="60" readonly>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd" align="right">
                                                                            <b>Subject:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="text" name="email_subject" value="" size="60">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="menutd" align="right">
                                                                            <p></p>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd" align="right" valign="top">
                                                                            <b>Message:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <textarea name="message_body" rows="15" cols="60" dir="ltr" >
                                                                            </textarea>
                                                                        </td>
                                                                    </tr>
                                                                    <!--<tr>
                                                                        <td class="menutd" align="right" valign="top">
                                                                            <b>BCC:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="checkbox" name="bcc">
                                                                        </td>
                                                                    </tr> -->
                                                                    <!--TODO: Set read Receipts for sent emails
                                                                    <tr>
                                                                        <td class="menutd" align="right" valign="top">
                                                                            <b>Read Receipt?</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="checkbox" name="rr" value="1">
                                                                        </td>
                                                                    </tr>-->
                                                                    <tr>
                                                                        <td class="menutd" align="right" valign="top">
                                                                            <b>Priority:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <select class="olotd5" name="priority">
                                                                                <option value="1">Low</option>
                                                                                <option value="2" SELECTED>Normal</option>
                                                                                <option value="3">High</option>
                                                                            </select>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd" align="right" valign="top">
                                                                            <b>Attachment 1:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                            <input type="file" name="attachment1" size="50" id="attachment1">
                                                                        </td>
                                                                    </tr>
                                                                    <!--<tr>
                                                                        <td class="menutd" align="right" valign="top">
                                                                            <b>Attachment 2:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                            <input type="file" name="attachment2" size="50" id="attachment2">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd" align="right" valign="top">
                                                                            <b>Attachment 3:</b>
                                                                        </td>
                                                                        <td class="menutd" colspan="2">
                                                                            <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                            <input type="file" name="attachment3" size="50" id="attachment3">
                                                                        </td>
                                                                    </tr> -->
                                                                    <tr>
                                                                        <td>
                                                                            <input type="submit" name="submit" id="submit" value="Send" >
                                                                        </td>
                                                                    </tr>

                                                                    {assign var="customer_id" value=$customer_details[i].CUSTOMER_ID} {assign var="customer_name" value=$customer_details[i].CUSTOMER_DISPLAY_NAME}
                                                                </table>
                                                                {/section}
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tab 2 Contents -->
                    <div id="tab_2_contents" class="tab_contents"><br>
                        <b>Emails</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr align="center">
                                <td class="olohead">
                                    Email ID</td>
                                <td class="olohead">
                                    Date Sent</td>
                                <td class="olohead">
                                    Emailed To</td>
                                <td class="olohead">
                                    Subject</td>
                                <td class="olohead">
                                    Sent By</td>
                                <td class="olohead">
                                    Sent From Email</td>
                                <td class="olohead">
                                    Attachment1</td>
                                <!--
                                <td class="olohead">
                                    Attachment2</td>
                                <td class="olohead">
                                    Attachment3</td>
                                -->
                            </tr> {section name=z loop=$customer_emails}
                            <tr align="center">
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_EMAIL_ID}</td>
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_EMAIL_SENT_ON|date_format:"$date_format %H:%M"}</td>
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_EMAIL_ADDRESS}</td>
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_EMAIL_SUBJECT} </td>
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_EMAIL_SENT_BY}</td>
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_FROM_EMAIL_ADDRESS}</td>
                                <td class="olotd4">
                                    <a href="?page=customer:email&amp;download_id={$customer_emails[z].CUSTOMER_EMAIL_ID}">{$customer_emails[z].CUSTOMER_EMAIL_ATT_NAME1}</a></td>
                                <!--
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_EMAIL_ATT_NAME2}</td>
                                <td class="olotd4">
                                    {$customer_emails[z].CUSTOMER_EMAIL_ATT_NAME3}</td>
                                -->
                            </tr> {/section}
                        </table>                        
                    </div>   
                </div>
            </div>
        </td>
    </tr>
</table>
