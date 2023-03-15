function DateRangePicker(name,options)
{
    this.name = name;    
    this.options = options;

    this.init();
}
DateRangePicker.prototype = {
    name:null,
    options:null,
    stardardFormat:'YYYY-MM-DD HH:mm:ss',
    init:function()
    {
        var options = this.options;
        
        var startDate = $('#'+this.name+'_start').val();
        var endDate = $('#'+this.name+'_end').val();
        
        options.startDate = moment(startDate!=""?startDate:options.startDate,this.stardardFormat);
        options.endDate = moment(endDate!=""?endDate:options.endDate,this.stardardFormat);
        
        if(options.minDate)
        {
            options.minDate = moment(options.minDate,this.stardardFormat);
        }
        if(options.maxDate)
        {
            options.maxDate = moment(options.maxDate,this.stardardFormat);
        }
    
        for(var i in options.ranges)
        {
            options.ranges[i][0] = moment(options.ranges[i][0],this.stardardFormat);
            options.ranges[i][1] = moment(options.ranges[i][1],this.stardardFormat);
        }
    
        this.origin().daterangepicker(options,this.cb.bind(this));
        this.cb(options.startDate,options.endDate);    
    },
    val:function()
    {
        //Return the array of selected date
        return [
            this.origin().data('daterangepicker').startDate.format(this.stardardFormat),
            this.origin().data('daterangepicker').endDate.format(this.stardardFormat)
        ];
    },
    on:function(name,func)
    {
        this.origin().on(name+".daterangepicker",func);
    },
    origin:function()
    {
        return $('#'+this.name);
    },
    cb:function(start,end){
        $('#'+this.name+'_start').val(start.format(this.stardardFormat));
        $('#'+this.name+'_end').val(end.format(this.stardardFormat));
        $('#'+this.name+' span').html(start.format(this.options.locale.format) + ' - ' + end.format(this.options.locale.format));
    },
    reset: function() {
        this.cb(this.options.startDate, this.options.endDate);
    },
    disable:function(bool) {
        $('#'+this.name).attr('disabled',bool);
        if(bool) {
            $('#'+this.name).off();
        } else {
            this.init();
        }
    }
}