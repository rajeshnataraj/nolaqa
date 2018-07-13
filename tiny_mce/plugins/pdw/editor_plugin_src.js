(function() 
{
	var DOM = tinymce.DOM;
	tinymce.PluginManager.requireLangPack('pdw');
	tinymce.create('tinymce.plugins.pdw', 
	{
		init : function(ed, url) 
		{
			var t = this, tbIds = new Array(), toolbars = new Array(), i;
				// Split toolbars
			toolbars = (ed.settings.pdw_toggle_toolbars).split(',');
			
			for(i = 0; i < toolbars.length; i++)
			{
				tbIds[i] = ed.getParam('', 'toolbar' + (toolbars[i]).replace(' ',''));
			}
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mcePDWToggleToolbars', function() 
			{
				var cm = ed.controlManager, id, j, Cookie = tinymce.util.Cookie, Toggle_PDW, Toggle = Cookie.getHash("TinyMCE_toggle") || new Object();
				for(j = 0; j < tbIds.length; j++)
				{
					obj = ed.controlManager.get(tbIds[j]);
                    if(typeof obj =="undefined") 
					{
                        continue;
                    }
                    id = obj.id;
					if (DOM.isHidden(id)) 
					{
						Toggle_PDW = 0;
						DOM.show(id);
						t._resizeIframe(ed, tbIds[j], -26);
					}
					else 
					{
						Toggle_PDW = 1;
						DOM.hide(id);
						t._resizeIframe(ed, tbIds[j], 26);
					}
				}
				cm.setActive('pdw_toggle', Toggle_PDW);
				ed.settings.pdw_toggle_on = Toggle_PDW;
				Toggle[ed.id] = Toggle_PDW;
				Cookie.setHash("TinyMCE_toggle", Toggle);
			});
			
			// Register pdw_toggle button
			ed.addButton('pdw_toggle', 
			{
				title : ed.getLang('pdw.desc', 0),
				cmd : 'mcePDWToggleToolbars',
				label :'More Options'
				
			});
			
			ed.onPostRender.add(function()
			{
				var toggle = tinymce.util.Cookie.getHash("TinyMCE_toggle") || new Object();
				var run = false;
				
				// Check if value is stored in cookie
				if(toggle[ed.id] == null)
				{
					// No cookie so check if the setting pdw_toggle_on is set to 1 then hide toolbars and set button active
					run = ed.settings.pdw_toggle_on == 1 ? true : false;
				} 
				else if(toggle[ed.id] == 1)
				{
					run = true;
				}
			
				if (run) 
				{
					var cm = ed.controlManager, tdId, id;
					for(i = 0; i < toolbars.length; i++)
					{
						tbId = ed.getParam('', 'toolbar' + (toolbars[i]).replace(' ',''));
						id = ed.controlManager.get(tbId).id;
						cm.setActive('pdw_toggle', 1);
						DOM.hide(id);
						t._resizeIframe(ed, tbId, 26);
					}
				}
			});
		},
		
		// Resizes the iframe by a relative height value
		_resizeIframe : function(ed, tb_id, dy) 
		{
			var ifr = ed.getContentAreaContainer().firstChild;
			DOM.setStyle(ifr, 'height',DOM.getSize(ifr).h + dy); // Resize iframe
			ed.theme.deltaHeight += dy; // For resize cookie
		},
		getInfo : function() 
		{
			return {
				longname : 'PDW Toggle Toolbars',
				author : '',
				authorurl : '',
				infourl : '',
				version : ""
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('pdw', tinymce.plugins.pdw);
})();