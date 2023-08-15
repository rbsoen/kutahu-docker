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

class clsRecordquestion { //question Class @7-25958DF6

//Variables @7-5C5E2D83

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

//Class_Initialize Event @7-EF9449A9
    function clsRecordquestion()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record question/Error";
        $this->ds = new clsquestionDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "question";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->QueTitle = new clsControl(ccsTextBox, "QueTitle", "Soal", ccsText, "", CCGetRequestParam("QueTitle", $Method));
            $this->QueTitle->Required = true;
            $this->QueChoiceA = new clsControl(ccsTextBox, "QueChoiceA", "Pilihan A", ccsText, "", CCGetRequestParam("QueChoiceA", $Method));
            $this->QueChoiceA->Required = true;
            $this->QueChoiceB = new clsControl(ccsTextBox, "QueChoiceB", "Pilihan B", ccsText, "", CCGetRequestParam("QueChoiceB", $Method));
            $this->QueChoiceB->Required = true;
            $this->QueChoiceC = new clsControl(ccsTextBox, "QueChoiceC", "Pilihan C", ccsText, "", CCGetRequestParam("QueChoiceC", $Method));
            $this->QueChoiceD = new clsControl(ccsTextBox, "QueChoiceD", "Pilihan D", ccsText, "", CCGetRequestParam("QueChoiceD", $Method));
            $this->QueChoiceE = new clsControl(ccsTextBox, "QueChoiceE", "Pilihan E", ccsText, "", CCGetRequestParam("QueChoiceE", $Method));
            $this->QueAnswer = new clsControl(ccsListBox, "QueAnswer", "Jawaban", ccsText, "", CCGetRequestParam("QueAnswer", $Method));
            $this->QueAnswer->DSType = dsListOfValues;
            $this->QueAnswer->Values = array(array("A", "A"), array("B", "B"), array("C", "C"), array("D", "D"), array("E", "E"));
            $this->QueAnswer->Required = true;
            $this->QueModule = new clsControl(ccsCheckBox, "QueModule", "Que Module", ccsInteger, "", CCGetRequestParam("QueModule", $Method));
            $this->QueModule->CheckedValue = $this->QueModule->GetParsedValue(true);
            $this->QueModule->UncheckedValue = $this->QueModule->GetParsedValue(false);
            $this->QueCategory = new clsControl(ccsCheckBox, "QueCategory", "Que Category", ccsInteger, "", CCGetRequestParam("QueCategory", $Method));
            $this->QueCategory->CheckedValue = $this->QueCategory->GetParsedValue(true);
            $this->QueCategory->UncheckedValue = $this->QueCategory->GetParsedValue(false);
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
        }
    }
//End Class_Initialize Event

//Initialize Method @7-6ED8B28A
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlQueID"] = CCGetFromGet("QueID", "");
    }
//End Initialize Method

