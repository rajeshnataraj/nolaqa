/** Close the Fancybox */
function fn_canceldestform()
{
	$.fancybox.close();
}

/****** Used to append the popup form data in to form in first time start here *******/
function fn_tempsavedestexpform(id,destid,rowid,type,rubid,proid,empdesid,oldscore,destname)
{   
    if($("#expdestform").validate().form())
    { 
		var txtdestcategoryname=$('#txtdestcategoryname').val();
		var txtdest4=$('#txtdest4').val();	
		var txtdest3=$('#txtdest3').val();	
		var txtdest2=$('#txtdest2').val();	
		var txtdest1=$('#txtdest1').val();	
		var txtdest0=$('#txtdest0').val();	
		var txtdestweight=$('#txtdestweight').val();	
		var txtdestscore=$('#txtdestscore').val();	
		fn_canceldestform();

		var txtoldscore=$('#hiddbtotscore').val();	
		
		if(oldscore=='')
		{
			oldscore=0;
		}		
		if(parseInt(oldscore)==parseInt(txtdestscore))
		{
			finalscore=parseInt(txtoldscore);
		}
		else
		{
			suboldscore=parseInt(txtoldscore)-parseInt(oldscore);
			finalscore=parseInt(suboldscore)+parseInt(txtdestscore);
		}
		$('#hiddbtotscore').val(finalscore);	
		$('#totscore').html(finalscore);
		var neworimport=$('#hidneworimport').val();
		if(neworimport==0)
		{
		 	$("#exp-extend-0").remove();
			var r=$('#rowcount').val();
			rowCount=parseInt(r)+1;        	
			$('#rowcount').val(rowCount);
		}
		else
		{
			$("#exp-extend-0").remove();
			var r=$('#rowcount').val();
			var rowCount=parseInt(r)+1;
			$('#rowcount').val(rowCount);
		}
        
		var rowCounta = $('#mytable tr').not("thead tr").length;		
        
        var mdestcount=$('#emptyhiddestcount').val();        
        if(mdestcount==1)
		{
            txtdestweight='X'+txtdestweight;
            var type=3;            

            var Content1='<tr class="exp-rubric-'+destid+'" id="exp-rubric-'+rowCount+'" onclick="fn_showdeststmteditform(\''+id+'\',\''+destid+'\',\''+rowCount+'\',\''+type+'\',\''+rubid+'\',\''+3+'\')">\n\
                    <td width="17%" id="emptyrubrictxt_'+rowCount+'_1">'+txtdestcategoryname+'</td>\n\
                    <td width="14%" id="emptyrubrictxt_'+rowCount+'_2" class="centerText">'+txtdest4+'</td>\n\
                    <td width="14%" id="emptyrubrictxt_'+rowCount+'_3" class="centerText">'+txtdest3+'</td>\n\
                    <td width="13%" id="emptyrubrictxt_'+rowCount+'_4" class="centerText">'+txtdest2+'</td>\n\
                    <td width="13%" id="emptyrubrictxt_'+rowCount+'_5" class="centerText">'+txtdest1+'</td>\n\
                    <td width="13%" id="emptyrubrictxt_'+rowCount+'_6" class="centerText">'+txtdest0+'</td>\n\\n\
                    <td width="7%" id="emptyrubrictxt_'+rowCount+'_7" class="centerText">'+txtdestweight+'</td>\n\
                    <td width="7%" id="emptyrubrictxt_'+rowCount+'_8" class="centerText m">'+txtdestscore+'</td> \n\
                    <input type="hidden" name="hidempinddestid" id="hidempinddestid_'+rowCount+'_0" value="'+destid+'" />\n\
					<input type="hidden" name="hiddestname" id="hiddestname_'+destid+'_0" value="'+destname+'" />\n\
                </tr>';
            var newRowContent=Content1;           
            $("#countrow-"+empdesid).before(newRowContent);
        }
        else
		{
            var type=2;

            txtdestweight='X'+txtdestweight;
            var Content1='<tr class="exp-rubric-'+destid+'" id="exp-rubric-'+rowCount+'" onclick="fn_showdeststmteditform(\''+id+'\',\''+destid+'\',\''+rowCount+'\',\''+type+'\',\''+rubid+'\',\''+2+'\')">\n\
                        <td width="17%" id="rubrictxte_'+rowCount+'_1">'+txtdestcategoryname+'</td>\n\
                        <td width="14%" id="rubrictxte_'+rowCount+'_2" class="centerText">'+txtdest4+'</td>\n\
                        <td width="14%" id="rubrictxte_'+rowCount+'_3" class="centerText">'+txtdest3+'</td>\n\
                        <td width="13%" id="rubrictxte_'+rowCount+'_4" class="centerText">'+txtdest2+'</td>\n\
                        <td width="13%" id="rubrictxte_'+rowCount+'_5" class="centerText">'+txtdest1+'</td>\n\
                        <td width="13%" id="rubrictxte_'+rowCount+'_6" class="centerText">'+txtdest0+'</td>\n\\n\
                        <td width="7%" id="rubrictxte_'+rowCount+'_7" class="centerText">'+txtdestweight+'</td>\n\
                        <td width="7%" id="rubrictxte_'+rowCount+'_8" class="centerText m">'+txtdestscore+'</td> \n\
                        <input type="hidden" name="hidinddestid" id="hidinddestid_'+rowCount+'_0" value="'+destid+'" />\n\
						<input type="hidden" name="hiddestname" id="hiddestname_'+destid+'_0" value="'+destname+'" />\n\
                    </tr>';
            var newRowContent=Content1;
 			$("#countrow-"+empdesid).before(newRowContent);
        }
    }
}
/****** Used to append the popup form data in to form END here *******/

