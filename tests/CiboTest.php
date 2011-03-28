<?php

require_once realpath(__DIR__ . '/../src') . '/Cibo.php';

class CiboTest extends \PHPUnit_Framework_TestCase {
  
  private static $can_connect, $connect_failure_msg;
  
  function setUp() {
    $this->clearTestDataDir();
    if (!$this->isCanConnectToTestServer()) {
      $this->markTestIncomplete("The test server is not setup properly.");
    }
    $this->file_url = "http://cibotest.local/foo.txt";
    $this->local_file = $this->getExpectedTestFilePath('bar.txt');
    $this->cibo = new Cibo;
  }
  
  function tearDown() {
    $this->clearTestDataDir();
  }
  
  function testDownloadDownloadsToFile() {
    $this->cibo->download($this->file_url, $this->local_file);
    $this->assertFileExists($this->local_file);
  }
  
  function testDownloadDownloadsToFileWithCorrectContents() {
    $this->cibo->download($this->file_url, $this->local_file);
    $this->assertEquals(
      file_get_contents($this->getFixturesDir() . '/foo.txt'),
      file_get_contents($this->local_file)
    );
  }
  
  function testDownloadReturnsTrueWhenSuccessful() {
    $this->assertSame(
      TRUE, $this->cibo->download($this->file_url, $this->local_file)
    );
  }
  
  function testDownloadReturnsFalseWhenUnsuccessful() {
    $file_url = "http://cibotest.local/nonexistent.file.txt";
    $this->assertSame(FALSE, $this->cibo->download($file_url, $this->local_file));
  }
  
  private function quickMock($class, array $methods = array()) {
    return $this->getMock($class, $methods, array(), '', false);
  }
  
  private function createTestFile($path, $contents) {
    $full_path = $this->getTestDataDir() . '/' . $path;
    $file = fopen($full_path, 'wb');
    fwrite($file, $contents);
    fclose($file);
    return $full_path;
  }
  
  private function recursiveDelete($directory, $this_too = true) {
    if (file_exists($directory) && is_dir($directory)) {
      foreach (scandir($directory) as $value) {
        if ($value != "." && $value != "..") {
          $value = $directory . "/" . $value;
          if (is_dir($value)) {
            $this->recursiveDelete($value);
          } elseif (is_file($value)) {
            @unlink($value);
          }
        }
      }
      if ($this_too) {
        return rmdir($directory);
      }
    } else {
       return false;
    }
  }
  
  private function clearTestDataDir() {
    $this->recursiveDelete($this->getTestDataDir(), false);
  }
  
  private function getTestDataDir() {
    $data_dir = realpath(__DIR__ ) . '/data';
    if (!file_exists($data_dir)) {
      mkdir($data_dir);
    }
    return $data_dir;
  }
  
  private function getFixturesDir() {
    return realpath(__DIR__) . '/test_server';
  }
  
  private function getTestFilePath($file) {
    $file_path = $this->getExpectedTestFilePath($file);
    return file_exists($file_path) ? $file_path : '';
  }
  
  private function getExpectedTestFilePath($file) {
    return $this->getTestDataDir() . "/$file";
  }
  
  function isCanConnectToTestServer() {
    if (is_null(self::$can_connect)) {
      self::$can_connect = false;
      $fp = fsockopen('cibotest.local', 80, $errno, $errstr, 30);
      if (!$fp) {
        self::$can_connect = false;
      } else {
        $out = "GET /foo.txt HTTP/1.0\r\n";
        $out .= "Host: cibotest.local\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        $test = stream_get_contents($fp);
        if (strpos($test, 'The quick brown fox jumps over the lazy dog.') > -1) {
          self::$can_connect = true;
        } else {
          self::$connect_failure_msg = $test;
          self::$can_connect = false;
        }
        fclose($fp);
      }
    }
    return self::$can_connect;
  }
  
}
