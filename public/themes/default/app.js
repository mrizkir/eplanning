/**
 * Global function
 */
  // To make Pace works on Ajax calls
$(document).ajaxStart(function () {
    Pace.restart()
})
//checking data type is json
function isJSON (data) 
{
    try 
    {
        JSON.parse(data);        
    }catch(e) 
    {
        return false;
    }
    return true;
}
//parsing ajax error
function parseMessageAjaxEror (xhr, status, error) 
{    
    if (isJSON(xhr)) {
        var jsonResponseText = $.parseJSON(xhr.responseText);
        var jsonResponseStatus = '';
        var message = '';

        $.each(jsonResponseText, function(name, val) 
        {
            switch(name) 
            {
                case 'message' :
                    message = 'message:' + val + ',';
                break;
                case 'exception' :
                    message = message + 'exception:' + val;
                break;
            }            
        });
        return message;
    }
}
/**
 *  customization jquery-validation
 */
if ($.validator) //check jquery-validation has loaded
{    
    //override default value
    $.validator.setDefaults({
        errorElement: "span",
        errorClass: "help-block", 
        highlight:function (element) 
        {
            $(element).closest('.form-group').addClass('has-error');
        },    
        unhighlight:function (element) 
        {
            $(element).closest('.form-group').removeClass('has-error');
        },           
        errorPlacement: function ( error, element )  
        {
            if (element.parent('.input-group').length) 
            {
                error.insertAfter(element.parent());
            }else
            {
                error.insertAfter(element);
            }         
        },
    });
    //new method value not equal
    $.validator.addMethod('valueNotEquals',function(value,element,arg){
        return arg !== value;
    }, "Value must not equal arg.");

}
/**
* form operations
*/
$(document).ready(function() 
{    
    if ($('#frmsearch').is("#frmsearch")) 
    {
        $('#frmsearch').submit (function(e) {
            e.preventDefault();
            var actionurl = e.currentTarget.action;     
            
            $.ajax({
                type:'post',
                url:actionurl,
                dataType: 'json',
                data: $("#frmsearch").serialize(),
                success:function(result){ 
                    $('#divdatatable').html(result.datatable);                       
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        });
        $('#btnReset').click(function(ev) 
        {
            $('#frmsearch').trigger('reset');
            $('#txtKriteria').focus();
            $.ajax({
                type:'post',
                url: $('#frmsearch').attr('action'),
                dataType: 'json',
                data: {                
                    "_token": token,
                    "action": 'reset',
                },
                success:function(result){ 
                    $('#divdatatable').html(result.datatable);    
                    $('#txtKriteria').val('');                   
                },
                error:function(xhr, status, error){
                    console.log('ERROR');
                    console.log(parseMessageAjaxEror(xhr, status, error));                           
                },
            });
        });
    }  
});  
/**
* data table operations
*/

//change number record of page function
function changeNumberRecordOfPage (selector)
{
    $.ajax({
        type:'post',
        url: url_current_page +'/changenumberrecordperpage',
        dataType: 'json',
        data: {                
            "_token": token,
            "numberRecordPerPage": $('#numberRecordPerPage').val(),
        },
        success:function(result)
        {          
            $(selector).html(result.datatable);                                   
        },
        error:function(xhr, status, error)
        {
            console.log('ERROR');
            console.log(parseMessageAjaxEror(xhr, status, error));                           
        },
    });
}
//sorting table data
function sortingTableData (selector,a)
{
    var column_name=a.attr('id');
    var orderby=a.data('order');
    $.ajax({
        type:'post',
        url: url_current_page +'/orderby',
        dataType: 'json',
        data: {                
            "_token": token,                
            "column_name":column_name,
            "orderby": orderby,
        },
        success:function(result)
        {          
            $(selector).html(result.datatable);           
        },
        error:function(xhr, status, error)
        {
            console.log('ERROR');
            console.log(parseMessageAjaxEror(xhr, status, error));                           
        },
    });
}
//paginate table data
function paginateTableData (selector,href)
{
    var a =  href.attr('href').split('?page=');        
    var page = a[1];
    var page_url = a[0]+'/paginate/'+page;
    if (typeof page !== 'undefined')
    {
        $.ajax({
            type:'get',
            url: page_url,
            dataType: 'json',
            success:function(result)
            {          
                $(selector).html(result.datatable);                       
            },
            error:function(xhr, status, error)
            {
                console.log('ERROR');
                console.log(parseMessageAjaxEror(xhr, status, error));                           
            },
        });
    }
}
$(document).ready(function() 
{ 
    //change number record of page
    $(document).on('change','#numberRecordPerPage', function (ev)
    {
        ev.preventDefault();    
        changeNumberRecordOfPage('#divdatatable');
    });
    //sorting table data
    $(document).on('click','.column-sort', function (ev)
    {
        ev.preventDefault();                
        sortingTableData ('#divdatatable',$(this));
    });
    //paginate table data
    $(document).on('click','#paginations a', function (ev)
    {
        ev.preventDefault();
        paginateTableData('#divdatatable',$(this));
    });
});

/**
 * admin lte customization
 */

$('ul.sidebar-menu a').filter(function() {
    return this.href == url_current_page;
}).parent().addClass('active');

// for treeview
$('ul.treeview-menu a').filter(function() {
    return this.href == url_current_page;
}).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');