/****** Used to append the popup form data in to form start here *******/
function fn_tempsavedestexpform1(id,destid,rowid,type,rubid,proid,rcount,empdesid,oldscore)
{
    if($("#expdestform").validate().form())
    {        
        var txtdestcategoryname=$('#txtdestcategoryname').val();
        var txtdest4=$('#txtdest4').val();	
        var txtdest3=$('#txtdest3').val();	
        var txtdest2=$('#txtdest2').val();	
        var txtdest1=$('#txtdest1').val();	
        var txtdest0=$('#txtdest0').val();	
        var txtdestweight=$('#txtdestweight').val();	
        var txtdestscore=$('#txtdestscore').val();	
	 	var txtoldscore=$('#hiddbtotscore').val();	
		
        fn_canceldestform();
		
		if(oldscore=='')
		{
			oldscore=0;
		}		
		
		if(parseInt(oldscore)==parseInt(txtdestscore))
		{
			finalscore=parseInt(txtoldscore);
		}
		else
		{
			suboldscore=parseInt(txtoldscore)-parseInt(oldscore);
			finalscore=parseInt(suboldscore)+parseInt(txtdestscore);
		}
		
		$('#hiddbtotscore').val(finalscore);	
		 $('#totscore').html(finalscore);
        
        if(type==1)
		{
            $('#rubrictxt_'+rowid+'_1').html(txtdestcategoryname);     
            $('#rubrictxt_'+rowid+'_2').html(txtdest4);
            $('#rubrictxt_'+rowid+'_3').html(txtdest3);
            $('#rubrictxt_'+rowid+'_4').html(txtdest2);
            $('#rubrictxt_'+rowid+'_5').html(txtdest1); 
            $('#rubrictxt_'+rowid+'_6').html(txtdest0);
            txtdestweight="X"+txtdestweight;
            $('#rubrictxt_'+rowid+'_7').html(txtdestweight);
            $('#rubrictxt_'+rowid+'_8').html(txtdestscore);
        }
        else if(type==2)
		{
            $('#rubrictxte_'+rowid+'_1').html(txtdestcategoryname);     
            $('#rubrictxte_'+rowid+'_2').html(txtdest4);
            $('#rubrictxte_'+rowid+'_3').html(txtdest3);
            $('#rubrictxte_'+rowid+'_4').html(txtdest2);
            $('#rubrictxte_'+rowid+'_5').html(txtdest1); 
            $('#rubrictxte_'+rowid+'_6').html(txtdest0);
            txtdestweight="X"+txtdestweight;
            $('#rubrictxte_'+rowid+'_7').html(txtdestweight);
            $('#rubrictxte_'+rowid+'_8').html(txtdestscore);
        }
        else
		{
            $('#emptyrubrictxt_'+rowid+'_1').html(txtdestcategoryname);     
            $('#emptyrubrictxt_'+rowid+'_2').html(txtdest4);
            $('#emptyrubrictxt_'+rowid+'_3').html(txtdest3);
            $('#emptyrubrictxt_'+rowid+'_4').html(txtdest2);
            $('#emptyrubrictxt_'+rowid+'_5').html(txtdest1); 
            $('#emptyrubrictxt_'+rowid+'_6').html(txtdest0);
            txtdestweight="X"+txtdestweight;
            $('#emptyrubrictxt_'+rowid+'_7').html(txtdestweight);
            $('#emptyrubrictxt_'+rowid+'_8').html(txtdestscore);
        }
    }
}
/****** Used to append the popup form data in to form END here *******/

