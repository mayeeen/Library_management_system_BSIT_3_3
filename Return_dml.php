<?php

// Data functions (insert, update, delete, form) for table Return

// This script and data application were generated by AppGini 5.70
// Download AppGini for free from https://bigprof.com/appgini/download/

function Return_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('Return_Book');
	if(!$arrPerm[1]){
		return false;
	}

	$data['Book_Number'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Book_Number'] == empty_lookup_value){ $data['Book_Number'] = ''; }
	$data['Book_Title'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Book_Title'] == empty_lookup_value){ $data['Book_Title'] = ''; }
	$data['Issue_Date'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Issue_Date'] == empty_lookup_value){ $data['Issue_Date'] = ''; }
	$data['Due_Date'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Due_Date'] == empty_lookup_value){ $data['Due_Date'] = ''; }
	$data['Return_Date'] = intval($_REQUEST['Return_DateYear']) . '-' . intval($_REQUEST['Return_DateMonth']) . '-' . intval($_REQUEST['Return_DateDay']);
	$data['Return_Date'] = parseMySQLDate($data['Return_Date'], '1');
	$data['Member'] = makeSafe($_REQUEST['Member']);
		if($data['Member'] == empty_lookup_value){ $data['Member'] = ''; }
	$data['Number'] = makeSafe($_REQUEST['Member']);
		if($data['Number'] == empty_lookup_value){ $data['Number'] = ''; }
	$data['Fine'] = makeSafe($_REQUEST['Fine']);
		if($data['Fine'] == empty_lookup_value){ $data['Fine'] = ''; }
	$data['Status'] = makeSafe($_REQUEST['Status']);
		if($data['Status'] == empty_lookup_value){ $data['Status'] = ''; }
	if($data['Issue_Date'] == '') $data['Issue_Date'] = "1";
	if($data['Due_Date'] == '') $data['Due_Date'] = "1";
	if($data['Fine'] == '') $data['Fine'] = "0.0";

	// hook: Return_before_insert
	if(function_exists('Return_before_insert')){
		$args=array();
		if(!Return_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `Return` set       `Book_Number`=' . (($data['Book_Number'] !== '' && $data['Book_Number'] !== NULL) ? "'{$data['Book_Number']}'" : 'NULL') . ', `Book_Title`=' . (($data['Book_Title'] !== '' && $data['Book_Title'] !== NULL) ? "'{$data['Book_Title']}'" : 'NULL') . ', `Issue_Date`=' . (($data['Issue_Date'] !== '' && $data['Issue_Date'] !== NULL) ? "'{$data['Issue_Date']}'" : 'NULL') . ', `Due_Date`=' . (($data['Due_Date'] !== '' && $data['Due_Date'] !== NULL) ? "'{$data['Due_Date']}'" : 'NULL') . ', `Return_Date`=' . (($data['Return_Date'] !== '' && $data['Return_Date'] !== NULL) ? "'{$data['Return_Date']}'" : 'NULL') . ', `Member`=' . (($data['Member'] !== '' && $data['Member'] !== NULL) ? "'{$data['Member']}'" : 'NULL') . ', `Number`=' . (($data['Number'] !== '' && $data['Number'] !== NULL) ? "'{$data['Number']}'" : 'NULL') . ', `Fine`=' . (($data['Fine'] !== '' && $data['Fine'] !== NULL) ? "'{$data['Fine']}'" : 'NULL') . ', `Status`=' . (($data['Status'] !== '' && $data['Status'] !== NULL) ? "'{$data['Status']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"Return_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: Return_after_insert
	if(function_exists('Return_after_insert')){
		$res = sql("select * from `Return` where `id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!Return_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('Return', $recID, getLoggedMemberID());

	return $recID;
}

function Return_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('Return');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='Return' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='Return' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: Return_before_delete
	if(function_exists('Return_before_delete')){
		$args=array();
		if(!Return_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `Return` where `id`='$selected_id'", $eo);

	// hook: Return_after_delete
	if(function_exists('Return_after_delete')){
		$args=array();
		Return_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='Return' and pkValue='$selected_id'", $eo);
}

function Return_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('Return');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='Return' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='Return' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['Book_Number'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Book_Number'] == empty_lookup_value){ $data['Book_Number'] = ''; }
	$data['Book_Title'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Book_Title'] == empty_lookup_value){ $data['Book_Title'] = ''; }
	$data['Issue_Date'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Issue_Date'] == empty_lookup_value){ $data['Issue_Date'] = ''; }
	$data['Due_Date'] = makeSafe($_REQUEST['Book_Number']);
		if($data['Due_Date'] == empty_lookup_value){ $data['Due_Date'] = ''; }
	$data['Return_Date'] = intval($_REQUEST['Return_DateYear']) . '-' . intval($_REQUEST['Return_DateMonth']) . '-' . intval($_REQUEST['Return_DateDay']);
	$data['Return_Date'] = parseMySQLDate($data['Return_Date'], '1');
	$data['Member'] = makeSafe($_REQUEST['Member']);
		if($data['Member'] == empty_lookup_value){ $data['Member'] = ''; }
	$data['Number'] = makeSafe($_REQUEST['Member']);
		if($data['Number'] == empty_lookup_value){ $data['Number'] = ''; }
	$data['Fine'] = makeSafe($_REQUEST['Fine']);
		if($data['Fine'] == empty_lookup_value){ $data['Fine'] = ''; }
	$data['Status'] = makeSafe($_REQUEST['Status']);
		if($data['Status'] == empty_lookup_value){ $data['Status'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: Return_before_update
	if(function_exists('Return_before_update')){
		$args=array();
		if(!Return_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `Return` set       `Book_Number`=' . (($data['Book_Number'] !== '' && $data['Book_Number'] !== NULL) ? "'{$data['Book_Number']}'" : 'NULL') . ', `Book_Title`=' . (($data['Book_Title'] !== '' && $data['Book_Title'] !== NULL) ? "'{$data['Book_Title']}'" : 'NULL') . ', `Issue_Date`=' . (($data['Issue_Date'] !== '' && $data['Issue_Date'] !== NULL) ? "'{$data['Issue_Date']}'" : 'NULL') . ', `Due_Date`=' . (($data['Due_Date'] !== '' && $data['Due_Date'] !== NULL) ? "'{$data['Due_Date']}'" : 'NULL') . ', `Return_Date`=' . (($data['Return_Date'] !== '' && $data['Return_Date'] !== NULL) ? "'{$data['Return_Date']}'" : 'NULL') . ', `Member`=' . (($data['Member'] !== '' && $data['Member'] !== NULL) ? "'{$data['Member']}'" : 'NULL') . ', `Number`=' . (($data['Number'] !== '' && $data['Number'] !== NULL) ? "'{$data['Number']}'" : 'NULL') . ', `Fine`=' . (($data['Fine'] !== '' && $data['Fine'] !== NULL) ? "'{$data['Fine']}'" : 'NULL') . ', `Status`=' . (($data['Status'] !== '' && $data['Status'] !== NULL) ? "'{$data['Status']}'" : 'NULL') . " where `id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="Return_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: Return_after_update
	if(function_exists('Return_after_update')){
		$res = sql("SELECT * FROM `Return` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id'];
		$args = array();
		if(!Return_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='Return' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function Return_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('Return');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_Book_Number = thisOr(undo_magic_quotes($_REQUEST['filterer_Book_Number']), '');
	$filterer_Member = thisOr(undo_magic_quotes($_REQUEST['filterer_Member']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: Book_Number
	$combo_Book_Number = new DataCombo;
	// combobox: Return_Date
	$combo_Return_Date = new DateCombo;
	$combo_Return_Date->DateFormat = "mdy";
	$combo_Return_Date->MinYear = 1900;
	$combo_Return_Date->MaxYear = 2100;
	$combo_Return_Date->DefaultDate = parseMySQLDate('1', '1');
	$combo_Return_Date->MonthNames = $Translation['month names'];
	$combo_Return_Date->NamePrefix = 'Return_Date';
	// combobox: Member
	$combo_Member = new DataCombo;
	// combobox: Status
	$combo_Status = new Combo;
	$combo_Status->ListType = 0;
	$combo_Status->MultipleSeparator = ', ';
	$combo_Status->ListBoxHeight = 10;
	$combo_Status->RadiosPerLine = 1;
	if(is_file(dirname(__FILE__).'/hooks/Return.Status.csv')){
		$Status_data = addslashes(implode('', @file(dirname(__FILE__).'/hooks/Return.Status.csv')));
		$combo_Status->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions($Status_data)));
		$combo_Status->ListData = $combo_Status->ListItem;
	}else{
		$combo_Status->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions("pending;;cleared")));
		$combo_Status->ListData = $combo_Status->ListItem;
	}
	$combo_Status->SelectName = 'Status';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='Return' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='Return' and pkValue='".makeSafe($selected_id)."'");
		if($arrPerm[2]==1 && getLoggedMemberID()!=$ownerMemberID){
			return "";
		}
		if($arrPerm[2]==2 && getLoggedGroupID()!=$ownerGroupID){
			return "";
		}

		// can edit?
		if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){
			$AllowUpdate=1;
		}else{
			$AllowUpdate=0;
		}

		$res = sql("select * from `Return` where `id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'Return_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_Book_Number->SelectedData = $row['Book_Number'];
		$combo_Return_Date->DefaultDate = $row['Return_Date'];
		$combo_Member->SelectedData = $row['Member'];
		$combo_Status->SelectedData = $row['Status'];
	}else{
		$combo_Book_Number->SelectedData = $filterer_Book_Number;
		$combo_Member->SelectedData = $filterer_Member;
		$combo_Status->SelectedText = ( $_REQUEST['FilterField'][1]=='10' && $_REQUEST['FilterOperator'][1]=='<=>' ? (get_magic_quotes_gpc() ? stripslashes($_REQUEST['FilterValue'][1]) : $_REQUEST['FilterValue'][1]) : "");
	}
	$combo_Book_Number->HTML = '<span id="Book_Number-container' . $rnd1 . '"></span><input type="hidden" name="Book_Number" id="Book_Number' . $rnd1 . '" value="' . html_attr($combo_Book_Number->SelectedData) . '">';
	$combo_Book_Number->MatchText = '<span id="Book_Number-container-readonly' . $rnd1 . '"></span><input type="hidden" name="Book_Number" id="Book_Number' . $rnd1 . '" value="' . html_attr($combo_Book_Number->SelectedData) . '">';
	$combo_Member->HTML = '<span id="Member-container' . $rnd1 . '"></span><input type="hidden" name="Member" id="Member' . $rnd1 . '" value="' . html_attr($combo_Member->SelectedData) . '">';
	$combo_Member->MatchText = '<span id="Member-container-readonly' . $rnd1 . '"></span><input type="hidden" name="Member" id="Member' . $rnd1 . '" value="' . html_attr($combo_Member->SelectedData) . '">';
	$combo_Status->Render();

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_Book_Number__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['Book_Number'] : $filterer_Book_Number); ?>"};
		AppGini.current_Member__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['Member'] : $filterer_Member); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(Book_Number_reload__RAND__) == 'function') Book_Number_reload__RAND__();
				if(typeof(Member_reload__RAND__) == 'function') Member_reload__RAND__();
			}, 10); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function Book_Number_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#Book_Number-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_Book_Number__RAND__.value, t: 'Return', f: 'Book_Number' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="Book_Number"]').val(resp.results[0].id);
							$j('[id=Book_Number-container-readonly__RAND__]').html('<span id="Book_Number-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Book_Issue_view_parent]').hide(); }else{ $j('.btn[id=Book_Issue_view_parent]').show(); }


							if(typeof(Book_Number_update_autofills__RAND__) == 'function') Book_Number_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'Return', f: 'Book_Number' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_Book_Number__RAND__.value = e.added.id;
				AppGini.current_Book_Number__RAND__.text = e.added.text;
				$j('[name="Book_Number"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Book_Issue_view_parent]').hide(); }else{ $j('.btn[id=Book_Issue_view_parent]').show(); }


				if(typeof(Book_Number_update_autofills__RAND__) == 'function') Book_Number_update_autofills__RAND__();
			});

			if(!$j("#Book_Number-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_Book_Number__RAND__.value, t: 'Return', f: 'Book_Number' },
					success: function(resp){
						$j('[name="Book_Number"]').val(resp.results[0].id);
						$j('[id=Book_Number-container-readonly__RAND__]').html('<span id="Book_Number-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Book_Issue_view_parent]').hide(); }else{ $j('.btn[id=Book_Issue_view_parent]').show(); }

						if(typeof(Book_Number_update_autofills__RAND__) == 'function') Book_Number_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_Book_Number__RAND__.value, t: 'Return', f: 'Book_Number' },
				success: function(resp){
					$j('[id=Book_Number-container__RAND__], [id=Book_Number-container-readonly__RAND__]').html('<span id="Book_Number-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Book_Issue_view_parent]').hide(); }else{ $j('.btn[id=Book_Issue_view_parent]').show(); }

					if(typeof(Book_Number_update_autofills__RAND__) == 'function') Book_Number_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function Member_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#Member-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_Member__RAND__.value, t: 'Return', f: 'Member' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="Member"]').val(resp.results[0].id);
							$j('[id=Member-container-readonly__RAND__]').html('<span id="Member-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Users_view_parent]').hide(); }else{ $j('.btn[id=Users_view_parent]').show(); }


							if(typeof(Member_update_autofills__RAND__) == 'function') Member_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'Return', f: 'Member' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_Member__RAND__.value = e.added.id;
				AppGini.current_Member__RAND__.text = e.added.text;
				$j('[name="Member"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Users_view_parent]').hide(); }else{ $j('.btn[id=Users_view_parent]').show(); }


				if(typeof(Member_update_autofills__RAND__) == 'function') Member_update_autofills__RAND__();
			});

			if(!$j("#Member-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_Member__RAND__.value, t: 'Return', f: 'Member' },
					success: function(resp){
						$j('[name="Member"]').val(resp.results[0].id);
						$j('[id=Member-container-readonly__RAND__]').html('<span id="Member-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Users_view_parent]').hide(); }else{ $j('.btn[id=Users_view_parent]').show(); }

						if(typeof(Member_update_autofills__RAND__) == 'function') Member_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_Member__RAND__.value, t: 'Return', f: 'Member' },
				success: function(resp){
					$j('[id=Member-container__RAND__], [id=Member-container-readonly__RAND__]').html('<span id="Member-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=Users_view_parent]').hide(); }else{ $j('.btn[id=Users_view_parent]').show(); }

					if(typeof(Member_update_autofills__RAND__) == 'function') Member_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/Return_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/Return_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Return details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return Return_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return Return_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'AppGini.closeParentModal(); return false;';
	}else{
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return Return_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#Book_Number').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#Book_Number_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#Return_Date').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#Return_DateDay, #Return_DateMonth, #Return_DateYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#Member').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#Member_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#Fine').replaceWith('<div class=\"form-control-static\" id=\"Fine\">' + (jQuery('#Fine').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#Status').replaceWith('<div class=\"form-control-static\" id=\"Status\">' + (jQuery('#Status').val() || '') + '</div>'); jQuery('#Status-multi-selection-help').hide();\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(Book_Number)%%>', $combo_Book_Number->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(Book_Number)%%>', $combo_Book_Number->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(Book_Number)%%>', urlencode($combo_Book_Number->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(Return_Date)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_Return_Date->GetHTML(true) . '</div>' : $combo_Return_Date->GetHTML()), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(Return_Date)%%>', $combo_Return_Date->GetHTML(true), $templateCode);
	$templateCode = str_replace('<%%COMBO(Member)%%>', $combo_Member->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(Member)%%>', $combo_Member->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(Member)%%>', urlencode($combo_Member->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(Status)%%>', $combo_Status->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(Status)%%>', $combo_Status->SelectedData, $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'Book_Number' => array('Book_Issue', 'Book Number'), 'Member' => array('Users', 'Member'));
	foreach($lookup_fields as $luf => $ptfc){
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']){
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']){
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(id)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Book_Number)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Return_Date)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Member)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Fine)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Status)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Book_Number)%%>', safe_html($urow['Book_Number']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Book_Number)%%>', html_attr($row['Book_Number']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Book_Number)%%>', urlencode($urow['Book_Number']), $templateCode);
		$templateCode = str_replace('<%%VALUE(Return_Date)%%>', @date('m/d/Y', @strtotime(html_attr($row['Return_Date']))), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Return_Date)%%>', urlencode(@date('m/d/Y', @strtotime(html_attr($urow['Return_Date'])))), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Member)%%>', safe_html($urow['Member']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Member)%%>', html_attr($row['Member']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Member)%%>', urlencode($urow['Member']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Fine)%%>', safe_html($urow['Fine']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Fine)%%>', html_attr($row['Fine']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Fine)%%>', urlencode($urow['Fine']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Status)%%>', safe_html($urow['Status']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Status)%%>', html_attr($row['Status']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Status)%%>', urlencode($urow['Status']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(Book_Number)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Book_Number)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(Return_Date)%%>', '1', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Return_Date)%%>', urlencode('1'), $templateCode);
		$templateCode = str_replace('<%%VALUE(Member)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Member)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(Fine)%%>', '0.0', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Fine)%%>', urlencode('0.0'), $templateCode);
		$templateCode = str_replace('<%%VALUE(Status)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Status)%%>', urlencode(''), $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode = str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == ''){
		$templateCode .= "\n\n<script>\$j(function(){\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption){
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id){
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';

	$templateCode .= "\tBook_Number_update_autofills$rnd1 = function(){\n";
	$templateCode .= "\t\t\$j.ajax({\n";
	if($dvprint){
		$templateCode .= "\t\t\turl: 'Return_autofill.php?rnd1=$rnd1&mfk=Book_Number&id=' + encodeURIComponent('".addslashes($row['Book_Number'])."'),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET'\n";
	}else{
		$templateCode .= "\t\t\turl: 'Return_autofill.php?rnd1=$rnd1&mfk=Book_Number&id=' + encodeURIComponent(AppGini.current_Book_Number{$rnd1}.value),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET', beforeSend: function(){ \$j('#Book_Number$rnd1').prop('disabled', true); \$j('#Book_NumberLoading').html('<img src=loading.gif align=top>'); }, complete: function(){".(($arrPerm[1] || (($arrPerm[3] == 1 && $ownerMemberID == getLoggedMemberID()) || ($arrPerm[3] == 2 && $ownerGroupID == getLoggedGroupID()) || $arrPerm[3] == 3)) ? "\$j('#Book_Number$rnd1').prop('disabled', false); " : "\$j('#Book_Number$rnd1').prop('disabled', true); ")."\$j('#Book_NumberLoading').html('');}\n";
	}
	$templateCode.="\t\t});\n";
	$templateCode.="\t};\n";
	if(!$dvprint) $templateCode.="\tif(\$j('#Book_Number_caption').length) \$j('#Book_Number_caption').click(function(){ Book_Number_update_autofills$rnd1(); });\n";

	$templateCode .= "\tMember_update_autofills$rnd1 = function(){\n";
	$templateCode .= "\t\t\$j.ajax({\n";
	if($dvprint){
		$templateCode .= "\t\t\turl: 'Return_autofill.php?rnd1=$rnd1&mfk=Member&id=' + encodeURIComponent('".addslashes($row['Member'])."'),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET'\n";
	}else{
		$templateCode .= "\t\t\turl: 'Return_autofill.php?rnd1=$rnd1&mfk=Member&id=' + encodeURIComponent(AppGini.current_Member{$rnd1}.value),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET', beforeSend: function(){ \$j('#Member$rnd1').prop('disabled', true); \$j('#MemberLoading').html('<img src=loading.gif align=top>'); }, complete: function(){".(($arrPerm[1] || (($arrPerm[3] == 1 && $ownerMemberID == getLoggedMemberID()) || ($arrPerm[3] == 2 && $ownerGroupID == getLoggedGroupID()) || $arrPerm[3] == 3)) ? "\$j('#Member$rnd1').prop('disabled', false); " : "\$j('#Member$rnd1').prop('disabled', true); ")."\$j('#MemberLoading').html('');}\n";
	}
	$templateCode.="\t\t});\n";
	$templateCode.="\t};\n";
	if(!$dvprint) $templateCode.="\tif(\$j('#Member_caption').length) \$j('#Member_caption').click(function(){ Member_update_autofills$rnd1(); });\n";


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('Return');
	if($selected_id){
		$jdata = get_joined_record('Return', $selected_id);
		if($jdata === false) $jdata = get_defaults('Return');
		$rdata = $row;
	}
	$cache_data = array(
		'rdata' => array_map('nl2br', array_map('addslashes', $rdata)),
		'jdata' => array_map('nl2br', array_map('addslashes', $jdata))
	);
	$templateCode .= loadView('Return-ajax-cache', $cache_data);

	// hook: Return_dv
	if(function_exists('Return_dv')){
		$args=array();
		Return_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>