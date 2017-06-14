<!-- error.tpl -->
<div class="">    
    <span style="color: black">{t}Error Page{/t}: </span>{$error_page}<br />
    <span style="color: black">{t}Error Type{/t}: </span>{$error_type}<br /><br />
    
    <span style="color: black">{t}Error Location{/t}: </span>{$error_location}<br />    
    <span style="color: black">{t}PHP Function{/t}: </span>{$php_function}<br /><br />      
   
    <span style="color: black">{t}Database Error{/t}: </span>{$database_error}<br />
    <span style="color: black">{t}SQL Query{/t}: </span><br />{$sql_query}<br /><br />
    
    <span style="color: black">{t}Error Message{/t}: </span>{$error_msg}<br /><br /> 
</div>