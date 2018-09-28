<?php
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/termoscommon.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/db.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.User.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.User.php';
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/class.Organisation.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/trms-php/functions.Organisation.php';
DBcnx();
if(isset($_REQUEST["action"]))$action = $_REQUEST["action"];
if(isset($_REQUEST["recordid"]))$recordid = $_REQUEST["recordid"];
if(isset($_REQUEST["programid"]))$programid = $_REQUEST["programid"];
if(isset($_REQUEST["programdetail"]))$programdetail = $_REQUEST["programdetail"];
if(isset($_REQUEST["programdetailvalue"]))$programdetailvalue = $_REQUEST["programdetailvalue"];


if( $admin = UserGetUserByID(TermosGetCurrentUserID()) ){

	if($action == "createprogram")
			createProgram($recordid);
	else if($action == "editprogram")
			editProgram($programid);
	else if($action == "displayprogram")
			displayProgram($programid);
	else if($action == "saveprgdetail")
			saveProgramDetail($programid, $programdetail, $programdetailvalue);
	else if($action == "deleteprogram")
			deleteProgram($programid, $recordid);
	else if($action == "listprograms")
			displayList($recordid);
	else	displayList($recordid);
}

function displayList($recordid){

	
	//print '<div id="console"></div>';
	
	$prg = ProgramGetAllForOrganisation($recordid);
	
	
	print '<div id="wcp_prg_list">';
	
	print '<h3>WCP programs for ' . OrganisationGetName($recordid) .'</h3>';
	
	print '<table id="tbl_prg_list">';
	
	print '<tr ><td colspan="9"></td><td colspan="7" style="font-size:11px">Extra copies</td><td></td></tr>';
	print '<tr id="tbl_caption">'.
		  '<td style="width:15px"></td>' .
		  '<td style="width:40px">Year</td>'.
		  '<td style="width:40px">Votes</td>' .
		  '<td style="width:100px">Tot. no of Students</td>'.
		  '<td style="width:100px">Students in Program</td>'.
		  '<td style="width:50px">Classes</td>'.
		  '<td style="width:60px">Max class</td>'.
		  '<td style="width:40px">Copies</td>'.
		  '<td style="width:100px">Language</td>'.
		  //'<td style="width:70px">Extra Copies</td>'. 
		  '<td style="width:30px">EN</td>'.
		  '<td style="width:30px">ES</td>'.
		  '<td style="width:30px">FR</td>'.
		  '<td style="width:30px">PT</td>'.
		  '<td style="width:30px">SW</td>'.
		  '<td style="width:30px">Other</td>'.
		  '<td style="width:100px">Other Language</td>'.
		  '<td style="width:280px">Comment</td>'.
		  '<td></td>'.
		  '</tr>';
	
	while($prg = ProgramGetNext($prg)){
		$row = 0;
		$row++;
		if($row % 2)
		print 	'<tr class="prg_row_even" id="prg_'.$prg->getID().'">';
		else
		print 	'<tr class="prg_row" id="prg_'.$prg->getID().'">';
		
		
		print	'<td><img class="editrow" src="/trms-admin/images/icon_edit.gif" id="prg_'.$prg->getID().'x'.$prg->getOrgID().'"/></td>'.
				'<td>' . substr($prg->getYear(),0,4) . '</td>' .
				'<td>' . $prg->getVotes() . '</td>' .
				'<td>' . $prg->getStudentsAtSchool() . '</td>' .
				'<td>' . $prg->getStudentsInProgram() . '</td>' .
				'<td>' . $prg->getNoOfClasses() . '</td>' .
				'<td>' . $prg->getMaxClassSize() . '</td>' .
				'<td>' . $prg->getCopies() . '</td>' .
				'<td>'.LanguageGet($prg->getLanguageID()).'</td>'.
				//'<td></td>'.
				'<td>' . $prg->getCopiesEN() . '</td>' .
				'<td>' . $prg->getCopiesES() . '</td>' .
				'<td>' . $prg->getCopiesFR() . '</td>' .
				'<td>' . $prg->getCopiesPT() . '</td>' .
				'<td>' . $prg->getCopiesSE() . '</td>' .
				'<td>' . $prg->getCopiesXX() . '</td>' .
				'<td>' . $prg->getAltLang() . '</td>' .
				'<td>' . $prg->getComment() . '</td>' .
				'<td><img class="deleterow" src="/trms-admin/images/delete_mini.gif" id="deleteprg_'.$prg->getID().'x'.$prg->getOrgID().'"/></td>'.
				'</tr>';
	}
	
	print '</table>';
	
	print '<input type="button" class="gf_button_right" id="createprg" value="+ Add new program"/>';
	print '</div>';
	//print PROGRAM_INSERT;

}

