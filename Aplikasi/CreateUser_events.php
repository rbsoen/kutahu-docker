<?php
//BindEvents Method @1-B69A3C53
function BindEvents()
{
    global $users;
    $users->Button_Insert->CCSEvents["OnClick"] = "users_Button_Insert_OnClick";
}
//End BindEvents Method

//users_Button_Insert_OnClick @3-CFBB3212
function users_Button_Insert_OnClick()
{
    $users_Button_Insert_OnClick = true;
//End users_Button_Insert_OnClick

//Custom Code @8-D37AD2A6
// -------------------------
    global $users;
	
	//	global $employees_record;

    $ProjectConnection = null;

	$db = new clsDBConnection1();
    $SQL = "select * from users where UserUsername=". $db->ToSQL($users->UserUsername->GetValue(), ccsText);
    $db->query($SQL);
 	$Result = $db->next_record();
    if($Result)
    {
        $users_Button_Insert_OnClick=false;
		$users->Errors->addError("Login ID Sudah ada.");
	}
    $db->close();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close users_Button_Insert_OnClick @3-5A7682ED
    return $users_Button_Insert_OnClick;
}
//End Close users_Button_Insert_OnClick


?>
