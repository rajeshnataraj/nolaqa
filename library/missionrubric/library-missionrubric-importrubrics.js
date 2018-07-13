/*----
    fn_importstudents()
	Function to shoe the import students page
----*/
function fn_showimportrubric()
{
$('#duplicate').hide();
dataparam="oper=showimportrubric&expid="+$('#expid').val();
	
	$.ajax({
	type: 'post',
	url: 'library/missionrubric/library-missionrubric-importrubrics-ajax.php',
	data: dataparam,
	beforeSend: function(){
	showloadingalert("Loading, please wait.");
	},
	success:function(ajaxdata) {        
	closeloadingalert();
	$('#fileupload').html(ajaxdata);
	}
	});

}

function fn_importstudents(path){
	console.log(path);
	
	var actionmsg ="Loading Rubric";
	var shl=$('#hidshl').val();
       
        dataparam="oper=importstudents&path="+path+"&exp="+$('#expid').val()+"&rubname="+$('#txtrubricname').val();        
	$.ajax({
		type: 'post',
		url:'library/missionrubric/library-missionrubric-importrubrics-ajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert(actionmsg+", please wait.");	
		},
		success:function(ajaxdata){
			closeloadingalert();
                       
			$('#duplicate').show();
			$('#duplicate').html('');
			$('#duplicate').html(ajaxdata);
                        
                   	
		}

	});

}
/*----
    fn_duplicaterecord()
	Function to show the duplicate record List
----*/
function fn_duplicaterecord(path,id)
{
	dataparam="oper=duplicate&path="+path+"&duplicateid="+id;
	$.ajax({
		type: 'post',
		url:'library/missionrubric/library-missionrubric-importrubrics-ajax.php',
		data: dataparam,
		success:function(ajaxdata){
			$('#duplicate').html(ajaxdata);
		}
	});
}
