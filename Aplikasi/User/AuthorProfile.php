<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsGridauthors { //authors class @2-9DACDE3F

//Variables @2-0B3A0FB0

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

//Class_Initialize Event @2-829D15B6
    function clsGridauthors()
    {
        global $FileName;
        $this->ComponentName = "authors";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid authors";
        $this->ds = new clsauthorsDataSource();
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

        $this->AutName = new clsControl(ccsLabel, "AutName", "AutName", ccsText, "", CCGetRequestParam("AutName", ccsGet));
        $this->AutPhoto = new clsControl(ccsLabel, "AutPhoto", "AutPhoto", ccsText, "", CCGetRequestParam("AutPhoto", ccsGet));
        $this->AutPhoto->HTML = true;
        $this->AutDept = new clsControl(ccsLabel, "AutDept", "AutDept", ccsText, "", CCGetRequestParam("AutDept", ccsGet));
        $this->AutInstance = new clsControl(ccsLabel, "AutInstance", "AutInstance", ccsText, "", CCGetRequestParam("AutInstance", ccsGet));
        $this->AutAddress = new clsControl(ccsLabel, "AutAddress", "AutAddress", ccsText, "", CCGetRequestParam("AutAddress", ccsGet));
        $this->AutPhone = new clsControl(ccsLabel, "AutPhone", "AutPhone", ccsText, "", CCGetRequestParam("AutPhone", ccsGet));
        $this->AutEmail = new clsControl(ccsLabel, "AutEmail", "AutEmail", ccsText, "", CCGetRequestParam("AutEmail", ccsGet));
        $this->AutEmail->HTML = true;
        $this->lblExperience = new clsControl(ccsLabel, "lblExperience", "lblExperience", ccsText, "", CCGetRequestParam("lblExperience", ccsGet));
        $this->lblExperience->HTML = true;
        $this->AutExperience1 = new clsControl(ccsHidden, "AutExperience1", "AutExperience1", ccsText, "", CCGetRequestParam("AutExperience1", ccsGet));
        $this->AutExperience2 = new clsControl(ccsHidden, "AutExperience2", "AutExperience2", ccsText, "", CCGetRequestParam("AutExperience2", ccsGet));
        $this->AutExperience3 = new clsControl(ccsHidden, "AutExperience3", "AutExperience3", ccsText, "", CCGetRequestParam("AutExperience3", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @2-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-7908AD8D
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlAutUsername"] = CCGetFromGet("AutUsername", "");

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
                $this->AutName->SetValue($this->ds->AutName->GetValue());
                $this->AutPhoto->SetValue($this->ds->AutPhoto->GetValue());
                $this->AutDept->SetValue($this->ds->AutDept->GetValue());
                $this->AutInstance->SetValue($this->ds->AutInstance->GetValue());
                $this->AutAddress->SetValue($this->ds->AutAddress->GetValue());
                $this->AutPhone->SetValue($this->ds->AutPhone->GetValue());
                $this->AutEmail->SetValue($this->ds->AutEmail->GetValue());
                $this->AutExperience1->SetValue($this->ds->AutExperience1->GetValue());
                $this->AutExperience2->SetValue($this->ds->AutExperience2->GetValue());
                $this->AutExperience3->SetValue($this->ds->AutExperience3->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->AutName->Show();
                $this->AutPhoto->Show();
                $this->AutDept->Show();
                $this->AutInstance->Show();
                $this->AutAddress->Show();
                $this->AutPhone->Show();
                $this->AutEmail->Show();
                $this->lblExperience->Show();
                $this->AutExperience1->Show();
                $this->AutExperience2->Show();
                $this->AutExperience3->Show();
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

//GetErrors Method @2-D2E71116
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->AutName->Errors->ToString();
        $errors .= $this->AutPhoto->Errors->ToString();
        $errors .= $this->AutDept->Errors->ToString();
        $errors .= $this->AutInstance->Errors->ToString();
        $errors .= $this->AutAddress->Errors->ToString();
        $errors .= $this->AutPhone->Errors->ToString();
        $errors .= $this->AutEmail->Errors->ToString();
        $errors .= $this->lblExperience->Errors->ToString();
        $errors .= $this->AutExperience1->Errors->ToString();
        $errors .= $this->AutExperience2->Errors->ToString();
        $errors .= $this->AutExperience3->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End authors Class @2-FCB6E20C

class clsauthorsDataSource extends clsDBConnection1 {  //authorsDataSource Class @2-16725FCD

//DataSource Variables @2-09C31AC0
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $AutName;
    var $AutPhoto;
    var $AutDept;
    var $AutInstance;
    var $AutAddress;
    var $AutPhone;
    var $AutEmail;
    var $AutExperience1;
    var $AutExperience2;
    var $AutExperience3;
//End DataSource Variables

//Class_Initialize Event @2-19802E11
    function clsauthorsDataSource()
    {
        $this->ErrorBlock = "Grid authors";
        $this->Initialize();
        $this->AutName = new clsField("AutName", ccsText, "");
        $this->AutPhoto = new clsField("AutPhoto", ccsText, "");
        $this->AutDept = new clsField("AutDept", ccsText, "");
        $this->AutInstance = new clsField("AutInstance", ccsText, "");
        $this->AutAddress = new clsField("AutAddress", ccsText, "");
        $this->AutPhone = new clsField("AutPhone", ccsText, "");
        $this->AutEmail = new clsField("AutEmail", ccsText, "");
        $this->AutExperience1 = new clsField("AutExperience1", ccsText, "");
        $this->AutExperience2 = new clsField("AutExperience2", ccsText, "");
        $this->AutExperience3 = new clsField("AutExperience3", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @2-3D9FABC7
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlAutUsername", ccsText, "", "", $this->Parameters["urlAutUsername"], "", true);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "AutUsername", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),true);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-E7C0293F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM authors";
        $this->SQL = "SELECT *  " .
        "FROM authors";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-9A62D522
    function SetValues()
    {
        $this->AutName->SetDBValue($this->f("AutName"));
        $this->AutPhoto->SetDBValue($this->f("AutPhoto"));
        $this->AutDept->SetDBValue($this->f("AutDept"));
        $this->AutInstance->SetDBValue($this->f("AutInstance"));
        $this->AutAddress->SetDBValue($this->f("AutAddress"));
        $this->AutPhone->SetDBValue($this->f("AutPhone"));
        $this->AutEmail->SetDBValue($this->f("AutEmail"));
        $this->AutExperience1->SetDBValue($this->f("AutExperience1"));
        $this->AutExperience2->SetDBValue($this->f("AutExperience2"));
        $this->AutExperience3->SetDBValue($this->f("AutExperience3"));
    }
//End SetValues Method

} //End authorsDataSource Class @2-FCB6E20C

//Initialize Page @1-B1AA7B8C
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

$FileName = "AuthorProfile.php";
$Redirect = "";
$TemplateFileName = "AuthorProfile.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-08937A36
$DBConnection1 = new clsDBConnection1();

// Controls
$authors = new clsGridauthors();
$authors->Initialize();

// Events
include("./AuthorProfile_events.php");
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

//Go to destination page @1-6F9FD7CC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBConnection1->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-34E5EFDD
$authors->Show();
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
