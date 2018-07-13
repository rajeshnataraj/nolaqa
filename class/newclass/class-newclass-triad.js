// JavaScript Document

function tablerowtriad(trlength,i,studentinfo,studentinfo1,flag)
{
	if(flag=="uncheck")
	{
		if(i!=2)
		{
			
			var val=parseInt(i)-parseInt(2);
		}
		else
		{
			var val=1;
		}
	}
	else
	{
		val=1;
	}
	
	var seg1row='true';
	var seg2row='true';
	
		for(zj=val;zj<i;zj++)
		{
				
				if(($('#seg1_'+trlength+"_"+zj).html()==studentinfo) || ($('#seg2_'+trlength+"_"+zj).html()==studentinfo))
				{
					
					// don't insert the student zk	
					seg1row="false";
					
				
					break;
				}
				if(($('#seg1_'+trlength+"_"+zj).html()==studentinfo1) || ($('#seg2_'+trlength+"_"+zj).html()==studentinfo1))
				{
					
					// don't insert the student zk	
					seg2row="false";
					break;
				}
		}
		
	return seg1row+"~"+seg2row;
}

/* Save Dyad table details */
function fn_savetriadscheduletable()
{

	// Get module id and name
	var module=new Array();
	var modulecount=$('#numberofmodules').val();
	var moduleid='';
	var rotationid='';
	var modulename='';
	var studentid='';
	var studentname='';
	var cell=new Array();

	
	
	var j=0;
	for(i=1;i<=modulecount;i++)
	{
		moduleid = $('#module'+i+" "+'span').attr('id');	
		modulename=$('#'+moduleid).html();

		if(moduleid!='undefined')
		{
		  module[j]=moduleid;
		}
		j++;
	}
	
	
	var k=0;
	for(i=1;i<=modulecount;i++)
	{
		for(j=1;j<=modulecount;j++)
		{
			
			moduleid = $('#module'+i+" "+'span').attr('id');
			rotationid=j;
			studentid=($('#seg1_'+i+"_"+j+' span').attr('id'));
			studentname=$('#'+studentid).html();
			
			cell[k]=moduleid+"~"+rotationid+"~"+"seg1_"+i+"_"+j+"~"+studentid+"~";
			k++;
			
			studentid=($('#seg2_'+i+"_"+j+' span').attr('id'));
			studentname=$('#'+studentid).html();
			
			cell[k]=moduleid+"~"+rotationid+"~"+"seg2_"+i+"_"+j+"~"+studentid+"~";
			k++;
			
		}
	}
	
	if($('#dyadflag').val()==1 && $('#hidgenerate').val()==1 && $('#hidmodule').val()>0)
	{
		$.Zebra_Dialog('Student grades will be lost, Are you sure you want to save ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { return false; }},
											{caption: 'Yes', callback: function() { 
	
	var dataparam="oper=saverotation&moduledet="+module+"&celldet="+cell+"&classid="+$('#hidclassid').val()+"&scheduleid="+$('#scheduleid').val()+"&numberofrotation="+$('#numberofmodules').val()+"&startdate="+$('#triadstartdate').val()+"&enddate="+$('#triadenddate').val()+"&dyadflag=1";
	
		
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-triad-ajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {	
				
				closeloadingalert();	
				showloadingalert("Saved Sucessfully.");	
				setTimeout("closeloadingalert();",2000);
				setTimeout("removesections('#class-newclass-newschedulestep');",500); 
				setTimeout("removesections('#class-newclass-steps');",500);		
				setTimeout("removesections('#class-newclass-actions');",500);			
				setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',500);
				
		}
	});
	
	}},
										]
							});
							return false;
			
	}
	else
	{
		var dataparam="oper=saverotation&moduledet="+module+"&celldet="+cell+"&classid="+$('#hidclassid').val()+"&scheduleid="+$('#scheduleid').val()+"&numberofrotation="+$('#numberofmodules').val()+"&startdate="+$('#triadstartdate').val()+"&enddate="+$('#triadenddate').val();
	
	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-triad-ajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {	
				
				closeloadingalert();	
				showloadingalert("Saved Sucessfully.");	
				setTimeout("closeloadingalert();",2000);
				setTimeout("removesections('#class-newclass-newschedulestep');",500);
				setTimeout("removesections('#class-newclass-steps');",500);			
				setTimeout("removesections('#class-newclass-actions');",500);			
				setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',500);
				
		}
	});
	}
}

function fn_viewtriadschedule(classid,scheduleid)
{
	var scheduletypeid=4;
	setTimeout("removesections('#class-newclass-calendar');",500);
	setTimeout('showpageswithpostmethod("class-newclass-viewtriadscheduleedit","class/newclass/class-newclass-viewtriadscheduleedit.php","id='+scheduleid+","+scheduletypeid+","+classid+'");',500);
}

function fn_generatetriadschedule(flag)
{
	showloadingalert("Generating the Triad schedule.");
	setTimeout("fn_generatetriad("+flag+");",1000);
}


