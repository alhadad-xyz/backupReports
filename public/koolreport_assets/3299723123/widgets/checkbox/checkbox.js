var KoolReport = KoolReport || {};
KoolReport.dashboard = KoolReport.dashboard || {};
KoolReport.dashboard.widgets = KoolReport.dashboard.widgets || {};
KoolReport.dashboard.widgets.CheckBox = KoolReport.dashboard.widgets.CheckBox || function(name){
    this.name = name;
}
KoolReport.dashboard.widgets.CheckBox.prototype = {
    name:null,
    val:function($value=null) {
        if($value===null) {
            return $('#'+this.name).is(':checked')?1:0;
        } else {
            if($value==1) {
                return $('#'+this.name).val(1);
            } else {
                return $('#'+this.name).val(0);
            }   
        }
    },
    disable:function(bool) {
        $('#'+this.name).attr('disabled',bool);
    },
    reset:function()
    {
        $('#'+this.name).prop('checked',false);
    }
}