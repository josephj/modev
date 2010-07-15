<?php
/**
 * Mini.php - Development tool for JavaScript and CSS files.
 */
class Mini 
{
    protected $config_file;
    protected $config_xml;
    protected $css_paths;
    protected $js_paths;

    public function __construct($config_file = "mini.xml")
    {
        $this->config_file = self::find($config_file);
        $this->load();
    }

    /**
     * Search for the specific file
     * @param string file_name File name only or absolute file path. If only file name provided, will check each path provided.
     * @param array path search path
     */
    public static function find($file_name, $paths = array("."))
    {
        $file_path = realpath($file_name);
        if ($file_path && file_exists($file_path)) 
        {
            return $file_path;
        }
        foreach ($paths as $path) 
        {
            $file_path = rtrim($path, "/") . "/" . $file_name;
            if (file_exists($file_path)) 
            {
                return $file_path;
            }
        }
        throw new FileNotFoundException("File not found: " . $file_name);
    }

    public function find_css_file($file_name)
    {
        return (strpos($file_name, "http://") === 0) ? $file_name : self::find($file_name, $this->css_paths);
    }

    public function find_js_file($file_name)
    {
        return (strpos($file_name, "http://") === 0) ? $file_name : self::find($file_name, $this->js_paths);
    }

    private function load()
    {
        $this->config_xml = simplexml_load_file($this->config_file);
        $dom = dom_import_simplexml($this->config_xml);
        $dom->ownerDocument->xinclude();
        foreach ($this->config_xml->xpath("path[@type='css']") as $path)
        {
            $this->css_paths[] = preg_replace_callback('/\$([A-Z][0-9A-Z_]*)/', array($this, "resolve"), $path); 
        }        
        foreach ($this->config_xml->xpath("path[@type='js']") as $path)
        {
            $this->js_paths[] = preg_replace_callback('/\$([A-Z][0-9A-Z_]*)/', array($this, "resolve"), $path); 
        }        
    }

    private function resolve($matches)
    {
        return isset($_SERVER[$matches[1]]) ? $_SERVER[$matches[1]] : "$" . $matches[1];
    }

    public function get_module($module_id)
    {
        return new MiniModule($this, $this->get_module_xml($module_id));
    }

    public function get_modules() {
        $modules = array();
        foreach ($this->config_xml->xpath("module") as $module_xml)
        {
            $modules[] = $this->get_module($module_xml["id"]);
        }
        return $modules;
    }

    public function get_module_xml($module_id)
    {
        $module_xml = $this->config_xml->xpath("module[@id='$module_id']");
        if ( ! count($module_xml)) 
        {
            throw new ModuleNotFoundException("Module not found: " . $module_id);
        }
        $module_xml = $module_xml[0];
        $module_dom = dom_import_simplexml($module_xml);
        // require directive
        foreach ($module_xml->xpath("require") as $require_xml)
        {
            $require_dom = dom_import_simplexml($require_xml);
            $include_xml = $this->get_module_xml($require_xml["module"]);
            $include_dom = dom_import_simplexml($include_xml);
            foreach ($include_xml->children() as $child_xml)
            {
                $module_dom->insertBefore(dom_import_simplexml($child_xml)->cloneNode(true), $require_dom);
            }
            $module_dom->removeChild($require_dom);
        }            
        // exclude directive
        foreach ($module_xml->xpath("exclude") as $exclude_xml)
        {
            // get the excluded files
            $files = $module_xml->xpath("file[@type='" . $exclude["type"] . "' and @src='" . $exclude["src"] . "']");
            foreach ($files as $file_xml)
            {
                $module_dom->removeChild(dom_import_simplexml($file_xml));
            }
        }
        return $module_xml;
    }

}

/**
 * A single Mini module
 */

class MiniModule
{
    protected $id;
    protected $mini;
    protected $css_files;
    protected $js_files;
    protected $file_size;

    public function __construct($mini, $module_xml)
    {
        $this->mini  = $mini;
        $this->id    = $module_xml["id"];
        $files       = array();
        // get css file list
        foreach ($module_xml->xpath("file[@type='css']") as $file)
        {
            $src = $this->mini->find_css_file($file["src"]);
            if (in_array($src, $files))
            {
                continue;
            }
            $files[] = $src;
            $this->css_files[] = $src;
        }
        // get javascript file list
        foreach ($module_xml->xpath("file[@type='js']") as $file)
        {
            $src = $this->mini->find_js_file($file["src"]);
            if (in_array($src, $files))
            {
                continue;
            }
            $files[] = $src;
            $this->js_files[] = $src;
        }
    }

    public function build($target_path = ".", $is_minify = TRUE)
    {
        $css_file = $this->build_css($target_path, $is_minify);
        $js_file  = $this->build_js($target_path, $is_minify);
        if ( ! is_null($css_file) && ! is_null($js_file)) {
            return array($css_file, $js_file);
        }
        else if ( ! is_null($css_file))
        {
            return array($css_file);
        }
        else if ( ! is_null($js_file))
        {
            return array($js_file);
        }
        return array();
    }
    
    public function build_css($target_path = ".", $is_minify = TRUE)
    {
        if (count($this->css_files))
        {
            return $this->compress("css", $this->get_target_file("css", $target_path, $is_minify));
        }
        else 
        {
            return null;
        }
    }

    public function build_js($target_path = ".", $is_minify = TRUE)
    {
        if (count($this->js_files))
        {
            return $this->compress("js", $this->get_target_file("js", $target_path, $is_minify));
        }
        else 
        {
            return null;
        }
    }

    public function get_target_file($type, $outpath = ".")
    {
        $filename = rtrim($outpath, '/') . '/';
        $filename .= "min_" . $this->id . "." . $type;
        return $filename;
    }

    public function compress($type, $outfile, $is_minify = TRUE)
    {
        $files = ($type === "css") ? $this->css_files : $this->js_files;
        $tmp_file = tempnam("/tmp/", "mini_");
        $handle = fopen($tmp_file, "w+");
        foreach ($files as $file)
        {
            if (strpos($file, "http://") === 0)
            {
                $ch = curl_init(file);
                curl_setopt($ch, CURLOPT_URL, "file");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-TW; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11");
                $content = curl_exec($ch);
                if ($result === FALSE) 
                {
                    throw new FileNotFoundException("File not found: " . $file);
                }
            }
            else 
            {
                $content = file_get_contents($file);
            }
            fwrite($handle, $content);
        }
        fclose($handle);
        $output = "";
        if ($is_minify)
        {
            $output = shell_exec("yuicompressor --charset utf-8 --preserve-semi --type " . escapeshellarg($type) . " " . escapeshellarg($tmp_file));
        }
        else 
        {
            $output = file_get_contents($tmp_file);
        }
        $this->file_size[$type] = filesize($tmp_file);
        unlink($tmp_file);
        if ($outfile) {
            // Delete the output file if it already exists.
            if (file_exists($outfile)) {
                unlink($outfile);
            }

            file_put_contents($outfile, $output);

            if (!defined('FUSE_QUIET')) {
                echo $outfile."\n";
            }

            return $outfile;
        }
        return $output;
    }

    public function echo_css($is_minify = TRUE)
    {
        echo $this->compress("css", null, $is_minify);
    }

    public function echo_js($is_minify = TRUE)
    {
        echo $this->compress("js", null, $is_minify);
    }
    
}

class MiniException extends Exception {};
class FileNotFoundException extends MiniException {};
class ModuleNotFoundException extends MiniException {};
?>