//Validate Method @7-ABFEFA9D
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->QueTitle->Validate() && $Validation);
        $Validation = ($this->QueChoiceA->Validate() && $Validation);
        $Validation = ($this->QueChoiceB->Validate() && $Validation);
        $Validation = ($this->QueChoiceC->Validate() && $Validation);
        $Validation = ($this->QueChoiceD->Validate() && $Validation);
        $Validation = ($this->QueChoiceE->Validate() && $Validation);
        $Validation = ($this->QueAnswer->Validate() && $Validation);
        $Validation = ($this->QueModule->Validate() && $Validation);
        $Validation = ($this->QueCategory->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @7-245ACC60
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->QueTitle->Errors->Count());
        $errors = ($errors || $this->QueChoiceA->Errors->Count());
        $errors = ($errors || $this->QueChoiceB->Errors->Count());
        $errors = ($errors || $this->QueChoiceC->Errors->Count());
        $errors = ($errors || $this->QueChoiceD->Errors->Count());
        $errors = ($errors || $this->QueChoiceE->Errors->Count());
        $errors = ($errors || $this->QueAnswer->Errors->Count());
        $errors = ($errors || $this->QueModule->Errors->Count());
        $errors = ($errors || $this->QueCategory->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @7-7F6B1296
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
        $Redirect = "Question.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @7-136BCC58
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->QueTitle->SetValue($this->QueTitle->GetValue());
        $this->ds->QueChoiceA->SetValue($this->QueChoiceA->GetValue());
        $this->ds->QueChoiceB->SetValue($this->QueChoiceB->GetValue());
        $this->ds->QueChoiceC->SetValue($this->QueChoiceC->GetValue());
        $this->ds->QueChoiceD->SetValue($this->QueChoiceD->GetValue());
        $this->ds->QueChoiceE->SetValue($this->QueChoiceE->GetValue());
        $this->ds->QueAnswer->SetValue($this->QueAnswer->GetValue());
        $this->ds->QueModule->SetValue($this->QueModule->GetValue());
        $this->ds->QueCategory->SetValue($this->QueCategory->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @7-C2AEF366
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->QueTitle->SetValue($this->QueTitle->GetValue());
        $this->ds->QueChoiceA->SetValue($this->QueChoiceA->GetValue());
        $this->ds->QueChoiceB->SetValue($this->QueChoiceB->GetValue());
        $this->ds->QueChoiceC->SetValue($this->QueChoiceC->GetValue());
        $this->ds->QueChoiceD->SetValue($this->QueChoiceD->GetValue());
        $this->ds->QueChoiceE->SetValue($this->QueChoiceE->GetValue());
        $this->ds->QueAnswer->SetValue($this->QueAnswer->GetValue());
        $this->ds->QueModule->SetValue($this->QueModule->GetValue());
        $this->ds->QueCategory->SetValue($this->QueCategory->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @7-E61B93A1
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->QueAnswer->Prepare();

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
                    echo "Error in Record question";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->QueTitle->SetValue($this->ds->QueTitle->GetValue());
                        $this->QueChoiceA->SetValue($this->ds->QueChoiceA->GetValue());
                        $this->QueChoiceB->SetValue($this->ds->QueChoiceB->GetValue());
                        $this->QueChoiceC->SetValue($this->ds->QueChoiceC->GetValue());
                        $this->QueChoiceD->SetValue($this->ds->QueChoiceD->GetValue());
                        $this->QueChoiceE->SetValue($this->ds->QueChoiceE->GetValue());
                        $this->QueAnswer->SetValue($this->ds->QueAnswer->GetValue());
                        $this->QueModule->SetValue($this->ds->QueModule->GetValue());
                        $this->QueCategory->SetValue($this->ds->QueCategory->GetValue());
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
            $Error .= $this->QueTitle->Errors->ToString();
            $Error .= $this->QueChoiceA->Errors->ToString();
            $Error .= $this->QueChoiceB->Errors->ToString();
            $Error .= $this->QueChoiceC->Errors->ToString();
            $Error .= $this->QueChoiceD->Errors->ToString();
            $Error .= $this->QueChoiceE->Errors->ToString();
            $Error .= $this->QueAnswer->Errors->ToString();
            $Error .= $this->QueModule->Errors->ToString();
            $Error .= $this->QueCategory->Errors->ToString();
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

        $this->QueTitle->Show();
        $this->QueChoiceA->Show();
        $this->QueChoiceB->Show();
        $this->QueChoiceC->Show();
        $this->QueChoiceD->Show();
        $this->QueChoiceE->Show();
        $this->QueAnswer->Show();
        $this->QueModule->Show();
        $this->QueCategory->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End question Class @7-FCB6E20C

class clsquestionDataSource extends clsDBConnection1 {  //questionDataSource Class @7-C9FD7A76

//DataSource Variables @7-5B9E75F5
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $QueTitle;
    var $QueChoiceA;
    var $QueChoiceB;
    var $QueChoiceC;
    var $QueChoiceD;
    var $QueChoiceE;
    var $QueAnswer;
    var $QueModule;
    var $QueCategory;
//End DataSource Variables

//Class_Initialize Event @7-CD5C0C4C
    function clsquestionDataSource()
    {
        $this->ErrorBlock = "Record question/Error";
        $this->Initialize();
        $this->QueTitle = new clsField("QueTitle", ccsText, "");
        $this->QueChoiceA = new clsField("QueChoiceA", ccsText, "");
        $this->QueChoiceB = new clsField("QueChoiceB", ccsText, "");
        $this->QueChoiceC = new clsField("QueChoiceC", ccsText, "");
        $this->QueChoiceD = new clsField("QueChoiceD", ccsText, "");
        $this->QueChoiceE = new clsField("QueChoiceE", ccsText, "");
        $this->QueAnswer = new clsField("QueAnswer", ccsText, "");
        $this->QueModule = new clsField("QueModule", ccsInteger, "");
        $this->QueCategory = new clsField("QueCategory", ccsInteger, "");

    }
//End Class_Initialize Event

//Prepare Method @7-4BFB7B19
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlQueID", ccsInteger, "", "", $this->Parameters["urlQueID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "QueID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @7-F97B7EE8
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM question";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-6BC68741
    function SetValues()
    {
        $this->QueTitle->SetDBValue($this->f("QueTitle"));
        $this->QueChoiceA->SetDBValue($this->f("QueChoiceA"));
        $this->QueChoiceB->SetDBValue($this->f("QueChoiceB"));
        $this->QueChoiceC->SetDBValue($this->f("QueChoiceC"));
        $this->QueChoiceD->SetDBValue($this->f("QueChoiceD"));
        $this->QueChoiceE->SetDBValue($this->f("QueChoiceE"));
        $this->QueAnswer->SetDBValue($this->f("QueAnswer"));
        $this->QueModule->SetDBValue(trim($this->f("QueModule")));
        $this->QueCategory->SetDBValue(trim($this->f("QueCategory")));
    }
//End SetValues Method

//Insert Method @7-45D896BC
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["QueTitle"] = new clsSQLParameter("ctrlQueTitle", ccsText, "", "", $this->QueTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueChoiceA"] = new clsSQLParameter("ctrlQueChoiceA", ccsText, "", "", $this->QueChoiceA->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueChoiceB"] = new clsSQLParameter("ctrlQueChoiceB", ccsText, "", "", $this->QueChoiceB->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueChoiceC"] = new clsSQLParameter("ctrlQueChoiceC", ccsText, "", "", $this->QueChoiceC->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueChoiceD"] = new clsSQLParameter("ctrlQueChoiceD", ccsText, "", "", $this->QueChoiceD->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueChoiceE"] = new clsSQLParameter("ctrlQueChoiceE", ccsText, "", "", $this->QueChoiceE->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueAnswer"] = new clsSQLParameter("ctrlQueAnswer", ccsText, "", "", $this->QueAnswer->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueModule"] = new clsSQLParameter("ctrlQueModule", ccsInteger, "", "", $this->QueModule->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["QueCategory"] = new clsSQLParameter("ctrlQueCategory", ccsInteger, "", "", $this->QueCategory->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["KnowAreaID"] = new clsSQLParameter("urlKnowAreaID", ccsInteger, "", "", CCGetFromGet("KnowAreaID", ""), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO question ("
             . "QueTitle, "
             . "QueChoiceA, "
             . "QueChoiceB, "
             . "QueChoiceC, "
             . "QueChoiceD, "
             . "QueChoiceE, "
             . "QueAnswer, "
             . "QueModule, "
             . "QueCategory, "
             . "KnowAreaID"
             . ") VALUES ("
             . $this->ToSQL($this->cp["QueTitle"]->GetDBValue(), $this->cp["QueTitle"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueChoiceA"]->GetDBValue(), $this->cp["QueChoiceA"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueChoiceB"]->GetDBValue(), $this->cp["QueChoiceB"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueChoiceC"]->GetDBValue(), $this->cp["QueChoiceC"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueChoiceD"]->GetDBValue(), $this->cp["QueChoiceD"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueChoiceE"]->GetDBValue(), $this->cp["QueChoiceE"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueAnswer"]->GetDBValue(), $this->cp["QueAnswer"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueModule"]->GetDBValue(), $this->cp["QueModule"]->DataType) . ", "
             . $this->ToSQL($this->cp["QueCategory"]->GetDBValue(), $this->cp["QueCategory"]->DataType) . ", "
             . $this->ToSQL($this->cp["KnowAreaID"]->GetDBValue(), $this->cp["KnowAreaID"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @7-B4356DAB
    function Update()
    {
        $this->BlockExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->SQL = "UPDATE question SET "
             . "QueTitle=" . $this->ToSQL($this->QueTitle->GetDBValue(), $this->QueTitle->DataType) . ", "
             . "QueChoiceA=" . $this->ToSQL($this->QueChoiceA->GetDBValue(), $this->QueChoiceA->DataType) . ", "
             . "QueChoiceB=" . $this->ToSQL($this->QueChoiceB->GetDBValue(), $this->QueChoiceB->DataType) . ", "
             . "QueChoiceC=" . $this->ToSQL($this->QueChoiceC->GetDBValue(), $this->QueChoiceC->DataType) . ", "
             . "QueChoiceD=" . $this->ToSQL($this->QueChoiceD->GetDBValue(), $this->QueChoiceD->DataType) . ", "
             . "QueChoiceE=" . $this->ToSQL($this->QueChoiceE->GetDBValue(), $this->QueChoiceE->DataType) . ", "
             . "QueAnswer=" . $this->ToSQL($this->QueAnswer->GetDBValue(), $this->QueAnswer->DataType) . ", "
             . "QueModule=" . $this->ToSQL($this->QueModule->GetDBValue(), $this->QueModule->DataType) . ", "
             . "QueCategory=" . $this->ToSQL($this->QueCategory->GetDBValue(), $this->QueCategory->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End questionDataSource Class @7-FCB6E20C



//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-D51B00C0
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

$FileName = "QuestionMaint.php";
$Redirect = "";
$TemplateFileName = "QuestionMaint.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-CD41818B
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
$question = new clsRecordquestion();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$question->Initialize();

// Events
include("./QuestionMaint_events.php");
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

//Execute Components @1-218DE9FC
$Header->Operations();
$MenuAuthor->Operations();
$question->Operation();
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

//Show Page @1-6352406E
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$question->Show();
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
