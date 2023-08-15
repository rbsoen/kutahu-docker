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

class clsRecordauthors { //authors Class @8-4AF545D0

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

//Class_Initialize Event @8-6C203DA8
    function clsRecordauthors()
    {

        global $FileName;
        $this->Visible = true;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record authors/Error";
        $this->ds = new clsauthorsDataSource();
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "authors";
            $CCSForm = split(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "multipart/form-data";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->AutUsername = new clsControl(ccsTextBox, "AutUsername", "Login ID", ccsText, "", CCGetRequestParam("AutUsername", $Method));
            $this->AutUsername->Required = true;
            $this->AutPassword = new clsControl(ccsTextBox, "AutPassword", "Password", ccsText, "", CCGetRequestParam("AutPassword", $Method));
            $this->AutPassword->Required = true;
            $this->AutName = new clsControl(ccsTextBox, "AutName", "Nama Lengkap", ccsText, "", CCGetRequestParam("AutName", $Method));
            $this->AutName->Required = true;
            $this->AutDept = new clsControl(ccsTextBox, "AutDept", "Departemen", ccsText, "", CCGetRequestParam("AutDept", $Method));
            $this->AutInstance = new clsControl(ccsTextBox, "AutInstance", "Instansi", ccsText, "", CCGetRequestParam("AutInstance", $Method));
            $this->AutAddress = new clsControl(ccsTextArea, "AutAddress", "Alamat", ccsText, "", CCGetRequestParam("AutAddress", $Method));
            $this->AutPhone = new clsControl(ccsTextBox, "AutPhone", "Telepon", ccsText, "", CCGetRequestParam("AutPhone", $Method));
            $this->AutPhone->Required = true;
            $this->AutEmail = new clsControl(ccsTextBox, "AutEmail", "Email", ccsText, "", CCGetRequestParam("AutEmail", $Method));
            $this->AutEmail->Required = true;
            $this->AutLevel = new clsControl(ccsListBox, "AutLevel", "Level", ccsText, "", CCGetRequestParam("AutLevel", $Method));
            $this->AutLevel->DSType = dsListOfValues;
            $this->AutLevel->Values = array(array("1", "Administrator"), array("2", "Pengajar"));
            $this->AutLevel->Required = true;
            $this->AutActive = new clsControl(ccsCheckBox, "AutActive", "AutActive", ccsText, "", CCGetRequestParam("AutActive", $Method));
            $this->AutActive->CheckedValue = $this->AutActive->GetParsedValue(true);
            $this->AutActive->UncheckedValue = $this->AutActive->GetParsedValue(false);
            $this->txtExperience1 = new clsControl(ccsTextBox, "txtExperience1", "txtExperience1", ccsText, "", CCGetRequestParam("txtExperience1", $Method));
            $this->txtTahun1 = new clsControl(ccsTextBox, "txtTahun1", "txtTahun1", ccsText, "", CCGetRequestParam("txtTahun1", $Method));
            $this->AutExperience1 = new clsControl(ccsHidden, "AutExperience1", "AutExperience1", ccsText, "", CCGetRequestParam("AutExperience1", $Method));
            $this->txtExperience2 = new clsControl(ccsTextBox, "txtExperience2", "txtExperience2", ccsText, "", CCGetRequestParam("txtExperience2", $Method));
            $this->txtTahun2 = new clsControl(ccsTextBox, "txtTahun2", "txtTahun2", ccsText, "", CCGetRequestParam("txtTahun2", $Method));
            $this->AutExperience2 = new clsControl(ccsHidden, "AutExperience2", "AutExperience2", ccsText, "", CCGetRequestParam("AutExperience2", $Method));
            $this->txtExperience3 = new clsControl(ccsTextBox, "txtExperience3", "txtExperience3", ccsText, "", CCGetRequestParam("txtExperience3", $Method));
            $this->txtTahun3 = new clsControl(ccsTextBox, "txtTahun3", "txtTahun3", ccsText, "", CCGetRequestParam("txtTahun3", $Method));
            $this->AutExperience3 = new clsControl(ccsHidden, "AutExperience3", "AutExperience3", ccsText, "", CCGetRequestParam("AutExperience3", $Method));
            $this->AutPhoto = new clsFileUpload("AutPhoto", "Foto", "/tmp/", "../Images/Author/", "*.gif;*.jpg", "", 1000000);
            $this->Button_Insert = new clsButton("Button_Insert");
            $this->Button_Update = new clsButton("Button_Update");
            $this->Button_Delete = new clsButton("Button_Delete");
            $this->Button_Cancel = new clsButton("Button_Cancel");
        }
    }
//End Class_Initialize Event

//Initialize Method @8-BED26F29
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->ds->Parameters["urlAutUsername"] = CCGetFromGet("AutUsername", "");
    }
