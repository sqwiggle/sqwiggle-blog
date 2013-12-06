//jQuery(document).ready(function($) {
//	$( "#team-sortable" ).sortable({
//		placeholder: "ui-socialize-highlight"
//	});
//	$( "#team-sortable" ).disableSelection();
//});


function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function HubSpotInsertCompanyShortcode(portalID) {
	var shortcodeData = '[hs_contact';
        
        if(document.getElementById("hs_company_name").value)
            shortcodeData += ' name="' + document.getElementById("hs_company_name").value + '"';

        if(document.getElementById("hs_company_address").value)
            shortcodeData += ' address="' + document.getElementById("hs_company_address").value + '"';

        if(document.getElementById("hs_company_citystate").value)
            shortcodeData += ' citystate="' + document.getElementById("hs_company_citystate").value + '"';
        
        if(document.getElementById("hs_company_phone").value)
            shortcodeData += ' phone="' + document.getElementById("hs_company_phone").value + '"';

        shortcodeData += ' display="' + document.getElementById("hs_contact_display").value + '"';

        shortcodeData += ']';

        tinyMCEPopup.editor.selection.setContent(shortcodeData);


	tinyMCEPopup.close();

        
}

function HubSpotInsertTeamShortcode(portalID) {
        //var result = jQuery( "#team-sortable" ).sortable('toArray');
        //alert(result[0]);
        //jQuery("#team-sortable").sortable('refresh');

	var shortcodeData = '[hs_team';

        var shortcodeDataParams = '';
            for(var i=0; i < document.popup_form2.team_members.length; i++){
            if(document.popup_form2.team_members[i].checked){
                if(shortcodeDataParams!=''){
                    shortcodeDataParams += ', ';
                }
                shortcodeDataParams += document.popup_form2.team_members[i].value;
            }
        }
        if (shortcodeDataParams!=""){
            shortcodeData += ' id="' + shortcodeDataParams + '"';
        }
        shortcodeData += ']';
        
        tinyMCEPopup.editor.selection.setContent(shortcodeData);

	tinyMCEPopup.close();
}

function HubSpotInsertFormShortcode(portalID) {
        //var result = jQuery( "#team-sortable" ).sortable('toArray');
        //alert(result[0]);
        //jQuery("#team-sortable").sortable('refresh');

	var shortcodeData = '[hs_form';

        shortcodeData += ' id="' + document.getElementById("lead-form").value + '"';
        shortcodeData += ']';

        tinyMCEPopup.editor.selection.setContent(shortcodeData);

	tinyMCEPopup.close();
}

function HubSpotInsertActionShortcode(portalID) {
	var shortcodeData = '[hs_action';

        var shortcodeDataParams = '';
            for(var i=0; i < document.popup_form4.hs_action.length; i++){
            if(document.popup_form4.hs_action[i].checked){
                if(shortcodeDataParams!=''){
                    shortcodeDataParams += ', ';
                }
                shortcodeDataParams += document.popup_form4.hs_action[i].value;
            }
        }
        if (shortcodeDataParams!=""){
            shortcodeData += ' id="' + shortcodeDataParams + '"';
        }
        shortcodeData += ']';

        tinyMCEPopup.editor.selection.setContent(shortcodeData);

	tinyMCEPopup.close();
}
tinyMCEPopup.onInit.add(init);