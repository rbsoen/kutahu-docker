/**
 * Returns the HTML contents of the zoom control.
 */
function TinyMCE_zoom_getControlHTML(control_name) {
	if (!tinyMCE.isMSIE)
		return "";

	switch (control_name) {
		case "zoom":
			return '<select id="{$editor_id}_formatSelect" name="{$editor_id}_zoomSelect" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceZoom\',false,this.options[this.selectedIndex].value);" class="mceSelectList">\
					<option value="100%">100%</option>\
					<option value="150%">150%</option>\
					<option value="200%">200%</option>\
					<option value="250%">250%</option>\
					</select>';
	}

	return "";
}

/**
 * Executes the mceZoom command.
 */
function TinyMCE_zoom_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceZoom":
			tinyMCE._getInstanceById(editor_id).contentDocument.body.style.zoom = value;
			tinyMCE._getInstanceById(editor_id).contentDocument.body.style.mozZoom = value;
			return true;
	}

	// Pass to next handler in chain
	return false;
}
