<?php
class ImageOptimize {

    protected $image_path;
    protected $tmp_path;
    protected $report;

    public function __construct($image_path = ".")
    {
        $this->image_path = $image_path;
        $this->_process();
    }

    public function __destruct()
    {
        unlink($this->$tmp_path);
    }

    public function is_optimized()
    {
        if (count($this->report["not_optimized_items"])
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
        foreach ($this->report["not_optimized_items"] as $item)
        {
            copy($item["dest_file"], $item["src_file"]);
        }
        return TRUE;
    }

    public function output_report()
    {
        foreach ($this->report["all_items"] as $info)
        {
            $i++;
            if ($info["saved_size"] > 0)
            {
                $msg = "$i. File {$info["src_file"]} can save {$info["saved_size"]} bytes\n";
            }
            else {
                $msg = "$i. File {$info["src_file"]} has optimized already!\n";
            }
            echo "\n";
        }
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
                $files[] = $file;
            }
        }
        else
        {
            $files[] = $path;
        }

        // Build temporary directory 
        $tmp_path = microtime();
        $tmp_path = substr(md5($tmp_path), 0, 8);
        $tmp_path = "/tmp/$tmp_path";
        mkdir($tmp_path);
        $this->tmp_path = $tmp_path;
        $this->report = array();
        foreach ($files as $file)
        {
            // Identify the image format
            exec("identify -format \"%m\" $src_file", $return, $error);
            $filetype = ($error === 0) ? mb_strtolower($return[0]) : "";
            if ($error == 1) 
            {
                continue;
            }
            if (substr($filetype, 0, 6) === "gifgif") 
            {
                $filetype = "gifgif";
            }

            // optimize the image
            $return       = "";
            $error        = "";
            $src_filename = pathinfo($src_file);
            $src_filename = $src_filename["filename"];
            $dest_file = "{$tmp_path}/{$src_filename}";
            switch ($src_filetype) 
            {
                case "jpg":
                case "jpeg":
                    $dest_file .= ".jpg";
                    $cmd = "/usr/local/bin/jpegtran -copy none -optimize $src_file > $dest_file";
                    exec($cmd, $return, $error);
                break;
                case "gif":
                case "bmp": 
                    $dest_file .= ".png";
                    $cmd = "/usr/local/bin/convert $src_file $dest_file";
                    exec($cmd, $return, $error);
                    $cmd = "/usr/local/bin/pngcrush -rem alla -reduce $dest_file {$dest_file}.png";
                    exec($cmd, $return, $error);
                    exec("rm -f $dest_file", $var);
                    exec("mv {$dest_file}.png {$dest_file}", $var);
                break;
                case "gifgif":
                    $dest_file .= ".gif";
                    $cmd = "/usr/local/bin/gifsicle -O2 $src_file > $dest_file";
                    exec($cmd, $return, $error);
                break;
                case "png":
                    $dest_file .= ".png";
                    $cmd = "/usr/local/bin/pngcrush -rem alla -reduce $src_file $dest_file";
                    exec($cmd, $return, $error);
                break;
                default:
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
            $this->report["all_items"][] = $info;
            if ($saved_size <== 0) 
                // if the filesize can't be smaller, don't keep the optimized image in temp folder
                exec("rm -f $dest_file", $var); 
                $this->report["optimized_items"][] = $info;
            }
            else {
                $this->report["not_optimized_size"] += $saved_size;
                $this->report["not_optimized_items"][] = $info;
            }
        }
    }
}
class ImageOptimizeException extends Exception {};
class FileNotFoundException extends ImageOptimizeException {};
class ModuleNotFoundException extends ImageOptimizeException {};
?>