function fn_generatetriad(flag)
{
	
	if($('#dyadflag').val()==1)
	{
		$('#hidgenerate').val(1);
	}
	
	$('div.triad').removeClass('lightrot darkrot');
	$('.triadtop').empty();
	$('.triadbottom').empty();
	
	var seats=$('#numberofmodules').val()*parseInt(2);
	var numofmodules=$('#numberofmodules').val();
	var studentcount=seats;
	var rot=$('#rotations').val();
	var trlength=$('#numberofmodules').val();
	
	if(seats==studentcount)
	{
		var combination="true";
	}
	
	var newstu = $('#stuidname').val();
	var tempstu ='';
	
	if(parseInt($('#studentcount').val())<seats)
	{
		var couval = seats - parseInt($('#studentcount').val());
		for(i=0;i<couval;i++)
		{
			if(tempstu=='')
			{
				tempstu = ",Student NN"+i+"~0";
			}
			else
			{
				tempstu = tempstu+",Student NN"+i+"~0";
			}
		}
		
		
		var tem = newstu+tempstu;
		$('#stuidname').val(tem);
	}
	
	var stuname=$('#stuidname').val().split(',');
	if(flag==2)
	{
		stuname = arrayShuffle(stuname); 
	}
	var stunamecopy = stuname; 
	
		var retry=0;
		var pairstudenttemp=new Array();
		var pairstudentdup=new Array();
		var pairstudentper=new Array();
		var studettemp=new Array();
		var stunameadd=new Array();
		var i=1;
		while(i<=rot)
		{
				
				
				if(i>3)
				{
					
					if(i%2==0 && i%3==1)
					{
						stuname = arrayShuffle(stunamecopy);
						stunameadd=[];
						pairstudentper=[];
						
					}
					else if(i%2==1 && i%3==1)
					{
						stuname = arrayShuffle(stunamecopy);
						stunameadd=[];
						pairstudentper=[];
						
					}
					else
					{
						if(stunameadd.length>0)
						{
						   var stunamedup=stunameadd;
						   stuname = stunameadd;
						}
						else
						{
							stuname=stunamedup;
						}
						
						var stuname1=new Array();
					var stuname2=new Array();
					var stuname3=new Array();
					var stuname4=new Array();
					var stuname5=new Array();
					var stuname6=new Array();
					var stuname7=new Array();
					var stuname8=new Array();
					var s1=0;
					var s2=0;
					var s3=0;
					var s4=0;
					var s5=0;
					var s6=0;
					var s7=0;
					var s8=0;
					for(v=0;v<stuname.length;v++)
					{
						if(v<=5)
						{
							stuname1[s1]=stuname[v];
							s1++;
						}
						else if(v>5 && v<=11)
						{
							stuname2[s2]=stuname[v];
							s2++;
						}
						else if(v>11 && v<=17)
						{
							stuname3[s3]=stuname[v];
							s3++;
						}
						else if(v>17 && v<=23)
						{
							stuname4[s4]=stuname[v];
							s4++;
						}
						else if(v>23 && v<=29)
						{
							stuname5[s5]=stuname[v];
							s5++;
						}
						else if(v>29 && v<=35)
						{
							stuname6[s6]=stuname[v];
							s6++;
						}
						else if(v>35 && v<=41)
						{
							stuname7[s7]=stuname[v];
							s7++;
						}
						else if(v>41 && v<=47)
						{
							stuname8[s8]=stuname[v];
							s8++;
						}
					}
				}
					
					
					
				}
				else
				{
					
					if(i>1)
					{
						
						if(stunameadd.length>0)
						{
						   var stunamedup=stunameadd;
						   stuname = stunameadd;
						}
						else
						{
							stuname=stunamedup;
						}
					}
					var stuname1=new Array();
					var stuname2=new Array();
					var stuname3=new Array();
					var stuname4=new Array();
					var stuname5=new Array();
					var stuname6=new Array();
					var stuname7=new Array();
					var stuname8=new Array();
					var s1=0;
					var s2=0;
					var s3=0;
					var s4=0;
					var s5=0;
					var s6=0;
					var s7=0;
					var s8=0;
					
					for(v=0;v<stuname.length;v++)
					{
						if(v<=5)
						{
							stuname1[s1]=stuname[v];
							s1++;
						}
						else if(v>5 && v<=11)
						{
							stuname2[s2]=stuname[v];
							s2++;
						}
						else if(v>11 && v<=17)
						{
							stuname3[s3]=stuname[v];
							s3++;
						}
						else if(v>17 && v<=23)
						{
							stuname4[s4]=stuname[v];
							s4++;
						}
						else if(v>23 && v<=29)
						{
							stuname5[s5]=stuname[v];
							s5++;
						}
						else if(v>29 && v<=35)
						{
							stuname6[s6]=stuname[v];
							s6++;
						}
						else if(v>35 && v<=41)
						{
							stuname7[s7]=stuname[v];
							s7++;
						}
						else if(v>41 && v<=47)
						{
							stuname8[s8]=stuname[v];
							s8++;
						}
					}
					
				}
				
				
				
				arraymove(stuname1,4,0);
				arraymove(stuname1,5,1);
						
				arraymove(stuname2,4,0);
				arraymove(stuname2,5,1);
				
				arraymove(stuname3,4,0);
				arraymove(stuname3,5,1);
				
				arraymove(stuname4,4,0);
				arraymove(stuname4,5,1);
				
				arraymove(stuname5,4,0);
				arraymove(stuname5,5,1);
				
				arraymove(stuname6,4,0);
				arraymove(stuname6,5,1);
				
				arraymove(stuname7,4,0);
				arraymove(stuname7,5,1);
				
				arraymove(stuname8,4,0);
				arraymove(stuname8,5,1);
				
				arrayShuffle(stuname1);
				arrayShuffle(stuname2);
				arrayShuffle(stuname3);
				arrayShuffle(stuname4);
				arrayShuffle(stuname5);
				arrayShuffle(stuname6);
				arrayShuffle(stuname7);
				arrayShuffle(stuname8);
				
				
				
				
				
			var j=1;
			var zkk=0;
			var countstudent=0;
			
			
			
			while(j<=trlength)
			{
				
				if($('#seg1_'+j+"_"+i).html()!='' && $('#seg2_'+j+"_"+i).html()!='' || i>trlength)
				{
					// don't insert the student zk	
		
					segcol="false";
					segrow="false";
					break;
								
				}
				else
				{
					var noofstudentscombchk=0;
					zkforloopbreakchk=0;
					
					if(i==1 || (i%2==0 && i%3==1) || (i%2==1 && i%3==1))
					{
						for(zk=0;zk<stuname.length;zk++) 
							{
							
							// check the first segment,check the first segment for empty,check the first segment with student zk
							var splitstuname=stuname[zk].split("~");
							for(zk1=zk+1;zk1<stuname.length;zk1++) // for loop starts
							{
								var splitstuname1=stuname[zk1].split("~");
								
								
									
										segrow=tablerowtriad(j,i,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>','<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>','fullcheck');
										segrowarr=segrow.split("~");
										
									if(segrowarr[0]=="true" && segrowarr[1]=="true")
									{										
										
											
											
											$('#seg1_'+j+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
											$('#seg2_'+j+"_"+i).html('<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
											var studet=new Array(splitstuname[0]+"~"+splitstuname[1],splitstuname1[0]+"~"+splitstuname1[1]);
											stunameadd.push(splitstuname[0]+"~"+splitstuname[1],splitstuname1[0]+"~"+splitstuname1[1]);
											
											stuname=arraycompare(stuname,studet);
											
											pairstudenttemp.push('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>'+":"+'<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
											
											countstudent=countstudent+2;
											zkforloopbreakchk=1;
											break;										
											
											
										
									}  // Row check if end
								
							
						  } // for loop ends
						  if(zkforloopbreakchk==1)
						  {
							  break;
						  }
						} // for loop end
						
				if(zkforloopbreakchk==0)
				{
					
					var jval=j-parseInt(1);
					
					for(k=0;k<stuname.length;k++)
					{
						var stu1=stuname[k].split("~");k++;
						var stu2=stuname[k].split("~");
						
							for(z=jval;z>=1;z--)
							{
									segrow=tablerowtriad(z,i,'<span id="'+stu1[1]+'">'+stu1[0]+'</span>','<span id="'+stu2[1]+'">'+stu2[0]+'</span>','fullcheck');
									
									segrowarr=segrow.split("~");
									
									if(segrowarr[0]=="false" || segrowarr[1]=="false")
									{
										segrow="false";
										
									}
									
									segrow1=tablerowtriad(j,i,$('#seg1_'+z+"_"+i).html(),$('#seg2_'+z+"_"+i).html(),'fullcheck');
									
									segrowarr1=segrow1.split("~");
									
									if(segrowarr1[0]=="false" || segrowarr1[1]=="false")
									{
										var segrow11='';
										segrow11="false";
										
									}
									
									if((segrow=="false") || (segrow11=="false")) // student in same row or same column 
									{
										var zkforloopbreakchk=0;
										
									}
									else
									{										
											$('#seg1_'+j+"_"+i).html($('#seg1_'+z+"_"+i).html());
											$('#seg2_'+j+"_"+i).html($('#seg2_'+z+"_"+i).html());
											$('#seg1_'+z+"_"+i).html('<span id="'+stu1[1]+'">'+stu1[0]+'</span>');
											$('#seg2_'+z+"_"+i).html('<span id="'+stu2[1]+'">'+stu2[0]+'</span>');
											
											var studett=new Array(stu1[0]+"~"+stu1[1],stu2[0]+"~"+stu2[1]);
											
											stunameadd.push(stu1[0]+"~"+stu1[1],stu2[0]+"~"+stu2[1]);
										
											stuname=arraycompare(stuname,studet);
											
											pairstudenttemp.push('<span id="'+stu1[1]+'">'+stu1[0]+'</span>'+":"+'<span id="'+stu2[1]+'">'+stu2[0]+'</span>');
												
											countstudent=countstudent+2;
											var zkforloopbreakchk=1;
											break;										
										 
									} // Row scheck else end
									
								} // z for loop end
								
					  if(zkforloopbreakchk==1)
					  {
						  break;
					  }
							
					} // for loop end 
				}
						
				if(zkforloopbreakchk==0)
				{
					var jval=j-parseInt(1);
					for(k=0;k<stuname.length;k++)
					{
						var stu1=stuname[k].split("~");k++;
						var stu2=stuname[k].split("~");
						
						
							for(z=jval;z>=1;z--)
							{
									
									segrow=tablerowtriad(z,i,'<span id="'+stu2[1]+'">'+stu2[0]+'</span>',$('#seg2_'+z+"_"+i).html(),'fullcheck');
									
									segrowarr=segrow.split("~");
									
									if(segrowarr[0]=="false" || segrowarr[1]=="false")
									{
										segrow="false";
										
									}
									
									
									segrow1=tablerowtriad(j,i,'<span id="'+stu1[1]+'">'+stu1[0]+'</span>',$('#seg1_'+z+"_"+i).html(),'fullcheck');
									
									
									segrowarr1=segrow1.split("~");
									
									if(segrowarr1[0]=="false" || segrowarr1[1]=="false")
									{
										var segrow11='';
										segrow11="false";
										
									}
									
									
									if((segrow=="false") || (segrow11=="false")) // student in same row or same column 
									{
										// don't insert the student zk
										var zkforloopbreakchk=0;
									}
									else
									{
										// insert the student zk in the first segment										
											$('#seg1_'+j+"_"+i).html('<span id="'+stu1[1]+'">'+stu1[0]+'</span>');
											$('#seg2_'+j+"_"+i).html($('#seg1_'+z+"_"+i).html());
											$('#seg1_'+z+"_"+i).html('<span id="'+stu2[1]+'">'+stu2[0]+'</span>');
											$('#seg2_'+z+"_"+i).html($('#seg2_'+z+"_"+i).html());
											
											var studett=new Array(stu1[0]+"~"+stu1[1],stu2[0]+"~"+stu2[1]);
											
											stunameadd.push(stu1[0]+"~"+stu1[1],stu2[0]+"~"+stu2[1]);
										
											stuname=arraycompare(stuname,studet);
											
											pairstudenttemp=arraycompare(pairstudenttemp,$('#seg1_'+z+"_"+i).html()+":"+$('#seg2_'+z+"_"+i).html());
											
											pairstudenttemp.push('<span id="'+stu1[1]+'">'+stu1[0]+'</span>'+":"+$('#seg1_'+z+"_"+i).html(),'<span id="'+stu2[1]+'">'+stu2[0]+'</span>'+":"+$('#seg2_'+z+"_"+i).html());
											
											countstudent=countstudent+2;
											var zkforloopbreakchk=1;
											break;										
										
									 
								} // else end
									
							} // for loop end
							if(zkforloopbreakchk==1)
						  	{
							  break;
						  	}
						} // for loop end
						
				} // if end
				
				
				if(zkforloopbreakchk==0)
				{
					var jval=j-parseInt(1);
					for(k=0;k<stuname.length;k++)
					{
						var stu1=stuname[k].split("~");k++;
						var stu2=stuname[k].split("~");
						
						
							for(z=jval;z>=1;z--)
							{
								
								
									
									segrow=tablerowtriad(z,i,'<span id="'+stu2[1]+'">'+stu2[0]+'</span>',$('#seg1_'+z+"_"+i).html(),'fullcheck');
									
									segrowarr=segrow.split("~");
									
									if(segrowarr[0]=="false" || segrowarr[1]=="false")
									{
										segrow="false";
										
									}
									
									
									
									segrow1=tablerowtriad(j,i,'<span id="'+stu1[1]+'">'+stu1[0]+'</span>',$('#seg2_'+z+"_"+i).html(),'fullcheck');
									
									
									segrowarr1=segrow1.split("~");
									
									if(segrowarr1[0]=="false" || segrowarr1[1]=="false")
									{
										var segrow11='';
										segrow11="false";
										
									}
									
									
									if((segrow=="false") || (segrow11=="false")) // student in same row or same column 
									{
										// don't insert the student zk
										var zkforloopbreakchk=0;
									}
									else
									{
										
										// insert the student zk in the first segment										
											$('#seg1_'+j+"_"+i).html('<span id="'+stu1[1]+'">'+stu1[0]+'</span>');
											$('#seg2_'+j+"_"+i).html($('#seg2_'+z+"_"+i).html());
											$('#seg1_'+z+"_"+i).html('<span id="'+stu2[1]+'">'+stu2[0]+'</span>');
											$('#seg2_'+z+"_"+i).html($('#seg1_'+z+"_"+i).html());
											
											var studett=new Array(stu1[0]+"~"+stu1[1],stu2[0]+"~"+stu2[1]);
											
											stunameadd.push(stu1[0]+"~"+stu1[1],stu2[0]+"~"+stu2[1]);
										
											stuname=arraycompare(stuname,studet);
											
											pairstudenttemp=arraycompare(pairstudenttemp,$('#seg1_'+z+"_"+i).html()+":"+$('#seg2_'+z+"_"+i).html());
											
											pairstudenttemp.push('<span id="'+stu1[1]+'">'+stu1[0]+'</span>'+":"+$('#seg2_'+z+"_"+i).html(),'<span id="'+stu2[1]+'">'+stu2[0]+'</span>'+":"+$('#seg1_'+z+"_"+i).html());
											
											countstudent=countstudent+2;
											var zkforloopbreakchk=1;
											break;										
										
									 
								} // else end
									
							} // for loop end
							
							if(zkforloopbreakchk==1)
						  	{
							  break;
						  	}
							
						} // for loop end
						
				} // if end
					
					}
					else
					{
					if(j<=3)
					{
						var length=stuname1.length;
						
					}
					else if(j>3 && j<=6)
					{
						var length=stuname2.length;
	
					}
					else if(j>6 && j<=9)
					{
						var length=stuname3.length;
					}
					else if(j>9 && j<=12)
					{
						var length=stuname4.length;
					}
					else if(j>12 && j<=15)
					{
						var length=stuname5.length;
					}
					else if(j>15 && j<=18)
					{
						var length=stuname6.length;
					}
					else if(j>18 && j<=21)
					{
						var length=stuname7.length;
					}
					else if(j>21 && j<=24)
					{
						var length=stuname8.length;
					}
					
					
					for(zk=0;zk<length;zk++) 
					{
						
						// check the first segment,check the first segment for empty,check the first segment with student zk
						
						if(j<=3)
						{
							var splitstuname=stuname1[zk].split("~");
						}
						else if(j>3 && j<=6)
						{
							var splitstuname=stuname2[zk].split("~");
						}
						else if(j>6 && j<=9)
						{
							var splitstuname=stuname3[zk].split("~");
						}
						else if(j>9 && j<=12)
						{
							var splitstuname=stuname4[zk].split("~");
						}
						else if(j>12 && j<=15)
						{
							var splitstuname=stuname5[zk].split("~");
						}
						else if(j>15 && j<=18)
						{
							var splitstuname=stuname6[zk].split("~");
						}
						else if(j>18 && j<=21)
						{
							var splitstuname=stuname7[zk].split("~");
						}
						else if(j>21 && j<=24)
						{
							var splitstuname=stuname8[zk].split("~");
						}
					
					for(zk1=zk+1;zk1<length;zk1++) // for loop starts
					{
							
						if(j<=3)
						{
							var splitstuname1=stuname1[zk1].split("~");
						}
						else if(j>3 && j<=6)
						{
							var splitstuname1=stuname2[zk1].split("~");
						}
						else if(j>6 && j<=9)
						{
							var splitstuname1=stuname3[zk1].split("~");
						}
						else if(j>9 && j<=12)
						{
							var splitstuname1=stuname4[zk1].split("~");
						}
						else if(j>12 && j<=15)
						{
							var splitstuname1=stuname5[zk1].split("~");
						}
						else if(j>15 && j<=18)
						{
							var splitstuname1=stuname6[zk1].split("~");
						}
						else if(j>18 && j<=21)
						{
							var splitstuname1=stuname7[zk1].split("~");
						}
						else if(j>21 && j<=24)
						{
							var splitstuname1=stuname8[zk1].split("~");
						}
							
									segrow=tablerowtriad(j,i,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>','<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>','uncheck');
									segrowarr=segrow.split("~");
									
									
								if(segrowarr[0]=="true" && segrowarr[1]=="true")
								{
									if(containsAll('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>','<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>',pairstudentper)=="false")
									{
										$('#seg1_'+j+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
										$('#seg2_'+j+"_"+i).html('<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
										var studet=new Array(splitstuname[0]+"~"+splitstuname[1],splitstuname1[0]+"~"+splitstuname1[1]);
										stunameadd.push(splitstuname[0]+"~"+splitstuname[1],splitstuname1[0]+"~"+splitstuname1[1]);
										
										
										if(j<=3)
										{
											stuname1=arraycompare(stuname1,studet);
											
										}
										else if(j>3 && j<=6)
										{
											stuname2=arraycompare(stuname2,studet);
											
										}
										else if(j>6 && j<=9)
										{
											stuname3=arraycompare(stuname3,studet);
											
										}
										else if(j>9 && j<=12)
										{
											stuname4=arraycompare(stuname4,studet);
											
										}
										else if(j>12 && j<=15)
										{
											stuname5=arraycompare(stuname5,studet);
										}
										else if(j>15 && j<=18)
										{
											stuname6=arraycompare(stuname6,studet);
										}
										else if(j>18 && j<=21)
										{
											stuname7=arraycompare(stuname7,studet);
										}
										else if(j>21 && j<=24)
										{
											stuname8=arraycompare(stuname8,studet);
										}
											
										pairstudenttemp.push('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>'+":"+'<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
										countstudent=countstudent+2;
										zkforloopbreakchk=1;
										break;
										
									}
									
					  			}  // Row check if end
							
						
					  } // for loop ends
					  if(zkforloopbreakchk==1)
					  {
						  break;
					  }
					}   // for loop end
					}
				} // main else end
				
				
				
				
				
				if(zkforloopbreakchk==0)
				{
					var count=1;
				}
				j++;
				zkk++;
			
					
			}
							// Check empty seats
							var seat='';
							
								if(retry==400)
								{
									var seat="true";
									retry=0;
									$('span[id^="0"]').replaceWith("");
									var s=new Array();
									s=$('#stuidname').val().split(",");
									var y=new Array();
									y=tempstu.split(",");
									$('#stuidname').val(arraycompare(s,y));
									$.Zebra_Dialog('Unable to generate a schedule for this class.');
									closeloadingalert();
									return false;
								}
								else
								{
									if(trlength>=i)
									{
										
										if(countstudent!=studentcount)
										{
											var seat="false";
											countstudent=0;
										}
										
										
									}
									
									
									
									retry++;
								}
							
							if(seat=="false")
							{
								var i=i;
								seat='';
								pairstudenttemp=[];
								stunameadd=[];
								
								$('.row'+i).empty();
								
								
							}
							else
							{
								i++;
								retry=0;
								
								for(y=0;y<pairstudenttemp.length;y++)
								{
									pairstudentper.push(pairstudenttemp[y]);
								}
								pairstudenttemp=[];
								
								
			}
	}
		
		$('span[id^="0"]').replaceWith("");
		var s=new Array();
		s=$('#stuidname').val().split(",");
		var y=new Array();
		y=tempstu.split(",");
		$('#stuidname').val(arraycompare(s,y));
		setTimeout("closeloadingalert();",2000);

	
}

var DELAY = 250, clicks = 0, timer = null;

$(".triad").click(function(e) {
	
     var cellid= this.id;
    if (timer == null) {
        timer = setTimeout(function() {
           clicks = 0;
            timer = null;
          
		  $('#tdval').val(cellid);
		   $('.popuptable').hide();
		   
		   
		  var offset = $('#'+cellid).position();
		   		  
			   var leftVal=offset.left-70;
			   var topVal=offset.top+31;
		   
		   var o = {
            left: e.pageX-140,
            top: e.pageY
        }
       
		$('.popuptable').show(1000).offset(o);
			
        }, DELAY);
    }

    if(clicks === 1) {
         clearTimeout(timer);
         timer = null;
         clicks = -1;
		var id=cellid.split("_");
	
		if(id[0]=="seg1")
		{
			if($('#'+cellid).hasClass('lightrot') || $('#'+cellid).hasClass('darkrot'))
			{
				$('DIV.triad').removeClass('darkrot lightrot');
			}
			else
			{
				$('DIV.triad').removeClass('darkrot lightrot');
				var cell1='#'+cellid+" "+'span';
				var studentidup=$(cell1).attr('id');
			
				$('span[id^='+studentidup+']').each(function(){
				
						$(this).parent().addClass('lightrot');
					
					});

			}
	}
	else
	{
		if($('#'+cellid).hasClass('lightrot') || $('#'+cellid).hasClass('darkrot')) 
		{
				$('DIV.triad').removeClass('darkrot lightrot');
		}
		else
		{
			$('DIV.triad').removeClass('darkrot lightrot');
			var cell1='#'+cellid+" "+'span';
			var studentidup=$(cell1).attr('id');
			                 
			$('span[id^='+studentidup+']').each(function(){
			
					$(this).parent().addClass('lightrot');
					
				});			

		}
	}
    }
    clicks++;
});

$(".triad").live({
	
	mouseover: function() {
		
	var cellid=this.id;
	
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && position=="seg1")
	{
		$('#'+cellid).addClass('mousehoverdt');
		$('#triadimagetop_'+divid[1]+"_"+divid[2]).addClass('mousehoverimgdt');
	}
	
	if($('#'+cellid).html()!='' && position=="seg2")
	{
		$('#'+cellid).addClass('mousehoverdt');
		$('#triadimagebottom_'+divid[1]+"_"+divid[2]).addClass('mousehoverimgdt');
	}
	},
	
	mouseout: function() {
	var cellid=this.id;
	
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && position=="seg1")
	{
		$('#'+cellid).removeClass('mousehoverdt');
		$('#triadimagetop_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimgdt');
	}
	
	if($('#'+cellid).html()!='' && position=="seg2")
	{
		$('#'+cellid).removeClass('mousehoverdt');
		$('#triadimagebottom_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimgdt');
	}
	}
	});
	
	
	/* events mouse - click,over and out */	
	$('.triadimagetop').die();
	$(".triadimagetop").live({
	
	click: function() {
		
		var stuname= new Array();
		var cellid=this.id;
		var cellid=cellid.replace("triadimagetop","seg1");
		studentid=($('#'+cellid+" "+'span').attr('id'));
		
		dataparam="oper=checkstudentscore&studentid="+studentid+"&classid="+$('#classid').val()+"&scheduleid="+$('#scheduleid').val();
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-triad-ajax.php',
			data: dataparam,
			baforeSend:function(){
					$.Zebra_Dialog('Loading, please wait.', {
					'buttons':  false,
					'position': ['right - 20', 'top + 20'],
					'auto_close': 2000
				});
			},
			success:function(data) {
				
					if(data=="fail")
					{
						$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('');	
											
											}},
										]
							});
							return false;
					}
					else
					{
						$.Zebra_Dialog('This student grades will be lost, Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('');	
											
											}},
										]
							});
							return false;
					}
				}
		});
	},
	mouseover: function() {
		
	var cellid=this.id;
	var cellid=cellid.replace("triadimagetop","seg1");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && position=="seg1")
	{
		$('#'+cellid).addClass('mousehoverdt');
		$('#triadimagetop_'+divid[1]+"_"+divid[2]).addClass('mousehoverimgdt');
	}
	},
	
	mouseout: function() {
	var cellid=this.id;
	var cellid=cellid.replace("triadimagetop","seg1");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && position=="seg1")
	{
		$('#'+cellid).removeClass('mousehoverdt');
		$('#triadimagetop_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimgdt');
	}
	}
	});
	
	
		/* events mouse - click,over and out */	
	$('.triadimagebottom').die();
	$(".triadimagebottom").live({
	
	click: function() {
		
		var stuname= new Array();
		var cellid=this.id;
		var cellid=cellid.replace("triadimagebottom","seg2");
		studentid=($('#'+cellid+" "+'span').attr('id'));
		
		dataparam="oper=checkstudentscore&studentid="+studentid+"&classid="+$('#classid').val()+"&scheduleid="+$('#scheduleid').val();
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-triad-ajax.php',
			data: dataparam,
			baforeSend:function(){
					$.Zebra_Dialog('Loading, please wait.', {
					'buttons':  false,
					'position': ['right - 20', 'top + 20'],
					'auto_close': 2000
				});
			},
			success:function(data) {
				
					if(data=="fail")
					{
						$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('');	
											
											}},
										]
							});
							return false;
					}
					else
					{
						$.Zebra_Dialog('This student grades will be lost, Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('');	
											
											}},
										]
							});
							return false;
					}
				}
		});
	},
	mouseover: function() {
		
	var cellid=this.id;
	var cellid=cellid.replace("triadimagebottom","seg2");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && position=="seg2")
	{
		$('#'+cellid).addClass('mousehoverdt');
		$('#triadimagebottom_'+divid[1]+"_"+divid[2]).addClass('mousehoverimgdt');
	}
	},
	
	mouseout: function() {
	var cellid=this.id;
	var cellid=cellid.replace("triadimagebottom","seg2");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && position=="seg2")
	{
		$('#'+cellid).removeClass('mousehoverdt');
		$('#triadimagebottom_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimgdt');
	}
	}
	});
	
