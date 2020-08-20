<!-- edit.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<script> 
    
    $(document).ready(function() {
        
        /* Correct onscreen values */
        
        /* Bindings */
        
        // Monitor Overall Cron Dropdown
        $("#common_options").on("change keyup", function() {            
            
            // Set Input Boxes
            let settings = $(this).val().split(' ');
            $("#minute").val(settings[0]);
            $("#hour").val(settings[1]);
            $("#day").val(settings[2]);
            $("#month").val(settings[3]);
            $("#weekday").val(settings[4]);            
            
            // Refresh other dynamic content
            refreshPage();
        });
            
        // Monitor individual Cron Input Boxes
        $("input[type='text'].setting").on("change keyup", function() { 
            
            // Set relative Dropdowns
            $(this).closest('td').next('td').find('select').val($(this).val());
            
            // Refresh other dynamic content
            refreshPage();
        });
        
        // Monitor individual Cron Dropdowns
        $("select.options").on("change", function() {
            
            // Set relative Input Boxes
            $(this).closest('td').prev('td').find('input').val($(this).val());
            
            // Refresh other dynamic content
            refreshPage();
        });
        
        // Refresh dynamic content
        refreshPage();
        
    } );
    
    // refresh all dynamic content onscreen
    function refreshPage() {
        
        // Set Global Dropdown to match Input Boxes
        $(function() {
            let minute = $("#minute").val();
            let hour = $("#hour").val();
            let day = $("#day").val();
            let month = $("#month").val();
            let weekday = $("#weekday").val();            
            let commonOptions = minute + ' ' + hour + ' ' + day + ' ' + month + ' ' + weekday;
            $("#common_options").val(commonOptions);            
        });
        
        // Set all individual cron dropdowns to match input boxes
        $('select.options').each(function() {              
            $(this).val($(this).closest('td').prev('td').find('input').val());
        });    
        
    }

    // Reset Cron to default settings
    function resetToDefaults() {
        
        let defaultSettings = {$cronjob_details.default_settings}        
        $("#active").val(defaultSettings.active);
        $("#pseudo_allowed").val(defaultSettings.pseudo_allowed);
        $("#minute").val(defaultSettings.minute);
        $("#hour").val(defaultSettings.hour);
        $("#day").val(defaultSettings.day);
        $("#month").val(defaultSettings.month);
        $("#weekday").val(defaultSettings.weekday);        
        
        // Refresh dynamic content
        refreshPage();
        
    }