//End Initialize Method

//Validate Method @8-EE7B7994
    function Validate()
    {
        $Validation = true;
        $Where = "";
        $Validation = ($this->AutUsername->Validate() && $Validation);
        $Validation = ($this->AutPassword->Validate() && $Validation);
        $Validation = ($this->AutName->Validate() && $Validation);
        $Validation = ($this->AutDept->Validate() && $Validation);
        $Validation = ($this->AutInstance->Validate() && $Validation);
        $Validation = ($this->AutAddress->Validate() && $Validation);
        $Validation = ($this->AutPhone->Validate() && $Validation);
        $Validation = ($this->AutEmail->Validate() && $Validation);
        $Validation = ($this->AutLevel->Validate() && $Validation);
        $Validation = ($this->AutActive->Validate() && $Validation);
        $Validation = ($this->txtExperience1->Validate() && $Validation);
        $Validation = ($this->txtTahun1->Validate() && $Validation);
        $Validation = ($this->AutExperience1->Validate() && $Validation);
        $Validation = ($this->txtExperience2->Validate() && $Validation);
        $Validation = ($this->txtTahun2->Validate() && $Validation);
        $Validation = ($this->AutExperience2->Validate() && $Validation);
        $Validation = ($this->txtExperience3->Validate() && $Validation);
        $Validation = ($this->txtTahun3->Validate() && $Validation);
        $Validation = ($this->AutExperience3->Validate() && $Validation);
        $Validation = ($this->AutPhoto->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate");
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @8-4A7B09BC
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->AutUsername->Errors->Count());
        $errors = ($errors || $this->AutPassword->Errors->Count());
        $errors = ($errors || $this->AutName->Errors->Count());
        $errors = ($errors || $this->AutDept->Errors->Count());
        $errors = ($errors || $this->AutInstance->Errors->Count());
        $errors = ($errors || $this->AutAddress->Errors->Count());
        $errors = ($errors || $this->AutPhone->Errors->Count());
        $errors = ($errors || $this->AutEmail->Errors->Count());
        $errors = ($errors || $this->AutLevel->Errors->Count());
        $errors = ($errors || $this->AutActive->Errors->Count());
        $errors = ($errors || $this->txtExperience1->Errors->Count());
        $errors = ($errors || $this->txtTahun1->Errors->Count());
        $errors = ($errors || $this->AutExperience1->Errors->Count());
        $errors = ($errors || $this->txtExperience2->Errors->Count());
        $errors = ($errors || $this->txtTahun2->Errors->Count());
        $errors = ($errors || $this->AutExperience2->Errors->Count());
        $errors = ($errors || $this->txtExperience3->Errors->Count());
        $errors = ($errors || $this->txtTahun3->Errors->Count());
        $errors = ($errors || $this->AutExperience3->Errors->Count());
        $errors = ($errors || $this->AutPhoto->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->ds->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @8-97554E02
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

        $this->AutPhoto->Upload();

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
        $Redirect = "AuthorList.php" . "?" . CCGetQueryString("QueryString", Array("ccsForm"));
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

//InsertRow Method @8-595BED24
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert");
        if(!$this->InsertAllowed) return false;
        $this->ds->AutUsername->SetValue($this->AutUsername->GetValue());
        $this->ds->AutPassword->SetValue($this->AutPassword->GetValue());
        $this->ds->AutName->SetValue($this->AutName->GetValue());
        $this->ds->AutDept->SetValue($this->AutDept->GetValue());
        $this->ds->AutInstance->SetValue($this->AutInstance->GetValue());
        $this->ds->AutAddress->SetValue($this->AutAddress->GetValue());
        $this->ds->AutPhone->SetValue($this->AutPhone->GetValue());
        $this->ds->AutEmail->SetValue($this->AutEmail->GetValue());
        $this->ds->AutPhoto->SetValue($this->AutPhoto->GetValue());
        $this->ds->AutActive->SetValue($this->AutActive->GetValue());
        $this->ds->AutLevel->SetValue($this->AutLevel->GetValue());
        $this->ds->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert");
        if($this->ds->Errors->Count() == 0) {
            $this->AutPhoto->Move();
        }
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @8-41B81B5E
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate");
        if(!$this->UpdateAllowed) return false;
        $this->ds->AutUsername->SetValue($this->AutUsername->GetValue());
        $this->ds->AutPassword->SetValue($this->AutPassword->GetValue());
        $this->ds->AutName->SetValue($this->AutName->GetValue());
        $this->ds->AutDept->SetValue($this->AutDept->GetValue());
        $this->ds->AutInstance->SetValue($this->AutInstance->GetValue());
        $this->ds->AutAddress->SetValue($this->AutAddress->GetValue());
        $this->ds->AutPhone->SetValue($this->AutPhone->GetValue());
        $this->ds->AutEmail->SetValue($this->AutEmail->GetValue());
        $this->ds->AutPhoto->SetValue($this->AutPhoto->GetValue());
        $this->ds->AutActive->SetValue($this->AutActive->GetValue());
        $this->ds->AutLevel->SetValue($this->AutLevel->GetValue());
        $this->ds->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate");
        if($this->ds->Errors->Count() == 0) {
            $this->AutPhoto->Move();
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @8-97205DA6
    function Show()
    {
        global $Tpl;
        global $FileName;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");

        $this->AutLevel->Prepare();

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
                    echo "Error in Record authors";
                }
                else if($this->ds->next_record())
                {
                    $this->ds->SetValues();
                    if(!$this->FormSubmitted)
                    {
                        $this->AutUsername->SetValue($this->ds->AutUsername->GetValue());
                        $this->AutPassword->SetValue($this->ds->AutPassword->GetValue());
                        $this->AutName->SetValue($this->ds->AutName->GetValue());
                        $this->AutDept->SetValue($this->ds->AutDept->GetValue());
                        $this->AutInstance->SetValue($this->ds->AutInstance->GetValue());
                        $this->AutAddress->SetValue($this->ds->AutAddress->GetValue());
                        $this->AutPhone->SetValue($this->ds->AutPhone->GetValue());
                        $this->AutEmail->SetValue($this->ds->AutEmail->GetValue());
                        $this->AutLevel->SetValue($this->ds->AutLevel->GetValue());
                        $this->AutActive->SetValue($this->ds->AutActive->GetValue());
                        $this->AutExperience1->SetValue($this->ds->AutExperience1->GetValue());
                        $this->AutExperience2->SetValue($this->ds->AutExperience2->GetValue());
                        $this->AutExperience3->SetValue($this->ds->AutExperience3->GetValue());
                        $this->AutPhoto->SetValue($this->ds->AutPhoto->GetValue());
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
            $Error .= $this->AutUsername->Errors->ToString();
            $Error .= $this->AutPassword->Errors->ToString();
            $Error .= $this->AutName->Errors->ToString();
            $Error .= $this->AutDept->Errors->ToString();
            $Error .= $this->AutInstance->Errors->ToString();
            $Error .= $this->AutAddress->Errors->ToString();
            $Error .= $this->AutPhone->Errors->ToString();
            $Error .= $this->AutEmail->Errors->ToString();
            $Error .= $this->AutLevel->Errors->ToString();
            $Error .= $this->AutActive->Errors->ToString();
            $Error .= $this->txtExperience1->Errors->ToString();
            $Error .= $this->txtTahun1->Errors->ToString();
            $Error .= $this->AutExperience1->Errors->ToString();
            $Error .= $this->txtExperience2->Errors->ToString();
            $Error .= $this->txtTahun2->Errors->ToString();
            $Error .= $this->AutExperience2->Errors->ToString();
            $Error .= $this->txtExperience3->Errors->ToString();
            $Error .= $this->txtTahun3->Errors->ToString();
            $Error .= $this->AutExperience3->Errors->ToString();
            $Error .= $this->AutPhoto->Errors->ToString();
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

        $this->AutUsername->Show();
        $this->AutPassword->Show();
        $this->AutName->Show();
        $this->AutDept->Show();
        $this->AutInstance->Show();
        $this->AutAddress->Show();
        $this->AutPhone->Show();
        $this->AutEmail->Show();
        $this->AutLevel->Show();
        $this->AutActive->Show();
        $this->txtExperience1->Show();
        $this->txtTahun1->Show();
        $this->AutExperience1->Show();
        $this->txtExperience2->Show();
        $this->txtTahun2->Show();
        $this->AutExperience2->Show();
        $this->txtExperience3->Show();
        $this->txtTahun3->Show();
        $this->AutExperience3->Show();
        $this->AutPhoto->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

} //End authors Class @8-FCB6E20C

class clsauthorsDataSource extends clsDBConnection1 {  //authorsDataSource Class @8-16725FCD

//DataSource Variables @8-A3449842
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $AutUsername;
    var $AutPassword;
    var $AutName;
    var $AutDept;
    var $AutInstance;
    var $AutAddress;
    var $AutPhone;
    var $AutEmail;
    var $AutLevel;
    var $AutActive;
    var $txtExperience1;
    var $txtTahun1;
    var $AutExperience1;
    var $txtExperience2;
    var $txtTahun2;
    var $AutExperience2;
    var $txtExperience3;
    var $txtTahun3;
    var $AutExperience3;
    var $AutPhoto;
//End DataSource Variables
var $Experience1;
var $Experience2;
var $Experience3;
//Class_Initialize Event @8-8610800A
    function clsauthorsDataSource()
    {
        $this->ErrorBlock = "Record authors/Error";
        $this->Initialize();
        $this->AutUsername = new clsField("AutUsername", ccsText, "");
        $this->AutPassword = new clsField("AutPassword", ccsText, "");
        $this->AutName = new clsField("AutName", ccsText, "");
        $this->AutDept = new clsField("AutDept", ccsText, "");
        $this->AutInstance = new clsField("AutInstance", ccsText, "");
        $this->AutAddress = new clsField("AutAddress", ccsText, "");
        $this->AutPhone = new clsField("AutPhone", ccsText, "");
        $this->AutEmail = new clsField("AutEmail", ccsText, "");
        $this->AutLevel = new clsField("AutLevel", ccsText, "");
        $this->AutActive = new clsField("AutActive", ccsText, "");
        $this->txtExperience1 = new clsField("txtExperience1", ccsText, "");
        $this->txtTahun1 = new clsField("txtTahun1", ccsText, "");
        $this->AutExperience1 = new clsField("AutExperience1", ccsText, "");
        $this->txtExperience2 = new clsField("txtExperience2", ccsText, "");
        $this->txtTahun2 = new clsField("txtTahun2", ccsText, "");
        $this->AutExperience2 = new clsField("AutExperience2", ccsText, "");
        $this->txtExperience3 = new clsField("txtExperience3", ccsText, "");
        $this->txtTahun3 = new clsField("txtTahun3", ccsText, "");
        $this->AutExperience3 = new clsField("AutExperience3", ccsText, "");
        $this->AutPhoto = new clsField("AutPhoto", ccsText, "");

    }
//End Class_Initialize Event

//Prepare Method @8-1C1EA095
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlAutUsername", ccsText, "", "", $this->Parameters["urlAutUsername"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "AutUsername", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @8-75154EA7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->SQL = "SELECT *  " .
        "FROM authors";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @8-24D80B4E
    function SetValues()
    {
        $this->AutUsername->SetDBValue($this->f("AutUsername"));
        $this->AutPassword->SetDBValue($this->f("AutPassword"));
        $this->AutName->SetDBValue($this->f("AutName"));
        $this->AutDept->SetDBValue($this->f("AutDept"));
        $this->AutInstance->SetDBValue($this->f("AutInstance"));
        $this->AutAddress->SetDBValue($this->f("AutAddress"));
        $this->AutPhone->SetDBValue($this->f("AutPhone"));
        $this->AutEmail->SetDBValue($this->f("AutEmail"));
        $this->AutLevel->SetDBValue($this->f("AutLevel"));
        $this->AutActive->SetDBValue($this->f("AutActive"));
        $this->AutExperience1->SetDBValue($this->f("AutExperience1"));
        $this->AutExperience2->SetDBValue($this->f("AutExperience2"));
        $this->AutExperience3->SetDBValue($this->f("AutExperience3"));
        $this->AutPhoto->SetDBValue($this->f("AutPhoto"));
    }
//End SetValues Method

//Insert Method @8-702C1164
    function Insert()
    {
        $this->BlockExecution = true;
        $this->cp["AutUsername"] = new clsSQLParameter("ctrlAutUsername", ccsText, "", "", $this->AutUsername->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutPassword"] = new clsSQLParameter("ctrlAutPassword", ccsText, "", "", $this->AutPassword->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutName"] = new clsSQLParameter("ctrlAutName", ccsText, "", "", $this->AutName->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutDept"] = new clsSQLParameter("ctrlAutDept", ccsText, "", "", $this->AutDept->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutInstance"] = new clsSQLParameter("ctrlAutInstance", ccsText, "", "", $this->AutInstance->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutAddress"] = new clsSQLParameter("ctrlAutAddress", ccsText, "", "", $this->AutAddress->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutPhone"] = new clsSQLParameter("ctrlAutPhone", ccsText, "", "", $this->AutPhone->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutEmail"] = new clsSQLParameter("ctrlAutEmail", ccsText, "", "", $this->AutEmail->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutPhoto"] = new clsSQLParameter("ctrlAutPhoto", ccsText, "", "", $this->AutPhoto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutActive"] = new clsSQLParameter("ctrlAutActive", ccsText, "", "", $this->AutActive->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutLevel"] = new clsSQLParameter("ctrlAutLevel", ccsText, "", "", $this->AutLevel->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutExperience1"] = new clsSQLParameter("expr57", ccsText, "", "", $this->Experience1, "", false, $this->ErrorBlock);
        $this->cp["AutExperience2"] = new clsSQLParameter("expr58", ccsText, "", "", $this->Experience2, "", false, $this->ErrorBlock);
        $this->cp["AutExperience3"] = new clsSQLParameter("expr59", ccsText, "", "", $this->Experience3, "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert");
        $this->SQL = "INSERT INTO authors ("
             . "AutUsername, "
             . "AutPassword, "
             . "AutName, "
             . "AutDept, "
             . "AutInstance, "
             . "AutAddress, "
             . "AutPhone, "
             . "AutEmail, "
             . "AutPhoto, "
             . "AutActive, "
             . "AutLevel, "
             . "AutExperience1, "
             . "AutExperience2, "
             . "AutExperience3"
             . ") VALUES ("
             . $this->ToSQL($this->cp["AutUsername"]->GetDBValue(), $this->cp["AutUsername"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutPassword"]->GetDBValue(), $this->cp["AutPassword"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutName"]->GetDBValue(), $this->cp["AutName"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutDept"]->GetDBValue(), $this->cp["AutDept"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutInstance"]->GetDBValue(), $this->cp["AutInstance"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutAddress"]->GetDBValue(), $this->cp["AutAddress"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutPhone"]->GetDBValue(), $this->cp["AutPhone"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutEmail"]->GetDBValue(), $this->cp["AutEmail"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutPhoto"]->GetDBValue(), $this->cp["AutPhoto"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutActive"]->GetDBValue(), $this->cp["AutActive"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutLevel"]->GetDBValue(), $this->cp["AutLevel"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutExperience1"]->GetDBValue(), $this->cp["AutExperience1"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutExperience2"]->GetDBValue(), $this->cp["AutExperience2"]->DataType) . ", "
             . $this->ToSQL($this->cp["AutExperience3"]->GetDBValue(), $this->cp["AutExperience3"]->DataType)
             . ")";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert");
        }
        $this->close();
    }
//End Insert Method

//Update Method @8-B12C1D69
    function Update()
    {
        $this->BlockExecution = true;
        $this->cp["AutUsername"] = new clsSQLParameter("ctrlAutUsername", ccsText, "", "", $this->AutUsername->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutPassword"] = new clsSQLParameter("ctrlAutPassword", ccsText, "", "", $this->AutPassword->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutName"] = new clsSQLParameter("ctrlAutName", ccsText, "", "", $this->AutName->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutDept"] = new clsSQLParameter("ctrlAutDept", ccsText, "", "", $this->AutDept->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutInstance"] = new clsSQLParameter("ctrlAutInstance", ccsText, "", "", $this->AutInstance->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutAddress"] = new clsSQLParameter("ctrlAutAddress", ccsText, "", "", $this->AutAddress->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutPhone"] = new clsSQLParameter("ctrlAutPhone", ccsText, "", "", $this->AutPhone->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutEmail"] = new clsSQLParameter("ctrlAutEmail", ccsText, "", "", $this->AutEmail->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutPhoto"] = new clsSQLParameter("ctrlAutPhoto", ccsText, "", "", $this->AutPhoto->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutActive"] = new clsSQLParameter("ctrlAutActive", ccsText, "", "", $this->AutActive->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutLevel"] = new clsSQLParameter("ctrlAutLevel", ccsText, "", "", $this->AutLevel->GetValue(), "", false, $this->ErrorBlock);
        $this->cp["AutExperience1"] = new clsSQLParameter("expr62", ccsText, "", "", $this->Experience1, "", false, $this->ErrorBlock);
        $this->cp["AutExperience2"] = new clsSQLParameter("expr63", ccsText, "", "", $this->Experience2, "", false, $this->ErrorBlock);
        $this->cp["AutExperience3"] = new clsSQLParameter("expr64", ccsText, "", "", $this->Experience3, "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "urlAutUsername", ccsText, "", "", CCGetFromGet("AutUsername", ""), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError("One or more parameters missing to perform the Update/Delete. The application is misconfigured.");
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate");
        $wp->Criterion[1] = $wp->Operation(opEqual, "AutUsername", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsText),false);
        $Where = $wp->Criterion[1];
        $this->SQL = "UPDATE authors SET "
             . "AutUsername=" . $this->ToSQL($this->cp["AutUsername"]->GetDBValue(), $this->cp["AutUsername"]->DataType) . ", "
             . "AutPassword=" . $this->ToSQL($this->cp["AutPassword"]->GetDBValue(), $this->cp["AutPassword"]->DataType) . ", "
             . "AutName=" . $this->ToSQL($this->cp["AutName"]->GetDBValue(), $this->cp["AutName"]->DataType) . ", "
             . "AutDept=" . $this->ToSQL($this->cp["AutDept"]->GetDBValue(), $this->cp["AutDept"]->DataType) . ", "
             . "AutInstance=" . $this->ToSQL($this->cp["AutInstance"]->GetDBValue(), $this->cp["AutInstance"]->DataType) . ", "
             . "AutAddress=" . $this->ToSQL($this->cp["AutAddress"]->GetDBValue(), $this->cp["AutAddress"]->DataType) . ", "
             . "AutPhone=" . $this->ToSQL($this->cp["AutPhone"]->GetDBValue(), $this->cp["AutPhone"]->DataType) . ", "
             . "AutEmail=" . $this->ToSQL($this->cp["AutEmail"]->GetDBValue(), $this->cp["AutEmail"]->DataType) . ", "
             . "AutPhoto=" . $this->ToSQL($this->cp["AutPhoto"]->GetDBValue(), $this->cp["AutPhoto"]->DataType) . ", "
             . "AutActive=" . $this->ToSQL($this->cp["AutActive"]->GetDBValue(), $this->cp["AutActive"]->DataType) . ", "
             . "AutLevel=" . $this->ToSQL($this->cp["AutLevel"]->GetDBValue(), $this->cp["AutLevel"]->DataType) . ", "
             . "AutExperience1=" . $this->ToSQL($this->cp["AutExperience1"]->GetDBValue(), $this->cp["AutExperience1"]->DataType) . ", "
             . "AutExperience2=" . $this->ToSQL($this->cp["AutExperience2"]->GetDBValue(), $this->cp["AutExperience2"]->DataType) . ", "
             . "AutExperience3=" . $this->ToSQL($this->cp["AutExperience3"]->GetDBValue(), $this->cp["AutExperience3"]->DataType);
        $this->SQL = CCBuildSQL($this->SQL, $Where, "");
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate");
        if($this->Errors->Count() == 0 && $this->BlockExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate");
        }
        $this->close();
    }
//End Update Method

} //End authorsDataSource Class @8-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-70BDBB57
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

$FileName = "AuthorMaint.php";
$Redirect = "";
$TemplateFileName = "AuthorMaint.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-62B28F2D
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
$authors = new clsRecordauthors();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$authors->Initialize();

// Events
include("./AuthorMaint_events.php");
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

//Execute Components @1-239EBB78
$Header->Operations();
$MenuAuthor->Operations();
$authors->Operation();
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

//Show Page @1-7AE8C823
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$authors->Show();
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
