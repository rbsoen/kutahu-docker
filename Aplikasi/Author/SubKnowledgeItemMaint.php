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

class clsRecordsubknowledgeitem { //subknowledgeitem Class @8-95720E78

//Variables @8-5C5E2D83

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

//Class_Initialize Event @8-D2DA9A4F
    function clsRecordsubknowledgeitem()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record subknowledgeitem/Error";
        $this->ds = new clssubknowledgeitemDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "subknowledgeitem";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->SubKnowlItemTitle = new clsControl(ccsTextBox, "SubKnowlItemTitle", "Sub Topik", ccsText, "", CCGetRequestParam("SubKnowlItemTitle", $Method));
            $this->SubKnowlItemTitle->Required = true;
            $this->SubKnowlItemContent = new clsControl(ccsTextArea, "SubKnowlItemContent", "Isi Sub Topik", ccsMemo, "", CCGetRequestParam("SubKnowlItemContent", $Method));
            $this->hdnURL = new clsControl(ccsHidden, "hdnURL", "hdnURL", ccsText, "", CCGetRequestParam("hdnURL", $Method));
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
        }
    }
//End Class_Initialize Event

//Initialize Method @8-7D04C45B
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlSubKnowItemID"] = CCGetFromGet("SubKnowItemID", "");
    }
//End Initialize Method

//Validate Method @8-A8D12CEF
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->SubKnowlItemTitle->Validate() && $Validation);
        $Validation = ($this->SubKnowlItemContent->Validate() && $Validation);
        $Validation = ($this->hdnURL->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @8-B0A5E6B9
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->SubKnowlItemTitle->Errors->Count());
        $errors = ($errors || $this->SubKnowlItemContent->Errors->Count());
        $errors = ($errors || $this->hdnURL->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @8-771A9EE2
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
        $Redirect = "SubKnowledgeItem.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
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

//InsertRow Method @8-33ACB704
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->SubKnowlItemTitle->SetValue($this->SubKnowlItemTitle->GetValue());
        $this->ds->SubKnowlItemContent->SetValue($this->SubKnowlItemContent->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @8-53242EA0
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->SubKnowlItemTitle->SetValue($this->SubKnowlItemTitle->GetValue());
        $this->ds->SubKnowlItemContent->SetValue($this->SubKnowlItemContent->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @8-ADD5E00D
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
                    echo "Error in Record subknowledgeitem";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->SubKnowlItemTitle->SetValue($this->ds->SubKnowlItemTitle->GetValue());
                        $this->SubKnowlItemContent->SetValue($this->ds->SubKnowlItemContent->GetValue());
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
            $Error .= $this->SubKnowlItemTitle->Errors->ToString();
            $Error .= $this->SubKnowlItemContent->Errors->ToString();
            $Error .= $this->hdnURL->Errors->ToString();
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

        $this->SubKnowlItemTitle->Show();
        $this->SubKnowlItemContent->Show();
        $this->hdnURL->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End subknowledgeitem Class @8-FCB6E20C

class clssubknowledgeitemDataSource extends clsDBConnection1 {  //subknowledgeitemDataSource Class @8-AE58F599

//DataSource Variables @8-4A5BC3D9
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $SubKnowlItemTitle;
    var $SubKnowlItemContent;
    var $hdnURL;
//End DataSource Variables

//Class_Initialize Event @8-2501D17C
    function clssubknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Record subknowledgeitem/Error";
        $this->Initialize();
        $this->SubKnowlItemTitle = new clsField("SubKnowlItemTitle", ccsText, "");
        $this->SubKnowlItemContent = new clsField("SubKnowlItemContent", ccsMemo, "");
        $this->hdnURL = new clsField("hdnURL", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @8-0FEC2CF0
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlSubKnowItemID", ccsInteger, "", "", $this->Parameters["urlSubKnowItemID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "SubKnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @8-5787CB70
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM subknowledgeitem";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @8-2CE74EFA
    function SetValues()
    {
        $this->SubKnowlItemTitle->SetDBValue($this->f("SubKnowlItemTitle"));
        $this->SubKnowlItemContent->SetDBValue($this->f("SubKnowlItemContent"));
    }
//End SetValues Method

//Insert Method @8-C78ABCC2
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["SubKnowlItemTitle"] = new clsSQLParameter("ctrlSubKnowlItemTitle", ccsText, "", "", $this->SubKnowlItemTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["SubKnowlItemContent"] = new clsSQLParameter("ctrlSubKnowlItemContent", ccsMemo, "", "", $this->SubKnowlItemContent->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["KnowItemID"] = new clsSQLParameter("urlKnowItemID", ccsInteger, "", "", CCGetFromGet("KnowItemID", ""), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO subknowledgeitem ("
             . "SubKnowlItemTitle, "
             . "SubKnowlItemContent, "
             . "KnowItemID"
             . ") VALUES ("
             . $this->ToSQL($this->cp["SubKnowlItemTitle"]->GetDBValue(), $this->cp["SubKnowlItemTitle"]->DataType) . ", "
             . $this->ToSQL($this->cp["SubKnowlItemContent"]->GetDBValue(), $this->cp["SubKnowlItemContent"]->DataType) . ", "
             . $this->ToSQL($this->cp["KnowItemID"]->GetDBValue(), $this->cp["KnowItemID"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @8-1274265E
    function Update()
    {
        $this->BlockExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE subknowledgeitem SET "
             . "SubKnowlItemTitle=" . $this->ToSQL($this->SubKnowlItemTitle->GetDBValue(), $this->SubKnowlItemTitle->DataType) . ", "
             . "SubKnowlItemContent=" . $this->ToSQL($this->SubKnowlItemContent->GetDBValue(), $this->SubKnowlItemContent->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End subknowledgeitemDataSource Class @8-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-5F690B15
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

$FileName = "SubKnowledgeItemMaint.php";
$Redirect = "";
$TemplateFileName = "SubKnowledgeItemMaint.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-3335BFD8
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
$subknowledgeitem = new clsRecordsubknowledgeitem();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$subknowledgeitem->Initialize();

// Events
include("./SubKnowledgeItemMaint_events.php");
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

//Execute Components @1-6FE84363
$Header->Operations();
$MenuAuthor->Operations();
$subknowledgeitem->Operation();
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

//Show Page @1-5AEBA707
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$subknowledgeitem->Show();
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
