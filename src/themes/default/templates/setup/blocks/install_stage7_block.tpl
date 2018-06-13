<!-- install_stage7_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="../`$theme_js_dir_finc`editor-config.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="900" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Stage 7 - Create an Administrator{/t}</td>
                    {*<td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}USER_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}USER_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>*}
                </tr>
                <tr>
                    <td class="menutd2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                    
                                    <form action="index.php?component=setup&page_tpl=install" method="post" name="new_user" id="new_user" onsubmit="return confirmPasswordsMatch();"> 
                                        <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="menutd">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                                        <tr>
                                                            <td>                                                                
                                                                <table class="olotable" width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                    
                                                                    <!-- Common -->
                                                                    
                                                                    <tr class="row2">
                                                                        <td class="menuhead" colspan="3" width="100%">&nbsp;{t}Common{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Display Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="display_name" class="olotd5" value="{$user_details.display_name}" type="text" maxlength="20" required onkeydown="return onlyName(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}First Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="first_name" class="olotd5" value="{$user_details.first_name}" type="text" maxlength="20" required onkeydown="return onlyName(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Last Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="last_name" class="olotd5" value="{$user_details.last_name}" type="text" maxlength="20" required onkeydown="return onlyName(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}User Type{/t}</strong><span style="color: #ff0000">*</span></td>                                                                                        
                                                                                        <td>
                                                                                            {if !$is_employee}
                                                                                                <span style="color: red; font-weight: 900;">{t}Customer{/t}</span>
                                                                                                <input type="hidden" name="is_employee" value="0">
                                                                                            {else}
                                                                                                <span style="color: red; font-weight: 900;">{t}Employee{/t}</span>
                                                                                                <input type="hidden" name="is_employee" value="1">
                                                                                            {/if}
                                                                                            &nbsp;-&nbsp;{t}The user type cannot be changed.{/t}
                                                                                        </td>                                                                                        
                                                                                    </tr>
                                                                                    <tr{if $is_employee} style="display: none;"{/if}>
                                                                                        <td align="right"><strong>{t}Customer{/t}</strong><span style="color: #ff0000">*</span></td>                                                                                        
                                                                                        <td>
                                                                                            {if !$is_employee}
                                                                                                <a href="index.php?component=customer&page_tpl=details&customer_id={$customer_id}">{$customer_display_name}</a>
                                                                                                <input type="hidden" name="customer_id" value="{$customer_id}">
                                                                                            {else}                                                                                                
                                                                                                <input type="hidden" name="customer_id" value="">
                                                                                            {/if}
                                                                                        </td>                                                                                        
                                                                                    </tr>
                                                                                    <tr{if !$is_employee} style="display: none;"{/if}>
                                                                                        <td align="right"><strong>{t}Based{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td>
                                                                                            <select name="based" class="olotd5">                                                                                                    
                                                                                                <option value="1" {if $user_details.based == 1 } selected{/if}>{t}Office{/t}</option>
                                                                                                <option value="2" {if $user_details.based == 2 } selected{/if}>{t}Home{/t}</option>
                                                                                                <option value="3" {if $user_details.based == 3 } selected{/if}>{t}OnSite{/t}</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Account -->
                                                                    
                                                                    <tr class="row2">
                                                                        <td class="menuhead" colspan="3" width="100%">&nbsp;{t}Account{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Email{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="email" class="olotd5" size="50" value="{$user_details.email}" type="email" maxlength="50" required onkeydown="return onlyEmail(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Username{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="username" class="olotd5" value="{$user_details.username}" type="text" maxlength="20" required onkeydown="return onlyUsername(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Password{/t}</strong></td>
                                                                                        <td><input id="password" name="password" class="olotd5" type="password" minlength="8" maxlength="20" required onkeydown="return onlyPassword(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Confirm Password{/t}</strong></td>
                                                                                        <td>
                                                                                            <input id="confirmPassword" name="confirmPassword" class="olotd5" type="password" minlength="8" maxlength="20" onkeyup="checkPasswordsMatch('{t}Passwords Match!{/t}', '{t}Passwords Do Not Match!{/t}');" onkeydown="onlyPassword(event);">
                                                                                            <div id="passwordMessage" style="min-height: 5px;"></div>
                                                                                        </td>
                                                                                    </tr>                                                                                    
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Usergroup{/t}</strong></td>
                                                                                        <td>                                                                                                
                                                                                            {*<select name="usergroup" class="olotd5">
                                                                                                {section name=b loop=$usergroups}
                                                                                                    <option value="{$usergroups[b].usergroup_id}" {if $user_details.usergroup == $usergroups[b].usergroup_id} selected{/if}>{$usergroups[b].usergroup_display_name}</option>
                                                                                                {/section}
                                                                                            </select>*}      
                                                                                            <input type="hidden" name="usergroup" value="1">
                                                                                            {t}Administrator{/t}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="1" align="right"><b>{t}Status{/t}</b></td>
                                                                                        <td>
                                                                                            {*<select name="status" class="olotd5">
                                                                                                <option value="0" {if $user_details.active == '0'} selected {/if}>{t}Blocked{/t}</option>
                                                                                                <option value="1" {if $user_details.active == '1'} selected {/if}>{t}Active{/t}</option>
                                                                                            </select>*}
                                                                                            <input type="hidden" name="active" value="1">
                                                                                            {t}Active{/t}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="1" align="right"><b>{t}Require Reset{/t}</b></td>
                                                                                        <td>
                                                                                            {*<select name="require_reset" class="olotd5">
                                                                                                <option value="0" {if $user_details.require_reset == '0'} selected {/if}>{t}No{/t}</option>
                                                                                                <option value="1" {if $user_details.require_reset == '1'} selected {/if}>{t}Yes{/t}</option>
                                                                                            </select>*}
                                                                                            <input type="hidden" name="require_reset" value="0">
                                                                                            {t}No{/t}
                                                                                        </td>
                                                                                    </tr>                                                                                    
                                                                                    
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Work -->
                                                                    
                                                                    <tr class="row2"{if !$is_employee} style="display: none;"{/if}>
                                                                        <td class="menuhead" colspan="2">&nbsp;{t}Work{/t}</td>
                                                                    </tr>
                                                                    <tr{if !$is_employee} style="display: none;"{/if}>
                                                                        <td colspan="2" align="left">
                                                                            <table>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Work Phone{/t}</strong></td>
                                                                                    <td><input name="work_primary_phone" class="olotd5" value="{$user_details.work_primary_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Work Mobile Phone{/t}</strong></td>
                                                                                    <td><input name="work_mobile_phone" class="olotd5" value="{$user_details.work_mobile_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Work Fax{/t}</strong></td>
                                                                                    <td><input name="work_fax" class="olotd5" value="{$user_details.work_fax}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>                                                                                
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Home -->
                                                                    
                                                                    <tr class="row2"{if !$is_employee} style="display: none;"{/if}>
                                                                        <td class="menuhead" colspan="2">&nbsp;{t}Home{/t}</td>
                                                                    </tr>
                                                                    <tr{if !$is_employee} style="display: none;"{/if}>
                                                                        <td colspan="2" align="left">
                                                                            <table>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Home Phone{/t}</strong></td>
                                                                                    <td><input name="home_primary_phone" class="olotd5" value="{$user_details.home_primary_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Home Mobile Phone{/t}</strong></td>
                                                                                    <td><input name="home_mobile_phone" class="olotd5" value="{$user_details.home_mobile_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Home Email{/t}</strong></td>
                                                                                    <td><input name="home_email" class="olotd5" size="50" value="{$user_details.home_email}" type="email" maxlength="50" onkeydown="return onlyEmail(event);"></td>
                                                                                </tr>
                                                                                                                                                           
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Address{/t}</strong></td>
                                                                                    <td><textarea name="home_address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);">{$user_details.home_address}</textarea></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}City{/t}</strong></td>
                                                                                    <td><input name="home_city" class="olotd5" value="{$user_details.home_city}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}State{/t}</strong></td>
                                                                                    <td><input name="home_state" class="olotd5" value="{$user_details.home_state}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Zip{/t}</strong></td>
                                                                                    <td ><input name="home_zip" class="olotd5" value="{$user_details.home_zip}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Country{/t}</strong></td>
                                                                                    <td><input name="home_country" class="olotd5" value="{$user_details.home_country}" type="text" maxlength="50" onkeydown="return onlyAlpha(event);"></td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- note -->
                                                                    
                                                                    <tr class="row2">
                                                                        <td class="menuhead" colspan="2">{t}Note{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <table>
                                                                                <tr>
                                                                                    <td align="left"><strong>{t}Note{/t}</strong></td>
                                                                                    <td><textarea name="note" class="olotd5" cols="50" rows="2">{$user_details.note}</textarea></td> 
                                                                                </tr>                                                                                
                                                                            </table>
                                                                        </td>
                                                                    </tr>                                                                     
                                                                    
                                                                    <!-- Submit Button -->
                                                                    
                                                                    <tr>                                                                        
                                                                        <td colspan="2">
                                                                            <input type="hidden" name="stage" value="7">                                                                            
                                                                            <button class="olotd5" type="submit" name="submit" value="stage7">{t}Next{/t}</button>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </table>                                                                
                                                            </td>
                                                    </table>
                                                </td>
                                        </table>
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