function fn_addstudenttotdtriad(id,name)
{
	var trlength=$('#numberofmodules').val();
	var thlength=$('#countrot').val();
	var getdivid=$('#tdval').val();
	var cellid=$('#tdval').val();
	var stuid=id;
	getdivid=getdivid.split("_");
	
	for(j=1;j<=trlength;j++)
	{
					if(($('#seg1_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>'))
					{
						var i=getdivid[2];
						$.Zebra_Dialog( name +' is already in Rotation '+i);
						return false;
					}
					else
					{
						var column="true";
					}
				}
		
			/* check row */
			var row='';
			
				for(zj=1;zj<=thlength;zj++)
				{
					
						if(($('#seg1_'+getdivid[1]+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+getdivid[1]+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>'))
						{
							
							var modulename=$(".module"+getdivid[1]).attr('title');
							$.Zebra_Dialog( name +' is already in '+ modulename);
							return false;
						}
						else
						{
							row="true";	
							
						}
				}
			
			
			if(column=="true" && row=="true")
			{		
				$('.popuptable').hide();
				var tdid=$('#tdval').val();
				if($('#'+tdid).html()=='')
				{
					$('#'+tdid).html("<span id="+id+">"+name+"</span>");
				}
			}
}

function fn_showtriadtable()
{
	var dataparam = "oper=checkstage&sid="+$('#hidscheduleid').val();
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-triad-ajax.php",
		data: dataparam,
		success:function(data) {
			var data=data.split("~");
			if(data[0]>0 && data[1]>0)
			{
			setTimeout("removesections('#class-newclass-newschedulestep');",500);
	setTimeout('showpageswithpostmethod("class-newclass-viewtriadtable","class/newclass/class-newclass-viewtriadtable.php","id='+trim($('#hidscheduleid').val())+","+trim($('#hidclassid').val())+'");',500);
			}
			else if(data[0]==0)
			{
				closeloadingalert();
				$.Zebra_Dialog('No stage available in this schedule', {
					'buttons':  false,
					'auto_close': 2000
				});
			}
			else if(data[1]==0)
			{
				closeloadingalert();
				$.Zebra_Dialog('No Dyad available in this schedule', {
					'buttons':  false,
					'auto_close': 2000
				});
			} 
			
		}
	});	
	
}

function fn_triadstage(sid,type,flag)
{
	if(flag==1)
	{
		sid=$('#triadtemplateid').val();
	}
	
	var dataparam = "oper=triadinstructions&sid="+sid+"&licenseid="+$('#licenseid').val()+"&flag="+flag;
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-triad-ajax.php",
		data: dataparam,
		success:function(data) {
			closeloadingalert();
			var classid=$('#hidclassid').val();
			$('#tlab').show();
			$('#instructionstages').html(data);
			removesections('#class-newclass-newschedulestep');
			if(type=="ins")
			{
				var $target = $('html,body'); 
				$target.animate({scrollTop: $target.height()}, 1000);
			}
			
		}
	});	
}

