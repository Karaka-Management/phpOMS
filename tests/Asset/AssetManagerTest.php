<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Asset;

use phpOMS\Asset\AssetManager;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Asset\AssetManagerTest: Asset manager to handle/access assets')]
final class AssetManagerTest extends \PHPUnit\Framework\TestCase
{
    protected $manager = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->manager = new AssetManager();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The manager has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertNull($this->manager->get('myAsset'));
        self::assertEquals(0, $this->manager->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An asset can be added to the manager')]
    public function testAddAsset() : void
    {
        $this->manager->set('first', 'FirstUri');

        $set = $this->manager->set('myAsset', 'AssetUri');
        self::assertTrue($set);
        self::assertEquals(2, $this->manager->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An asset can be retrieved from the manager')]
    public function testRetrieveAsset() : void
    {
        $this->manager->set('myAsset', 'AssetUri');
        self::assertEquals('AssetUri', $this->manager->get('myAsset'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An asset can only be added once to the manager (no duplication unless overwritten)')]
    public function testInvalidAssetReplacement() : void
    {
        $this->manager->set('myAsset', 'AssetUri');

        $set = $this->manager->set('myAsset', 'AssetUri2', false);
        self::assertFalse($set);
        self::assertEquals('AssetUri', $this->manager->get('myAsset'));
        self::assertEquals(1, $this->manager->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An asset can be replaced upon request')]
    public function testAssetReplacement() : void
    {
        $this->manager->set('myAsset', 'AssetUri');

        $set = $this->manager->set('myAsset', 'AssetUri2');
        self::assertTrue($set);
        self::assertEquals('AssetUri2', $this->manager->get('myAsset'));
        self::assertEquals(1, $this->manager->count());

        $set = $this->manager->set('myAsset', 'AssetUri3', true);
        self::assertTrue($set);
        self::assertEquals('AssetUri3', $this->manager->get('myAsset'));
        self::assertEquals(1, $this->manager->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An asset can be removed from the manager')]
    public function testAssetRemove() : void
    {
        $this->manager->set('myAsset', 'AssetUri');
        self::assertEquals(1, $this->manager->count());

        self::assertTrue($this->manager->remove('myAsset'));
        self::assertEquals(0, $this->manager->count());
        self::assertNull($this->manager->get('myAsset'));

        self::assertFalse($this->manager->remove('myAsset'));
        self::assertEquals(0, $this->manager->count());
    }
}
