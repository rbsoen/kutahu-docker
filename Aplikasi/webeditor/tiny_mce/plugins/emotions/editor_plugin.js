/* Import theme specific language pack */
tinyMCE.importPluginLanguagePack('emotions', 'uk,se');

/**
 * Returns the HTML contents of the emotions control.
 */
function TinyMCE_emotions_getControlHTML(control_name) {
	switch (control_name) {
		case "emotions":
			return '<img id="{$editor_id}_emotions" src="{$pluginurl}/images/emotions.gif" title="{$lang_emotions_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceEmotion\');">';
	}

	return "";
}

/**
 * Executes the mceEmotion command.
 */
function TinyMCE_emotions_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceEmotion":
			var template = new Array();

			template['file'] = '../../plugins/emotions/emotions.htm'; // Relative to theme
			template['width'] = 150;
			template['height'] = 170;

			tinyMCE.openWindow(template, {editor_id : editor_id});

			return true;
	}

	// Pass to next handler in chain
	return false;
}