function fn_showinstructionstagetriad(stageval,stagetype,stageid)
{
	var dataparam = "oper=checkstageins&stageval="+stageval+"&stagetype="+stagetype+"&stageid="+stageid+"&sid="+$('#hidscheduleid').val();
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-triad-ajax.php",
		data: dataparam,
		success:function(data) {
			closeloadingalert();
			if(stagetype==1)
			{
				if(data==1)
				{
					$.Zebra_Dialog('<strong>Teacher led already exist in this stage</strong>');
				}
				else
				{
					removesections('#class-newclass-newschedulestep');
					setTimeout('showpageswithpostmethod("class-newclass-instructionstagetriad","class/newclass/class-newclass-instructionstagetriad.php","id='+stageval+","+stagetype+","+stageid+'");',500);
				}
			}
			else if(stagetype==2)
			{
				if(data==1)
				{
					$.Zebra_Dialog('<strong>Orientation already exist in this stage</strong>');
				}
				else
				{
					removesections('#class-newclass-newschedulestep');
					setTimeout('showpageswithpostmethod("class-newclass-instructionstagetriad","class/newclass/class-newclass-instructionstagetriad.php","id='+stageval+","+stagetype+","+stageid+'");',500);
				}
			}
			else if(stagetype==3)
			{
				if(data==2)
				{
					$.Zebra_Dialog('<strong>Triad already exist in this stage</strong>');
				}
				else
				{
					removesections('#class-newclass-newschedulestep');
					setTimeout('showpageswithpostmethod("class-newclass-instructionstagetriad","class/newclass/class-newclass-instructionstagetriad.php","id='+stageval+","+stagetype+","+stageid+'");',500);
				}
			}
			
		}
	});	
}

