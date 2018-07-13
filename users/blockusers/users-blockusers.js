// JavaScript Document
/*----
    fn_selectusers()
	Function to show the city drop down
----*/	
function fn_selectusers1(shlid,usertype){
    var dataparam = "oper=selectusers&usertype="+usertype+"&shlid="+shlid;
    $.ajax({
            type: 'post',
            url: 'users/blockusers/users-blockusersdb.php',
            data: dataparam,
            success:function(ajaxdata) {
                $('#userslist1').show();
                $('#userslist1').html(ajaxdata);
                $("html,body").animate({scrollTop:$(document).height()}, 1000)

            }

    });	
}


function fn_selectusers(usertype){
        $('#schoollist').hide();
        if(usertype ==-1 || usertype ==-2 || usertype ==-3 || usertype ==-4 || usertype ==5){
            
           var dataparam = "oper=selectusers&usertype="+usertype;
            $.ajax({
                    type: 'post',
                    url: 'users/blockusers/users-blockusersdb.php',
                    data: dataparam,
                    success:function(ajaxdata) {
                        $('#userslist1').show();
                        $('#userslist1').html(ajaxdata);
                        $("html,body").animate({scrollTop:$(document).height()}, 1000)

                    }

            });
        }
        else{
            var dataparam = "oper=changeschool&usertype="+usertype;
            $.ajax({
                    type: 'post',
                    url: 'users/blockusers/users-blockusersdb.php',
                    data: dataparam,
                    success:function(ajaxdata) {
                        $('#schoollist').show();
                        $('#schoollist').html(ajaxdata);
                        fn_selectusers1(0,usertype)

                    }

            });
        }
		
}

/*----
    fn_changestatus()
	Function to be change tag types
----*/	
function fn_changestatus(id,type,usertype){
    if(type == 0){
       var alertmsg = "Activate ?"; 
    }
    else{
        var alertmsg = "Block ?";
    }
    $.Zebra_Dialog('Are you sure you want to '+alertmsg,
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() {
                                if(type == 0){
                                    $('#radio1_'+id).prop('checked', false);
                                    $('#radio2_'+id).prop('checked', true);
                                }
                                if(type == 1){
                                    $('#radio1_'+id).prop('checked', true);
                                    $('#radio2_'+id).prop('checked', false);
                                }
                            }},
			{caption: 'Yes', callback: function() {	
                            var dataparam = "oper=changestatus&id="+id+"&type="+type+"&usertype="+usertype;
                            $.ajax({
                                    type: 'post',
                                    url: 'users/blockusers/users-blockusersdb.php',
                                    data: dataparam,
                                    async: false,
                                    beforeSend: function(){						
                                    },			
                                    success:function(data) {
                                    }
                            }); 
				
			}}
		]
	});
}
