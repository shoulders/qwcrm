<!-- workorder/validate.js -->

{literal}
<script type="text/javascript">
    
    var errorFlagged = false;
    var elementFlaggedErrors = new Array();
    var elementErrorsPresent;    
    var _qfMsg = '';

    function validateForm(frm) {       
        
        _qfMsg = '';
        
        // Work Order Scope Validator
        if(frm.elements['workorder_scope']){
            
            value = frm.elements['workorder_scope'].value;
            
            // Work Order Scope is not empty
            if (value === '') {
                errorFlagged = true;
                elementFlaggedErrors['workorder_scope'] = true;
                _qfMsg = _qfMsg + "\n - {/literal}{$translate_workorder_validate_scope_notpresent}{literal}";
                frm.elements['workorder_scope'].className = 'error';              
            } else
            
            // Work Order Scope is not more than 40 characters    
            if (value.length > 40 ) {
                errorFlagged = true;
                elementFlaggedErrors['workorder_scope'] = true;
                _qfMsg = _qfMsg + "\n - {/literal}{$translate_workorder_validate_scope_length}{literal}";
                frm.elements['workorder_scope'].className = 'error';               
            } else {
                elementFlaggedErrors['workorder_scope'] = false;
                frm.elements['workorder_scope'].className = '';
            }
        }             

        // Work Order Description Validator
        if(frm.elements['workorder_description']){
            
            //value = frm.elements['workorder_description'].value;
            
            // Get the HTML contents of the currently active editor
            //value = tinyMCE.activeEditor.getContent();
            
            // Get content of a specific editor: (name will work if id is not set)
            value = tinyMCE.get('workorder_description').getContent();
            
            // Work Order description is not empty
            if (value === '') {
                errorFlagged = true;
                elementFlaggedErrors['workorder_description'] = true;
                _qfMsg = _qfMsg + "\n - {/literal}{$translate_workorder_validate_description_notpresent}{literal}";
                frm.elements['workorder_description'].className = 'error';         
            } else {
                elementFlaggedErrors['workorder_description'] = false;
                frm.elements['workorder_description'].className = '';
            }
        }
        
        // Work Order Creation Date is not empty
        // this is currently not used but might be if i make the ability to add a date manually
        if(frm.elements['workorder_date']){
            value = frm.elements['workorder_date'].value;
            if (value === '') {
                errorFlagged = true;
                elementFlaggedErrors['workorder_date'] = true;
                _qfMsg = _qfMsg + "\n - {/literal}{$translate_workorder_validate_creation_date_notpresent}{literal}";
                frm.elements['workorder_date'].className = 'error';         
            } else {
                elementFlaggedErrors['workorder_date'] = false;
                frm.elements['workorder_date'].className = '';
            }
        }        
       
        // Check if the key/value (associative array) has any element validation errors stored, if so return false      
        elementErrorsPresent = false;        
        for (var key in elementFlaggedErrors){
            if(elementFlaggedErrors[key] === true){
                elementErrorsPresent = true;
            }            
        }
        if (elementErrorsPresent === false){
            errorFlagged = false;
        }       

        // If there are element/form errors show the warning message that has been built
        if (errorFlagged === true) {
            _qfMsg = "{/literal}{$translate_workorder_validate_warning_message_invalidinformation}{literal}\n" + _qfMsg;
            _qfMsg = _qfMsg + "\n\n{/literal}{$translate_workorder_validate_warning_message_pleasecorrect}{literal}";
            alert(_qfMsg);            
            return false;
        } else {            
            return true;
            }
            
        return false; 
            
    }
    
</script>
{/literal}