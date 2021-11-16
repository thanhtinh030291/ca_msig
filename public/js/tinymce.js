var editor_config = {
    language: "vi_VN",
    forced_root_block : false,
    path_absolute: "/",
    selector: "textarea.editor",
    branding: false,
    paste_retain_style_properties: "all",
    keep_styles: true,
    schema: "html4",
    fontsize_formats: "8pt 10pt 11pt 12pt 14pt 18pt 24pt 36pt",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar:
            ["insertfile undo redo | styleselect | bold italic sizeselect fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            "applicantName | IOPDiag | PRefNo | PhName  | memberNameCap | ltrDate | pstAmt | apvAmt | payMethod | deniedAmt | CSRRemark | TermRemark | tableInfoPayment | benefitOfClaim | createrSign | approveSign | claimNo | memRefNo | invoicePatient | time_pay | paymentAmt"
            ]
        ,
    relative_urls: false,
    file_browser_callback: function(field_name, url, type, win) {
        var x =
            window.innerWidth ||
            document.documentElement.clientWidth ||
            document.getElementsByTagName("body")[0].clientWidth;
        var y =
            window.innerHeight ||
            document.documentElement.clientHeight ||
            document.getElementsByTagName("body")[0].clientHeight;

        var cmsURL =
            editor_config.path_absolute +
            "laravel-filemanager?field_name=" +
            field_name;
        if (type == "image") {
            cmsURL = cmsURL + "&type=Images";
        } else {
            cmsURL = cmsURL + "&type=Files";
        }

        tinyMCE.activeEditor.windowManager.open({
            file: cmsURL,
            title: "Filemanager",
            width: x * 0.8,
            height: y * 0.8,
            resizable: "yes",
            close_previous: "no"
        });
    },
    setup: function(editor) {
        editor.addButton("applicantName", {
            text: "Applicant Name",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$applicantName]]&nbsp;");
            }
        });
        editor.addButton("IOPDiag", {
            text: "IOPDiag",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$IOPDiag]]&nbsp;");
            }
        });
        editor.addButton("PRefNo", {
            text: "PRefNo",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$PRefNo]]&nbsp;");
            }
        });
        editor.addButton("PhName", {
            text: "PhName",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$PhName]]&nbsp;");
            }
        });
        editor.addButton("memberNameCap", {
            text: "memberNameCap",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$memberNameCap]]&nbsp;");
            }
        });

        editor.addButton("ltrDate", {
            text: "ltrDate",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$ltrDate]]&nbsp;");
            }
        });

        editor.addButton("pstAmt", {
            text: "pstAmt",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$pstAmt]]&nbsp;");
            }
        });

        editor.addButton("apvAmt", {
            text: "apvAmt",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$apvAmt]]&nbsp;");
            }
        });

        editor.addButton("payMethod", {
            text: "payMethod",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$payMethod]]&nbsp;");
            }
        });

        editor.addButton("deniedAmt", {
            text: "deniedAmt",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$deniedAmt]]&nbsp;");
            }
        });

        editor.addButton("CSRRemark", {
            text: "CSRRemark",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$CSRRemark]]&nbsp;");
            }
        });

        editor.addButton("TermRemark", {
            text: "TermRemark",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$TermRemark]]&nbsp;");
            }
        });
        
        editor.addButton("tableInfoPayment", {
            text: "tableInfoPayment",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$tableInfoPayment]]&nbsp;");
            }
        });

        editor.addButton("benefitOfClaim", {
            text: "benefitOfClaim",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$benefitOfClaim]]&nbsp;");
            }
        });

        editor.addButton("createrSign", {
            text: "createrSign",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$per_creater_sign]]&nbsp;");
            }
        });

        editor.addButton("approveSign", {
            text: "approveSign",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$per_approve_sign]]&nbsp;");
            }
        });

        editor.addButton("claimNo", {
            text: "claimNo",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$claimNo]]&nbsp;");
            }
        });

        editor.addButton("memRefNo", {
            text: "memRefNo",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$memRefNo]]&nbsp;");
            }
        });
        editor.addButton("invoicePatient", {
            text: "invoicePatient",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$invoicePatient]]&nbsp;");
            }
        });

        editor.addButton("time_pay", {
            text: "time_pay",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$time_pay]]&nbsp;");
            }
        });
        

        editor.addButton("paymentAmt", {
            text: "paymentAmt",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[[$paymentAmt]]&nbsp;");
            }
        });
    }
};
tinymce.init(editor_config);