/****** this function to show the popup to get destination content text form in EXISTING Content  start here******/
function fn_showdeststmteditform(expid,destid,rowid,type,rubid,addcategorg,destname)
{
    var indrubricdet=new Array();
    var rubricvalue='';
    var k=0;
    if(addcategorg==1)
	{  ///existing records
        for(var i=1;i<=8;i++) //column count
        {
        rubricvalue=$('#rubrictxt_'+rowid+'_'+i).html();
        if(rubricvalue!=undefined)
        {
            if(i==1){
                indrubricdet[k]=','+$('#rubrictxt_'+rowid+'_'+i).html()+"^";
            }
            else{
                indrubricdet[k]=$('#rubrictxt_'+rowid+'_'+i).html()+"^";
            }
        }
        k++;
        }
    }
    else if(addcategorg==2)
	{ ///add aditional category for existing record
        for(var i=1;i<=8;i++) //column count
         {
             rubricvalue=$('#rubrictxte_'+rowid+'_'+i).html();
             if(rubricvalue!=undefined)
             { 
                 if(i==1){
                     indrubricdet[k]=','+$('#rubrictxte_'+rowid+'_'+i).html()+"^";    
                 }
                 else{
                     indrubricdet[k]=$('#rubrictxte_'+rowid+'_'+i).html()+"^";
                 }
             }
             k++;
         } 
    }
    else
	{ ///empty destination additional category
        for(var i=1;i<=8;i++) //column count
        {
            rubricvalue=$('#emptyrubrictxt_'+rowid+'_'+i).html();
            if(rubricvalue!=undefined)
            {
                if(i==1){
                    indrubricdet[k]=','+$('#emptyrubrictxt_'+rowid+'_'+i).html()+"^";
                }
                else{
                    indrubricdet[k]=$('#emptyrubrictxt_'+rowid+'_'+i).html()+"^";
                }
            }
            k++;
        }
    }   
    
    $.fancybox.showActivity();
    $.ajax({
            type	: "POST",
            cache	: false,
            url	: "library/missionrubric/library-missionrubric-graderubricajax.php",
             data    :"oper=deststmtform&_="+timestamp+"&exp_id="+expid+"&dest_id="+destid+"&rowid="+rowid+"&type="+type+"&rubid="+rubid+"&indrubricdet="+indrubricdet+"&destname="+destname,
            success: function(data) {
                    $.fancybox(data,{'modal': true,'autoDimensions':false,'width':560,'autoScale':true,'height':500, 'scrolling':'no'});
                    $.fancybox.resize();
            }
        });
    return false;
}
/****** this function to show the popup to get destination content text form in EXISTING Content  END here******/

/****** this function to show the popup to get destination content to new text form  start here******/
var timestamp=new Date().getTime();
function fn_showdeststmtform(expid,destid,type,rubid,emptydestid,destname)
{ 	
    $.fancybox.showActivity();
    $.ajax({
		type	: "POST",
		cache	: false,
		url	: "library/missionrubric/library-missionrubric-graderubricajax.php",
	 	data    :"oper=deststmtform&_="+timestamp+"&exp_id="+expid+"&dest_id="+destid+"&type="+type+"&rubid="+rubid+"&empdestid="+emptydestid+"&destname="+destname,

		success: function(data) {
			$.fancybox(data,{'modal': true,'autoDimensions':false,'width':560,'autoScale':true,'height':500, 'scrolling':'no'});
			$.fancybox.resize();
		}
	});
    return false;
}
/****** this function to show the popup to get destination content text form  End here******/

