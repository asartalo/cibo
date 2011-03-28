Cibo
====

**Cibo** is a lightweight PHP class for downloading files through HTTP.

Usage
-----

    // Include the file
    require_once '/path/to/cibo/src/Cibo.php';
    
    $cibo = new Cibo;
    $cibo->download("http://url.toa/file.txt", "/path/to/local/file");
