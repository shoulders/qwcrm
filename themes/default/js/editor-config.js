  tinymce.init({
     
    selector : "textarea:not(.mceNoEditor)",    // Select all textarea exluding the mceNoEditor class    
    theme: 'modern',
    //content_css: 'css/content.css',           // point this to your template CSS for inline styling
    browser_spellcheck: true,                   // enable browser native spell check

    // Menu Items and Toolbar Buttons
    menubar: false,                             // file/edit menu at the top - enabled by default    
    contextmenu: 'cut copy paste | link',       // Enable these items in the context menu   
    
    // Enable these Toolbar Buttons
    toolbar: [        
        'newdocument undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | blockquote hr charmap',
        'bullist numlist | table | unlink link | print | searchreplace spellchecker | cut copy paste removeformat | code'      
    ],
    
    // Enable these Plugins
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'save table contextmenu directionality emoticons template paste textcolor',
      'codesample colorpicker importcss searchreplace toc'
    ]

  });