/***********SAVE or Update the Rubric statement in all level start here************/
function fn_saveasrubric(id,rubricid,updateid)
{
    if($("#rubricforms").validate().form()) //Validates the Expedition Form
    {
        
        var rubricname=$('#txtrubricnameforsaveas').val();
        var ownrubricname=$('#txtrubricname').val();
        var rubcount=$('#hidrubriccount').val();
        var rubriccount=JSON.parse(rubcount);
      
        if(updateid=='0'){
            if(rubricname == ''){
                showloadingalert("To assign a new name to the Rubric");	
                setTimeout('closeloadingalert()',2000);
                $('#txtrubricnameforsaveas').focus();
                return false;
            }
        }
	 /****** existing rubric statement Start here********/
        var rubricdet=new Array();
        var rubricvalue='';
        var k=0;
        for(var j=0;j<=rubriccount.length;j++) //row count
        { 
            for(i=1;i<=8;i++) //column count
            {
                rubricvalue=$('#rubrictxt_'+rubriccount[j]+'_'+i).html();
            
                if(rubricvalue!=undefined)
                {
                    if(i==8)
                    {
						var destid=$('#hiddestid_'+rubriccount[j]+'_'+0).val();
                        rubricdet[k]=$('#rubrictxt_'+rubriccount[j]+'_'+i).html()+"^"+','+$('#hiddestid_'+rubriccount[j]+'_'+0).val()+"^"+','+$('#hidrubrowid_'+rubriccount[j]+'_'+0).val()+"^"+','+$('#hiddestname_'+destid+'_0').val()+"~";
                    }
                    else{ 
                        if(j==0){
                            if(i==1){//add the comma for first value
                                rubricdet[k]=','+$('#rubrictxt_'+rubriccount[j]+'_'+i).html()+"^"; 
                            }
                            else{
                                  rubricdet[k]=$('#rubrictxt_'+rubriccount[j]+'_'+i).html()+"^";
                            }
                        }
                        else{
                            rubricdet[k]=$('#rubrictxt_'+rubriccount[j]+'_'+i).html()+"^";
                        }
                    }
                }
                k++;
            }
        }
      	//alert("existing rubric statement: "+rubricdet);
	/****** existing rubric statement end here********/
        
   	/******* Add aditional category start*******/ 
        var dbrubcount=$('#hiddbrubriccount').val();
	  	var dbendrowcount=0;//$('#hiddbrdestcount').val();
        var tblrowCount = $('#mytable tr').not("thead tr").length;
        var addrubricdet=new Array();
        var addrubricvalue='';
        var h=0;
        var mmm=$('#hidinddestid_'+m+'_'+0).val();		
        for(var m=dbendrowcount;m<=tblrowCount;m++) //row count    
        { 
            for(var n=1;n<=8;n++) //column count
            {
                addrubricvalue=$('#rubrictxte_'+m+'_'+n).html();
                if(addrubricvalue!=undefined)
                {
                    if(n==8)
                    {
						var destid=$('#hidinddestid_'+m+'_'+0).val();
                        addrubricdet[h]=$('#rubrictxte_'+m+'_'+n).html()+"^"+','+$('#hidinddestid_'+m+'_'+0).val()+"^"+','+$('#hiddestname_'+destid+'_0').val()+"~";
                    }
                    else
                    {
                        if(m==dbendrowcount){  
                            if(n==1){
                                addrubricdet[h]=','+$('#rubrictxte_'+m+'_'+n).html()+"^";
                            }
                            else{
                                  addrubricdet[h]=$('#rubrictxte_'+m+'_'+n).html()+"^";
                            }
                        }
                        else{
                           addrubricdet[h]=$('#rubrictxte_'+m+'_'+n).html()+"^";
                        }
                   }

                }
                h++;
            }
        }
       //	alert("Add aditional category :"+addrubricdet);
	/******* Add aditional category End*******/ 
    
	/******* if a destination is empty*******/ 
        var dbrubcount1=$('#hiddbrubriccount').val();
        var dbendrowcount=0;//$('#hiddbrdestcount').val();
        var tblrowCount1 = $('#mytable tr').not("thead tr").length;
        var addrubricdet1=new Array();
        var addrubricvalue1='';
        var s=0;
        var mmm=$('#hidinddestid_'+m+'_'+0).val();

        for(var m=dbendrowcount;m<=tblrowCount1;m++) //row count    
        { 
            for(var n=1;n<=8;n++) //column count
            {
                addrubricvalue1=$('#emptyrubrictxt_'+m+'_'+n).html();
                if(addrubricvalue1!=undefined)
                {
                    if(n==8)
                    {
						var destid=$('#hidempinddestid_'+m+'_'+0).val();
                        addrubricdet1[s]=$('#emptyrubrictxt_'+m+'_'+n).html()+"^"+','+$('#hidempinddestid_'+m+'_'+0).val()+"^"+','+$('#hiddestname_'+destid+'_0').val()+"~";
                    }
                    else
                    {
                        if(m==dbendrowcount){ 
                            if(n==1){
                                addrubricdet1[s]=','+$('#emptyrubrictxt_'+m+'_'+n).html()+"^";
                            }
                            else{
                                  addrubricdet1[s]=$('#emptyrubrictxt_'+m+'_'+n).html()+"^";
                            }
                        }
                        else{
                           addrubricdet1[s]=$('#emptyrubrictxt_'+m+'_'+n).html()+"^";
                        }
                   }

                }
                s++;
            }
        }
        // alert("if a destination is empty :"+addrubricdet1);
    /******* if a destination is empty*******/  
    
          if(updateid=='0')
		  {
				actionmsg = "Saving";
				alertmsg = "Rubric has been Created Successfully"; 
          }
          else
		  {
				actionmsg = "Updating";
				alertmsg = "Rubric has been updated Successfully"; 
          }

        var dataparam = "oper=saveasrubric&rubname="+rubricname+"&expid="+id+"&rubricid="+rubricid+"&rubricdet="+rubricdet+"&addrubricdet="+addrubricdet+"&updatedid="+updateid+"&ownrubricname="+ownrubricname+"&addrubricdet1="+addrubricdet1;
        $.ajax({
               url: 'library/missionrubric/library-missionrubric-graderubricajax.php',
                data: dataparam,
                type: "POST",
                beforeSend: function(){
                        showloadingalert(actionmsg+", please wait.");	
                },
                success: function (data) {                  
                        if(data=="success") //Works if the data saved in db
                        {
                                $('.lb-content').html(alertmsg);
                                setTimeout('closeloadingalert()',500);
                                setTimeout('removesections("#library-missionrubric");',1000);
                                setTimeout('showpageswithpostmethod("library-missionrubric-rublist","library/missionrubric/library-missionrubric-rublist.php","id='+id+'");',1000);
                        }
                        else
                        {
                                $('.lb-content').html("Invalid data So it cannot update");
                                setTimeout('closeloadingalert()',1000);
                        }
                },
        });
     }
}
/***********SAVE or Update the Rubric statement in all level End here************/