</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Cronjob Edit{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}CRONJOB_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}CRONJOB_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>                                          
                                                <table width="100%" cellpadding="2" cellspacing="2" border="0">  
                                                    
                                                    <form action="index.php?component=cronjob&page_tpl=edit&cronjob_id={$cronjob_id}" method="post" name="edit_cron" id="edit_cron" autocomplete="off">                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Cronjob ID{/t}</b></td>
                                                            <td colspan="2">{$cronjob_id}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Name{/t}</b></td>
                                                            <td colspan="2" class="menutd">{$cronjob_details.name}</td>                                                                                     
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Description{/t}</b></td>
                                                            <td colspan="2" class="menutd">{$cronjob_details.description}</td>                                                                                     
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Active{/t}</b></td>
                                                            <td colspan="2" class="menutd">
                                                                <select class="olotd5" id="active" name="qform[active]" required>                                                                    
                                                                    <option value="0"{if $cronjob_details.active == '0'} selected{/if}>{t}No{/t}</option>
                                                                    <option value="1"{if $cronjob_details.active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                </select>                                                
                                                            </td>                                                                                  
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Pseudo Cron System Allowed{/t}</b></td>
                                                            <td colspan="2" class="menutd">
                                                                <select class="olotd5" id="pseudo_allowed" name="qform[pseudo_allowed]" required>                                                                    
                                                                    <option value="0"{if $cronjob_details.pseudo_allowed == '0'} selected{/if}>{t}No{/t}</option>
                                                                    <option value="1"{if $cronjob_details.pseudo_allowed == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                </select>                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Last Active{/t}</b></td>
                                                            <td colspan="2" class="menutd">{$cronjob_details.last_run_time|date_format:$date_format} {$cronjob_details.last_run_time|date_format:'H:i:s'}</td>                                                                                     
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Last Run Status{/t}</b></td>
                                                            <td colspan="2" class="menutd">{if $cronjob_details.last_run_status == 1}{t}Success{/t}{else}{t}Failed{/t}{/if}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Locked{/t}</b></td>
                                                            <td colspan="2" class="menutd">{if $cronjob_details.locked == 1}{t}Yes{/t}{else}{t}No{/t}{/if}</td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td colspan="3" style="padding-top: 10px; padding-bottom: 10px;"><hr></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{t}Select from preconfigured settings below (optional){/t}</td>
                                                        <tr>
                                                            <td></td>
                                                            <td align="right">{t}Common Options{/t}</td>
                                                            <td>                                                                
                                                                <select id="common_options">                                                                    
                                                                    <option value="--" disabled>-- {t}Common Settings{/t} --</option>
                                                                    <option value="* * * * *">{t}Once Per Minute{/t} (* * * * *)</option>
                                                                    <option value="*/5 * * * *">{t}Once Per Five Minutes{/t} (*/5 * * * *)</option>
                                                                    <option value="*/10 * * * *">{t}Once Per Ten Minutes{/t} (*/10 * * * *)</option>
                                                                    <option value="*/15 * * * *">{t}Once Per Fifteen Minutes{/t} (*/15 * * * *)</option>
                                                                    <option value="0,30 * * * *">{t}Twice Per Hour{/t} (0,30 * * * *)</option>
                                                                    <option value="0 * * * *">{t}Once Per Hour{/t} (0 * * * *)</option>
                                                                    <option value="0 0,12 * * *">{t}Twice Per Day{/t} (0 0,12 * * *)</option>
                                                                    <option value="0 0 * * *">{t}Once Per Day{/t} (0 0 * * *)</option>
                                                                    <option value="0 0 * * 0">{t}Once Per Week{/t} (0 0 * * 0)</option>
                                                                    <option value="0 0 1,15 * *">{t}On the 1st and 15th of the Month{/t} (0 0 1,15 * *)</option>
                                                                    <option value="0 0 1 * *">{t}Once Per Month{/t} (0 0 1 * *)</option>
                                                                    <option value="0 0 1 1 *">{t}Once Per Year{/t} (0 0 1 1 *)</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Minute{/t}</b></td>
                                                            <td><input id="minute" name="qform[minute]" class="setting olotd5" value="{$cronjob_details.minute}" type="text" maxlength="50" required onkeydown="return onlyCronjobMinHourDay(event);"></td>
                                                            <td>
                                                                <select id="minute_options" class="options">
                                                                    <option value="--" disabled>-- {t}Common Settings{/t} --</option>
                                                                    <option value="*">{t}Once Per Minute{/t} (*)</option>
                                                                    <option value="*/2">{t}Once Per Two Minutes{/t} (*/2)</option>
                                                                    <option value="*/5">{t}Once Per Five Minutes{/t} (*/5)</option>
                                                                    <option value="*/10">{t}Once Per Ten Minutes{/t} (*/10)</option>
                                                                    <option value="*/15">{t}Once Per Fifteen Minutes{/t} (*/15)</option>
                                                                    <option value="0,30">{t}Once Per Thirty Minutes{/t} (0,30)</option>
                                                                    <option value="--" disabled>-- {t}Minutes{/t} --</option>
                                                                    <option value="0">:00 ({t}At the beginning of the hour.{/t}) (0)</option>
                                                                    <option value="1">:01 (1)</option>
                                                                    <option value="2">:02 (2)</option>
                                                                    <option value="3">:03 (3)</option>
                                                                    <option value="4">:04 (4)</option>
                                                                    <option value="5">:05 (5)</option>
                                                                    <option value="6">:06 (6)</option>
                                                                    <option value="7">:07 (7)</option>
                                                                    <option value="8">:08 (8)</option>
                                                                    <option value="9">:09 (9)</option>
                                                                    <option value="10">:10 (10)</option>
                                                                    <option value="11">:11 (11)</option>
                                                                    <option value="12">:12 (12)</option>
                                                                    <option value="13">:13 (13)</option>
                                                                    <option value="14">:14 (14)</option>
                                                                    <option value="15">:15 ({t}At one quarter past the hour.{/t}) (15)</option>
                                                                    <option value="16">:16 (16)</option>
                                                                    <option value="17">:17 (17)</option>
                                                                    <option value="18">:18 (18)</option>
                                                                    <option value="19">:19 (19)</option>
                                                                    <option value="20">:20 (20)</option>
                                                                    <option value="21">:21 (21)</option>
                                                                    <option value="22">:22 (22)</option>
                                                                    <option value="23">:23 (23)</option>
                                                                    <option value="24">:24 (24)</option>
                                                                    <option value="25">:25 (25)</option>
                                                                    <option value="26">:26 (26)</option>
                                                                    <option value="27">:27 (27)</option>
                                                                    <option value="28">:28 (28)</option>
                                                                    <option value="29">:29 (29)</option>
                                                                    <option value="30">:30 ({t}At half past the hour.{/t}) (30)</option>
                                                                    <option value="31">:31 (31)</option>
                                                                    <option value="32">:32 (32)</option>
                                                                    <option value="33">:33 (33)</option>
                                                                    <option value="34">:34 (34)</option>
                                                                    <option value="35">:35 (35)</option>
                                                                    <option value="36">:36 (36)</option>
                                                                    <option value="37">:37 (37)</option>
                                                                    <option value="38">:38 (38)</option>
                                                                    <option value="39">:39 (39)</option>
                                                                    <option value="40">:40 (40)</option>
                                                                    <option value="41">:41 (41)</option>
                                                                    <option value="42">:42 (42)</option>
                                                                    <option value="43">:43 (43)</option>
                                                                    <option value="44">:44 (44)</option>
                                                                    <option value="45">:45 ({t}At one quarter until the hour.{/t}) (45)</option>
                                                                    <option value="46">:46 (46)</option>
                                                                    <option value="47">:47 (47)</option>
                                                                    <option value="48">:48 (48)</option>
                                                                    <option value="49">:49 (49)</option>
                                                                    <option value="50">:50 (50)</option>
                                                                    <option value="51">:51 (51)</option>
                                                                    <option value="52">:52 (52)</option>
                                                                    <option value="53">:53 (53)</option>
                                                                    <option value="54">:54 (54)</option>
                                                                    <option value="55">:55 (55)</option>
                                                                    <option value="56">:56 (56)</option>
                                                                    <option value="57">:57 (57)</option>
                                                                    <option value="58">:58 (58)</option>
                                                                    <option value="59">:59 (59)</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Hour{/t}</b></td>
                                                            <td><input id="hour" name="qform[hour]" class="setting olotd5" value="{$cronjob_details.hour}" type="text" maxlength="50" required onkeydown="return onlyCronjobMinHourDay(event);"></td>
                                                            <td>
                                                                <select id="hour_options" class="options">
                                                                    <option value="--" disabled>-- {t}Common Settings{/t} --</option>
                                                                    <option value="*">{t}Every Hour{/t} (*)</option>
                                                                    <option value="*/2">{t}Every Other Hour{/t} (*/2)</option>
                                                                    <option value="*/3">{t}Every Third Hour{/t} (*/3)</option>
                                                                    <option value="*/4">{t}Every Fourth Hour{/t}(*/4)</option>
                                                                    <option value="*/6">{t}Every Sixth Hour{/t} (*/6)</option>
                                                                    <option value="0,12">{t}Every Twelve Hours{/t} (0,12)</option>
                                                                    <option value="--" disabled>-- {t}Hours{/t} --</option>
                                                                    <option value="0">12:00 a.m. {t}Midnight{/t} (0)</option>
                                                                    <option value="1">1:00 a.m. (1)</option>
                                                                    <option value="2">2:00 a.m. (2)</option>
                                                                    <option value="3">3:00 a.m. (3)</option>
                                                                    <option value="4">4:00 a.m. (4)</option>
                                                                    <option value="5">5:00 a.m. (5)</option>
                                                                    <option value="6">6:00 a.m. (6)</option>
                                                                    <option value="7">7:00 a.m. (7)</option>
                                                                    <option value="8">8:00 a.m. (8)</option>
                                                                    <option value="9">9:00 a.m. (9)</option>
                                                                    <option value="10">10:00 a.m. (10)</option>
                                                                    <option value="11">11:00 a.m. (11)</option>
                                                                    <option value="12">12:00 p.m. {t}Noon{/t} (12)</option>
                                                                    <option value="13">1:00 p.m. (13)</option>
                                                                    <option value="14">2:00 p.m. (14)</option>
                                                                    <option value="15">3:00 p.m. (15)</option>
                                                                    <option value="16">4:00 p.m. (16)</option>
                                                                    <option value="17">5:00 p.m. (17)</option>
                                                                    <option value="18">6:00 p.m. (18)</option>
                                                                    <option value="19">7:00 p.m. (19)</option>
                                                                    <option value="20">8:00 p.m. (20)</option>
                                                                    <option value="21">9:00 p.m. (21)</option>
                                                                    <option value="22">10:00 p.m. (22)</option>
                                                                    <option value="23">11:00 p.m. (23)</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Day{/t}</b></td>                                            
                                                            <td><input id="day" name="qform[day]" class="setting olotd5" value="{$cronjob_details.day}" type="text" maxlength="50" required onkeydown="return onlyCronjobMinHourDay(event);"></td>
                                                            <td>
                                                                <select id="day_options" class="options">
                                                                    <option value="--" disabled>-- {t}Common Settings{/t} --</option>
                                                                    <option value="*">{t}Every Day{/t} (*)</option>
                                                                    <option value="*/2">{t}Every Other {/t} (*/2)</option>
                                                                    <option value="1,15">{t}On the 1st and 15th of the Month{/t} (1,15)</option>
                                                                    <option value="--" disabled>-- {t}Days{/t} --</option>
                                                                    <option value="1">1st (1)</option>
                                                                    <option value="2">2nd (2)</option>
                                                                    <option value="3">3rd (3)</option>
                                                                    <option value="4">4th (4)</option>
                                                                    <option value="5">5th (5)</option>
                                                                    <option value="6">6th (6)</option>
                                                                    <option value="7">7th (7)</option>
                                                                    <option value="8">8th (8)</option>
                                                                    <option value="9">9th (9)</option>
                                                                    <option value="10">10th (10)</option>
                                                                    <option value="11">11th (11)</option>
                                                                    <option value="12">12th (12)</option>
                                                                    <option value="13">13th (13)</option>
                                                                    <option value="14">14th (14)</option>
                                                                    <option value="15">15th (15)</option>
                                                                    <option value="16">16th (16)</option>
                                                                    <option value="17">17th (17)</option>
                                                                    <option value="18">18th (18)</option>
                                                                    <option value="19">19th (19)</option>
                                                                    <option value="20">20th (20)</option>
                                                                    <option value="21">21st (21)</option>
                                                                    <option value="22">22nd (22)</option>
                                                                    <option value="23">23rd (23)</option>
                                                                    <option value="24">24th (24)</option>
                                                                    <option value="25">25th (25)</option>
                                                                    <option value="26">26th (26)</option>
                                                                    <option value="27">27th (27)</option>
                                                                    <option value="28">28th (28)</option>
                                                                    <option value="29">29th (29)</option>
                                                                    <option value="30">30th (30)</option>
                                                                    <option value="31">31st (31)</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Month{/t}</b></td>
                                                            <td><input id="month" name="qform[month]" class="setting olotd5" value="{$cronjob_details.month}" type="text" maxlength="26" required onkeydown="return onlyCronjobMonthWeekday(event);"></td>
                                                            <td>
                                                                <select id="month_options" class="options">
                                                                    <option value="--" disabled>-- {t}Common Settings{/t} --</option>
                                                                    <option value="*">{t}Every Month{/t} (*)</option>
                                                                    <option value="*/2">{t}Every Other Month{/t} (*/2)</option>
                                                                    <option value="*/4">{t}Every Third Month{/t} (*/4)</option>
                                                                    <option value="1,7">{t}Every Six Months{/t} (1,7)</option>
                                                                    <option value="--" disabled>-- {t}Months{/t} --</option>
                                                                    <option value="1">{t}January{/t} (1)</option>
                                                                    <option value="2">{t}February{/t} (2)</option>
                                                                    <option value="3">{t}March{/t} (3)</option>
                                                                    <option value="4">{t}April{/t} (4)</option>
                                                                    <option value="5">{t}May{/t} (5)</option>
                                                                    <option value="6">{t}June{/t} (6)</option>
                                                                    <option value="7">{t}July{/t} (7)</option>
                                                                    <option value="8">{t}August{/t} (8)</option>
                                                                    <option value="9">{t}September{/t} (9)</option>
                                                                    <option value="10">{t}October{/t} (10)</option>
                                                                    <option value="11">{t}November{/t} (11)</option>
                                                                    <option value="12">{t}December{/t} (12)</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Weekday{/t}</b></td>
                                                            <td><input id="weekday" name="qform[weekday]" class="setting olotd5" value="{$cronjob_details.weekday}" type="text" maxlength="13" required onkeydown="return onlyCronjobMonthWeekday(event);"></td>
                                                            <td>
                                                                <select id="weekday_options" class="options">
                                                                    <option value="--" disabled>-- {t}Common Settings{/t} --</option>
                                                                    <option value="*">{t}Every Day{/t} (*)</option>
                                                                    <option value="1-5">{t}Every Weekday{/t} (1-5)</option>
                                                                    <option value="0,6">{t}Every Weekend Day{/t} (6,0)</option>
                                                                    <option value="1,3,5">{t}Every Monday, Wednesday, and Friday{/t} (1,3,5)</option>
                                                                    <option value="2,4">{t}Every Tuesday and Thursday{/t} (2,4)</option>
                                                                    <option value="--" disabled>-- {t}Weekdays{/t} --</option>
                                                                    <option value="0">{t}Sunday{/t} (0)</option>
                                                                    <option value="1">{t}Monday{/t} (1)</option>
                                                                    <option value="2">{t}Tuesday{/t} (2)</option>
                                                                    <option value="3">{t}Wednesday{/t} (3)</option>
                                                                    <option value="4">{t}Thursday{/t} (4)</option>
                                                                    <option value="5">{t}Friday{/t} (5)</option>
                                                                    <option value="6">{t}Saturday{/t} (6)</option>
                                                                </select>
                                                            </td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Command{/t}</b></td>
                                                            <td colspan="2" class="menutd">{$cronjob_details.command|regex_replace:"/\{|\}|\"/":""|regex_replace:"/,/":" , "}</td>                                                                                     
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" style="padding-top: 20px; padding-bottom: 20px;"><hr></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <input type="hidden" name="qform[cronjob_id]" value="{$cronjob_details.cronjob_id}">
                                                                <button type="submit" name="submit" value="update">{t}Update{/t}</button>
                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=cronjob&page_tpl=details&cronjob_id={$cronjob_id}';">{t}Cancel{/t}</button>
                                                                <button type="button" value="reset_default" style="float: right;" onclick="return confirm('{t}Are you sure you want to reset the cron to it\'s default settings?{/t}') && resetToDefaults();">{t}Reset to defaults{/t}</button>
                                                            </td>
                                                        </tr>
                                                    </form>
                                                    
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>