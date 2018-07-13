function fn_createcategory(cid)
{
	var catname = $('#catname').val();
	var catcode = $('#catcode').val();
	var defield = $('#dfield').val();
	var hidtxtid = $('#hidtxtid').val();
	var fldval='';
	
	for(var i=1;i<hidtxtid;i++)
	{
		if(i=='1')
		{
			fldval=$('#dfield').val()+","+$('#field_'+i).val();
		}
		else
		{
			fldval=fldval+","+$('#field_'+i).val();
		}
	}
	if(catname =='' || catcode == '' || defield == ''){

	   showloadingalert("Please Fill all input box.");
	   setTimeout('closeloadingalert()',3000);
	   return false;
	}
	var dataparam = "oper=category&catname="+catname+"&catcode="+catcode+"&defield="+defield+"&fldval="+fldval+"&cid="+cid;
	$.ajax({
            type: 'post',
            url: 'sim/category/sim-category-ajax.php',
            data: dataparam,
            beforeSend: function(){                  	
            },
            success:function(data) {
				
				setTimeout('removesections("#home");',500);
				setTimeout('showpages("sim","sim/sim.php");',500);
            }
    });
}