/*--- Delete the rubric statement start here---*/
function fn_deletedestexpform(expid,destid,rowid,type,rubid,proid,score)
{
    fn_canceldestform();
    var dataparam = "oper=deletedestexpform"+"&exp_id="+expid+"&dest_id="+destid+"&rowid="+rowid+"&type="+type;		
	$.Zebra_Dialog('Are you sure you want to delete this Rubric Statement?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
					type: 'post',
					url: 'library/missionrubric/library-missionrubric-graderubricajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert('Deleting Rubric Statement, please wait.');	
					},
					success: function (data) {	
						if(data=="success")
						{
							closeloadingalert();	
							showloadingalert("Rubric Statement deleted successfully");
							setTimeout('closeloadingalert()',500);
							
							var txtoldscore=$('#hiddbtotscore').val();	
							suboldscore=parseInt(txtoldscore)-parseInt(score);
							finalscore=parseInt(suboldscore);

							$('#hiddbtotscore').val(finalscore);	
							$('#totscore').html(finalscore);
							
							$("#exp-rubric-"+rowid).remove();
							
							var dbendrowcount=$('#hiddbrdestcount').val();
							var tblrowCount = $('#mytable tr').not("thead tr").length;
							var neworimport=$('#hidneworimport').val();
							
							removerow=parseInt(dbendrowcount)-parseInt(tblrowCount);

							finalrow=parseInt(dbendrowcount)-parseInt(removerow);
							if(neworimport==0)
							{
								$('#hiddbrdestcount').val(finalrow);
							}
						}
						else
						{
							closeloadingalert();	
							showloadingalert("Deleting the Rubric Statement has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}}
		]
	});
}
function fn_deleterubric(expid,rubid)
{
    var dataparam = "oper=deleterubric"+"&exp_id="+expid+"&rowid="+rubid;	   
    $.Zebra_Dialog('Are you sure you want to delete this Rubric?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
						type: 'post',
						url: 'library/missionrubric/library-missionrubric-graderubricajax.php',
						data: dataparam,
						beforeSend: function(){
								showloadingalert('Checking, please wait.');	
						},
						success: function (data) {	
							if(data=="success")
							{
								closeloadingalert();	
								showloadingalert("Rubric has been Deleted Successfully");
								setTimeout('closeloadingalert()',500);
								setTimeout('removesections("#library-missionrubric");',1000);
								setTimeout('showpageswithpostmethod("library-missionrubric-rublist","library/missionrubric/library-missionrubric-rublist.php","id='+expid+'");',1000);
							}
							else
							{
								closeloadingalert();	
								showloadingalert("Deleting has been failed");
								setTimeout('closeloadingalert()',1000);
							}
						}
				});	
			}}
		]
	});
}
/*--- Delete the rubric statement End here---*/

