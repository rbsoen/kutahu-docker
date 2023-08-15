<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @5-39DC296A
include_once("./Header.php");
//End Include Page implementation

//Include Page implementation @32-281214C7
include_once("./MyTree.php");
//End Include Page implementation

class clsGridquestion { //question class @9-15F72B84

//Variables @9-0B3A0FB0

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

//Class_Initialize Event @9-D0CDAD4C
    function clsGridquestion()
    {
        global $FileName;
        $this->ComponentName = "question";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid question";
        $this->ds = new clsquestionDataSource();
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

        $this->lblTable = new clsControl(ccsLabel, "lblTable", "lblTable", ccsText, "", CCGetRequestParam("lblTable", ccsGet));
        $this->lblTable->HTML = true;
        $this->QueTitle = new clsControl(ccsHidden, "QueTitle", "QueTitle", ccsText, "", CCGetRequestParam("QueTitle", ccsGet));
        $this->QueID = new clsControl(ccsHidden, "QueID", "QueID", ccsText, "", CCGetRequestParam("QueID", ccsGet));
        $this->QueChoiceA = new clsControl(ccsHidden, "QueChoiceA", "QueChoiceA", ccsText, "", CCGetRequestParam("QueChoiceA", ccsGet));
        $this->QueChoiceB = new clsControl(ccsHidden, "QueChoiceB", "QueChoiceB", ccsText, "", CCGetRequestParam("QueChoiceB", ccsGet));
        $this->QueChoiceC = new clsControl(ccsHidden, "QueChoiceC", "QueChoiceC", ccsText, "", CCGetRequestParam("QueChoiceC", ccsGet));
        $this->QueChoiceD = new clsControl(ccsHidden, "QueChoiceD", "QueChoiceD", ccsText, "", CCGetRequestParam("QueChoiceD", ccsGet));
        $this->QueChoiceE = new clsControl(ccsHidden, "QueChoiceE", "QueChoiceE", ccsText, "", CCGetRequestParam("QueChoiceE", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @9-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @9-67F14E14
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;


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
                $this->QueTitle->SetValue($this->ds->QueTitle->GetValue());
                $this->QueID->SetValue($this->ds->QueID->GetValue());
                $this->QueChoiceA->SetValue($this->ds->QueChoiceA->GetValue());
                $this->QueChoiceB->SetValue($this->ds->QueChoiceB->GetValue());
                $this->QueChoiceC->SetValue($this->ds->QueChoiceC->GetValue());
                $this->QueChoiceD->SetValue($this->ds->QueChoiceD->GetValue());
                $this->QueChoiceE->SetValue($this->ds->QueChoiceE->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->lblTable->Show();
                $this->QueTitle->Show();
                $this->QueID->Show();
                $this->QueChoiceA->Show();
                $this->QueChoiceB->Show();
                $this->QueChoiceC->Show();
                $this->QueChoiceD->Show();
                $this->QueChoiceE->Show();
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

//GetErrors Method @9-928B616D
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->lblTable->Errors->ToString();
        $errors .= $this->QueTitle->Errors->ToString();
        $errors .= $this->QueID->Errors->ToString();
        $errors .= $this->QueChoiceA->Errors->ToString();
        $errors .= $this->QueChoiceB->Errors->ToString();
        $errors .= $this->QueChoiceC->Errors->ToString();
        $errors .= $this->QueChoiceD->Errors->ToString();
        $errors .= $this->QueChoiceE->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End question Class @9-FCB6E20C

class clsquestionDataSource extends clsDBConnection1 {  //questionDataSource Class @9-C9FD7A76

//DataSource Variables @9-D40C7848
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $QueTitle;
    var $QueID;
    var $QueChoiceA;
    var $QueChoiceB;
    var $QueChoiceC;
    var $QueChoiceD;
    var $QueChoiceE;
//End DataSource Variables

//Class_Initialize Event @9-B617057F
    function clsquestionDataSource()
    {
        $this->ErrorBlock = "Grid question";
        $this->Initialize();
        $this->QueTitle = new clsField("QueTitle", ccsText, "");
        $this->QueID = new clsField("QueID", ccsText, "");
        $this->QueChoiceA = new clsField("QueChoiceA", ccsText, "");
        $this->QueChoiceB = new clsField("QueChoiceB", ccsText, "");
        $this->QueChoiceC = new clsField("QueChoiceC", ccsText, "");
        $this->QueChoiceD = new clsField("QueChoiceD", ccsText, "");
        $this->QueChoiceE = new clsField("QueChoiceE", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @9-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @9-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @9-252EE2AC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM question";
        $this->SQL = "SELECT *  " .
        "FROM question";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @9-39C1E817
    function SetValues()
    {
        $this->QueTitle->SetDBValue($this->f("QueTitle"));
        $this->QueID->SetDBValue($this->f("QueID"));
        $this->QueChoiceA->SetDBValue($this->f("QueChoiceA"));
        $this->QueChoiceB->SetDBValue($this->f("QueChoiceB"));
        $this->QueChoiceC->SetDBValue($this->f("QueChoiceC"));
        $this->QueChoiceD->SetDBValue($this->f("QueChoiceD"));
        $this->QueChoiceE->SetDBValue($this->f("QueChoiceE"));
    }
//End SetValues Method

} //End questionDataSource Class @9-FCB6E20C

//Include Page implementation @6-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-0016CA8F
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

$FileName = "QuestionSlide.php";
$Redirect = "";
$TemplateFileName = "QuestionSlide.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-BF3D99C0
$DBConnection1 = new clsDBConnection1();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$MyTree = new clsMyTree();
$MyTree->BindEvents();
$MyTree->TemplatePath = "./";
$MyTree->Initialize();
$lblTopLink = new clsControl(ccsLabel, "lblTopLink", "lblTopLink", ccsText, "", CCGetRequestParam("lblTopLink", ccsGet));
$lblTopLink->HTML = true;
$question = new clsGridquestion();
$lblEnd = new clsControl(ccsLabel, "lblEnd", "lblEnd", ccsText, "", CCGetRequestParam("lblEnd", ccsGet));
$lblEnd->HTML = true;
$lblWarning = new clsControl(ccsLabel, "lblWarning", "lblWarning", ccsText, "", CCGetRequestParam("lblWarning", ccsGet));
$lblWarning->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$question->Initialize();

// Events
include("./QuestionSlide_events.php");
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

//Execute Components @1-1C3660D4
$Header->Operations();
$MyTree->Operations();
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

//Show Page @1-5131C456
$Header->Show("Header");
$MyTree->Show("MyTree");
$question->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblEnd->Show();
$lblWarning->Show();
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
