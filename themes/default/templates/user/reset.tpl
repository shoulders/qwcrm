<!-- password_reset.tpl-->

{if $stage == 'enter_email'}
    {include file="user/blocks/reset_send_email_block.tpl"}    

{elseif $stage == 'enter_token'}
    {include file="user/blocks/reset_enter_token_block.tpl"}

{elseif $stage == 'enter_password'}
    {include file="user/blocks/reset_enter_password_block.tpl"}

{else}
    {t}There has been an error{/t}
    
{/if}    