/*********** ADD New Section code start here ************/
function fn_addnewsection(expid,rubid,destid,destname,editornew,flag)
{
	var lastrowid=$('#hidaddnewsec').val();	
	var destname = $('#destnationtxt-'+destid).text();	
	if(flag==0)
	{
		document.getElementById('bottomOfDiv').scrollIntoView(true);
	}	
	$.fancybox.showActivity();
    $.ajax({
		type	: "POST",
		cache	: false,
		url: 'library/missionrubric/library-missionrubric-graderubricajax.php',
	 	data    :"oper=addnewsectiondestination&exp_id="+expid+"&rubid="+rubid+"&editid="+destid+"&destname="+destname+"&editornew="+editornew+"&lastrowid="+lastrowid+"&flag="+flag,
		success: function(data) 
		{
			$.fancybox(data,{'modal': true,'autoDimensions':false,'width':480,'autoScale':true,'height':260, 'scrolling':'no'});
			$.fancybox.resize();
		}
	});
    return false;
}
/*********** ADD New Section code End here ************/

/**************Append the tempory data to form code start here****************/
function fn_tempsavedestsecform(expid,rubid,tempdestid,editornew,flag)
{
    if($("#expdestsecform").validate().form())
    {
		var txtdestcategoryname=$('#txtdestsecname').val();
	 	fn_canceldestform();
		var lastrowid=$('#hidaddnewsec').val();	
		var lastrowincre=parseInt(lastrowid)+1;
		$('#hidaddnewsec').val(lastrowincre);

		var hidtrrubric=$('#hidtrrubric').val();
		var countdest=parseInt(hidtrrubric)+1;
		$('#hidtrrubric').val(countdest);
		
		var hidtrrubricas=$('#hidtrcrubric').val();
		var countdestas=parseInt(hidtrrubricas)+1;
		$('#hidtrcrubric').val(countdestas);
		
		var rowCount = $('#mytable tr').not("thead tr").length;
		$('#hiddestname_'+tempdestid+'_0').val(txtdestcategoryname);		
		
		
		if(editornew==0)
		{
			 $('#destnationtxt-'+tempdestid).html(txtdestcategoryname);
			
		}
		else
		{
			var Content1='<tbody id="row_'+tempdestid+'"><tr >\n\
							<td >Interval '+countdestas+'</td>\n\
							<td id="destnationtxt-'+tempdestid+'" colspan="7" class="createnewtd" onclick="fn_addnewsection(\''+expid+'\',\''+rubid+'\',\''+tempdestid+'\',\''+txtdestcategoryname+'\',\''+0+'\')">'+txtdestcategoryname+'</td>\n\
						  </tr>';
			var Content2='<tr id="exp-extend-0"><td colspan="8" class="createnewtd"></td></tr>';
			var Content3='<tr value="'+countdest+'" id="countrow-'+countdest+'">\n\
							<td colspan="8"><span onclick="fn_showdeststmtform(\''+expid+'\',\''+tempdestid+'\',\''+0+'\',\''+rubid+'\',\''+countdest+'\',\''+txtdestcategoryname+'\')"><span class="icon-synergy-add-dark small-icon" style="padding-top:10px;"></span> Add additional category to this Interval</span></td>\n\
						  </tr></tbody>';
			var newRowContent=Content1+Content2+Content3;//

			$('#mytable').last().append(newRowContent);
			if(flag==1)
			{
			$('html, body').animate({scrollTop: $(document).height()}, 800);
			}
			else
			{
				document.getElementById('bottomOfDiv').scrollIntoView(true);
			}
			
		}
    }
}
/**************Append the tempory data to form code start here****************/

