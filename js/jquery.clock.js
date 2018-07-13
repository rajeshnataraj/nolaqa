(function(jQuery)
{
  jQuery.fn.clock = function(options)
  {
    var _this = this;
    setInterval( function() {
		var d = new Date();	
      	var seconds = d.getSeconds();
		seconds  = seconds<10 ? '0'+seconds : seconds;
      	//jQuery(_this).find(".sec").html(seconds+'&nbsp;');
		
		var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][d.getMonth()];
		var day = day<10 ? '0'+ d.getDate() : d.getDate(); 
		var weekday = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"][d.getDay()]; 
		var today = weekday + ' ' + month + ' ' + day  ;
		jQuery(_this).find(".day").html(today);
    }, 1000 );

//    setInterval( function() {
//      var d = new Date();
//	  var hours = d.getHours();
//      var mins = d.getMinutes();
//      jQuery(_this).find(".hour").html(hours+':');
//      var meridiem = hours<12 ? 'a.m.':'p.m.';
//      jQuery(_this).find('.meridiem').html(meridiem);
//    }, 1000 );
//
//    setInterval( function() {
//      var d = new Date();
//	  var mins = d.getMinutes();
//	  mins  = mins<10 ? '0'+mins : mins;
//      jQuery(_this).find(".min").html(mins+':');
//    }, 1000 );
  }
})
(jQuery);