function fn_deletetriadstage(rowid,insid)
{
	if(insid!=0)
	{
	$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
												
												var dataparam = "oper=deleteinstructions&insid="+insid;
												$.ajax({
													type: 'post',
													url: "class/newclass/class-newclass-triad-ajax.php",
													data: dataparam,
													baforeSend:function(){
															showloadingalert("Loading, please wait.");
													},
													success:function(data) {
														closeloadingalert();
														showloadingalert("Deleted Successfully.");
														fn_triadstage($('#hidscheduleid').val(),'ins',0);
															
													}
												});	
												
											
											}},
										]
							});
							return false;
	}
	else
	{
		$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
												
												
												showloadingalert("Deleted Successfully.");
												$('#mytable tr.'+rowid).remove();	
												setTimeout("closeloadingalert()",1000);
												$('.addtriadstage').show();
											
											}},
										]
							});
							return false;
		
	}
	
}

function fn_deletedefinetriad(triadid,rowid,flag)
{
	
	$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 												
												var dataparam = "oper=deletedefinetriad&triadid="+triadid;
												$.ajax({
													type: 'post',
													url: "class/newclass/class-newclass-triad-ajax.php",
													data: dataparam,
													baforeSend:function(){
															showloadingalert("Loading, please wait.");
													},
													success:function(data) {
														closeloadingalert();
														showloadingalert("Deleted Successfully.");
														fn_triadstage($('#hidscheduleid').val(),'addtriad',0); 
															
													}
												});												
											
											}},
										]
							});
							return false;

	
}

function fn_showdefinetriad(triadid,flag)
{
	dataparam="oper=showdefinetriad&triadid="+triadid+"&flag="+flag+"&scheduleid="+$('#hidscheduleid').val();
	
	$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-triad-ajax.php',
			data: dataparam,		
			beforeSend: function(){	
				showloadingalert("Loading, please wait.");
			},
			success:function(ajaxdata) {
				closeloadingalert();
				data=ajaxdata.split("~");
				$('#triadformdet').show();
				$('#triadname').val(data[0]);
				$('#module1').val(data[1]);
				$('#mod1name').html(data[2]);
				$('#module2').val(data[3]);
				$('#mod2name').html(data[4]);
				$('#module3').val(data[5]);
				$('#mod3name').html(data[6]);
				$('#triadid').val(triadid);
				if(flag==1)
				{
					$('.triadddbox').addClass('dim');
					$('#triadname').attr("readonly","readonly");
				}
			}
		});
	
}

function fn_loaddefinetriad()
{
	if($('#stagetype').val()==1)
	{
		
		$('.orientationdd').hide();
		$('.rotationdd').hide();
		
	}
	
	
	if($('#stagetype').val()==2)
	{
		$('.orientationdd').show();
		$('.rotationdd').hide();
	}
	else
	{
		$('.orientationdd').hide();
	}
	
	
	if($('#stagetype').val()==3)
	{
		$('.rotationdd').show();
	}
}

function fn_loadstagetypetriad()
{
	var stagevalue=$('#stagevalue').val();
	
	var dataparam="oper=loadstage&stageval="+stagevalue+"&sid="+$('#hidscheduleid').val();
	
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-triad-ajax.php",
		data: dataparam,
		baforeSend:function(){
			
			showloadingalert("Loading, please wait.");
		},
		success:function(data) {
			closeloadingalert();
			data=data.split("~");
			if(trim(data[1])=="success")
			{
				$('#triadstagetype').html(data[0]);
				if(stagevalue==1)
				{
					$('.orientationdd').hide();
					$('.rotationdd').hide();
				}
			}
			if(trim(data[1])=="fail")
			{
				$('#triadstagetype').html(data[0]);
				$.Zebra_Dialog('<strong>Teacher led exist in this stage</strong>');
			}
		}
	});
}

function fn_checktriadstagedate()
{
	if($("#stageform").validate().form())
	{
		if($('#insstageid').val()=='')
		{
			fn_savetriadinsschedule('ins',0,'save');
		}
		else
		{
			if($('#adjdate').is(':checked'))
			{
				var adflag=1;
			}
			else
			{
				var adflag=2;
			}
			
			
			var dataparam="oper=checkstagedate&stagevalue="+$('#stagevalue').val()+"&stagetype="+$('#stagetype').val()+"&distartdate="+$('#distartdate').val()+"&dienddate="+$('#dienddate').val()+"&stagename="+$('#stagename').val()+"&instype="+$('#instype').val()+"&insstageid="+$('#insstageid').val()+"&sid="+$('#hidscheduleid').val()
		
			$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-triad-ajax.php',
			data: dataparam,		
			success:function(data) {
					var data=data.split("~");
					if(data[0]=="success" || (data[0]=="below" && adflag==1))
					{
						fn_savetriadinsschedule('ins',0,'save');
					}
					else
					{
						$.Zebra_Dialog(data[1]);
					}
			}
			});
		}
	}
}


