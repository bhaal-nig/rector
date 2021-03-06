<?php

declare(strict_types=1);

namespace Rector\Nette\Tests\Rector\ClassMethod\TranslateClassMethodToVariadicsRector;

use Iterator;
use Nette\Utils\FileSystem;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Nette\Rector\ClassMethod\TranslateClassMethodToVariadicsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TranslateClassMethodToVariadicsRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $localFilePath = __DIR__ . '/../../../../../../vendor/nette/utils/src/Utils/ITranslator.php';
        if (file_exists($localFilePath)) {
            FileSystem::delete($localFilePath);
        }

        require_once __DIR__ . '/../../../../../../stubs/Nette/Localization/ITranslation.php';

        // to make test work with fixture
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return TranslateClassMethodToVariadicsRector::class;
    }
}