/************Remove section code start here***********/
function fn_tempdeletedestsecform(expid,rubid,destid)
{
    fn_canceldestform();
    $.Zebra_Dialog('Are you sure you want to remove this section?',
    {
        'type': 'confirmation',
        'buttons': [
                {caption: 'No', callback: function() { }},
                {caption: 'Yes', callback: function() {
                    //alert(destid);
                    $("#head_"+destid).remove();
                    $("#row_"+destid).remove();

				var hidtrrubric=$('#hidtrcrubric').val();
				var countdest=parseInt(hidtrrubric)-1;
				$('#hidtrcrubric').val(countdest);
				
                    var dbendrowcount=$('#hiddbrdestcount').val();
                    var tblrowCount = $('#mytable tr').not("thead tr").length;                    
                    removerow=parseInt(dbendrowcount)-parseInt(tblrowCount);

                    finalrow=parseInt(dbendrowcount)-parseInt(removerow);
                    var neworimport=$('#hidneworimport').val();
                    if(neworimport==0)
                    {
                            $('#hiddbrdestcount').val(finalrow);
                    }
            }}
        ]
    });
}
/************Remove section code start here***********/

/**********Download PDF COde start here*********/
function fn_downloadpdf(expid,rubid,rubricname)
{
	var dataparam = "oper=download";	
	$.ajax({
		 url: 'library/missionrubric/library-missionrubric-graderubricajax.php',
		data: dataparam,
		type: "POST",
		beforeSend: function()
		{
			showloadingalert("Loading, please wait.");	
		},
		success: function (data) 
		{	
			if(data=="success") //Works if the data saved in db
			{
				var val = expid+"~"+rubid;
				var oper="downloadpdfmissionreport";
				var filename=rubricname+new Date().getTime();
				var a=1;
				setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'&downloadid='+a+'");',500);
			}
			setTimeout(function(){ fn_download(filename); });
		},
	});
}
function fn_download(filename)
{
 	var actionmsg = "Loading";
 	var dataparam = "oper=download";	
	$.ajax({
		 url: 'library/missionrubric/library-missionrubric-graderubricajax.php',
		data: dataparam,
		type: "POST",
		beforeSend: function()
		{
			showloadingalert(actionmsg+", please wait.");	
		},
		success: function (data) 
		{	
			if(data=="success") //Works if the data saved in db
			{
				var fileformat = "pdf";				
				setTimeout('closeloadingalert()');
				window.location=("library/rubric/library-rubric-download.php?filename="+filename+'&fileformat='+fileformat);				
			}

		},
	});
}
/**********Download PDF COde end here*********/