function fn_savetriadinsschedule(flag,tempflag,statusval)
{
	
	
	
	
	var status=$("#scheduleform").validate().form();
	
	if(flag=="ins")
	{
		var insstatus=$("#stageform").validate().form();
		if(status==true && insstatus==true)
		{
			var val="true";
		}
		else
		{
			var val="false";
		}
	}
	else if(flag=="addtriad")
	{
		var triadstatus=$("#triadform").validate().form();
		if(status==true && triadstatus==true)
		{
			var val="true";
		}
		else
		{
			var val="false";
		}
	}
	else
	{
		if(status==true)
		{
			var val="true";
		}
		else
		{
			var val="false";
		}
	}
	
	
	
	if(val=="true")
	{
		
		var list10 = [];
		var list9=[];
		
		$("div[id^=list10_]").each(function()
		{
			list10.push($(this).attr('id').replace('list10_',''));
		});
		
		$("div[id^=list9_]").each(function()
		{
			list9.push($(this).attr('id').replace('list9_',''));
		});
		
		if(list10=='' && $('#studenttype').val()==2)
		{
			$.Zebra_Dialog('<strong>Please select a student</strong>', {
			'buttons':  false,
			'auto_close': 3000
			});
			return false;
		}
		
		if(statusval=="view")
		{
		
			if($('#studenttype').val()==2)
			{
				var studentcount=list10.length;
			}
			else
			{
				var studentcount=$('#assignstudents').val();
			}
			
			var modcount=parseInt($('#ttcount').val())*parseInt(6);
			
			if(parseInt(studentcount)> parseInt(modcount))
			{
				$.Zebra_Dialog('You have '+modcount+ ' seats for '+studentcount + ' student');
				return false;
			}
		}
		
		
		var sname = escapestr($('#sname').val());
		var dataparam="oper=saveschedule&sname="+sname+"&startdate="+$('#startdate').val()+"&scheduletype="+$('#scheduletype').val()+"&studenttype="+$('#studenttype').val()+"&sid="+$('#hidscheduleid').val()+"&students="+list10+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&flag="+flag+"&unstudents="+list9+"&tempflag="+tempflag+"&tempid="+$('#insscheduleid').val();
		
		if(flag=="ins")
		{
			if($('#adjdate').is(':checked'))
			{
				var adflag=1;
			}
			else
			{
				var adflag=2;
			}
			
			var dataparam=dataparam+"&stagevalue="+$('#stagevalue').val()+"&stagetype="+$('#stagetype').val()+"&distartdate="+$('#distartdate').val()+"&dienddate="+$('#dienddate').val()+"&stagename="+$('#stagename').val()+"&instype="+$('#instype').val()+"&insstageid="+$('#insstageid').val()+"&adflag="+adflag;
		}
		
		if($('#stagetype').val()==2)
		{
			var dataparam=dataparam+"&orientationmod="+$('#orientationmod').val();
		}
		else if($('#stagetype').val()==3)
		{
			var dataparam=dataparam+"&rotation="+$('#rotation').val();
		}
		else if(flag=="addtriad")
		{
			if($('#module1').val()==$('#module2').val() || $('#module1').val()==$('#module3').val() || $('#module2').val()==$('#module3').val())
			{
				 $.Zebra_Dialog('<strong>Please select different modules</strong>', {
					'buttons':  false,
					'auto_close': 3000
					});
					return false;
			}
			list4=$('#module1').val()+","+$('#module2').val()+","+$('#module3').val();
			
			var dataparam=dataparam+"&modules="+list4+"&triadid="+$('#triadid').val()+"&triadname="+$('#triadname').val();
			
		}
		
		
		
		var stagedet=new Array();
		var stagevalue='';
		
		if($('#hidscheduleid').val()==0 && tempflag==0)
		{
			var j=0;
			for(i=1;i<=10;i++)
			{
					stagevalue=$('#triad_'+i+'_'+2).html();
					if(stagevalue!=undefined)
					{
						stagedet[j]=$('#triad_'+i+'_'+2).attr('class')+"~"+$('#triad_'+i+'_'+2).html()+"~"+$('#triad_'+i+'_'+3).html();
					}
					j++;
			}
		}
		
		if(stagedet!='')
		{
			var dataparam=dataparam+"&stagedet="+stagedet;
		}
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-triad-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				if(statusval=="save" || flag=="ins" || flag=="addtriad")
				{
				showloadingalert("Loading, Please wait");
				}
			},
			success:function(data) {
				closeloadingalert();
				var data=data.split("~");
				$('#scheduleid').val(data[1]);
				var sid=$('#scheduleid').val();
				var classid=$('#hidclassid').val();
				
				if(trim(data[0])=="success")
				{
					removesections('#class-newclass-newschedulestep');
					if(flag=="ins" || flag=="addtriad")
					{
						$('#triadformdet').hide();
						fn_triadstage(data[1],flag,0);
						$('#hidscheduleid').val(data[1]);
						$('#triadtemplate').hide();
						
						if($('#triadtableflag').val()==1)
						{
							
							$.Zebra_Dialog("<div style='text-align:left'>You have made changes to this assignment and there are some steps that need to be followed to complete this process successfully.</br></br> 1. If you change the order of the dyads/triads, add/remove students, change any detail in the instruction stages,<strong> please make sure to upgrade the schedule after doing the changes</strong>.  Please click view schedule button > you will see a preview of the changes in the schedule > scroll to the bottom of the schedule page and click <strong>'Save Instruction'</strong>. </br></br> 2. Please check that the changes that you have made to the schedule have been applied successfully. Please review the schedule details and the gradebook.</div>");
						}
						
						$('.ZebraDialog').css({"left":"250px","width":"1100px"});
						
					}
					else
					{
						if(statusval=="save")
						{
						setTimeout("removesections('#class-newclass-steps');",500);	
							setTimeout("removesections('#class-newclass-actions');",500);			
						setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',500);
						}
						else
						{
							$('#triadtemplate').hide();
							fn_showtriadtable();
						}
					}
					
				}
				if(trim(data[0])=="fail")
				{
					 $.Zebra_Dialog('<strong>Student limit exceed</strong>', {
					'buttons':  false,
					'auto_close': 3000
					});
				}
				
				
				
			}
		});
												
			
	}

}

function fn_setenddatetriad()
{
	var startdate=$('#distartdate').val();
	var stageval=$('#stagevalue').val();
	var stagetype=$('#stagetype').val();
	
	dataparam="oper=setenddate"+"&startdate="+startdate+"&stageval="+stageval+"&stagetype="+stagetype+"&rotation="+$('#rotation').val();
	
	$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-triad-ajax.php',
			data: dataparam,
			beforeSend:function(){ showloadingalert('Loading, Please wait'); },		
			success:function(data) {
				closeloadingalert();
				if(data!="")
				{
					$('#dienddate').val(data);
				}
			}
	});
	
}

function fn_tloadmodddbox(modid,type)
{
		if(type=="mod1")
		{
			var mod1=modid;
			var mod2=$('#module2').val();
			var mod3=$('#module3').val();
		}
		else if(type=="mod2")
		{
			var mod1=$('#module1').val();
			var mod2=modid;
			var mod3=$('#module3').val();
		}
		else if(type=="mod3")
		{
			var mod1=$('#module1').val();
			var mod2=$('#module2').val();
			var mod3=modid;
		}
		
		
		$("a[id^='toption1']").each(function() { //remove licenses which is selected previous 		
				var id = $(this).attr('data-option');
				if(mod2==id || mod3==id)		
				$('#toption1'+id).hide();								
				else
				$('#toption1'+id).show();	
			});	
			
			$("a[id^='toption2']").each(function() { //remove licenses which is selected previous 		
				var id = $(this).attr('data-option');
				if(mod1==id || mod3==id)		
				$('#toption2'+id).hide();								
				else
				$('#toption2'+id).show();	
			});	
			
			$("a[id^='toption3']").each(function() { //remove licenses which is selected previous 		
				var id = $(this).attr('data-option');
				if(mod1==id || mod2==id)		
				$('#toption3'+id).hide();								
				else
				$('#toption3'+id).show();	
			});					
	
	
}

function fn_editstagerotdate(id)
{
	var tdid="rot"+id;
	var startdate=$('#'+tdid+'-3').html();
	var enddate=$('#'+tdid+'-4').html();
	
	$('#'+tdid+'-3').html('<input type="text" id="'+tdid+'-s3" class="rotstartdate" readonle="readonly" value="'+startdate+'">');
	$('#'+tdid+'-4').html('<input type="text" id="'+tdid+'-e4" class="rotenddate" readonle="readonly" value="'+enddate+'">');
	
	$('#'+tdid+'-5').html('<div class="icon-synergy-close" style="float:right; font-size:18px;padding-right: 60px;margin-top:3px; cursor:pointer;" onclick="fn_textcancel('+id+');"></div><div class="icon-synergy-create"  style="float:right;font-size:20px;padding-right:10px;margin-top:3px;cursor:pointer;" onclick="fn_updatetriadrotdates('+id+');"></div>');
	
	 $( ".rotstartdate" ).datepicker( {
			
            onSelect: function(selected){
			 $(".rotenddate").datepicker("option","minDate", selected);
             $(this).parents().parents().removeClass('error');
            }
          }
        );
				
				$( ".rotenddate" ).datepicker( {
				 onSelect: function(selected){
				$(".rotstartdate").datepicker("option","maxDate", selected);
             $(this).parents().parents().removeClass('error');
            }
          }
        );
	 
}

function fn_updatestagedates(stagevalue,stagetype,id,rowid)
{
	var tdid="stage"+id;
	var startdate=$('#'+tdid+'-3').html();
	var enddate=$('#'+tdid+'-4').html();
	
	$('#'+tdid+'-3').html('<input type="text" id="'+tdid+'-s3" class="rotstartdate" readonle="readonly" value="'+startdate+'">');
	$('#'+tdid+'-4').html('<input type="text" id="'+tdid+'-e4" class="rotenddate" readonle="readonly" value="'+enddate+'">');
	
	$('#'+tdid+'-5').html('<div class="icon-synergy-close" style="float:right; font-size:18px;padding-right: 60px;margin-top:3px; cursor:pointer;" onclick="fn_stagetextcancel('+stagevalue+','+stagetype+','+id+','+rowid+');"></div><div class="icon-synergy-create"  style="float:right;font-size:20px;padding-right:10px;margin-top:3px;cursor:pointer;" onclick="fn_updatetriadstagedates('+stagevalue+','+stagetype+','+id+');"></div>');
	
	
				
			 $( ".rotstartdate" ).datepicker( {
			
            onSelect: function(selected){
			 $(".rotenddate").datepicker("option","minDate", selected);
             $(this).parents().parents().removeClass('error');
            }
          }
        );
				
				$( ".rotenddate" ).datepicker( {
				 onSelect: function(selected){
				$(".rotstartdate").datepicker("option","maxDate", selected);
             $(this).parents().parents().removeClass('error');
            }
          }
        );
}

