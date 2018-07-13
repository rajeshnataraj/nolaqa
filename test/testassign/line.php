<?php 
@include("sessioncheck.php");
$img=isset($_REQUEST['img']) ? $_REQUEST['img'] : '0';
$value=isset($_REQUEST['val']) ? $_REQUEST['val'] : '0';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lines using D3.js</title>
    <script type="text/javascript" src="../../js/jquery.pack.js"></script>
    <script type="text/javascript" src="../../js/excanvas.min.js"></script>
  <script language="Javascript">
document.domain = 'pitsco.com';
$(function() {

    var canvas = document.getElementById('drawing');
    var ctx = canvas.getContext('2d');

	// Functions from blog tutorial
	function drawFilledPolygon(canvas,shape)
	{
		canvas.beginPath();
		canvas.moveTo(shape[0][0],shape[0][1]);

		for(p in shape)
			if (p > 0) canvas.lineTo(shape[p][0],shape[p][1]);

		canvas.lineTo(shape[0][0],shape[0][1]);
		canvas.fill();
	};	
	function translateShape(shape,x,y)
	{
		var rv = [];
		for(p in shape)
			rv.push([ shape[p][0] + x, shape[p][1] + y ]);
		return rv;
	};	
	function rotateShape(shape,ang)
	{
		var rv = [];
		for(p in shape)
			rv.push(rotatePoint(ang,shape[p][0],shape[p][1]));
		return rv;
	};	
	function rotatePoint(ang,x,y)
	{
		return [
			(x * Math.cos(ang)) - (y * Math.sin(ang)),
			(x * Math.sin(ang)) + (y * Math.cos(ang))
		];
	};	
	function drawLineArrow(canvas,x1,y1,x2,y2) 
	{
		canvas.beginPath();
		canvas.moveTo(x1,y1);
		canvas.lineTo(x2,y2);
		canvas.stroke();
		var ang = Math.atan2(y2-y1,x2-x1);
		drawFilledPolygon(canvas,translateShape(rotateShape(arrow_shape,ang),x2,y2));
	};	

	function redrawLine(canvas,x1,y1,x2,y2)
	{
		canvas.clearRect(0,0,maxx,maxy);
		drawLineArrow(canvas,x1,y1,x2,y2);
	};	

	// Event handlers
	function mDown(e)
	{		
		read_position();
		var p = get_offset(e);
		if ((p[0] < 0) || (p[1] < 0)) return;
		if ((p[0] > maxx) || (p[1] > maxy)) return;
		drawing = true;
		ox = p[0];
		oy = p[1];
		return nothing(e);
	};	
	function mMove(e)
	{		
		if (!!drawing)
		{	
			var p = get_offset(e);
			// Constrain the line to the canvas...
			if (p[0] < 0) p[0] = 0;
			if (p[1] < 0) p[1] = 0;
			if (p[0] > maxx) p[0] = maxx;
			if (p[1] > maxy) p[1] = maxy;
			redrawLine(ctx,ox,oy,p[0],p[1]);
		}
		return nothing(e);
	};	
	function mDone(e)
	{		
		if (drawing) {
			var p = get_offset(e);			
			debug_msg([ox,oy,p[0],p[1]].toString(),ctx);
			drawing = false;
			return mMove(e);
		}
	};	
	function nothing(e)
	{
		e.stopPropagation();
		e.preventDefault();
		return false;
	};	
	function read_position()
	{
		var o = $obj.position();
		yoff = o.top;
		xoff = o.left;
	};	
	function get_offset(e)
	{
		return [ e.pageX - xoff, e.pageY - yoff ];
	};	
	function debug_msg(msg)
	{
		if (debug_ctr > debug_clr) {
			$('#debug').children().remove();
			debug_ctr = 0;
		}
		debug_ctr++;		
		$('#hidlinevalue').val(msg);
	};	

	var arrow_shape = [
		[ -10, -4 ],
		[ -8, 0 ],
		[ -10, 4 ],
		[ 2, 0 ]
	];

	var debug_ctr = 0;
	var debug_clr = 12;
	var $obj = $('#drawing');
	var maxx = 700, maxy = 700;
	var xoff,yoff;
	var ox,oy;
	var drawing;

	var attach_to = $.browser.msie ? '#drawing' : window;
	$(attach_to)
		.mousedown(mDown)
		.mousemove(mMove)
		.mouseup(mDone)
	;
	<?php if($value != 0) {?>
		redrawLine(ctx,<?php echo $value;?>);
	<?php }?>
});


</script>
<?php //Get image width
    list($width,$height) = getimagesize(_CONTENTURL_."question/ansimg/".$img);	
    if($width > 700){
        $width = 700;
        $height = 700;
    }
?>
<style type="text/css">
h1 { margin: 0; padding: 0; }
h1 small { display: block; margin: .5em 0; font-size: 11px; font-weight: normal; }
canvas {     
   background-image: url("../../thumb.php?src=<?php echo _CONTENTURL_."question/ansimg/".$img; if($width > 700){?>&w=700&h=700&zc=2<?php }else{ echo "&w=".$width."&h=".$height."&zc=2"; } ?>");
    background-position: 0 0;
    background-repeat: no-repeat;
    border: 1px solid #CCCCCC;
    margin: 0.5em 0;

}
#debug {
	margin: .5em 0;
	font-size: 9px;
	font-family: "Lucida Sans",Arial,Helvetica,sans-serif;
	color: #999;
}
</style>
</head>
<body>

<canvas width="<?php echo $width; ?>" height="<?php echo $height; ?>"  id="drawing"></canvas>

<div id="debug">
</div>
<input type="hidden" id="hidlinevalue" name="hidlinevalue" value="<?php echo $value;?>" />
</body>
</html>
<?php
	@include("footer.php");