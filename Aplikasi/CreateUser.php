<?php
//Include Common Files @1-5471E0F2
define("RelativePath", ".");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsRecordusers { //users Class @2-9BE1AF6F

//Variables @2-5C5E2D83

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $Recordset;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $ds;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;

    // Class variables
//End Variables

//Class_Initialize Event @2-74928FB9
    function clsRecordusers()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record users/Error";
        $this->ds = new clsusersDataSource();
        $this->InsertAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "users";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->UserUsername = new clsControl(ccsTextBox, "UserUsername", "Username", ccsText, "", CCGetRequestParam("UserUsername", $Method));
            $this->UserUsername->Required = true;
            $this->UserPassword = new clsControl(ccsTextBox, "UserPassword", "Password", ccsText, "", CCGetRequestParam("UserPassword", $Method));
            $this->UserPassword->Required = true;
            $this->UserFullName = new clsControl(ccsTextBox, "UserFullName", "UserFullName", ccsText, "", CCGetRequestParam("UserFullName", $Method));
            $this->UserFullName->Required = true;
            $this->UserAddress = new clsControl(ccsTextArea, "UserAddress", "UserAddress", ccsText, "", CCGetRequestParam("UserAddress", $Method));
            $this->UserEmail = new clsControl(ccsTextBox, "UserEmail", "UserEmail", ccsText, "", CCGetRequestParam("UserEmail", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Cancel = new clsButton("Button_Cancel");
        }
    }
//End Class_Initialize Event

//Initialize Method @2-F66CC420
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlUserUsername"] = CCGetFromGet("UserUsername", "");
    }
//End Initialize Method

