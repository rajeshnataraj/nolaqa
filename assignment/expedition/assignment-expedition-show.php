<?php
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");

$ids = isset($method['id']) ? $method['id'] : '';	
$id = explode("~",$ids);

$scheduleid=$id[0];
$expeditionid=$id[1];
$schtype=$id[2];
$txpport=$id[3]; 
if($txpport==''){
    $txpport=0;
}
if($uid1 == ''){
    $uid1=0;
}
$exptype = $ObjDB->SelectSingleValueInt("SELECT fld_exptype
                                        FROM itc_exp_master 
                                        WHERE fld_id='".$expeditionid."' AND fld_delstatus='0'");
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
	        var contenturl = '<?= CONTENT_EXP_URL ?>';

            var cloudcontenturl = "cloudfront.pitsco.com";

            if(location.host == "localhost") {
                contenturl = "localhost";
            }
            var expid1 = <?= $exptype ?>;
            var tempexpid = <?= $expeditionid ?>;
            if(expid1 =='1'){
                var newurl = contenturl+'/emaps-masterclassic/index.php?schid='+<?php echo $scheduleid;?>+'&schtype='+<?php echo $schtype;?>+'&expid='+<?php echo $expeditionid;?>+'&uid='+<?php echo $uid;?>+'&uid1='+<?php echo $uid1;?>+'&txpport='+"<?php echo $txpport;?>"+'&height='+heigh+'&width='+weigh;
            }
            else if(expid1 =='2'){
                var newurl = contenturl+'/emaps-masterstem/index.php?schid='+<?php echo $scheduleid;?>+'&schtype='+<?php echo $schtype;?>+'&expid='+<?php echo $expeditionid;?>+'&uid='+<?php echo $uid;?>+'&height='+heigh+'&width='+weigh;
            }
            else{
                        var newurl = contenturl+'/emaps-masterline/index.php?schid='+<?php echo $scheduleid;?>+'&schtype='+<?php echo $schtype;?>+'&expid='+<?php echo $expeditionid;?>+'&uid='+<?php echo $uid;?>+'&uid1='+<?php echo $uid1;?>+'&txpport='+"<?php echo $txpport;?>"+'&height='+heigh+'&width='+weigh;
                }
            $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="'+newurl+'&hostname='+location.host+'" width="100%"></iframe></div></div>');
            
            $('#expedition-fullscreecn-header').css(cssObjOuter);
            $('#divlbcontent').css(cssObjInner);
            $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });

            if (navigator.userAgent.match(/iPad/i) != null){
                removesections('#home');
            }
            else{
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
                }
            });
            }
        });
</script>