<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @3-39DC296A
include_once("./Header.php");
//End Include Page implementation

//Include Page implementation @2-D20A616D
include_once("./MenuAuthor.php");
//End Include Page implementation

class clsGridsubknowledgeitem { //subknowledgeitem class @5-BB616816

//Variables @5-0B3A0FB0

    // Public variables
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    // Grid Controls
    var $StaticControls; var $RowControls;
//End Variables

//Class_Initialize Event @5-92C65168
    function clsGridsubknowledgeitem()
    {
        global $FileName;
        $this->ComponentName = "subknowledgeitem";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid subknowledgeitem";
        $this->ds = new clssubknowledgeitemDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->SubKnowlItemTitle = new clsControl(ccsLabel, "SubKnowlItemTitle", "SubKnowlItemTitle", ccsText, "", CCGetRequestParam("SubKnowlItemTitle", ccsGet));
        $this->SubKnowlItemContent = new clsControl(ccsLabel, "SubKnowlItemContent", "SubKnowlItemContent", ccsMemo, "", CCGetRequestParam("SubKnowlItemContent", ccsGet));
        $this->SubKnowlItemContent->HTML = true;
    }
//End Class_Initialize Event

//Initialize Method @5-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @5-40A9943C
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlSubKnowItemID"] = CCGetFromGet("SubKnowItemID", "");

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->Prepare();
        $this->ds->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        $is_next_record = $this->ds->next_record();
        if($is_next_record && $ShownRecords < $this->PageSize)
        {
            do {
                    $this->ds->SetValues();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->SubKnowlItemTitle->SetValue($this->ds->SubKnowlItemTitle->GetValue());
                $this->SubKnowlItemContent->SetValue($this->ds->SubKnowlItemContent->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->SubKnowlItemTitle->Show();
                $this->SubKnowlItemContent->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record && $ShownRecords < $this->PageSize);
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @5-B5BBD81C
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->SubKnowlItemTitle->Errors->ToString();
        $errors .= $this->SubKnowlItemContent->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End subknowledgeitem Class @5-FCB6E20C

class clssubknowledgeitemDataSource extends clsDBConnection1 {  //subknowledgeitemDataSource Class @5-AE58F599

//DataSource Variables @5-D36BDB27
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $SubKnowlItemTitle;
    var $SubKnowlItemContent;
//End DataSource Variables

//Class_Initialize Event @5-D21991D2
    function clssubknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Grid subknowledgeitem";
        $this->Initialize();
        $this->SubKnowlItemTitle = new clsField("SubKnowlItemTitle", ccsText, "");
        $this->SubKnowlItemContent = new clsField("SubKnowlItemContent", ccsMemo, "");

    }
//End Class_Initialize Event

//SetOrder Method @5-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @5-48F932CE
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlSubKnowItemID", ccsInteger, "", "", $this->Parameters["urlSubKnowItemID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "SubKnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @5-0B1FD354
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM subknowledgeitem";
        $this->SQL = "SELECT *  " .
        "FROM subknowledgeitem";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @5-2CE74EFA
    function SetValues()
    {
        $this->SubKnowlItemTitle->SetDBValue($this->f("SubKnowlItemTitle"));
        $this->SubKnowlItemContent->SetDBValue($this->f("SubKnowlItemContent"));
    }
//End SetValues Method

} //End subknowledgeitemDataSource Class @5-FCB6E20C

class clsEditableGridglossary { //glossary Class @9-C39055ED

//Variables @9-1BF7F555

    // Public variables
    var $ComponentName;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormParameters;
    var $FormState;
    var $FormEnctype;
    var $CachedColumns;
    var $TotalRows;
    var $UpdatedRows;
    var $EmptyRows;
    var $Visible;
    var $EditableGridset;
    var $RowsErrors;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode;
    var $ValidatingControls;
    var $Controls;
    var $ControlsErrors;

    // Class variables
    var $Sorter_GlosTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @9-A201451A
    function clsEditableGridglossary()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid glossary/Error";
        $this->ComponentName = "glossary";
        $this->CachedColumns["GlosID"][0] = "GlosID";
        $this->ds = new clsglossaryDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->EmptyRows = 1;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if(!$this->Visible) return;

        $CCSForm = CCGetFromGet("ccsForm", "");
        $this->FormEnctype = "application/x-www-form-urlencoded";
        $this->FormSubmitted = ($CCSForm == $this->ComponentName);
        if($this->FormSubmitted) {
            $this->FormState = CCGetFromPost("FormState", "");
            $this->SetFormState($this->FormState);
        } else {
            $this->FormState = "";
        }
        $Method = $this->FormSubmitted ? ccsPost : ccsGet;

        $this->SorterName = CCGetParam("glossaryOrder", "");
        $this->SorterDirection = CCGetParam("glossaryDir", "");

        $this->Sorter_GlosTitle = new clsSorter($this->ComponentName, "Sorter_GlosTitle", $FileName);
        $this->GlosTitle = new clsControl(ccsTextBox, "GlosTitle", "Istilah", ccsText, "");
        $this->GlosTitle->Required = true;
        $this->CheckBox_Delete = new clsControl(ccsCheckBox, "CheckBox_Delete", "CheckBox_Delete", ccsBoolean, "");
        $this->CheckBox_Delete->CheckedValue = $this->CheckBox_Delete->GetParsedValue(true);
        $this->CheckBox_Delete->UncheckedValue = $this->CheckBox_Delete->GetParsedValue(false);
        $this->GlosDesc = new clsControl(ccsTextArea, "GlosDesc", "Keterangan", ccsMemo, "");
        $this->GlosDesc->Required = true;
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @9-5D086A10
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlSubKnowItemID"] = CCGetFromGet("SubKnowItemID", "");
    }
//End Initialize Method

//GetFormParameters Method @9-6C302C01
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["GlosTitle"][$RowNumber] = CCGetFromPost("GlosTitle_" . $RowNumber);
            $this->FormParameters["CheckBox_Delete"][$RowNumber] = CCGetFromPost("CheckBox_Delete_" . $RowNumber);
            $this->FormParameters["GlosDesc"][$RowNumber] = CCGetFromPost("GlosDesc_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @9-2F1CFA1E
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["GlosID"] = $this->CachedColumns["GlosID"][$RowNumber];
            $this->GlosTitle->SetText($this->FormParameters["GlosTitle"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            $this->GlosDesc->SetText($this->FormParameters["GlosDesc"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if(!$this->CheckBox_Delete->Value)
                    $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
            else if($this->CheckInsert($RowNumber))
            {
                $Validation = ($this->ValidateRow($RowNumber) && $Validation);
            }
        }
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//ValidateRow Method @9-55140BED
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->GlosTitle->Validate() && $Validation);
        $Validation = ($this->CheckBox_Delete->Validate() && $Validation);
        $Validation = ($this->GlosDesc->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->GlosTitle->Errors->ToString();
            $errors .= $this->CheckBox_Delete->Errors->ToString();
            $errors .= $this->GlosDesc->Errors->ToString();
            $this->GlosTitle->Errors->Clear();
            $this->CheckBox_Delete->Errors->Clear();
            $this->GlosDesc->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @9-C431276D
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["GlosTitle"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["GlosDesc"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @9-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @9-7B861278
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->ds->Prepare();
        if(!$this->FormSubmitted)
            return;

        $this->GetFormParameters();
        $this->PressedButton = "Button_Submit";
        if(strlen(CCGetParam("Button_Submit", ""))) {
            $this->PressedButton = "Button_Submit";
        } else if(strlen(CCGetParam("Cancel", ""))) {
            $this->PressedButton = "Cancel";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @9-248114D8
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["GlosID"] = $this->CachedColumns["GlosID"][$RowNumber];
            $this->GlosTitle->SetText($this->FormParameters["GlosTitle"][$RowNumber], $RowNumber);
            $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
            $this->GlosDesc->SetText($this->FormParameters["GlosDesc"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->CheckBox_Delete->Value) {
                    if($this->DeleteAllowed) { $Validation = ($this->DeleteRow($RowNumber) && $Validation); }
                } else if($this->UpdateAllowed) {
                    $Validation = ($this->UpdateRow($RowNumber) && $Validation);
                }
            }
            else if($this->CheckInsert($RowNumber) && $this->InsertAllowed)
            {
                $Validation = ($this->InsertRow($RowNumber) && $Validation);
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit");
        return ($this->Errors->Count() == 0 && $Validation);
    }
//End UpdateGrid Method

//InsertRow Method @9-C50AB727
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->GlosTitle->SetValue($this->GlosTitle->GetValue());
        $this->ds->GlosDesc->SetValue($this->GlosDesc->GetValue());
        $this->ds->Insert();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//UpdateRow Method @9-897D95D9
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->GlosTitle->SetValue($this->GlosTitle->GetValue());
        $this->ds->GlosDesc->SetValue($this->GlosDesc->GetValue());
        $this->ds->Update();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//DeleteRow Method @9-E90CB5E3
    function DeleteRow($RowNumber)
    {
        if(!$this->DeleteAllowed) return false;
        $this->ds->Delete();
        $errors = "";
        if($this->ds->Errors->Count() > 0) {
            $errors = $this->ds->Errors->ToString();
            $this->RowsErrors[$RowNumber] = $errors;
            $this->ds->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End DeleteRow Method

//FormScript Method @9-63E4DCCE
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var glossaryElements;\n";
        $script .= "var glossaryEmptyRows = 1;\n";
        $script .= "var " . $this->ComponentName . "GlosTitleID = 0;\n";
        $script .= "var " . $this->ComponentName . "DeleteControl = 1;\n";
        $script .= "var " . $this->ComponentName . "GlosDescID = 2;\n";
        $script .= "\nfunction initglossaryElements() {\n";
        $script .= "\tvar ED = document.forms[\"glossary\"];\n";
        $script .= "\tglossaryElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.GlosTitle_" . $i . ", " . "ED.CheckBox_Delete_" . $i . ", " . "ED.GlosDesc_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @9-31067CEB
    function SetFormState($FormState)
    {
        if(strlen($FormState)) {
            $FormState = str_replace("\\\\", "\\" . ord("\\"), $FormState);
            $FormState = str_replace("\\;", "\\" . ord(";"), $FormState);
            $pieces = explode(";", $FormState);
            $this->UpdatedRows = $pieces[0];
            $this->EmptyRows   = $pieces[1];
            $this->TotalRows = $this->UpdatedRows + $this->EmptyRows;
            $RowNumber = 0;
            for($i = 2; $i < sizeof($pieces); $i = $i + 1)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns["GlosID"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["GlosID"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @9-AF96AAB9
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["GlosID"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @9-9F3F1A60
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) { return; }

        $this->Button_Submit->Visible = $this->Button_Submit->Visible && ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $is_next_record = false;
        if($this->Errors->Count() == 0)
        {
            $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
            if($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed))
            {
                do
                {
                    $RowNumber++;
                    if($is_next_record) {
                        $NonEmptyRows++;
                        $this->ds->SetValues();
                    } else {
                    }
                    if(!$is_next_record || !$this->DeleteAllowed)
                        $this->CheckBox_Delete->Visible = false;
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["GlosID"][$RowNumber] = $this->ds->CachedColumns["GlosID"];
                        $this->GlosTitle->SetValue($this->ds->GlosTitle->GetValue());
                        $this->GlosDesc->SetValue($this->ds->GlosDesc->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["GlosID"][$RowNumber] = "";
                        $this->GlosTitle->SetText("");
                        $this->CheckBox_Delete->SetText("");
                        $this->GlosDesc->SetText("");
                    } else {
                        $this->GlosTitle->SetText($this->FormParameters["GlosTitle"][$RowNumber], $RowNumber);
                        $this->CheckBox_Delete->SetText($this->FormParameters["CheckBox_Delete"][$RowNumber], $RowNumber);
                        $this->GlosDesc->SetText($this->FormParameters["GlosDesc"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->GlosTitle->Show($RowNumber);
                    $this->CheckBox_Delete->Show($RowNumber);
                    $this->GlosDesc->Show($RowNumber);
                    if(isset($this->RowsErrors[$RowNumber]) && $this->RowsErrors[$RowNumber] !== "") {
                        $Tpl->setvar("Error", $this->RowsErrors[$RowNumber]);
                        $Tpl->parse("RowError", false);
                    } else {
                        $Tpl->setblockvar("RowError", "");
                    }
                    $Tpl->setvar("FormScript", $this->FormScript($RowNumber));
                    $Tpl->parse();
                    if($is_next_record) $is_next_record = $this->ds->next_record() && $this->ReadAllowed && $RowNumber < $this->PageSize;
                    else $EmptyRowsLeft--;
                } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
            } else {
                $Tpl->block_path = $EditableGridPath;
                $Tpl->parse("NoRecords", false);
            }
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_GlosTitle->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();

        if($this->CheckErrors()) {
            $Error .= $this->Errors->ToString();
            $Error .= $this->ds->Errors->ToString();
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", $this->HTMLFormAction);
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $Tpl->SetVar("HTMLFormProperties", "method=\"POST\" action=\"" . $this->HTMLFormAction . "\" name=\"" . $this->ComponentName . "\"");
        $Tpl->SetVar("FormState", htmlspecialchars($this->GetFormState($NonEmptyRows)));
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End glossary Class @9-FCB6E20C

class clsglossaryDataSource extends clsDBConnection1 {  //glossaryDataSource Class @9-7025B4A9

//DataSource Variables @9-64B6AFD4
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $DeleteParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $GlosTitle;
    var $CheckBox_Delete;
    var $GlosDesc;
//End DataSource Variables

//Class_Initialize Event @9-CFF366C4
    function clsglossaryDataSource()
    {
        $this->ErrorBlock = "EditableGrid glossary/Error";
        $this->Initialize();
        $this->GlosTitle = new clsField("GlosTitle", ccsText, "");
        $this->CheckBox_Delete = new clsField("CheckBox_Delete", ccsBoolean, Array("true", "false", ""));
        $this->GlosDesc = new clsField("GlosDesc", ccsMemo, "");

    }
//End Class_Initialize Event

//SetOrder Method @9-F997B7AF
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "GlosID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_GlosTitle" => array("GlosTitle", "")));
    }
//End SetOrder Method

//Prepare Method @9-A6A28740
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlSubKnowItemID", ccsInteger, "", "", $this->Parameters["urlSubKnowItemID"], 0, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "SubKnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @9-9F4B33A7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM glossary";
        $this->SQL = "SELECT *  " .
        "FROM glossary";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @9-620B803E
    function SetValues()
    {
        $this->CachedColumns["GlosID"] = $this->f("GlosID");
        $this->GlosTitle->SetDBValue($this->f("GlosTitle"));
        $this->GlosDesc->SetDBValue($this->f("GlosDesc"));
    }
//End SetValues Method

//Insert Method @9-24F9EBCD
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["GlosTitle"] = new clsSQLParameter("ctrlGlosTitle", ccsText, "", "", $this->GlosTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["GlosDesc"] = new clsSQLParameter("ctrlGlosDesc", ccsMemo, "", "", $this->GlosDesc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["SubKnowItemID"] = new clsSQLParameter("urlSubKnowItemID", ccsInteger, "", "", CCGetFromGet("SubKnowItemID", ""), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO glossary ("
             . "GlosTitle, "
             . "GlosDesc, "
             . "SubKnowItemID"
             . ") VALUES ("
             . $this->ToSQL($this->cp["GlosTitle"]->GetDBValue(), $this->cp["GlosTitle"]->DataType) . ", "
             . $this->ToSQL($this->cp["GlosDesc"]->GetDBValue(), $this->cp["GlosDesc"]->DataType) . ", "
             . $this->ToSQL($this->cp["SubKnowItemID"]->GetDBValue(), $this->cp["SubKnowItemID"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @9-4857D3B4
    function Update()
    {
        $this->BlockExecution = true;
        $this->cp["GlosTitle"] = new clsSQLParameter("ctrlGlosTitle", ccsText, "", "", $this->GlosTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["GlosDesc"] = new clsSQLParameter("ctrlGlosDesc", ccsMemo, "", "", $this->GlosDesc->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["SubKnowItemID"] = new clsSQLParameter("urlSubKnowItemID", ccsInteger, "", "", CCGetFromGet("SubKnowItemID", ""), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "dsGlosID", ccsInteger, "", "", $this->CachedColumns["GlosID"], "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("One or more parameters missing to perform the Update/Delete. The application is misconfigured.");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "GlosID", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE glossary SET "
             . "GlosTitle=" . $this->ToSQL($this->cp["GlosTitle"]->GetDBValue(), $this->cp["GlosTitle"]->DataType) . ", "
             . "GlosDesc=" . $this->ToSQL($this->cp["GlosDesc"]->GetDBValue(), $this->cp["GlosDesc"]->DataType) . ", "
             . "SubKnowItemID=" . $this->ToSQL($this->cp["SubKnowItemID"]->GetDBValue(), $this->cp["SubKnowItemID"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

//Delete Method @9-E87D5994
    function Delete()
    {
        $this->BlockExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete");
        $this->Where = "GlosID=" . $this->ToSQL($this->CachedColumns["GlosID"], ccsInteger);
        $this->SQL = "DELETE FROM glossary";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete");
        }
        $this->close();
    }
//End Delete Method

} //End glossaryDataSource Class @9-FCB6E20C

//Include Page implementation @4-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-8ECEBF1E
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

$FileName = "Glossary.php";
$Redirect = "";
$TemplateFileName = "Glossary.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-023543DA
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
$subknowledgeitem = new clsGridsubknowledgeitem();
$glossary = new clsEditableGridglossary();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$subknowledgeitem->Initialize();
$glossary->Initialize();

// Events
include("./Glossary_events.php");
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

//Execute Components @1-5BF16643
$Header->Operations();
$MenuAuthor->Operations();
$glossary->Operation();
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

//Show Page @1-D3E0E30B
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$subknowledgeitem->Show();
$glossary->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
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
