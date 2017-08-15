tinymce.init({
     
    selector : "textarea:not(.mceNoEditor)",                                // Select all textarea excluding the mceNoEditor class    
    theme: 'modern',
    //content_css: 'css/content.css',                                       // point this to your template CSS for inline styling
    browser_spellcheck: true,                                               // enable browser native spell check
    schema: 'html5',                                                        // set to use html5
    insertdatetime_dateformat: '{$date_format}',                            // override the default formatting rule for date formats inserted by the mceInsertDate command
    //insertdatetime_element: true,                                         // HTML5 time elements gets generated when you insert dates/times.
    insertdatetime_formats: ['{$date_format}', '%H:%M:%S', '%I:%M:%S %p'],  // specify a list of date/time formats to be used in the date menu or date select box.
        
    // Menu Items and Toolbar Buttons
    menubar: false,                                                         // file/edit menu at the top - enabled by default    
    contextmenu: 'cut copy paste | link',                                   // Enable these items in the context menu   
        
    // Enable these Toolbar Buttons
    toolbar: [        
        'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | blockquote hr charmap | insertdatetime',
        'bullist numlist | table | unlink link | spellchecker | cut copy paste removeformat | fullscreen code'      
    ],
    
    // Enable these Plugins
    plugins: [
      'advlist autolink link lists charmap hr spellchecker visualchars code fullscreen table contextmenu paste textcolor insertdatetime'      
    ],

    // On Submit if TinyMCE Editor is empty and has the class "mceCheckForContent" on its placeholder, dont submit the form and put a red border around it
    setup: function(editor) {
        editor.on('submit', function(e) {

            var placeholderElement = editor.getElement();   
            var testClass = "mceCheckForContent";            
            
            if(placeholderElement.classList.contains(testClass) && editor.getContent() === '') {
                editor.getContainer().style.border = '3px solid red';
                return false;
            }

        });
    }

});