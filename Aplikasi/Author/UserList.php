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

class clsGridusers { //users class @7-0CB76799

//Variables @7-26AB8FDC

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
    var $Sorter_UserFullName;
    var $Sorter_UserEmail;
    var $Navigator;
//End Variables

//Class_Initialize Event @7-07CA1B67
    function clsGridusers()
    {
        global $FileName;
        $this->ComponentName = "users";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid users";
        $this->ds = new clsusersDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("usersOrder", "");
        $this->SorterDirection = CCGetParam("usersDir", "");

        $this->ImageLink2 = new clsControl(ccsImageLink, "ImageLink2", "ImageLink2", ccsText, "", CCGetRequestParam("ImageLink2", ccsGet));
        $this->lblDelete = new clsControl(ccsLabel, "lblDelete", "lblDelete", ccsText, "", CCGetRequestParam("lblDelete", ccsGet));
        $this->lblDelete->HTML = true;
        $this->hdnUserActive = new clsControl(ccsHidden, "hdnUserActive", "hdnUserActive", ccsText, "", CCGetRequestParam("hdnUserActive", ccsGet));
        $this->UserFullName = new clsControl(ccsLabel, "UserFullName", "UserFullName", ccsText, "", CCGetRequestParam("UserFullName", ccsGet));
        $this->UserEmail = new clsControl(ccsLabel, "UserEmail", "UserEmail", ccsText, "", CCGetRequestParam("UserEmail", ccsGet));
        $this->lblActive = new clsControl(ccsLabel, "lblActive", "lblActive", ccsText, "", CCGetRequestParam("lblActive", ccsGet));
        $this->lblActive->HTML = true;
        $this->Sorter_UserFullName = new clsSorter($this->ComponentName, "Sorter_UserFullName", $FileName);
        $this->Sorter_UserEmail = new clsSorter($this->ComponentName, "Sorter_UserEmail", $FileName);
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", Array("UserUsername", "del_user", "on", "update_act", "ccsForm"));
        $this->Link1->Page = "UserMaint.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
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

//Show Method @7-DAA81C5D
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
                $this->ImageLink2->Parameters = CCGetQueryString("QueryString", Array("del_user", "on", "update_act", "ccsForm"));
                $this->ImageLink2->Parameters = CCAddParam($this->ImageLink2->Parameters, "UserUsername", $this->ds->f("UserUsername"));
                $this->ImageLink2->Page = "UserMaint.php";
                $this->lblDelete->SetValue($this->ds->lblDelete->GetValue());
                $this->hdnUserActive->SetValue($this->ds->hdnUserActive->GetValue());
                $this->UserFullName->SetValue($this->ds->UserFullName->GetValue());
                $this->UserEmail->SetValue($this->ds->UserEmail->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ImageLink2->Show();
                $this->lblDelete->Show();
                $this->hdnUserActive->Show();
                $this->UserFullName->Show();
                $this->UserEmail->Show();
                $this->lblActive->Show();
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
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_UserFullName->Show();
        $this->Sorter_UserEmail->Show();
        $this->Link1->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @7-307DED44
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ImageLink2->Errors->ToString();
        $errors .= $this->lblDelete->Errors->ToString();
        $errors .= $this->hdnUserActive->Errors->ToString();
        $errors .= $this->UserFullName->Errors->ToString();
        $errors .= $this->UserEmail->Errors->ToString();
        $errors .= $this->lblActive->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End users Class @7-FCB6E20C

class clsusersDataSource extends clsDBConnection1 {  //usersDataSource Class @7-DF0C03ED

//DataSource Variables @7-EFC5E25C
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lblDelete;
    var $hdnUserActive;
    var $UserFullName;
    var $UserEmail;
//End DataSource Variables

//Class_Initialize Event @7-C64E62F2
    function clsusersDataSource()
    {
        $this->ErrorBlock = "Grid users";
        $this->Initialize();
        $this->lblDelete = new clsField("lblDelete", ccsText, "");
        $this->hdnUserActive = new clsField("hdnUserActive", ccsText, "");
        $this->UserFullName = new clsField("UserFullName", ccsText, "");
        $this->UserEmail = new clsField("UserEmail", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-0DAD5C69
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_UserFullName" => array("UserFullName", ""), 
            "Sorter_UserEmail" => array("UserEmail", "")));
    }
//End SetOrder Method

//Prepare Method @7-DFF3DD87
    function Prepare()
    {
    }
//End Prepare Method

//Open Method @7-28C412B2
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM users";
        $this->SQL = "SELECT *  " .
        "FROM users";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-DB6686AE
    function SetValues()
    {
        $this->lblDelete->SetDBValue($this->f("UserUsername"));
        $this->hdnUserActive->SetDBValue($this->f("UserActive"));
        $this->UserFullName->SetDBValue($this->f("UserFullName"));
        $this->UserEmail->SetDBValue($this->f("UserEmail"));
    }
//End SetValues Method

} //End usersDataSource Class @7-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-E41279FE
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

$FileName = "UserList.php";
$Redirect = "";
$TemplateFileName = "UserList.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-D116CA20
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
$users = new clsGridusers();
$lblError = new clsControl(ccsLabel, "lblError", "lblError", ccsText, "", CCGetRequestParam("lblError", ccsGet));
$lblError->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$users->Initialize();

// Events
include("./UserList_events.php");
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

//Execute Components @1-1A7139A9
$Header->Operations();
$MenuAuthor->Operations();
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

//Show Page @1-43A79D8F
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$users->Show();
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
