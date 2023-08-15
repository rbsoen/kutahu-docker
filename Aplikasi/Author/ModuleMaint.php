<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-39DC296A
include_once("./Header.php");
//End Include Page implementation

//Include Page implementation @4-D20A616D
include_once("./MenuAuthor.php");
//End Include Page implementation

class clsRecordmodule { //module Class @5-8BEA008D

//Variables @5-5C5E2D83

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

//Class_Initialize Event @5-281A5DA9
    function clsRecordmodule()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record module/Error";
        $this->ds = new clsmoduleDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "module";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->ModTitle = new clsControl(ccsTextBox, "ModTitle", "Mod Title", ccsText, "", CCGetRequestParam("ModTitle", $Method));
            $this->ModTitle->Required = true;
            $this->ModVersion = new clsControl(ccsTextBox, "ModVersion", "Mod Version", ccsText, "", CCGetRequestParam("ModVersion", $Method));
            $this->lblModTitle = new clsControl(ccsLabel, "lblModTitle", "lblModTitle", ccsText, "", CCGetRequestParam("lblModTitle", $Method));
            $this->lblModTitle->HTML = true;
            $this->lblLinkModule = new clsControl(ccsLabel, "lblLinkModule", "lblLinkModule", ccsText, "", CCGetRequestParam("lblLinkModule", $Method));
            $this->lblLinkModule->HTML = true;
            $this->Mod_ModID = new clsControl(ccsHidden, "Mod_ModID", "Mod_ModID", ccsText, "", CCGetRequestParam("Mod_ModID", $Method));
            $this->ModDesc = new clsControl(ccsTextArea, "ModDesc", "Mod Desc", ccsMemo, "", CCGetRequestParam("ModDesc", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
        }
    }
//End Class_Initialize Event

//Initialize Method @5-A59C912B
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlModID"] = CCGetFromGet("ModID", "");
    }
//End Initialize Method

