<?php
class StaticFile
{
    public function __construct($config_file, $module_name)
    {
        $this->configFile = $config_file;
        $this->moduleName = $module_name;
    }

    public function getFiles($is_top = TRUE)
    {
        if ( ! file_exists($this->configFile)) 
        {
            return FALSE;
        }

        require_once $this->configFile;
        if ( ! isset($static[$this->moduleName]) || count($static[$this->moduleName]) === 0)
        {
            return FALSE;
        }
        $files          = $static[$this->moduleName];    
        $js_files       = array();
        $css_files      = array();
        $noscript_files = array();
        $other_files    = array();
        foreach ($files as $file)
        {
            $file["is_top"] = isset($file["is_top"]) ? $file["is_top"] : TRUE;
            $file["is_noscript"] = isset($file["is_noscript"]) ? $file["is_noscript"] : FALSE;
            if ($file["is_top"] !== $is_top)
            {
                continue;
            }
            $jsTypes  = array("js", "text/javascript", "javascript");
            $cssTypes = array("css", "text/css", "stylesheet");
            if (in_array($file["type"], $cssTypes))
            {
                $file["rel"] = "stylesheet";
                $file["type"] = "text/css";
                $file["tag"]  = "link";
                $file["media"] = isset($file["media"]) ? $file["media"] : "all";
                if ( ! $file["is_noscript"])
                { 
                    $css_files[] = $file;
                }
                else 
                {
                    $noscript_files[] = $file;
                }
                continue;
            } 
            if (in_array($file["type"], $jsTypes))
            {
                $file["type"] = "text/javascript";
                $file["tag"] = "script";
                $js_files[] = $file;
                continue;
            } 
            $other_files[] = $file;

        }
        $result = array_merge($css_files, $noscript_files, $js_files, $other_files);    
        return $result;
    }

    public function getTopFiles()
    {
        $files = $this->getFiles(TRUE);
        return $this->getHtml($files);
    }
    public function getBottomFiles()
    {
        $files = $this->getFiles(FALSE);
        return $this->getHtml($files);
    }
    public function getHtml($files = array())
    {
        $html = "";
        foreach ($files as $file)
        {
            switch ($file["type"])
            {
                case "text/javascript" : 
                    $html.= "<script type=\"text/javascript\" src=\"{$file["src"]}\"></script>\n";
                break;
                case "text/css" :
                    if ($file["is_noscript"]) 
                    {
                        $html.= "<noscript><link type=\"text/css\" rel=\"stylesheet\" href=\"{$file["href"]}\" media=\"{$file["media"]}\"></noscript>\n";
                    }
                    else 
                    {
                        $html.= "<link type=\"text/css\" rel=\"stylesheet\" href=\"{$file["href"]}\" media=\"{$file["media"]}\">\n";
                    }
                break;
                default :
            }
        }
        return $html;
    }
}
?>
