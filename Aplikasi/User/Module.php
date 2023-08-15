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

//Include Page implementation @13-281214C7
include_once("./MyTree.php");
//End Include Page implementation

class clsGridmodule { //module class @7-DFA3016C

//Variables @7-0B3A0FB0

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

//Class_Initialize Event @7-D45B8619
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

        $this->module1_ModTitle = new clsControl(ccsLabel, "module1_ModTitle", "module1_ModTitle", ccsText, "", CCGetRequestParam("module1_ModTitle", ccsGet));
        $this->module1_ModTitle->HTML = true;
        $this->Mod_ModID = new clsControl(ccsHidden, "Mod_ModID", "Mod_ModID", ccsText, "", CCGetRequestParam("Mod_ModID", ccsGet));
        $this->ModDesc = new clsControl(ccsLabel, "ModDesc", "ModDesc", ccsMemo, "", CCGetRequestParam("ModDesc", ccsGet));
        $this->ModDesc->HTML = true;
        $this->ModTitle = new clsControl(ccsLabel, "ModTitle", "ModTitle", ccsText, "", CCGetRequestParam("ModTitle", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @7-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @7-D648824C
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlModID"] = CCGetFromGet("ModID", "");

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
                $this->module1_ModTitle->SetValue($this->ds->module1_ModTitle->GetValue());
                $this->Mod_ModID->SetValue($this->ds->Mod_ModID->GetValue());
                $this->ModDesc->SetValue($this->ds->ModDesc->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->module1_ModTitle->Show();
                $this->Mod_ModID->Show();
                $this->ModDesc->Show();
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
        $this->ModTitle->SetValue($this->ds->ModTitle->GetValue());
        $this->ModTitle->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @7-7D9553F4
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->module1_ModTitle->Errors->ToString();
        $errors .= $this->Mod_ModID->Errors->ToString();
        $errors .= $this->ModDesc->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End module Class @7-FCB6E20C

class clsmoduleDataSource extends clsDBConnection1 {  //moduleDataSource Class @7-3199DDCA

//DataSource Variables @7-866DE8B0
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ModTitle;
    var $module1_ModTitle;
    var $Mod_ModID;
    var $ModDesc;
//End DataSource Variables

//Class_Initialize Event @7-511FB0D6
    function clsmoduleDataSource()
    {
        $this->ErrorBlock = "Grid module";
        $this->Initialize();
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->module1_ModTitle = new clsField("module1_ModTitle", ccsText, "");
        $this->Mod_ModID = new clsField("Mod_ModID", ccsText, "");
        $this->ModDesc = new clsField("ModDesc", ccsMemo, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @7-9185298E
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlModID", ccsInteger, "", "", $this->Parameters["urlModID"], 0, false);
    }
//End Prepare Method

//Open Method @7-FE7AEA24
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*) FROM `module` LEFT JOIN `module` module1 ON " .
        "`module`.Mod_ModID = module1.ModID " .
        "WHERE `module`.ModID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->SQL = "SELECT `module`.*, module1.ModTitle AS module1_ModTitle  " .
        "FROM `module` LEFT JOIN `module` module1 ON " .
        "`module`.Mod_ModID = module1.ModID " .
        "WHERE `module`.ModID = " . $this->SQLValue($this->wp->GetDBValue("1"), ccsInteger) . "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue($this->CountSQL, $this);
        $this->query(CCBuildSQL($this->SQL, "", $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-870A7ECD
    function SetValues()
    {
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->module1_ModTitle->SetDBValue($this->f("module1_ModTitle"));
        $this->Mod_ModID->SetDBValue($this->f("Mod_ModID"));
        $this->ModDesc->SetDBValue($this->f("ModDesc"));
    }
//End SetValues Method

} //End moduleDataSource Class @7-FCB6E20C

//Include Page implementation @4-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-696F887F
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

$FileName = "Module.php";
$Redirect = "";
$TemplateFileName = "Module.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-8D6FB156
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
$module = new clsGridmodule();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$module->Initialize();

// Events
include("./Module_events.php");
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

//Show Page @1-42F22D82
$Header->Show("Header");
$MyTree->Show("MyTree");
$module->Show();
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
