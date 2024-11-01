<?php 
    if (! function_exists('assets_version')) {
        function assets_version($file)
        {
            $filePath =  URL::asset($file);
            $version = now()->timestamp;
            return $filePath/*.'?v=' . $version*/;
        }
    }
?>