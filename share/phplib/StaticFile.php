<?php
class StaticFile {

    public $config_file;
    public $module_name;
    protected $config;

    public function __construct($config_file, $module_name)
    {
        $this->config_file = $config_file;
        $this->module_name = $module_name;
    }

    /*
     * From configuration file, get static file list array for current module
     *
     * @method get_files
     * @param  $is_top
     * @return array
     */
    public function get_files($is_top = TRUE)
    {
        if ( ! file_exists($this->config_file)) 
        {
            return FALSE;
        }
        if ( ! isset($this->config))
        {
            require_once $this->config_file;
            $this->config = $static;
        }


        if ( ! isset($this->config[$this->module_name]) || count($this->config[$this->module_name]) === 0)
        {
            return FALSE;
        }
        $files          = $this->config[$this->module_name];    
        $js_files       = array();
        $css_files      = array();
        $noscript_files = array();
        $other_files    = array();
        foreach ($files as $file)
        {
            $file["is_top"] = isset($file["is_top"]) ? $file["is_top"] : TRUE;
            $file["is_noscript"] = isset($file["is_noscript"]) ? $file["is_noscript"] : FALSE;
            if ($file["is_top"] != $is_top)
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

    public function get_top_files()
    {
        $files = $this->get_files(TRUE);
        return $this->get_html($files);
        //return self::get_html($files);
    }

    public function get_bottom_files()
    {
        $files = $this->get_files(FALSE);
        return $this->get_html($files);
        //return self::get_html($files);
    }

    private function get_html($files = array())
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
