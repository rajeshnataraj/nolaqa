<?php
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");

$ids = isset($method['id']) ? $method['id'] : '';	
$id = explode("~",$ids);

$scheduleid=$id[0];
$missionid=$id[1];
$schtype=$id[2];
if($uid1 == ''){
    $uid1=0;
}
if($schtype == '23'){
    $usid= $id[3];
}
else{
     $usid= $uid;
}
$exptype = $ObjDB->SelectSingleValueInt("SELECT fld_mistype
                                        FROM itc_mission_master 
                                        WHERE fld_id='".$missionid."' AND fld_delstatus='0'");
?>
<script type="text/javascript" charset="utf-8">	
	$(document).ready(function () 
        {  
            $('html, body').animate({scrollTop: '0px'}, 0);
            $('body').css('overflow','hidden');

            var cssObjOuter = {
                'display' : 'block',
                'width' : $('body').width(),
                'height' : $(window).height()
            };

            var cssObjInner = {
                'display' : 'block',
                'width' : $('body').width(),
                'height' : $(window).height()
            };
            
            var heigh =$(window).height();
            var weigh =$('body').width();
            var contenturl = CONTENT_EXP_URL;

            var schtype = <?php echo $schtype;?>;
            var tempexpid = <?php echo $missionid;?>;
            var newurl = contenturl+'/emaps-missioninline4sdsg/index.php?schid='+<?php echo $scheduleid;?>+'&schtype='+<?php echo $schtype;?>+'&expid='+<?php echo $missionid;?>+'&uid='+"<?php echo $usid;?>"+'&uid1='+<?php echo $uid1;?>+'&height='+heigh+'&width='+weigh;
            $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="'+newurl+'&hostname='+location.host+'" width="100%"></iframe></div></div>');
            
            $('#expedition-fullscreecn-header').css(cssObjOuter);
            $('#divlbcontent').css(cssObjInner);
            $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });

            if (navigator.userAgent.match(/iPad/i) != null){
                removesections('#home');
            }
    
            $(window).resize(function() {
                if($('#expedition-fullscreecn-header').length){
                    var cssObjOuter = {
                      'display' : 'block',
                      'width' : $('body').width(),
                      'height' : $(window).height()
                    };

                    var cssObjInner = {
                      'display' : 'block',
                      'width' : $('body').width(),
                      'height' : $(window).height()
                    };

                    $('#expedition-fullscreecn-header').css(cssObjOuter);
                    $('#divlbcontent').css(cssObjInner);
                    $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
                    $('iframe').contents().find('body').css('backgroundColor', 'white');
                }
            });
        });
</script>