function displayProgram($pid){

	$prg = ProgramGetByID($pid);
	print 	'<td><img class="editrow" src="/trms-admin/images/icon_edit.gif" id="prg_'.$prg->getID().'x'.$prg->getOrgID().'"/></td>'.
			'<td>' . substr($prg->getYear(),0,4) . '</td>' .
			'<td>' . $prg->getVotes() . '</td>' .
			'<td>' . $prg->getStudentsAtSchool() . '</td>' .
			'<td>' . $prg->getStudentsInProgram() . '</td>' .
			'<td>' . $prg->getNoOfClasses() . '</td>' .
			'<td>' . $prg->getMaxClassSize() . '</td>' .
			'<td>' . $prg->getCopies() . '</td>' .
			'<td>'.LanguageGet($prg->getLanguageID()).'</td>'.
			//'<td></td>'.
			'<td>' . $prg->getCopiesEN() . '</td>' .
			'<td>' . $prg->getCopiesES() . '</td>' .
			'<td>' . $prg->getCopiesFR() . '</td>' .
			'<td>' . $prg->getCopiesPT() . '</td>' .
			'<td>' . $prg->getCopiesSE() . '</td>' .
			'<td>' . $prg->getCopiesXX() . '</td>' .
			'<td>' . $prg->getAltLang() . '</td>' .
			'<td>' . $prg->getComment() . '</td>'.
			'<td><img class="deleterow" src="/trms-admin/images/delete_mini.gif" id="deleteprg_'.$prg->getID().'x'.$prg->getOrgID().'"/></td>';

}

function editProgram($pid){

	if($pid > 0){
	
	$prg = ProgramGetByID($pid);
	print 	'<td><img class="editrow" src="/trms-admin/images/icon_content.gif" id="saveprg_'.$prg->getID().'x'.$prg->getOrgID().'"/></td>'.
			'<td><input type="text" class="prg_input_edit" id="year_'.$prg->getID().'" value="' . substr($prg->getYear(),0,4) . '" size="3" maxlength="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="votes_'.$prg->getID().'" value="' . $prg->getVotes() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="studentsatschool_'.$prg->getID().'" value="' . $prg->getStudentsAtSchool() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="studentsinprogram_'.$prg->getID().'" value="' . $prg->getStudentsInProgram() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="noofclasses_'.$prg->getID().'" value="' . $prg->getNoOfClasses() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="maxclassize_'.$prg->getID().'" value="' . $prg->getMaxClassSize() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copies_'.$prg->getID().'" value="' . $prg->getCopies() . '" size="3"/></td>' .
			'<td>'.LanguageGet($prg->getLanguageID()).'</td>'.
			//'<td></td>'.
			'<td><input type="text" class="prg_input_edit" id="copiesen_'.$prg->getID().'" value="' . $prg->getCopiesEN() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copieses_'.$prg->getID().'" value="' . $prg->getCopiesES() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiesfr_'.$prg->getID().'" value="' . $prg->getCopiesFR() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiespt_'.$prg->getID().'" value="' . $prg->getCopiesPT() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiesse_'.$prg->getID().'" value="' . $prg->getCopiesSE() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiesxx_'.$prg->getID().'" value="' . $prg->getCopiesXX() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit_medium" id="altlang_'.$prg->getID().'" value="' . $prg->getAltLang() . '" size="3"/></td>' .
			'<td><input type="text" class="prg_input_edit_wide" id="comment_'.$prg->getID().'" value="' . $prg->getComment() . '" size="30"/></td>';
	}
	
	
	
}

