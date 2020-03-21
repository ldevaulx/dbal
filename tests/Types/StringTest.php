<?php

namespace Doctrine\DBAL\Tests\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
    /** @var AbstractPlatform|MockObject */
    private $platform;

    /** @var StringType */
    private $type;

    protected function setUp() : void
    {
        $this->platform = $this->createMock(AbstractPlatform::class);
        $this->type     = Type::getType('string');
    }

    public function testReturnsSqlDeclarationFromPlatformVarchar() : void
    {
        $this->platform->expects($this->once())
            ->method('getVarcharTypeDeclarationSQL')
            ->willReturn('TEST_VARCHAR');

        self::assertEquals('TEST_VARCHAR', $this->type->getSqlDeclaration([], $this->platform));
    }

    public function testReturnsDefaultLengthFromPlatformVarchar() : void
    {
        $this->platform->expects($this->once())
            ->method('getVarcharDefaultLength')
            ->willReturn(255);

        self::assertEquals(255, $this->type->getDefaultLength($this->platform));
    }

    public function testConvertToPHPValue() : void
    {
        self::assertIsString($this->type->convertToPHPValue('foo', $this->platform));
        self::assertIsString($this->type->convertToPHPValue('', $this->platform));
    }

    public function testNullConversion() : void
    {
        self::assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    public function testSQLConversion() : void
    {
        self::assertFalse($this->type->canRequireSQLConversion(), 'String type can never require SQL conversion to work.');
        self::assertEquals('t.foo', $this->type->convertToDatabaseValueSQL('t.foo', $this->platform));
        self::assertEquals('t.foo', $this->type->convertToPHPValueSQL('t.foo', $this->platform));
    }
}