//Validate Method @2-5C05DE48
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->UserUsername->Validate() && $Validation);
        $Validation = ($this->UserPassword->Validate() && $Validation);
        $Validation = ($this->UserFullName->Validate() && $Validation);
        $Validation = ($this->UserAddress->Validate() && $Validation);
        $Validation = ($this->UserEmail->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-4EE46C11
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->UserUsername->Errors->Count());
        $errors = ($errors || $this->UserPassword->Errors->Count());
        $errors = ($errors || $this->UserFullName->Errors->Count());
        $errors = ($errors || $this->UserAddress->Errors->Count());
        $errors = ($errors || $this->UserEmail->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-90E0E2E7
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        $this->EditMode = $this->ds->AllParametersSet;
        if(!$this->FormSubmitted)
            return;

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "Login.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "Login.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @2-652F9E17
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->UserUsername->SetValue($this->UserUsername->GetValue());
        $this->ds->UserPassword->SetValue($this->UserPassword->GetValue());
        $this->ds->UserFullName->SetValue($this->UserFullName->GetValue());
        $this->ds->UserAddress->SetValue($this->UserAddress->GetValue());
        $this->ds->UserEmail->SetValue($this->UserEmail->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//Show Method @2-869357C7
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode)
        {
            $this->ds->open();
            if($this->Errors->Count() == 0)
            {
                if($this->ds->Errors->Count() > 0)
                {
                    echo "Error in Record users";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->UserUsername->SetValue($this->ds->UserUsername->GetValue());
                        $this->UserPassword->SetValue($this->ds->UserPassword->GetValue());
                        $this->UserFullName->SetValue($this->ds->UserFullName->GetValue());
                        $this->UserAddress->SetValue($this->ds->UserAddress->GetValue());
                        $this->UserEmail->SetValue($this->ds->UserEmail->GetValue());
                    }
                }
                else
                {
                    $this->EditMode = false;
                }
            }
        }
        if(!$this->FormSubmitted)
        {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error .= $this->UserUsername->Errors->ToString();
            $Error .= $this->UserPassword->Errors->ToString();
            $Error .= $this->UserFullName->Errors->ToString();
            $Error .= $this->UserAddress->Errors->ToString();
            $Error .= $this->UserEmail->Errors->ToString();
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->UserUsername->Show();
        $this->UserPassword->Show();
        $this->UserFullName->Show();
        $this->UserAddress->Show();
        $this->UserEmail->Show();
        $this->Button_Insert->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End users Class @2-FCB6E20C

class clsusersDataSource extends clsDBConnection1 {  //usersDataSource Class @2-DF0C03ED

//DataSource Variables @2-0F8C0927
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $UserUsername;
    var $UserPassword;
    var $UserFullName;
    var $UserAddress;
    var $UserEmail;
//End DataSource Variables

//Class_Initialize Event @2-0F5FC7AF
    function clsusersDataSource()
    {
        $this->ErrorBlock = "Record users/Error";
        $this->Initialize();
        $this->UserUsername = new clsField("UserUsername", ccsText, "");
        $this->UserPassword = new clsField("UserPassword", ccsText, "");
        $this->UserFullName = new clsField("UserFullName", ccsText, "");
        $this->UserAddress = new clsField("UserAddress", ccsText, "");
        $this->UserEmail = new clsField("UserEmail", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @2-73464183
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlUserUsername", ccsText, "", "", $this->Parameters["urlUserUsername"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "UserUsername", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-DC1AA46D
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM users";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-54C2689B
    function SetValues()
    {
        $this->UserUsername->SetDBValue($this->f("UserUsername"));
        $this->UserPassword->SetDBValue($this->f("UserPassword"));
        $this->UserFullName->SetDBValue($this->f("UserFullName"));
        $this->UserAddress->SetDBValue($this->f("UserAddress"));
        $this->UserEmail->SetDBValue($this->f("UserEmail"));
    }
//End SetValues Method

//Insert Method @2-E124D3E4
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["UserUsername"] = new clsSQLParameter("ctrlUserUsername", ccsText, "", "", $this->UserUsername->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["UserPassword"] = new clsSQLParameter("ctrlUserPassword", ccsText, "", "", $this->UserPassword->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["UserFullName"] = new clsSQLParameter("ctrlUserFullName", ccsText, "", "", $this->UserFullName->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["UserAddress"] = new clsSQLParameter("ctrlUserAddress", ccsText, "", "", $this->UserAddress->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["UserEmail"] = new clsSQLParameter("ctrlUserEmail", ccsText, "", "", $this->UserEmail->GetValue(), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO users ("
             . "UserUsername, "
             . "UserPassword, "
             . "UserFullName, "
             . "UserAddress, "
             . "UserEmail"
             . ") VALUES ("
             . $this->ToSQL($this->cp["UserUsername"]->GetDBValue(), $this->cp["UserUsername"]->DataType) . ", "
             . $this->ToSQL($this->cp["UserPassword"]->GetDBValue(), $this->cp["UserPassword"]->DataType) . ", "
             . $this->ToSQL($this->cp["UserFullName"]->GetDBValue(), $this->cp["UserFullName"]->DataType) . ", "
             . $this->ToSQL($this->cp["UserAddress"]->GetDBValue(), $this->cp["UserAddress"]->DataType) . ", "
             . $this->ToSQL($this->cp["UserEmail"]->GetDBValue(), $this->cp["UserEmail"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

} //End usersDataSource Class @2-FCB6E20C

//Initialize Page @1-83120DB9
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "CreateUser.php";
$Redirect = "";
$TemplateFileName = "CreateUser.html";
$BlockToParse = "main";
$PathToRoot = "./";
//End Initialize Page

//Initialize Objects @1-68868FB1
$DBConnection1 = new clsDBConnection1();

// Controls
$users = new clsRecordusers();
$users->Initialize();

// Events
include("./CreateUser_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if($Charset) {
    header("Content-Type: text/html; charset=" . $Charset);
}
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-0C9864E9
$users->Operation();
//End Execute Components

//Go to destination page @1-6F9FD7CC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBConnection1->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-5CE26D48
$users->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $generated_with . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $generated_with;
}
echo $main_block;
//End Show Page

//Unload Page @1-A4D34ABE
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBConnection1->close();
unset($Tpl);
//End Unload Page


?>