function saveProgramDetail($programid, $programdetail, $programdetailvalue){

	$prg = ProgramGetByID($programid);

	switch($programdetail){
			case 'votes':
			$prg->setVotes($programdetailvalue);
			break;
			case 'year':
			$programdetailvalue .= '-01-01';
			$prg->setYear($programdetailvalue);
			break;
			case 'studentsatschool':
			$prg->setStudentsAtSchool($programdetailvalue);
			break;
			case 'studentsinprogram':
			$prg->setStudentsInProgram($programdetailvalue);
			break;
			case 'noofclasses':
			$prg->setNoOfClasses($programdetailvalue);
			break;
			case 'maxclassize':
			$prg->setMaxClassSize($programdetailvalue);
			break;
			case 'copies':
			$prg->setCopies($programdetailvalue);
			break;
			case 'copiesen':
			$prg->setCopiesEN($programdetailvalue);
			break;
			case 'copieses':
			$prg->setCopiesES($programdetailvalue);
			break;
			case 'copiesfr':
			$prg->setCopiesFR($programdetailvalue);
			break;
			case 'copiespt':
			$prg->setCopiesPT($programdetailvalue);
			break;
			case 'copiesse':
			$prg->setCopiesSE($programdetailvalue);
			break;
			case 'copiesxx':
			$prg->setCopiesXX($programdetailvalue);
			break;
			case 'altlang':
			print "Hejhej";
			$prg->setAltLang($programdetailvalue);
			break;
			case 'comment':
			print "Hoho";
			$prg->setComment($programdetailvalue);
			break;
		}
		
		ProgramSave($prg);
}

function createProgram($recordid){

	$org = OrganisationGetByID($recordid);

	$prg = new Program;
	$prg->setOrgID($recordid);
	if($org->getLanguageID() < 1)
	$prg->setLanguageID(1);
	else
	$prg->setLanguageID($org->getLanguageID());
	$id = ProgramSave($prg);

	//print '<tr><td colspan="16">'.$id.'</td></tr>';

	print 	'<tr id="prg_'.$id.'">'.
			'<td><img src="/trms-admin/images/edit_mini.gif" id="saveprg_'.$id.'x'.$prg->getOrgID().'"/></td>'.
			'<td><input type="text" class="prg_input_edit" id="year_'.$id.'" value="' . substr($prg->getYear(),0,4) . '" size="4" maxlength="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="votes_'.$id.'" value="' . $prg->getVotes() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="studentsatschool_'.$id.'" value="' . $prg->getStudentsAtSchool() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="studentsinprogram_'.$id.'" value="' . $prg->getStudentsInProgram() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="noofclasses_'.$id.'" value="' . $prg->getNoOfClasses() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="maxclassize_'.$id.'" value="' . $prg->getMaxClassSize() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copies_'.$id.'" value="' . $prg->getCopies() . '" size="4"/></td>' .
			'<td>'.LanguageGet($prg->getLanguageID()).'</td>'.
			//'<td></td>'.
			'<td><input type="text" class="prg_input_edit" id="copiesen_'.$id.'" value="' . $prg->getCopiesEN() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copieses_'.$id.'" value="' . $prg->getCopiesES() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiesfr_'.$id.'" value="' . $prg->getCopiesFR() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiespt_'.$id.'" value="' . $prg->getCopiesPT() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiesse_'.$id.'" value="' . $prg->getCopiesSE() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit" id="copiesxx_'.$id.'" value="' . $prg->getCopiesXX() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit_medium" id="altlang_'.$id.'" value="' . $prg->getAltLang() . '" size="4"/></td>' .
			'<td><input type="text" class="prg_input_edit_wide" id="comment_'.$id.'" value="' . $prg->getComment() . '" size="30"/></td>'.
			'</tr>';
}

function deleteProgram($prgid, $orgid){
	
	ProgramDelete($prgid, $orgid);
}


?>