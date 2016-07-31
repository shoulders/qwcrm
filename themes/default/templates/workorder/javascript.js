{literal}
<!-- Scope Auto Suggest -->
<script type="text/javascript">
    function lookup(scope) {
        if(scope.length == 0) {
            // Hide the suggestion box.
            $('#suggestions').hide();
        } else {
            $.post("modules/workorder/autosuggest.php", {queryString: ""+scope+""}, function(data){
                if(data.length >0) {
                    $('#suggestions').show();
                    $('#autoSuggestionsList').html(data);
                }
            });
        }
    } // lookup

    function fill(thisValue) {
        $('#scope').val(thisValue);
        setTimeout("$('#suggestions').hide();", 200);
    }
</script>
{/literal}    