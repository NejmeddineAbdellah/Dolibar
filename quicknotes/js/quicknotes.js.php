<?php

define('NOLOGIN', 1);
define('NOREDIRECTBYMAINTOLOGIN', 1);
define('NOTOKENRENEWAL', 1);

// Load Dolibarr environment
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
	require '../../../main.inc.php'; // From "custom" directory
}

global $langs;

$langs->load('quicknotes@quicknotes');

header('Content-Type: text/javascript');

?>

$(document).ready(function() {
	var quick_notes_history, quick_notes_cleared, quick_notes_saved;
	$('#quick-notes-button').click(function(e){
		var quick_notes_textarea = $('#quick-notes-textarea');
		$('#quick-notes-dialog').show().dialog({
			width: 400,
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $langs->transnoentities('ClearAll'); ?>": function() {
					quick_notes_textarea.val('');
					quick_notes_cleared = true;
				},
				"<?php echo $langs->transnoentities('SaveAndClose'); ?>": function() {
					$.ajax({
						url: '<?php echo dol_buildpath('quicknotes/ajax/ajax.php', 1); ?>',
						type: 'post',
						data: {action: 'save', notes: quick_notes_textarea.val()},
						async: false
					}).done(function(response) {
						//console.log(response);
						if (response != 'KO') {
							quick_notes_saved = true;
							$('#quick-notes-dialog').dialog('close');
						}
						else {
							alert('<?php echo $langs->transnoentities('ErrorWhileSaving'); ?>');
						}
					});
				}
			},
			open: function() {
				quick_notes_history = quick_notes_textarea.val();
				quick_notes_cleared = false;
				quick_notes_saved = false;
				$(this).parent().find("button.ui-button:eq(2)").focus();
			},
			close: function() {
				if (quick_notes_cleared && ! quick_notes_saved) {
					quick_notes_textarea.val(quick_notes_history);
				}
			}
		});
		e.preventDefault();
	});
});
