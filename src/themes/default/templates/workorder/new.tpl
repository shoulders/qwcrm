<!-- new.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<script>{include file="`$theme_js_dir_finc`modules/workorder.js"}</script>

<table width="100%"> 
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>                                
                    <td class="menuhead2" width="80%">{t}New Work Order for{/t} {$customer_details.display_name}</td>
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
                                            <li class="active"><a href="javascript:void(0)" rel="#tab_1_contents" class="tab">{t}Workorder Details{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_2_contents" class="tab">{t}Customer Details{/t}</a></li>
                                        </ul>

                                        <!-- This is used so the contents do not appear to the right of the tabs -->
                                        <div class="clear"></div>

                                        <!-- This is a div that hold all the tabbed contents -->
                                        <div class="tab_contents_container">

                                            <!-- Tab 1 Contents - New Work Order Form -->
                                            <div id="tab_1_contents" class="tab_contents tab_contents_active">                        
                                                
                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                                    <tr>
                                                        <td valign="top">                                                    
                                                            <form method="post" action="index.php?component=workorder&page_tpl=new" name="new_workorder" id="new_workorder"> 
                                                                
                                                                <!-- Header -->
                                                                <table class="olotable" width="100%" border="0"  cellpadding="4" cellspacing="0" summary="Work order display">
                                                                    <tr>
                                                                        <td class="olohead">{t}Opened{/t}</td>
                                                                        <td class="olohead">{t}Customer{/t}</td>
                                                                        <td class="olohead">{t}Scope{/t}</td>                                                                        
                                                                        <td class="olohead">{t}Technician{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4">{$smarty.now|date_format:$date_format}</td>
                                                                        <td class="olotd4">{$customer_details.display_name}</td>
                                                                        <td class="olotd4">
                                                                            <input id="scope" name="scope" size="40" type="text" maxlength="80" required onkeydown="return onlyAlphaNumericPunctuation(event);" onkeyup="lookupSuggestions(this.value);" onblur="closeSuggestions();">
                                                                            <div class="suggestionsBoxWrapper">
                                                                                <div class="suggestionsBox" id="suggestions">
                                                                                    <img src="{$theme_images_dir}upArrow.png" style="position: relative; top: -12px; left: 1px;" alt="upArrow" />
                                                                                    <div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
                                                                                </div>
                                                                            </div>    
                                                                        </td>                                                                        
                                                                        <td class="olotd4">{$login_display_name}</td>
                                                                    </tr>
                                                                </table>
                                                                <br>
                                                                
                                                                <!-- Description -->
                                                                <table class="olotable" width="100%" border="0">
                                                                    <tr>
                                                                        <td class="olohead">&nbsp;{t}Description{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd">
                                                                            <textarea class="olotd4 mceCheckForContent" rows="15" cols="70" name="description"></textarea>
                                                                        </td>
                                                                    </tr>
                                                                </table>                                                                                                                  
                                                                <br>
                                                                
                                                                <!-- Comments -->
                                                                <table class="olotable" width="100%" border="0">
                                                                    <tr>
                                                                        <td class="olohead">&nbsp;{t}Comments{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd"><textarea class="olotd4" rows="15" cols="70" name="comments"></textarea></td>
                                                                    </tr>
                                                                </table>
                                                                <br>
                                                                
                                                                <!-- Submit Button -->
                                                                <table width="100%" border="0">
                                                                    <tr>
                                                                        <td>                                                                            
                                                                            <input name="assign_to_employee" type="checkbox" value="1" checked>{t}Assign to the current employee{/t} ({$login_display_name}).
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input name="customer_id" value="{$customer_id}" type="hidden">
                                                                            <button type="submit" name="submit" value="submit">{t}Submit{/t}</button> 
                                                                        </td>
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
                                                                        <a href="index.php?component=customer&page_tpl=edit&amp;customer_id={$customer_details.id}"<img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" alt="" /></a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}Contact{/t}</b></td>
                                                        <td class="menutd"> {$customer_details.first_name} {$customer_details.last_name}</td>
                                                        <td class="menutd"><b>{t}Email{/t}</b></td>
                                                        <td class="menutd"> {$customer_details.email}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}First Name{/t}</b></td>
                                                        <td class="menutd">{$customer_details.first_name}</td>
                                                        <td class="menutd"><b>{t}Last Name{/t}</b>
                                                        <td class="menutd">{$customer_details.last_name}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="row2" colspan="4">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}Address{/t}</b></td>
                                                        <td class="menutd"></td>
                                                        <td class="menutd"><b>{t}Phone{/t}</b></td>
                                                        <td class="menutd">{$customer_details.primary_phone}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd" colspan="2">
                                                            {$customer_details.address|nl2br}<br>
                                                            {$customer_details.city}<br>
                                                            {$customer_details.state}<br>
                                                            {$customer_details.zip}<br>
                                                            {$customer_details.country}
                                                        </td>
                                                        <td class="menutd"><b>{t}Fax{/t}</b></td>
                                                        <td class="menutd"> {$customer_details.work_primary_phone}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"></td>
                                                        <td class="menutd"></td>
                                                        <td class="menutd"><b>{t}Mobile{/t}</b></td>
                                                        <td class="menutd">{$customer_details.mobile_phone}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="row2" colspan="4">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd"><b>{t}Type{/t}</b></td>
                                                        <td class="menutd">
                                                            {section name=s loop=$customer_types}    
                                                                {if $customer_details.type == $customer_types[s].customer_type_id}{t}{$customer_types[s].display_name}{/t}{/if}        
                                                            {/section}   
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