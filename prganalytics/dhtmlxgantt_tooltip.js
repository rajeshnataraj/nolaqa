/*
This software is allowed to use under GPL or you need to obtain Commercial or Enterprise License
 to use it in non-GPL project. Please contact sales@dhtmlx.com for details
*/
gantt._tooltip = {}, gantt._tooltip_class = "gantt_tooltip", gantt.config.tooltip_timeout = 30, gantt._create_tooltip = function () {
    return this._tooltip_html || (this._tooltip_html = document.createElement("div"), this._tooltip_html.className = gantt._tooltip_class), this._tooltip_html
}, gantt._show_tooltip = function (t, i) {
    
    var unres = t.split("edate");//checking the date and subtract one day from end date -> Start
    var d = new Date(unres[1]);
    var dd = d.getDate();
    var mm = d.getMonth()+1;
    var yyyy = d.getFullYear();
    var mm=mm.toString();
    var mlen=mm.length;
    
    var leap=yyyy/4;
    var type = getType(leap);
    if(type=='float')
    {
      leap=false;
    }
    else if(type=='int')
    {
       leap=true;
    }
    
    if(mlen==1)
    {
       mm="0"+mm;
    }
    else
    {
        mm=mm;
    }
    if(dd==1)
    {
        if(mm=='03')
        {
            mm=mm-1;
            var len=mm.length;
            if(mlen==1)
           {
              mm="0"+mm;
           }
           else
           {
               mm=mm;
           }
           if(leap==true)
           {
                dd=29;
           }
           else if(leap==false)
           {
                dd=28;
           }
        }
        else if(mm=='01' || mm=='02' || mm=='04' || mm=='06' || mm=='08' || mm=='09' || mm =='11')
        {
            if(mm=='01' && dd==1)
            {
                yyyy=yyyy-1;
                mm='12';
                dd=31;
            }
            else
            {
                mm=mm-1;
                var len=mm.length;
                if(mlen==1)
                {
                   mm="0"+mm;
                }
                else
                {
                    mm=mm;
                }
                dd=31;
            }
        }
        else if(mm=='05' || mm=='07' || mm=='10' || mm=='12' )
        {
            mm=mm-1;
            var len=mm.length;
            if(mlen==1)
            {
               mm="0"+mm;
            }
            else
            {
                mm=mm;
            }
            dd=30;
        }
    }
    else
    {
        dd=dd-1;
    }
   if(dd<10)
   {
       dd="0"+dd;
   }
    var endate=yyyy+"-"+mm+"-"+dd;
    
    var dateend = t.split("edate");
    var t=dateend[0]+""+endate;//checking the date and subtract one day from end date -> End
    
    if (!gantt.config.touch || gantt.config.touch_tooltip) {
        var e = this._create_tooltip();
        e.innerHTML = t, gantt.$task_data.appendChild(e);
        var n = e.offsetWidth + 20,
            o = e.offsetHeight + 40,
            a = this.$task.offsetHeight,
            _ = this.$task.offsetWidth,
            l = this.getScrollState();
        i.x += l.x, i.y += l.y, i.y = Math.min(Math.max(l.y, i.y), l.y + a - o), i.x = Math.min(Math.max(l.x, i.x), l.x + _ - n), e.style.left = i.x + "px", e.style.top = i.y + "px"
    }
}, gantt._hide_tooltip = function () {
    this._tooltip_html && this._tooltip_html.parentNode && this._tooltip_html.parentNode.removeChild(this._tooltip_html), this._tooltip_id = 0
}, gantt._is_tooltip = function (t) {
    var i = t.target || t.srcElement;
    return gantt._is_node_child(i, function (t) {
        return t.className == this._tooltip_class
    })
}, gantt._is_task_line = function (t) {
    var i = t.target || t.srcElement;
    return gantt._is_node_child(i, function (t) {
        return t == this.$task_data
    })
}, gantt._is_node_child = function (t, i) {
    for (var e = !1; t && !e;) e = i.call(gantt, t), t = t.parentNode;
    return e
}, gantt._tooltip_pos = function (t) {
    if (t.pageX || t.pageY) var i = {
        x: t.pageX,
        y: t.pageY
    };
    var e = _isIE ? document.documentElement : document.body,
        i = {
            x: t.clientX + e.scrollLeft - e.clientLeft,
            y: t.clientY + e.scrollTop - e.clientTop
        },
        n = gantt._get_position(gantt.$task);
    return i.x = i.x - n.x, i.y = i.y - n.y, i
}, gantt.attachEvent("onMouseMove", function (t, i) {
    if (this.config.tooltip_timeout) {
        document.createEventObject && !document.createEvent && (i = document.createEventObject(i));
        var e = this.config.tooltip_timeout;
        this._tooltip_id && !t && (isNaN(this.config.tooltip_hide_timeout) || (e = this.config.tooltip_hide_timeout)), clearTimeout(gantt._tooltip_ev_timer), gantt._tooltip_ev_timer = setTimeout(function () {
            gantt._init_tooltip(t, i)
        }, e)
    } else gantt._init_tooltip(t, i)
}), gantt._init_tooltip = function (t, i) {
    if (!this._is_tooltip(i) && (t != this._tooltip_id || this._is_task_line(i))) {
        if (!t) return this._hide_tooltip();
        this._tooltip_id = t;
        var e = this.getTask(t),
            n = this.templates.tooltip_text(e.start_date, e.end_date, e);
        n || this._hide_tooltip(), this._show_tooltip(n, this._tooltip_pos(i))
    }
}, gantt.attachEvent("onMouseLeave", function (t) {
    gantt._is_tooltip(t) || this._hide_tooltip()
}), gantt.templates.tooltip_date_format = gantt.date.date_to_str("%Y-%m-%d"), gantt.templates.tooltip_text = function (t, i, e) {
    var progress=e.progress*100;
    progress = Math.round(progress);
    var txt=e.text.split("/");
    return "<b>"+txt[1]+":</b> "   + txt[0] +"<br/><b>Progress:</b> " + progress+"%"+ "<br/><b>Start date:</b> " + gantt.templates.tooltip_date_format(t) + "<br/><b>End date:</b> " + "edate"+ gantt.templates.tooltip_date_format(i)
};
//# sourceMappingURL=../sources/ext/dhtmlxgantt_tooltip.js.map
/*
 * Function: getType
 * This is used for check the value of argument is which data type ex: float,int or string
 */
function getType(leap) {
    var m = (/[\d]+(\.[\d]+)?/).exec(leap);
    if (m) {
       // Check if there is a decimal place
       if (m[1]) { 
          return 'float'; }
       else { 
           return 'int'; }          
    }
    return 'string';
}