function fn_stagetextcancel(stagevalue,stagetype,id,rowid)
{
	var tdid="stage"+id;
	var row="row-"+rowid;
	
				dataparam="oper=stagecancel&stageid="+id;
	
				$.ajax({
						type: 'post',
						url: 'class/newclass/class-newclass-triad-ajax.php',
						data: dataparam,
						beforeSend:function(){ showloadingalert('Loading, Please wait'); },		
						success:function(data) {
							closeloadingalert();
							
							var data=data.split("~");
							
							$('#'+tdid+'-3').html(data[0]);
							$('#'+tdid+'-4').html(data[1]);

							$('#'+tdid+'-5').html('<div class="icon-synergy-trash" onclick="fn_deletetriadstage('+row+','+id+');" style="float:right; font-size:18px;padding-right: 60px;"></div><div class="icon-synergy-edit" style="float:right; font-size:18px;padding-right: 10px;" onclick="fn_updatestagedates('+stagevalue+','+stagetype+','+id+','+rowid+');"></div>'
);
					}
				});
	
	
}

function fn_textcancel(id)
{
	var tdid="rot"+id;
	
				dataparam="oper=rotcancel&stageid="+id;
	
				$.ajax({
						type: 'post',
						url: 'class/newclass/class-newclass-triad-ajax.php',
						data: dataparam,
						beforeSend:function(){ showloadingalert('Loading, Please wait'); },		
						success:function(data) {
							closeloadingalert();
							
							var data=data.split("~");
							
							$('#'+tdid+'-3').html(data[0]);
							$('#'+tdid+'-4').html(data[1]);

							$('#'+tdid+'-5').html('<div class="icon-synergy-edit"  style="font-size:18px;padding-right: 10px;" id="rot'+id+'-5.1" onclick="fn_editstagerotdate('+id+');"></div>');
					}
				});
}

function fn_updatetriadrotdates(id)
{
	
	var tdid="rot"+id;
	var startdate=$('#'+tdid+'-s3').val();
	var enddate=$('#'+tdid+'-e4').val();
	
	var dataparam="oper=checkstageroteditmode&id="+id+"&sid="+$('#hidscheduleid').val()+"&startdate="+startdate;
	
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-triad-ajax.php",
		data: dataparam,
		success:function(data) {
						
						var data=data.split("~");
						if(data[0]=="success")
						{
							if(data[1]>0)
							{
								$.Zebra_Dialog('Subsequent schedule dates will be changed when either the Start or End date is changed. Do you want to proceed ?',
								 {
								'type':     'confirmation',
								'buttons':  [
												{caption: 'No', callback: function() { 
													
													dataparam="oper=updatestagerotdate"+"&startdate="+startdate+"&enddate="+enddate+"&id="+id+"&adjustflag=0";
		
													$.ajax({
															type: 'post',
															url: 'class/newclass/class-newclass-triad-ajax.php',
															data: dataparam,
															beforeSend:function(){ showloadingalert('Loading, Please wait'); },		
															success:function(data) {
																closeloadingalert();
																showloadingalert("Updated Successfully.");
																fn_triadstage($('#hidscheduleid').val(),'addtriad',0);
																
																setTimeout('fn_zebradialog();',2000);
														}
													});
												}},
												{caption: 'Yes', callback: function() { 
													
													dataparam="oper=updatestagerotdate"+"&startdate="+startdate+"&enddate="+enddate+"&id="+id+"&adjustflag=1";
		
														$.ajax({
																type: 'post',
																url: 'class/newclass/class-newclass-triad-ajax.php',
																data: dataparam,
																beforeSend:function(){ showloadingalert('Loading, Please wait'); },		
																success:function(data) {
																	closeloadingalert();
																	showloadingalert("Updated Successfully.");
																	fn_triadstage($('#hidscheduleid').val(),'addtriad',0);
																	
																	setTimeout('fn_zebradialog();',2000);
																}
														});
													
												
												}},
											]
								});
								
								$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"800px"});
return false;
							}
							else
							{
												var dataparam="oper=updatestagerotdate"+"&startdate="+startdate+"&enddate="+enddate+"&id="+id+"&adjustflag=0";
		
													$.ajax({
															type: 'post',
															url: 'class/newclass/class-newclass-triad-ajax.php',
															data: dataparam,
															beforeSend:function(){ showloadingalert('Loading, Please wait'); },		
															success:function(data) {
																closeloadingalert();
																showloadingalert("Updated Successfully.");
																fn_triadstage($('#hidscheduleid').val(),'addtriad',0);
																
																setTimeout('fn_zebradialog();',2000);
														}
													});
							}
						}
						else
						{
							$.Zebra_Dialog(data[0]);
							$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"500px"});
						}
				}
		});
							
							
						
}


function fn_updatenumofrotation(stageid)
{
	var dataparam="oper=checkbelowstage&stageid="+stageid+"&sid="+$('#hidscheduleid').val();
	
		$.ajax({
			type: 'post',
			url: "class/newclass/class-newclass-triad-ajax.php",
			data: dataparam,
			success:function(data) {
				if(data>0)
				{
						$.Zebra_Dialog('Subsequent schedule dates will be changed when either the Start or End date is changed. Do you want to proceed ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { 
												
												var dataparam = "oper=updatenumofrotation&stageid="+stageid+"&adjustflag=0"+"&sid="+$('#hidscheduleid').val();
												
												$.ajax({
													type: 'post',
													url: "class/newclass/class-newclass-triad-ajax.php",
													data: dataparam,
													baforeSend:function(){
															showloadingalert("Loading, please wait.");
													},
													success:function(data) {
														closeloadingalert();
														showloadingalert("Updated Successfully.");
														fn_triadstage($('#hidscheduleid').val(),'addtriad',0); 
														
														setTimeout('fn_zebradialog();',2000);
													}
												});
											}},
											{caption: 'Yes', callback: function() { 
												
												var dataparam = "oper=updatenumofrotation&stageid="+stageid+"&adjustflag=1"+"&sid="+$('#hidscheduleid').val();
												
												$.ajax({
													type: 'post',
													url: "class/newclass/class-newclass-triad-ajax.php",
													data: dataparam,
													baforeSend:function(){
															showloadingalert("Loading, please wait.");
													},
													success:function(data) {
														closeloadingalert();
														showloadingalert("Updated Successfully.");
														fn_triadstage($('#hidscheduleid').val(),'addtriad',0); 
														
														setTimeout('fn_zebradialog();',2000);
															
													}
												});	
												
											
											}},
										]
							});
							
							$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"800px"});
return false;
				}
				else
				{
								var dataparam = "oper=updatenumofrotation&stageid="+stageid+"&adjustflag=0"+"&sid="+$('#hidscheduleid').val();
												
												$.ajax({
													type: 'post',
													url: "class/newclass/class-newclass-triad-ajax.php",
													data: dataparam,
													baforeSend:function(){
															showloadingalert("Loading, please wait.");
													},
													success:function(data) {
														closeloadingalert();
														showloadingalert("Updated Successfully.");
														fn_triadstage($('#hidscheduleid').val(),'addtriad',0); 
														
														setTimeout('fn_zebradialog();',2000);
													}
												});
				}
			}
		});
							
							
							
}

