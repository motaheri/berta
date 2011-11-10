<?
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.customEntryHeader.php
 * Type:     function
 * Name:     customEntryHeader
 * Purpose:  
 * -------------------------------------------------------------
 */
function smarty_function_customEntryHeader($params, &$smarty) {
	global $berta;
	$settings = $berta->template->settings;
	
	if($berta->environment != 'engine') return '';
	
	$markedValue = !empty($params['entry']['marked']['value']) ? 1 : 0;
	
	$tags=isset($params['entry']['tags'])?implode(', ',$params['entry']['tags']):'';
	
	return <<<DOC
		<a class="xCreateNewEntry xPanel xAction-entryCreateNew" href="#"><span>create new entry here</span></a>
	
		<div class="xEntryEditWrap">
			<div class="xEntryEditWrapButtons xPanel">
				
				<a href="#" class="xEntryMove xHandle" title="drag to move around"><span>move entry</span></a>
								
				<div class="tagsList">
					<div title="$tags" class="xEditableRC xProperty-submenu xFormatModifier-toTags">$tags</div>
				</div>				
				
				<div class="xEntryDropdown"></div>
				
			</div>
			<div class="xEntryDropdownBox">
				<ul>
					<li>				
						<a href="#" class="xEntryToBack" title="send to back behind others"><span>Send to back</span></a>
					</li>				
					<li>
						<a><div class="xEntryCheck"><label><span class="xEditableRealCheck xProperty-marked">$markedValue</span>Marked</label></div></a>
					</li>
					<li>					
						<a href="#" class="xEntryDelete xAction-entryDelete" title="delete"><span>Delete</span></a>
					</li>									
				</ul>
			</div>			
DOC;
}
?>