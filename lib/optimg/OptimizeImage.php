<?php
class OptimizeImage {

    public static function is_image($file_path)
    {
        if ( ! file_exists($file_path))
        {
            throw new FileNotFoundException("File or path not found: " . $file_path);
        }
        $types = array("gif", "png", "gifgif", "jpg", "jpeg", "bmp");
        exec("/usr/bin/identify -quiet -format \"%m\" $file_path", $return, $error);
        $type = ($error === 0) ? mb_strtolower($return[0]) : "";
        if ($error == 1) 
        {
            return FALSE;
        }
        if (substr($type, 0, 6) === "gifgif") 
        {
            $type = "gifgif";
        }
        return in_array($type, $types);
    }

    public static function get_type($file_path)
    {
        if ( ! self::is_image($file_path))
        {
            throw new FileNotImageException("It's not an image file: " . $file_path);
        }
        exec("/usr/bin/identify -quiet -format \"%m\" $file_path", $return, $error);
        $type = ($error === 0) ? mb_strtolower($return[0]) : "";
        $type = ($error === 0) ? mb_strtolower($return[0]) : "";
        if ($error == 1) 
        {
            return FALSE;
        }
        if (substr($type, 0, 6) === "gifgif") 
        {
            $type = "gifgif";
        }
        return $type;
    }

    protected $image_path;
    protected $tmp_path;
    protected $report;
    protected $options;

    public function __construct($image_path = ".", $options = array())
    {
        $this->image_path = $image_path;
        $this->options = $options;
        $this->_process();
    }

    public function __destruct()
    {
        exec("rm -rf {$this->tmp_path}");
    }

    public function is_optimized()
    {
        if (count($this->report["not_optimized"]))
        {
            return FALSE;
        }
        else 
        {
            return TRUE;
        }
    }

    public function optimize()
    {
        foreach ($this->report["not_optimized"] as $item)
        {
            copy($item["dest_file"], $item["src_file"]);
        }
        return TRUE;
    }

    public function get_report($type = "")
    {
        return $this->report; 
    }

    private function _process()
    {
        $path = realpath($this->image_path);

        // Check if the assigned file or path exists
        if ( ! is_dir($path) && ! file_exists($path))
        {
            throw new FileNotFoundException("File or path not found: " . $path);
        }
        // Get files 
        if (is_dir($path))
        {
            $handle = opendir($path);
            while ($file = readdir($handle)) 
            {
                if (is_dir($file))
                {
                    continue;
                }
                if ( ! self::is_image($file))
                {
                    continue;
                }
                $files[] = $file;
            }
        }
        else
        {
            if (self::is_image($path))
            {
                $files[] = $path;
            }
        }

        if ( ! count($files))
        {
            throw new NoImageFoundException("Image not found : $path");
        }

        // Build temporary directory 
        $tmp_path = microtime();
        $tmp_path = substr(md5($tmp_path), 0, 8);
        $tmp_path = "/tmp/$tmp_path";
        mkdir($tmp_path);
        $this->tmp_path = $tmp_path;
        $this->report = array(
            "all"           => array(),
            "optimized"     => array(),
            "not_optimized" => array(),
            "target_save_size"  => 0,
        );
        foreach ($files as $file)
        {
            // optimize image start
            $src_filetype = self::get_type($file);
            $src_file     = $file;
            $return       = "";
            $error        = "";
            $src_filename = pathinfo($src_file);
            $src_filename = $src_filename["basename"];
            $dest_file = "{$tmp_path}/{$src_filename}";
            switch ($src_filetype) 
            {
                case "jpg":
                case "jpeg":
                    $dest_file .= ".jpg";
                    $cmd = "/usr/bin/jpegtran -copy none -progressive -optimize $src_file > $dest_file";
                    exec($cmd, $return, $error);
                break;
                case "gif":
                case "bmp": 
                    // transform first
                    $dest_file .= ".png";
                    $cmd = "/usr/bin/convert $src_file $dest_file";
                    // remove crush
                    $src_file = $dest_file;
                    $dest_file .= ".png";
                    exec($cmd, $return, $error);
                    $cmd = "/usr/bin/pngcrush -rem alla -reduce $src_file $dest_file";
                    // change to png8 if necessary
                    if ($this->options["use_png8"])
                    {
                        $src_file = $dest_file;
                        $dest_file .= ".png";
                        $cmd = "/usr/bin/convert $src_file PNG8:$dest_file";
                        exec($cmd, $return, $error);
                    }
                    exec($cmd, $return, $error);
                    exec("rm -f $dest_file", $var);
                    exec("mv {$dest_file}.png {$dest_file}", $var);
                break;
                case "gifgif":
                    $dest_file .= ".gif";
                    $cmd = "/usr/bin/gifsicle -O2 $src_file > $dest_file";
                    exec($cmd, $return, $error);
                break;
                case "png":
                    $cmd = "/usr/bin/pngcrush -rem alla -brute -reduce $src_file $dest_file";
                    exec($cmd, $return, $error);
                    if ($this->options["use_png8"])
                    {
                        $cmd = "/usr/bin/convert $dest_file PNG8:$dest_file";
                        exec($cmd, $return, $error);
                        $dest_file .= ".png";
                    }
                break;
                default:
                    continue;
            }
            // check whether the image is being optimized
            $src_size   = filesize($src_file);
            $dest_size  = filesize($dest_file);
            $saved_size = $src_size - $dest_size;
            $info = array(
                "src_file"   => $src_file,
                "dest_file"  => $dest_file,
                "src_size"   => $src_size,
                "dest_size"  => $dest_size,
                "saved_size" => $saved_size,
            );
            $this->report["all"][] = $info;
            if ($saved_size <= 0) 
            {
                // if the filesize can't be smaller, don't keep the optimized image in temp folder
                exec("rm -f $dest_file", $var); 
                $this->report["optimized"][] = $info;
            }
            else {
                $this->report["target_save_size"] += $saved_size;
                $this->report["not_optimized"][] = $info;
            }
        }
    }
}
class OptimizeImageException extends Exception {};
class FileNotFoundException extends OptimizeImageException {};
class FileNotImageException extends OptimizeImageException {};
class ModuleNotFoundException extends OptimizeImageException {};
?>
