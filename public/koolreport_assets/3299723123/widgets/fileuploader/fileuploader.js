var KoolReport = KoolReport || {};
KoolReport.dashboard = KoolReport.dashboard || {};
KoolReport.dashboard.widgets = KoolReport.dashboard.widgets || {};
KoolReport.dashboard.widgets.FileUploader = KoolReport.dashboard.widgets.FileUploader || function(name,options){
    this.options = options;
    this.name = name;
    this.init();
};

KoolReport.dashboard.widgets.FileUploader.prototype = {
    options:null,
    name:null,
    init:function()
    {
        this.selectButton().click(function(){
            this.fileInput().click();
        }.bind(this));
        this.fileInput().change(this.onChange.bind(this));
        this.removeButton().click(function(){
            this.reset();
        }.bind(this));
        if(this.options.disabled===true) {
            this.disabled();
        }
    },
    disabled:function()
    {
        this.selectButton().attr('disabled','disabled');
        this.removeButton().attr('disabled','disabled');
        $('#'+this.name).addClass('disabled');
    },
    selectButton:function()
    {
        return $('#'+this.name+' button.btn-select');
    },
    statusContent:function()
    {
        return $('#'+this.name+' div.status-content');
    },
    fileInput:function()
    {
        return $('#'+this.name+' input.file');
    },
    removeButton:function()
    {
        return $('#'+this.name+' button.btn-remove');
    },
    getFiles:function()
    {
        return this.fileInput()[0].files;
    },
    getFileExtension:function(filename) {
        var n = filename.lastIndexOf(".");
        return (n<0) ? "" : filename.substr(n+1).toLowerCase();
    },
    setTimeToBeBack:function()
    {
        setTimeout(function(){
            var state = KoolReport.dashboard.dboard.widgetGetState(this.name);
            if(state.value!==null) {
                this.statusContent().empty().append("<span>"+state.value+"</span>");
            } else {
                this.statusContent().empty().append("<span>"+this.options.messages.noFileSelectedText+"</span>");
            }
        }.bind(this),3000);
    },
    fileValidate:function(file){
        var ext = this.getFileExtension(file.name);
        var accept = this.options.accept;
        var notAccept = this.options.notAccept;

        if(Array.isArray(accept) && accept.indexOf(ext)<0) {
            this.statusContent().empty().append("<span class='text-danger'>"+this.options.messages.fileNotAllowedError+"</span>");
            this.setTimeToBeBack();
            return false;
        }

        if(Array.isArray(notAccept) && notAccept.indexOf(ext)>-1) {
            this.statusContent().empty().append("<span class='text-danger'>"+this.options.messages.fileNotAllowedError+"</span>");
            this.setTimeToBeBack();
            return false;
        }

        if(this.options.maxFileSize!==null && file.size>this.options.maxFileSize) {
            this.statusContent().empty().append("<span class='text-danger'>"+this.options.messages.fileSizeLmitError+"</span>");
            this.setTimeToBeBack();
            return false;
        }
        return true;
    },
    submit:function()
    {   
        var formData = new FormData();
        //If there is no files, do nothing
        var files = this.getFiles();
        if(files.length==0) {
            return;
        }
        //If validate file false, cancel submitting
        if(this.fileValidate(files[0])===false) {
            return;
        }
        this.statusContent().empty().append($("<i class='fas fa-spin fa-spinner'></i>"));
        formData.append("fileuploader", files[0]);
        //KoolReport Dashboard Request
        formData.append('kdr',
            JSON.stringify({
                route:"App/"+KoolReport.dashboard.page.name+"/" + KoolReport.dashboard.dboard.getName() + "/" + this.name,
                action:"upload",
            })
        );
        //Dashboard State
        formData.append('state',base64_encode(JSON.stringify(KoolReport.dashboard.dboard.state)));

        //CSRF
        if (KoolReport.dashboard.security.csrf) {
            formData.append(KoolReport.dashboard.security.csrf.name, KoolReport.dashboard.security.csrf.token);
        }

        $.ajax({
            url:window.location.href.replace(window.location.hash, ""),
            data:formData,
            dataType:'json',
            contentType:false,
            processData:false,
            type:'post',
        })
        .fail(function(xhr){
            KoolReport.dashboard.contactFail(xhr);
            this.statusContent().empty().append("<span>"+this.options.messages.unknownError+"</span>");
        })
        .done(function(response){
            KoolReport.dashboard.contactDone(response);
        });
    },
    imagePreview:function()
    {
        return $('#'+this.name+' div.image-preview');
    },
    onChange:function()
    {
        this.submit();
    },
    reset:function() {
        this.imagePreview().remove();
        this.removeButton().parent().remove();
        this.statusContent().empty().append("<span>"+this.options.messages.noFileSelectedText+"</span>");
        KoolReport.dashboard.dboard.widgetSaveState(this.name,"value",null);
    }
};