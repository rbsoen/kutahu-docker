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

class clsGridmodule { //module class @24-DFA3016C

//Variables @24-0B3A0FB0

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

//Class_Initialize Event @24-02B07FD7
    function clsGridmodule()
    {
        global $FileName;
        $this->ComponentName = "module";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid module";
        $this->ds = new clsmoduleDataSource();
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

        $this->ModTitle = new clsControl(ccsLabel, "ModTitle", "ModTitle", ccsText, "", CCGetRequestParam("ModTitle", ccsGet));
        $this->CatTitle = new clsControl(ccsLabel, "CatTitle", "CatTitle", ccsText, "", CCGetRequestParam("CatTitle", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @24-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @24-81D328A6
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlCatID"] = CCGetFromGet("CatID", "");

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
                $this->ModTitle->SetValue($this->ds->ModTitle->GetValue());
                $this->CatTitle->SetValue($this->ds->CatTitle->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ModTitle->Show();
                $this->CatTitle->Show();
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

//GetErrors Method @24-EF038258
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ModTitle->Errors->ToString();
        $errors .= $this->CatTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End module Class @24-FCB6E20C

class clsmoduleDataSource extends clsDBConnection1 {  //moduleDataSource Class @24-3199DDCA

//DataSource Variables @24-3D811016
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ModTitle;
    var $CatTitle;
//End DataSource Variables

//Class_Initialize Event @24-FFCF398D
    function clsmoduleDataSource()
    {
        $this->ErrorBlock = "Grid module";
        $this->Initialize();
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->CatTitle = new clsField("CatTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @24-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @24-73CB67B8
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCatID", ccsInteger, "", "", $this->Parameters["urlCatID"], 0, false);
    }
//End Prepare Method

//Open Method @24-EC32CC78
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM category INNER JOIN `module` ON " .
        "category.ModID = `module`.ModID " .
        "WHERE category.CatID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->SQL = "SELECT `module`.*, CatTitle  " .
        "FROM category INNER JOIN `module` ON " .
        "category.ModID = `module`.ModID " .
        "WHERE category.CatID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @24-907DD992
    function SetValues()
    {
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->CatTitle->SetDBValue($this->f("CatTitle"));
    }
//End SetValues Method

} //End moduleDataSource Class @24-FCB6E20C

class clsEditableGridknowledgearea { //knowledgearea Class @7-A2E89AB4

//Variables @7-F3CCF69B

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
    var $Sorter_KnowAreaTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @7-00A53028
    function clsEditableGridknowledgearea()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid knowledgearea/Error";
        $this->ComponentName = "knowledgearea";
        $this->CachedColumns["KnowAreaID"][0] = "KnowAreaID";
        $this->ds = new clsknowledgeareaDataSource();
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

        $this->SorterName = CCGetParam("knowledgeareaOrder", "");
        $this->SorterDirection = CCGetParam("knowledgeareaDir", "");

        $this->Sorter_KnowAreaTitle = new clsSorter($this->ComponentName, "Sorter_KnowAreaTitle", $FileName);
        $this->lblAction = new clsControl(ccsLabel, "lblAction", "lblAction", ccsText, "");
        $this->lblAction->HTML = true;
        $this->hdnKnowAreaID = new clsControl(ccsHidden, "hdnKnowAreaID", "hdnKnowAreaID", ccsText, "");
        $this->KnowAreaTitle = new clsControl(ccsTextBox, "KnowAreaTitle", "Sub Bab", ccsText, "");
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
        $this->Button_Submit = new clsButton("Button_Submit");
        $this->Cancel = new clsButton("Cancel");
    }
//End Class_Initialize Event

//Initialize Method @7-F99891E6
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);

        $this->ds->Parameters["urlCatID"] = CCGetFromGet("CatID", "");
    }
//End Initialize Method

//GetFormParameters Method @7-BECD6FF6
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["hdnKnowAreaID"][$RowNumber] = CCGetFromPost("hdnKnowAreaID_" . $RowNumber);
            $this->FormParameters["KnowAreaTitle"][$RowNumber] = CCGetFromPost("KnowAreaTitle_" . $RowNumber);
        }
    }
//End GetFormParameters Method

//Validate Method @7-F9FC1BDC
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");

        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["KnowAreaID"] = $this->CachedColumns["KnowAreaID"][$RowNumber];
            $this->hdnKnowAreaID->SetText($this->FormParameters["hdnKnowAreaID"][$RowNumber], $RowNumber);
            $this->KnowAreaTitle->SetText($this->FormParameters["KnowAreaTitle"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
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

//ValidateRow Method @7-4F5995AB
    function ValidateRow($RowNumber)
    {
        $Validation = true;
        $Validation = ($this->hdnKnowAreaID->Validate() && $Validation);
        $Validation = ($this->KnowAreaTitle->Validate() && $Validation);
        $errors = "";
        if(!$Validation)
        {
            $errors .= $this->hdnKnowAreaID->Errors->ToString();
            $errors .= $this->KnowAreaTitle->Errors->ToString();
            $this->hdnKnowAreaID->Errors->Clear();
            $this->KnowAreaTitle->Errors->Clear();
        }
        $this->RowsErrors[$RowNumber] = $errors;
        return $Validation;
    }
//End ValidateRow Method

//CheckInsert Method @7-2F04A6F3
    function CheckInsert($RowNumber)
    {
        $filed = false;
        $filed = ($filed || strlen($this->FormParameters["hdnKnowAreaID"][$RowNumber]));
        $filed = ($filed || strlen($this->FormParameters["KnowAreaTitle"][$RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @7-242E5992
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @7-A8F4FE9A
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

        $Redirect = "KnowledgeArea.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "KnowAreaID", "del_area"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick") || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick")) {
                $Redirect = "";
            } else {
                $Redirect = "KnowledgeArea.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm", "KnowAreaID", "del_area"));
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//UpdateGrid Method @7-B973255C
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit");
        if(!$this->Validate()) return;
        $Validation = true;
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->ds->CachedColumns["KnowAreaID"] = $this->CachedColumns["KnowAreaID"][$RowNumber];
            $this->hdnKnowAreaID->SetText($this->FormParameters["hdnKnowAreaID"][$RowNumber], $RowNumber);
            $this->KnowAreaTitle->SetText($this->FormParameters["KnowAreaTitle"][$RowNumber], $RowNumber);
            if ($this->UpdatedRows >= $RowNumber) {
                if($this->UpdateAllowed) { $Validation = ($this->UpdateRow($RowNumber) && $Validation); }
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

//InsertRow Method @7-9A45DFA3
    function InsertRow($RowNumber)
    {
        if(!$this->InsertAllowed) return false;
        $this->ds->KnowAreaTitle->SetValue($this->KnowAreaTitle->GetValue());
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

//UpdateRow Method @7-86FAB94D
    function UpdateRow($RowNumber)
    {
        if(!$this->UpdateAllowed) return false;
        $this->ds->lblAction->SetValue($this->lblAction->GetValue());
        $this->ds->hdnKnowAreaID->SetValue($this->hdnKnowAreaID->GetValue());
        $this->ds->KnowAreaTitle->SetValue($this->KnowAreaTitle->GetValue());
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

//FormScript Method @7-BEAF8BEF
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\">\n<!--\n";
        $script .= "var knowledgeareaElements;\n";
        $script .= "var knowledgeareaEmptyRows = 1;\n";
        $script .= "var " . $this->ComponentName . "hdnKnowAreaIDID = 0;\n";
        $script .= "var " . $this->ComponentName . "KnowAreaTitleID = 1;\n";
        $script .= "\nfunction initknowledgeareaElements() {\n";
        $script .= "\tvar ED = document.forms[\"knowledgearea\"];\n";
        $script .= "\tknowledgeareaElements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.hdnKnowAreaID_" . $i . ", " . "ED.KnowAreaTitle_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @7-38743340
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
                $this->CachedColumns["KnowAreaID"][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns["KnowAreaID"][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @7-6D618436
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns["KnowAreaID"][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @7-C581C9EF
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
                    if(!$this->FormSubmitted && $is_next_record) {
                        $this->CachedColumns["KnowAreaID"][$RowNumber] = $this->ds->CachedColumns["KnowAreaID"];
                        $this->hdnKnowAreaID->SetValue($this->ds->hdnKnowAreaID->GetValue());
                        $this->KnowAreaTitle->SetValue($this->ds->KnowAreaTitle->GetValue());
                        $this->ValidateRow($RowNumber);
                    } else if (!$this->FormSubmitted){
                        $this->CachedColumns["KnowAreaID"][$RowNumber] = "";
                        $this->hdnKnowAreaID->SetText("");
                        $this->KnowAreaTitle->SetText("");
                    } else {
                        $this->hdnKnowAreaID->SetText($this->FormParameters["hdnKnowAreaID"][$RowNumber], $RowNumber);
                        $this->KnowAreaTitle->SetText($this->FormParameters["KnowAreaTitle"][$RowNumber], $RowNumber);
                    }
                    $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                    $this->lblAction->Show($RowNumber);
                    $this->hdnKnowAreaID->Show($RowNumber);
                    $this->KnowAreaTitle->Show($RowNumber);
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
        $this->Sorter_KnowAreaTitle->Show();
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

} //End knowledgearea Class @7-FCB6E20C

class clsknowledgeareaDataSource extends clsDBConnection1 {  //knowledgeareaDataSource Class @7-F981DACE

//DataSource Variables @7-29C079F3
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $CountSQL;
    var $wp;
    var $AllParametersSet;

    var $CachedColumns;

    // Datasource fields
    var $lblAction;
    var $hdnKnowAreaID;
    var $KnowAreaTitle;
//End DataSource Variables

//Class_Initialize Event @7-1040F738
    function clsknowledgeareaDataSource()
    {
        $this->ErrorBlock = "EditableGrid knowledgearea/Error";
        $this->Initialize();
        $this->lblAction = new clsField("lblAction", ccsText, "");
        $this->hdnKnowAreaID = new clsField("hdnKnowAreaID", ccsText, "");
        $this->KnowAreaTitle = new clsField("KnowAreaTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-4C177783
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "KnowAreaID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_KnowAreaTitle" => array("KnowAreaTitle", "")));
    }
//End SetOrder Method

//Prepare Method @7-CBCC75F3
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCatID", ccsInteger, "", "", $this->Parameters["urlCatID"], 0, false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "CatID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @7-7F842570
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM knowledgearea";
        $this->SQL = "SELECT *  " .
        "FROM knowledgearea";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-380480FD
    function SetValues()
    {
        $this->CachedColumns["KnowAreaID"] = $this->f("KnowAreaID");
        $this->hdnKnowAreaID->SetDBValue($this->f("KnowAreaID"));
        $this->KnowAreaTitle->SetDBValue($this->f("KnowAreaTitle"));
    }
//End SetValues Method

//Insert Method @7-FAFC6673
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["KnowAreaTitle"] = new clsSQLParameter("ctrlKnowAreaTitle", ccsText, "", "", $this->KnowAreaTitle->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["CatID"] = new clsSQLParameter("urlCatID", ccsInteger, "", "", CCGetFromGet("CatID", ""), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO knowledgearea ("
             . "KnowAreaTitle, "
             . "CatID"
             . ") VALUES ("
             . $this->ToSQL($this->cp["KnowAreaTitle"]->GetDBValue(), $this->cp["KnowAreaTitle"]->DataType) . ", "
             . $this->ToSQL($this->cp["CatID"]->GetDBValue(), $this->cp["CatID"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @7-B0D68C12
    function Update()
    {
        $this->BlockExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $this->Where = "KnowAreaID=" . $this->ToSQL($this->CachedColumns["KnowAreaID"], ccsInteger);
        $this->SQL = "UPDATE knowledgearea SET "
             . "KnowAreaID=" . $this->ToSQL($this->hdnKnowAreaID->GetDBValue(), $this->hdnKnowAreaID->DataType) . ", "
             . "KnowAreaTitle=" . $this->ToSQL($this->KnowAreaTitle->GetDBValue(), $this->KnowAreaTitle->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End knowledgeareaDataSource Class @7-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-60837D79
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

$FileName = "KnowledgeArea.php";
$Redirect = "";
$TemplateFileName = "KnowledgeArea.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-18369AD9
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
$module = new clsGridmodule();
$knowledgearea = new clsEditableGridknowledgearea();
$lblError = new clsControl(ccsLabel, "lblError", "lblError", ccsText, "", CCGetRequestParam("lblError", ccsGet));
$lblError->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$module->Initialize();
$knowledgearea->Initialize();

// Events
include("./KnowledgeArea_events.php");
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

//Execute Components @1-E6DDD101
$Header->Operations();
$MenuAuthor->Operations();
$knowledgearea->Operation();
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

//Show Page @1-34FA8F1B
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$module->Show();
$knowledgearea->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblError->Show();
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
