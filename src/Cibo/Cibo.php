<?php

namespace Cibo;

/**
 * A lightweight PHP class for downloading files through HTTP.
 *
 * Example:
 * <code>
 *   $cibo = new Cibo;
 *   $cibo->download("http://url.toa/file.txt", "/path/to/local/file");
 * </code>
 *
 * Created on March 29, 2011
 *
 * @author Wayne Duran
 *
 * Copyright (c) 2011 Wayne Duran
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class Cibo {

  /**
   * Downloads a remote file
   * 
   * @param string $url url of the remote file to download
   * @param string $file path where the remote file will be saved
   * @return boolean TRUE on success, FALSE on failure
   */
  function download($url, $file) {
    $remote_file = @fopen($url, 'rb');
    if ($remote_file) {
      $local_file = fopen($file, 'wb');
      fwrite($local_file, stream_get_contents($remote_file));
      fclose($local_file);
      return true;
    }
    return false;
  }

}
