
function fn_savecontenttagstatus(expid,rubricid)
{
    var categoryid = '';
    var categorystatus = '';
    
    $("input[id^=form_mytags_category_]").each(function()
    {
        if(categoryid=='')
        {
           categoryid = $(this).attr('id').replace('form_mytags_category_','');
           categorystatus = $(this).val();
        }
        else
        {
           categoryid = categoryid+"~"+$(this).attr('id').replace('form_mytags_category_','');
           categorystatus = categorystatus+"~"+$(this).val();
        }
    });
      
    var dataparam = "oper=savecontenttagdetails&expid="+expid+"&categoryid="+categoryid+"&categorystatus="+categorystatus+"&rubricid="+rubricid;
 
    $.ajax({
                type: 'post',
                url: "library/missionrubric/library-missionrubric-mytagsajax.php",
                data: dataparam,
                beforeSend: function(){
                        showloadingalert("Loading, please wait.");	
                },
                success: function (data) {	
                        if(data=="success") //Works if the data saved in db
                        {
                                $('.lb-content').html("Saved Successfully.");
                                setTimeout('closeloadingalert()',500);

                                setTimeout('removesections("#library-missionrubric-actions");',1000);
                                setTimeout('showpageswithpostmethod("library-missionrubric-mytags","library/missionrubric/library-missionrubric-mytags.php","id='+expid+","+rubricid+'");',1000);
                        }
                        else
                        {
                                $('.lb-content').html("Invalid data");
                                setTimeout('closeloadingalert()',1000);
                        }
                },
        });

    
}






