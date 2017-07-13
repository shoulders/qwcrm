<!-- new.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<script>{include file="`$theme_js_dir_finc`modules/workorder.js"}</script>

<table width="100%"> 
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>                                
                    <td class="menuhead2" width="80%">{t}New Work Order for{/t} {$customer_details.CUSTOMER_DISPLAY_NAME}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top">       

                                    <!-- Start of Tabs -->
                                    <div id="tabs_container">

                                        <!-- The Actual Tabs -->
                                        <ul class="tabs">
                                            <li class="active"><a href="#" rel="#tab_1_contents" class="tab">{t}Workorder Details{/t}</a></li>
                                            <li><a href="#" rel="#tab_2_contents" class="tab">{t}Customer Details{/t}</a></li>
                                        </ul>

                                        <!-- This is used so the contents don't appear to the right of the tabs -->
                                        <div class="clear"></div>

                                        <!-- This is a div that hold all the tabbed contents -->
                                        <div class="tab_contents_container">

                                            <!-- Tab 1 Contents - New Work Order Form -->
                                            <div id="tab_1_contents" class="tab_contents tab_contents_active">                        
                                                
                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                                    <tr>
                                                        <td valign="top">                                                    
                                                            <form method="POST" action="index.php?page=workorder:new" name="new_workorder" id="new_workorder">                                                    
                                                                <input name="customer_id" value="{$customer_details.CUSTOMER_ID}" type="hidden">                                                    
                                                                <input name="created_by" value="{$login_user_id}" type="hidden" >                                                    
                                                                <table class="olotable" width="100%" border="0"  cellpadding="4" cellspacing="0" summary="Work order display">
                                                                    <tr>
                                                                        <td class="olohead">{t}Opened{/t}</td>
                                                                        <td class="olohead">{t}Customer{/t}</td>
                                                                        <td class="olohead">{t}Scope{/t}</td>
                                                                        <td class="olohead">{t}Status{/t}</td>
                                                                        <td class="olohead">{t}Entered By{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4">{$smarty.now|date_format:$date_format}</td>
                                                                        <td class="olotd4">{$customer_details.CUSTOMER_DISPLAY_NAME}</td>
                                                                        <td class="olotd4">
                                                                            <input id="workorder_scope" name="workorder_scope" size="40" type="text" maxlength="80" required onkeydown="return onlyAlphaNumeric(event);" onkeyup="lookupSuggestions(this.value);" onblur="closeSuggestions();">
                                                                            <div class="suggestionsBoxWrapper">
                                                                                <div class="suggestionsBox" id="suggestions">
                                                                                    <img src="{$theme_images_dir}upArrow.png" style="position: relative; top: -12px; left: 1px;" alt="upArrow" />
                                                                                    <div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
                                                                                </div>
                                                                            </div>    
                                                                        </td>
                                                                        <td class="olotd4">{t}Created{/t}</td>
                                                                        <td class="olotd4">{$login_username}</td>
                                                                    </tr>
                                                                </table>
                                                                <br>
                                                                <!-- Display Work Order Description -->
                                                                <table class="olotable" width="100%" border="0" summary="Work order display">
                                                                    <tr>
                                                                        <td class="olohead">&nbsp;{t}Description{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd">
                                                                            <textarea class="olotd4 mceCheckForContent" rows="15" cols="70" name="workorder_description"></textarea>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <br>
                                                                <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>                                                   
                                                                <br>
                                                                <br>
                                                                <table class="olotable" width="100%" border="0" summary="Work order display">
                                                                    <tr>
                                                                        <td class="olohead">&nbsp;{t}Comments{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd"><textarea class="olotd4" rows="15" cols="70" name="workorder_comments"></textarea></td>
                                                                    </tr>
                                                                </table>
                                                                <br>                                                                                                                                                              
                                                            </form>                                                    
                                                        </td>
                                                    </tr>
                                                </table>                                                
                                                                         
                                            </div>

                                            <!-- Tab 2 Contents - Customer Details -->
                                            <div id="tab_2_contents" class="tab_contents">                                        
                                                <table class="olotable" border="0" cellpadding="2" cellspacing="0" width="80%" summary="Customer Contact">
                                                    <tr>
                                                        <td class="olohead" colspan="4">
                                                            <table width="100%">
                                                                <tr>
                                                                    <td class="menuhead2" width="80%">{t}Customer Details{/t}</td>
                                                                    <td class="menuhead2" width="20%" align="right">
                                                                        <a href="index.php?page=customer:edit&amp;customer_id={$customer_details.CUSTOMER_ID}"<img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" alt="" /></a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}Contact{/t}</b></td>
                                                        <td class="menutd"> {$customer_details.CUSTOMER_FIRST_NAME} {$customer_details.CUSTOMER_LAST_NAME}</td>
                                                        <td class="menutd"><b>{t}Email{/t}</b></td>
                                                        <td class="menutd"> {$customer_details.CUSTOMER_EMAIL}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}First Name{/t}</b></td>
                                                        <td class="menutd">{$customer_details.CUSTOMER_FIRST_NAME}</td>
                                                        <td class="menutd"><b>{t}Last Name{/t}</b>
                                                        <td class="menutd">{$customer_details.CUSTOMER_LAST_NAME}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="row2" colspan="4">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}Address{/t}</b></td>
                                                        <td class="menutd"></td>
                                                        <td class="menutd"><b>{t}Phone{/t}</b></td>
                                                        <td class="menutd">{$customer_details.CUSTOMER_PHONE}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd" colspan="2">{$customer_details.CUSTOMER_ADDRESS|nl2br}</td>
                                                        <td class="menutd"><b>{t}Fax{/t}</b></td>
                                                        <td class="menutd"> {$customer_details.CUSTOMER_WORK_PHONE}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"> {$customer_details.CUSTOMER_CITY},</td>
                                                        <td class="menutd">{$customer_details.CUSTOMER_STATE} {$customer_details.CUSTOMER_ZIP}</td>
                                                        <td class="menutd"><b>{t}Mobile{/t}</b></td>
                                                        <td class="menutd"> {$customer_details.CUSTOMER_MOBILE_PHONE}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="row2" colspan="4">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}Type{/t}</b></td>
                                                        <td class="menutd">
                                                            {if $customer_details.CUSTOMER_TYPE =='1'}{t}CUSTOMER_TYPE_1{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='2'}{t}CUSTOMER_TYPE_2{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='3'}{t}CUSTOMER_TYPE_3{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='4'}{t}CUSTOMER_TYPE_4{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='5'}{t}CUSTOMER_TYPE_5{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='6'}{t}CUSTOMER_TYPE_6{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='7'}{t}CUSTOMER_TYPE_7{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='8'}{t}CUSTOMER_TYPE_8{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='9'}{t}CUSTOMER_TYPE_9{/t}{/if}
                                                            {if $customer_details.CUSTOMER_TYPE =='10'}{t}CUSTOMER_TYPE_10{/t}{/if}
                                                        </td>
                                                        <td class="menutd"></td>
                                                        <td class="menutd"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="row2" colspan="4">&nbsp;</td>
                                                    </tr>
                                                </table>                        
                                            </div>

                                        </div>
                                                        
                                </div>
                            </td>
                        </tr>
                    </table>