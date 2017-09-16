<!-- password_reset.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

{if $stage == 'enter_email'}
    
    {include file="user/blocks/reset_send_email_block.tpl"}
    
{elseif $stage == 'enter_token'}
    
    {include file="user/blocks/reset_enter_token_block.tpl"}
    
{elseif $stage == 'enter_password'}
    
    {include file="user/blocks/reset_enter_password_block.tpl"}
    
{else}
    
    {t}There has been an error.{/t}  
    
{/if}    