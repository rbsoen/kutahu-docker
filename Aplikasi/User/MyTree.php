<?php

class clsMyTree { //MyTree class @1-F3847AD5

//Variables @1-1987AB94
    var $FileName = "";
    var $Redirect = "";
    var $Tpl = "";
    var $TemplateFileName = "";
    var $BlockToParse = "";
    var $ComponentName = "";

    // Events;
    var $CCSEvents = "";
    var $CCSEventResult = "";
    var $TemplatePath;
    var $Visible;
//End Variables

//Class_Initialize Event @1-7C91F16B
    function clsMyTree()
    {
        $this->Visible = true;
        if($this->Visible)
        {
            $this->FileName = "MyTree.php";
            $this->Redirect = "";
            $this->TemplateFileName = "MyTree.html";
            $this->BlockToParse = "main";

            // Create Components
            $this->lblTree = new clsControl(ccsLabel, "lblTree", "lblTree", ccsText, "", CCGetRequestParam("lblTree", ccsGet));
            $this->lblTree->HTML = true;
        }
    }
//End Class_Initialize Event

//Class_Terminate Event @1-A3749DF6
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
    }
//End Class_Terminate Event

//BindEvents Method @1-C94E1D8D
    function BindEvents()
    {
        $this->lblTree->CCSEvents["BeforeShow"] = "MyTree_lblTree_BeforeShow";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInitialize");
    }
//End BindEvents Method

//Operations Method @1-7E2A14CF
    function Operations()
    {
        global $Redirect;
        if(!$this->Visible)
            return "";
    }
//End Operations Method

//Initialize Method @1-EDD74DD5
    function Initialize()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView");
        if(!$this->Visible)
            return "";
    }
//End Initialize Method

//Show Method @1-B5F80326
    function Show($Name)
    {
        global $Tpl;
        $block_path = $Tpl->block_path;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible)
            return "";
        $Tpl->LoadTemplate($this->TemplatePath . $this->TemplateFileName, $Name);
        $Tpl->block_path = $Name;
        $this->lblTree->Show();
        $Tpl->Parse();
        $Tpl->SetVar($Name, $Tpl->GetVar());
        $Tpl->block_path = $block_path;
    }
//End Show Method

} //End MyTree Class @1-FCB6E20C

//Include Event File @1-A3B910B2
include(RelativePath . "/User/MyTree_events.php");
//End Include Event File
?>