function fn_updatetriadstagedates(stagevalue,stagetype,id,rowid)
{
	var tdid="stage"+id;
	var startdate=$('#'+tdid+'-s3').val();
	var enddate=$('#'+tdid+'-e4').val();
	
	var dataparam="oper=checkstageeditmode&stageid="+id+"&sid="+$('#hidscheduleid').val()+"&startdate="+startdate;
	
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-triad-ajax.php",
		data: dataparam,
		success:function(data) {
						
						var data=data.split("~");
						if(data[0]=="success")
						{
							 if(data[1]>0)
							 {
								 $.Zebra_Dialog('Subsequent schedule dates will be changed when either the Start or End date is changed. Do you want to proceed ?',
								 {
								'type':     'confirmation',
								'buttons':  [
												{caption: 'No', callback: function() { 
													
													var dataparam = "oper=updatestagedates&stageid="+id+"&adjustflag=0"+"&sid="+$('#hidscheduleid').val()+"&startdate="+startdate+"&enddate="+enddate; 
													
													$.ajax({
														type: 'post',
														url: "class/newclass/class-newclass-triad-ajax.php",
														data: dataparam,
														baforeSend:function(){
																showloadingalert("Loading, please wait.");
														},
														success:function(data) {
															closeloadingalert();
															if(data=="success")
															{
																showloadingalert("Updated Successfully.");
																fn_triadstage($('#hidscheduleid').val(),'addtriad',0);
																setTimeout('fn_zebradialog();',2000);
															}
															else
															{
																alert(data);
															}
																
														}
													});
												}},
												{caption: 'Yes', callback: function() { 
													
													var dataparam = "oper=updatestagedates&stageid="+id+"&adjustflag=1"+"&sid="+$('#hidscheduleid').val()+"&startdate="+startdate+"&enddate="+enddate;
													
													$.ajax({
														type: 'post',
														url: "class/newclass/class-newclass-triad-ajax.php",
														data: dataparam,
														baforeSend:function(){
																showloadingalert("Loading, please wait.");
														},
														success:function(data) {
															closeloadingalert();
															if(data=="success")
															{
																showloadingalert("Updated Successfully.");
																fn_triadstage($('#hidscheduleid').val(),'addtriad',0);
																setTimeout('fn_zebradialog();',2000);
															}
															else
															{
																alert(data);
															}
																
														}
													});	
													
												
												}},
											]
								});
								
								$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"800px"});
return false;
							 }
							 else
							 {
												var dataparam = "oper=updatestagedates&stageid="+id+"&adjustflag=0"+"&sid="+$('#hidscheduleid').val()+"&startdate="+startdate+"&enddate="+enddate; 
													
													$.ajax({
														type: 'post',
														url: "class/newclass/class-newclass-triad-ajax.php",
														data: dataparam,
														baforeSend:function(){
																showloadingalert("Loading, please wait.");
														},
														success:function(data) {
															closeloadingalert();
															if(data=="success")
															{
																showloadingalert("Updated Successfully.");
																fn_triadstage($('#hidscheduleid').val(),'addtriad',0);
																setTimeout('fn_zebradialog();',2000);
															}
															else
															{
																alert(data);
															}
																
														}
													}); 
							 }
						}
						else
						{
							$.Zebra_Dialog(data[0]);
							$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"500px"});
						}
				}
		});
							
							
	
}


function fn_zebradialog()
{
		if($('#triadtableflag').val()==1)
		{
			
			$.Zebra_Dialog("<div style='text-align:left'>You have made changes to this assignment and there are some steps that need to be followed to complete this process successfully.</br></br> 1. If you change the order of the dyads/triads, add/remove students, change any detail in the instruction stages,<strong> please make sure to upgrade the schedule after doing the changes</strong>.  Please click view schedule button > you will see a preview of the changes in the schedule > scroll to the bottom of the schedule page and click <strong>'Save Instruction'</strong>. </br></br> 2. Please check that the changes that you have made to the schedule have been applied successfully. Please review the schedule details and the gradebook.</div>");
		}
			$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"1000px"});
}

function fn_generatetriadscheduleajaxduplicate(modules)
{
	
	dataparam="oper=generatetriad&modules="+modules;

	$.ajax({
				type: 'post',
				url: "class/newclass/class-newclass-triad-ajax.php",
				data: dataparam,
				success:function(data) {
					showloadingalert('Generating the rotational schedule');
					setTimeout('closeloadingalert()',2000);
					if(data!='')
					{
						var parsed=JSON.parse(data);

						var seats=$('#numberofmodules').val()*parseInt(2);
						var newstu = $('#stuidname').val();
						var tempstu ='';
	
						if(parseInt($('#studentcount').val())<seats)
						{
							var couval = seats - parseInt($('#studentcount').val());
							for(i=0;i<couval;i++)
							{
								if(tempstu=='')
								{
									tempstu = ",Student NN"+i+"~0";
								}
								else
								{
									tempstu = tempstu+",Student NN"+i+"~0";
								}
							}
							
							
							var tem = newstu+tempstu;
							$('#stuidname').val(tem);
						}

						var studname=$('#stuidname').val();
						var stuname = arrayShuffle(studname);
						stuname=stuname.split(",");

						var maintriad = $('#numberofmodules').val()/3;
						var numtriad = maintriad*maintriad;
						var mainmodules = $('#numberofmodules').val();;
						var mainrot = $('#rotations').val();

						var a = 1;
						var b = a+2;
						var c = 1;
						var d = c+2;
						var cnt = 0;
						var inc = 1;
						var z=0;
						for(i=1;i<=numtriad;i++)
						{

							if(cnt == maintriad)
							{
								cnt = 1;
								inc = inc+3;
								
								c = 1;
								d = c+2;
							}
							else
							{
								cnt++;
								
								a = inc;
								b = a+2;
							}
							
							if(maintriad+1 == i)
							{
								
							}
							for(j=a;j<=b;j++)
							{
								for(k=c;k<=d;k++)
								{
														var num=parsed[z].split("~");
														var student1=stuname[num[0]];
														var student2=stuname[num[1]];

														student1=student1.split("~");
														student2=student2.split("~");

														$('#seg1_'+j+'_'+k).html('<span id="'+student1[1]+'">'+student1[0]+'</span>');
														$('#seg2_'+j+'_'+k).html('<span id="'+student2[1]+'">'+student2[0]+'</span>');

														z++;
								}
								
							}
							a=b+1;
							b=a+2;
							c=d+1;
							d=c+2;
						}

						$('span[id^="0"]').replaceWith("");

						
					}
						
				}
			}); 
}


function fn_generatetriadscheduleajax(modules)
{
	$('div.triad').removeClass('lightrot darkrot');
	$('.triadtop').empty();
	$('.triadbottom').empty();
        $('.triadtop').html('&nbsp;');
         $('.triadbottom').html('&nbsp;');
         
        var studentcount=$('#numberofmodules').val()*parseInt(2);
        
        dataparam="oper=generatetriad&modules="+modules+"&rotation="+$('#rotations').val()+"&student="+studentcount;
        
	$.ajax({
				type: 'post',
				url: "class/newclass/class-newclass-triad-ajax.php",
				data: dataparam,
				success:function(data) {
					showloadingalert('Generating the rotational schedule');
					setTimeout('closeloadingalert()',1000);
					if(data!='')
					{
                                            
                                        if(data!="false")
                                        {
                                           var parsed=JSON.parse(data);

                                        var newstu = $('#stuidname').val();
                                        var tempstu ='';
                                        var seats=$('#numberofmodules').val()*parseInt(2);
                                        if(parseInt($('#studentcount').val())<seats)
                                        {
                                                var couval = seats - parseInt($('#studentcount').val());
                                                for(i=0;i<couval;i++)
                                                {
                                                        if(tempstu=='')
                                                        {
                                                                tempstu = ",Student NN"+i+"~0";
                                                        }
                                                        else
                                                        {
                                                                tempstu = tempstu+",Student NN"+i+"~0";
                                                        }
                                                }


                                                var tem = newstu+tempstu;
                                                $('#stuidname').val(tem);
                                        }

                                           var stuidname=$('#stuidname').val().split(",");
                                           

                                           for(mod=1;mod<=modules;mod++)
                                           {
                                                   for(rot=1;rot<=$('#rotations').val();rot++)
                                                   {

                                                           var modid=mod;
                                                           var rotid=rot;



                                                               if(parsed[modid+','+rotid]!=undefined)
                                                               {
                                                                   if($('#seg1_'+mod+'_'+rot).html()=="&nbsp;" && parsed[modid+','+rotid][0]!='' && parsed[modid+','+rotid][0]>0)
                                                                   {
                                                                       var stuseg1=parsed[modid+','+rotid][0]-parseInt(1);
                                                                       if(stuidname[stuseg1]!=undefined)
                                                                       {
                                                                           var student=stuidname[stuseg1].split("~");
                                                                           $('#seg1_'+mod+'_'+rot).html('<span id="'+student[1]+'">'+student[0]+'</span>');
                                                                       }
                                                                   }
                                                                   if($('#seg2_'+mod+'_'+rot).html()=="&nbsp;" && parsed[modid+','+rotid][1]!='' && parsed[modid+','+rotid][1]>0)
                                                                   {

                                                                        var stuseg2=parsed[modid+','+rotid][1]-parseInt(1);
                                                                        if(stuidname[stuseg2]!=undefined)
                                                                        {
                                                                           var student1=stuidname[stuseg2].split("~");

                                                                           $('#seg2_'+mod+'_'+rot).html('<span id="'+student1[1]+'">'+student1[0]+'</span>');
                                                                       }
                                                                   }
                                                               }
                                                    }
                                           }

                                            $('span[id^="0"]').replaceWith("");
                                            var s=new Array();
                                            s=$('#stuidname').val().split(",");
                                            var y=new Array();
                                            y=tempstu.split(",");
                                            $('#stuidname').val(arraycompare(s,y));

                                        }
                                        else
                                        {
                                             $.Zebra_Dialog("Unable to generate a schedule for this class");
                                        }

						
		  }
						
            }
    }); 
}

