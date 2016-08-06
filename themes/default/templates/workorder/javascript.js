{literal}
<!-- Scope Auto Suggest -->
<script type="text/javascript">
    function lookup(scope) {
        if(scope.length == 0) {
            // Hide the suggestion box.
            $('#suggestions').hide();
        } else {
            // Lookup Records
            $.post("includes/autosuggest/autosuggest.php", {queryString: ""+scope+""}, function(data){
                if(data.length >0) {
                    $('#suggestions').show();
                    $('#autoSuggestionsList').html(data);
                }
            });
        }
    } 

    function fill(thisValue) {
        $('#workorder_scope').val(thisValue);
        setTimeout("$('#suggestions').hide();", 200);
    }
</script>
{/literal}    