<!-- details_details_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table class="olotable" border="0" cellpadding="2" cellspacing="0" width="100%">
    
    <tr>
        <td class="olohead" colspan="4">{t}Contact Information{/t}</td>
    </tr>
    
    <!-- Common -->
    
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>    
    
    <tr>                        
        <td class="menutd"><b>{t}Name{/t}</b></td>
        <td class="menutd">{$user_details.display_name}</td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td class="menutd"><b>{t}First Name{/t}</b></td>
        <td class="menutd">{$user_details.first_name}</td>
        <td class="menutd"><b>{t}Last Name{/t}</b>
        <td class="menutd">{$user_details.last_name}</td>
    </tr>
    
    <!-- Account -->
    
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>

    <tr>
        <td class="menutd"><b>{t}Username{/t}</b></td>
        <td class="menutd">{$user_details.username}</td>
        <td class="menutd"><b>{t}Email{/t}</b></td>
        <td class="menutd"><a href="mailto:{$user_details.email}">{$user_details.email}</a></td>
    </tr>
    <tr>
        <td class="menutd"><b>{t}User ID{/t}</b></td>
        <td class="menutd">{$user_details.user_id}</td>
        <td class="menutd"><b>{t}Based{/t}</b></td>
        <td class="menutd">            
            {section name=l loop=$user_locations}    
                {if $user_details.based == $user_locations[l].user_location_id}{t}{$user_locations[l].display_name}{/t}{/if}
            {/section}
        </td>    
    </tr>
    <tr>
        <td class="menutd"><b>{t}Is Employee{/t}</b></td>
        <td class="menutd">
            {if $user_details.is_employee == '0'}{t}Client{/t}{/if}
            {if $user_details.is_employee == '1'}{t}Employee{/t}{/if}
        </td>
        <td class="menutd"><b>{t}Client{/t}</b></td>
        <td class="menutd">
            {if $user_details.client_id == ''}
                {t}n/a{/t}
            {else}                
                <a href="index.php?component=client&page_tpl=details&client_id={$user_details.client_id}">{$client_display_name}</a>
            {/if}
        </td>
    </tr>
    <tr>                                
        <td class="menutd"><b>{t}Usergroup{/t}</b></td>
        <td class="menutd">      
            {section name=b loop=$usergroups}
                {if $user_details.usergroup == $usergroups[b].usergroup_id}{$usergroups[b].usergroup_display_name}{/if}
            {/section}        
        </td>         
        <td class="menutd"><b>{t}Status{/t}</b></td>
        <td class="menutd">
            {if $user_details.active == '0'}{t}Blocked{/t}{/if}
            {if $user_details.active == '1'}{t}Active{/t}{/if}
        </td>
    </tr>
    <tr>
        <td class="menutd"><b>{t}Last Active{/t}</b></td>
        <td class="menutd">{$user_details.last_active|date_format:$date_format}</td>
        <td class="menutd"><b>{t}Register Date{/t}</b></td>
        <td class="menutd">{$user_details.register_date|date_format:$date_format}</td>
    </tr>
    <tr>        
        <td class="menutd"><b>{t}Require Reset{/t}</b></td>
        <td class="menutd">
            {if $user_details.require_reset == '0'}{t}No{/t}{/if}
            {if $user_details.require_reset == '1'}{t}Yes{/t}{/if}
        </td>
        <td class="menutd">&nbsp;</td>
        <td class="menutd">&nbsp;</td>
    </tr>
    <tr>        
        <td class="menutd"><b>{t}Last Reset Time{/t}</b></td>
        <td class="menutd">{$user_details.last_reset_time|date_format:$date_format}</td>
        <td class="menutd"><b>{t}Count of password resets since Last Reset Time{/t}</b></td>
        <td class="menutd">{$user_details.reset_count}</td>
    </tr>

    
    <!-- Work -->
    
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
    
    <tr>
        <td class="menutd"><b>{t}Work Phone{/t}</b></td>
        <td class="menutd">{$user_details.work_primary_phone}</td>
        <td class="menutd"><b>{t}Work Mobile Phone{/t}</b></td>
        <td class="menutd">{$user_details.work_mobile_phone}</td>
    </tr>
    <tr>                                
        <td class="menutd"><b>{t}Work Fax{/t}</b></td>
        <td class="menutd">{$user_details.work_fax}</td>
        <td colspan="2">&nbsp;</td>
    </tr>
    
    <!-- Home -->
    
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
    
    <tr>
        <td class="menutd"><b>{t}Home Phone{/t}</b></td>
        <td class="menutd">{$user_details.home_primary_phone}</td>
        <td class="menutd"><b>{t}Home Mobile Phone{/t}</b></td>
        <td class="menutd">{$user_details.home_mobile_phone}</td>
    </tr>
    <tr>
        <td class="menutd"><b>{t}Home Email{/t}</b></td>
        <td class="menutd">{$user_details.home_email}</td>
        <td colspan="2">&nbsp;</td>
    </tr>    
    <tr>
        <td class="menutd"><b>{t}Address{/t}</b></td>
        <td class="menutd">
            {$user_details.home_address|nl2br}<br>
            {$user_details.home_city}<br>
            {$user_details.home_state}<br>
            {$user_details.home_zip}<br>
            {$user_details.home_country}
        </td>
        <td colspan="2">&nbsp;</td>
    </tr>
    
    <!-- note -->
    
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>    
    <tr>
        <td class="menutd"><b>{t}Note{/t}</b></td>
        <td class="menutd">{$user_details.note}</td>
        <td colspan="2">&nbsp;</td>
    </tr>    
    
</table>