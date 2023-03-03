<?php
namespace wpPageForTerm\Helper;

use wpPageForTerm\Helper\CacheBust;

use Brain\Monkey\Functions;
use Mockery;

class CacheBustTest extends \PluginTestCase\PluginTestCase
{
    public function testNoManifestFileInDebugMode()
    {
        Functions\when('file_exists')->justReturn(false);

        $cacheBust = Mockery::mock('wpPageForTerm\Helper\CacheBust')->makePartial();
        $cacheBust->shouldReceive('isDebug')->andReturn(true);

        $realfile = $cacheBust->name('nofile');
        
        // Just expect some output.
        $this->expectOutputRegex('/^.+$/');
        $this->assertSame($realfile, null);
    }

    public function testReturnRealFileWhenFoundInManifest()
    {
        Functions\when('file_exists')->justReturn(true);
        Functions\when('file_get_contents')->justReturn('{"file.js": "realfile.js"}');

        $cacheBust = new CacheBust();
        $realfile = $cacheBust->name('file.js');
        
        $this->assertSame($realfile, 'realfile.js');
    }
}