////type 2
var config2 = {
    language: "vi_VN",
    path_absolute: "/",
    forced_root_block : false,
    selector: "textarea.editor2",
    branding: false,
    
    fontsize_formats: "8pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 36pt",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar:
            ["insertfile undo redo | styleselect | bold italic sizeselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            "nameItem | amountItem | Date | Text | Begin | End | Example | insertfile undo redo | paste | copy"]
        ,
    
    
    content_css: [
        "//fonts.googleapis.com/css?family=Lato:300,300i,400,400i",
        "//www.tinymce.com/css/codepen.min.css",
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'
    ],

    setup: function(editor) {
        editor.addButton("nameItem", {
            text: "Name Item",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[##nameItem##]&nbsp;");
            }
        });
        editor.addButton("amountItem", {
            text: "Amount Item",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[##amountItem##]&nbsp;");
            }
        });
        editor.addButton("Date", {
            text: "Date",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[##Date##]&nbsp;");
            }
        });
        editor.addButton("Text", {
            text: "Text",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[##Text##]&nbsp;");
            }
        });
        editor.addButton("Begin", {
            text: "Begin",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[Begin]&nbsp;");
            }
        });
        editor.addButton("End", {
            text: "End",
            icon: false,
            onclick: function() {
                editor.insertContent("&nbsp;[End]&nbsp;");
            }
        });
        editor.addButton("Example", {
            text: "Example",
            icon: false,
            onclick: function() {
                editor.insertContent("<br>Chi phí &nbsp;<b class = 'text-danger'>[##nameItem##]</b>&nbsp; từ ngày &nbsp;<b class = 'text-danger'>[##Date##]</b>&nbsp; đến &nbsp;<b class = 'text-danger'>[##Date##]</b>&nbsp; là &nbsp;<b class = 'text-danger'>[##amountItem##]</b>&nbsp; , tuy nhiên công ty Bảo hiểm CATHAY từ chối chi trả &nbsp;<b class = 'text-danger'>[##Text##]</b>&nbsp; đồng do vượt mức quyền lợi tối đa( &nbsp;<b class = 'text-danger'>[##Text##]</b>&nbsp; đồng/ ngày x &nbsp;<b class = 'text-danger'>[##Text##]</b>&nbsp; = &nbsp;<b class = 'text-danger'>[##Text##]</b>&nbsp; đồng).");
            }
        });
    }
};
tinymce.init(config2);

//default
var config3 = {
    language: "vi_VN",
    path_absolute : "/",
    selector: "textarea.editor_default",
    branding: false,
    forced_root_block : false,
    fontsize_formats: "8pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 36pt",
    paste_retain_style_properties: "all",
    keep_styles: true,
    schema: "html4",
    plugins: [
    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
    "searchreplace wordcount visualblocks visualchars code fullscreen",
    "insertdatetime media nonbreaking save table contextmenu directionality",
    "emoticons template  textcolor colorpicker textpattern",
    ],
    toolbar: ["insertfile undo redo | styleselect | bold italic sizeselect fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media ",
            "Note"
            ],
    relative_urls: false,
    height: 500,
    file_browser_callback : function(field_name, url, type, win) {
    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
    if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
    } else {
        cmsURL = cmsURL + "&type=Files";
    }
    },
    setup: function(editor) {
        editor.addButton("Note", {
            text: "Note",
            icon: false,
            onclick: function() {
                editor.insertContent('&nbsp;<div style="width:340px ; border: 3px solid red; color:red ; background-color: #d4d4d4"> Enter text here...</div> &nbsp;');
            }
        });
        
    }
};
tinymce.init(config3);

//text 
var config4 = {
    language: "vi_VN",
    path_absolute : "/",
    forced_root_block : false,
    selector: "textarea.editor_default2",
    branding: false,
    plugins: [
    "paste advlist autolink lists link image charmap print preview hr anchor pagebreak",
    "searchreplace wordcount visualblocks visualchars code fullscreen",
    "insertdatetime media nonbreaking save table contextmenu directionality",
    "emoticons template paste textcolor colorpicker textpattern",
    ],
    toolbar: ["insertfile undo redo | styleselect | bold italic sizeselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media"
            ],
    relative_urls: false,
    height: 200,
    file_browser_callback : function(field_name, url, type, win) {
    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
    if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
    } else {
        cmsURL = cmsURL + "&type=Files";
    }
    }
    
};
tinymce.init(config4);

var editor_readonly = {
    selector: "textarea.editor_readonly",
    branding: false,
    readonly : 1,
    menubar:false,
    toolbar:false,
    height : "500",
    };
tinymce.init(editor_readonly);

var editor_not_menu = {
    selector: "textarea.editor_not_menu",
    branding: false,
    menubar:false,
    toolbar:false,
    
    };
tinymce.init(editor_not_menu);




