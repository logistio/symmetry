<?php


namespace Logistio\Symmetry\Test\PublicId;


use Logistio\Symmetry\PublicId\PublicIdConverter;
use Logistio\Symmetry\PublicId\PublicIdManager;
use Logistio\Symmetry\Test\TestCase;

/**
 * PublicIdConverterTest
 * ----
 *
 *
 */
class PublicIdConverterTest extends TestCase
{
    /**
     * @var PublicIdConverter
     */
    protected $pubIdConverter;

    protected $idsToTest = [
        0,
        1,
        2,
        3,
        4,
        16,
        70,
        71,
        98765478,
        67182423,
        19111911192121,
        // 1911191119212167645678,  // This is too big for HashIds!
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->pubIdConverter = $this->app->make(PublicIdConverter::class);
    }

    /**
     * @test
     */
    public function testEncodeDecode()
    {
        foreach ($this->idsToTest as $id) {
            $message = "Failed for id=$id;";

            $pubId = $this->pubIdConverter->encode($id);

            self::assertNotEquals($pubId, $id, $message);
            self::assertTrue(is_string($pubId), $message);

            try {
                self::assertEquals($id, $this->pubIdConverter->decode($pubId), $message);
            } catch (ApiException $e) {
                self::fail("Invlaid id value for id=$message;");
            }
        }
    }

    /**
     * Tests that the PublicIdConverter instance encodes and decodes ids to
     * the same values as the statically-accessed PublicIdManager class.
     *
     * @test
     */
    public function testEncodesSameAsPublicIdManager()
    {
        foreach ($this->idsToTest as $id) {

            $message = "Failed for id=$id;";

            $converterEncodedId = $this->pubIdConverter->encode($id);
            $managerEncodedId = PublicIdManager::encode($id);

            self::assertEquals($managerEncodedId, $converterEncodedId, $message);
        }


    }


}