//Validate Method @5-72214BC9
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->ModTitle->Validate() && $Validation);
        $Validation = ($this->ModVersion->Validate() && $Validation);
        $Validation = ($this->Mod_ModID->Validate() && $Validation);
        $Validation = ($this->ModDesc->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @5-2E983EC7
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ModTitle->Errors->Count());
        $errors = ($errors || $this->ModVersion->Errors->Count());
        $errors = ($errors || $this->lblModTitle->Errors->Count());
        $errors = ($errors || $this->lblLinkModule->Errors->Count());
        $errors = ($errors || $this->Mod_ModID->Errors->Count());
        $errors = ($errors || $this->ModDesc->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @5-8F743183
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
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if(strlen(CCGetParam("Button_Insert", ""))) {
                $this->PressedButton = "Button_Insert";
            } else if(strlen(CCGetParam("Button_Update", ""))) {
                $this->PressedButton = "Button_Update";
            } else if(strlen(CCGetParam("Button_Delete", ""))) {
                $this->PressedButton = "Button_Delete";
            } else if(strlen(CCGetParam("Button_Cancel", ""))) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "ModuleList.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "ModuleList.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick") || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick") || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//InsertRow Method @5-8560BB7F
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->ModTitle->SetValue($this->ModTitle->GetValue());
        $this->ds->ModDesc->SetValue($this->ModDesc->GetValue());
        $this->ds->ModVersion->SetValue($this->ModVersion->GetValue());
        $this->ds->Mod_ModID->SetValue($this->Mod_ModID->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @5-32B3C6B9
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->ModTitle->SetValue($this->ModTitle->GetValue());
        $this->ds->ModDesc->SetValue($this->ModDesc->GetValue());
        $this->ds->ModVersion->SetValue($this->ModVersion->GetValue());
        $this->ds->Mod_ModID->SetValue($this->Mod_ModID->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @5-521D497C
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
                    echo "Error in Record module";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    $this->lblModTitle->SetValue($this->ds->lblModTitle->GetValue());
                    $this->lblLinkModule->SetValue($this->ds->lblLinkModule->GetValue());
                    if(!$this->FormSubmitted)
                    {
                        $this->ModTitle->SetValue($this->ds->ModTitle->GetValue());
                        $this->ModVersion->SetValue($this->ds->ModVersion->GetValue());
                        $this->Mod_ModID->SetValue($this->ds->Mod_ModID->GetValue());
                        $this->ModDesc->SetValue($this->ds->ModDesc->GetValue());
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
            $Error .= $this->ModTitle->Errors->ToString();
            $Error .= $this->ModVersion->Errors->ToString();
            $Error .= $this->lblModTitle->Errors->ToString();
            $Error .= $this->lblLinkModule->Errors->ToString();
            $Error .= $this->Mod_ModID->Errors->ToString();
            $Error .= $this->ModDesc->Errors->ToString();
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
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->ModTitle->Show();
        $this->ModVersion->Show();
        $this->lblModTitle->Show();
        $this->lblLinkModule->Show();
        $this->Mod_ModID->Show();
        $this->ModDesc->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End module Class @5-FCB6E20C

class clsmoduleDataSource extends clsDBConnection1 {  //moduleDataSource Class @5-3199DDCA

//DataSource Variables @5-9A23626D
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $ModTitle;
    var $ModVersion;
    var $lblModTitle;
    var $lblLinkModule;
    var $Mod_ModID;
    var $ModDesc;
//End DataSource Variables
var $ModCreated;
var $ModModify ;
//Class_Initialize Event @5-7F48CFB9
    function clsmoduleDataSource()
    {
        $this->ErrorBlock = "Record module/Error";
        $this->Initialize();
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->ModVersion = new clsField("ModVersion", ccsText, "");
        $this->lblModTitle = new clsField("lblModTitle", ccsText, "");
        $this->lblLinkModule = new clsField("lblLinkModule", ccsText, "");
        $this->Mod_ModID = new clsField("Mod_ModID", ccsText, "");
        $this->ModDesc = new clsField("ModDesc", ccsMemo, "");

    }
//End Class_Initialize Event

//Prepare Method @5-35436C01
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlModID", ccsInteger, "", "", $this->Parameters["urlModID"], 0, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
    }
//End Prepare Method

//Open Method @5-FCDD905E
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT `module`.*, module1.ModTitle AS module1_ModTitle  " .
        "FROM `module` LEFT JOIN `module` module1 ON " .
        "`module`.Mod_ModID = module1.ModID " .
        "WHERE `module`.ModID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @5-ABDBD2BB
    function SetValues()
    {
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->ModVersion->SetDBValue($this->f("ModVersion"));
        $this->lblModTitle->SetDBValue($this->f("module1_ModTitle"));
        $this->lblLinkModule->SetDBValue($this->f("ModID"));
        $this->Mod_ModID->SetDBValue($this->f("Mod_ModID"));
        $this->ModDesc->SetDBValue($this->f("ModDesc"));
    }
//End SetValues Method

//Insert Method @5-66447C3C
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["AutUsername"] = new clsSQLParameter("sesAutID", ccsText, "", "", CCGetSession("AutID"), "", false, $this->ErrorBlock);
        $this->cp["ModTitle"] = new clsSQLParameter("ctrlModTitle", ccsText, "", "", $this->ModTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModDesc"] = new clsSQLParameter("ctrlModDesc", ccsMemo, "", "", $this->ModDesc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModVersion"] = new clsSQLParameter("ctrlModVersion", ccsText, "", "", $this->ModVersion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModCreated"] = new clsSQLParameter("expr23", ccsDate, Array("dd", "/", "mm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd"), $this->ModCreated, "", false, $this->ErrorBlock);
        $this->cp["Mod_ModID"] = new clsSQLParameter("ctrlMod_ModID", ccsInteger, "", "", $this->Mod_ModID->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModModify"] = new clsSQLParameter("expr60", ccsDate, Array("dd", "/", "mm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd"), $this->ModModify, "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO `module` ("
             . "AutUsername, "
             . "ModTitle, "
             . "ModDesc, "
             . "ModVersion, "
             . "ModCreated, "
             . "Mod_ModID, "
             . "ModModify"
             . ") VALUES ("
             . $this->ToSQL($this->cp["AutUsername"]->GetDBValue(), $this->cp["AutUsername"]->DataType) . ", "
             . $this->ToSQL($this->cp["ModTitle"]->GetDBValue(), $this->cp["ModTitle"]->DataType) . ", "
             . $this->ToSQL($this->cp["ModDesc"]->GetDBValue(), $this->cp["ModDesc"]->DataType) . ", "
             . $this->ToSQL($this->cp["ModVersion"]->GetDBValue(), $this->cp["ModVersion"]->DataType) . ", "
             . $this->ToSQL($this->cp["ModCreated"]->GetDBValue(), $this->cp["ModCreated"]->DataType) . ", "
             . $this->ToSQL($this->cp["Mod_ModID"]->GetDBValue(), $this->cp["Mod_ModID"]->DataType) . ", "
             . $this->ToSQL($this->cp["ModModify"]->GetDBValue(), $this->cp["ModModify"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @5-10D3D7A9
    function Update()
    {
        $this->BlockExecution = true;
        $this->cp["AutUsername"] = new clsSQLParameter("sesAutID", ccsText, "", "", CCGetSession("AutID"), "", false, $this->ErrorBlock);
        $this->cp["ModTitle"] = new clsSQLParameter("ctrlModTitle", ccsText, "", "", $this->ModTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModDesc"] = new clsSQLParameter("ctrlModDesc", ccsMemo, "", "", $this->ModDesc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModVersion"] = new clsSQLParameter("ctrlModVersion", ccsText, "", "", $this->ModVersion->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["Mod_ModID"] = new clsSQLParameter("ctrlMod_ModID", ccsInteger, "", "", $this->Mod_ModID->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["ModModify"] = new clsSQLParameter("expr61", ccsDate, Array("dd", "/", "mm", "/", "yyyy"), Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"), $this->ModModify, "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlModID", ccsInteger, "", "", CCGetFromGet("ModID", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("One or more parameters missing to perform the Update/Delete. The application is misconfigured.");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "ModID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE `module` SET "
             . "AutUsername=" . $this->ToSQL($this->cp["AutUsername"]->GetDBValue(), $this->cp["AutUsername"]->DataType) . ", "
             . "ModTitle=" . $this->ToSQL($this->cp["ModTitle"]->GetDBValue(), $this->cp["ModTitle"]->DataType) . ", "
             . "ModDesc=" . $this->ToSQL($this->cp["ModDesc"]->GetDBValue(), $this->cp["ModDesc"]->DataType) . ", "
             . "ModVersion=" . $this->ToSQL($this->cp["ModVersion"]->GetDBValue(), $this->cp["ModVersion"]->DataType) . ", "
             . "Mod_ModID=" . $this->ToSQL($this->cp["Mod_ModID"]->GetDBValue(), $this->cp["Mod_ModID"]->DataType) . ", "
             . "ModModify=" . $this->ToSQL($this->cp["ModModify"]->GetDBValue(), $this->cp["ModModify"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End moduleDataSource Class @5-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-0D8AA2B9
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

$FileName = "ModuleMaint.php";
$Redirect = "";
$TemplateFileName = "ModuleMaint.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-CF537A02
$DBConnection1 = new clsDBConnection1();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$MenuAuthor = new clsMenuAuthor();
$MenuAuthor->BindEvents();
$MenuAuthor->TemplatePath = "./";
$MenuAuthor->Initialize();
$lblTopLink = new clsControl(ccsLabel, "lblTopLink", "lblTopLink", ccsText, "", CCGetRequestParam("lblTopLink", ccsGet));
$lblTopLink->HTML = true;
$lblTitle = new clsControl(ccsLabel, "lblTitle", "lblTitle", ccsText, "", CCGetRequestParam("lblTitle", ccsGet));
$lblTitle->HTML = true;
$module = new clsRecordmodule();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$module->Initialize();

// Events
include("./ModuleMaint_events.php");
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

//Execute Components @1-4DC483BA
$Header->Operations();
$MenuAuthor->Operations();
$module->Operation();
$Footer->Operations();
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

//Show Page @1-2B986029
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$module->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblTitle->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small></small></font></center>";
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
