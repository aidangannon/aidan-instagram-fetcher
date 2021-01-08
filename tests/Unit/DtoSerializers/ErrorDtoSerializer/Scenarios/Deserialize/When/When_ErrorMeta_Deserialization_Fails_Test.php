<?php


namespace InstaFetcherTests\Unit\DtoSerializers\ErrorDtoSerializer\Scenarios\Deserialize\When;


use InstaFetcher\DataAccess\Dtos\Serializers\Exception\ErrorDtoDeserializationError;
use InstaFetcherTests\Unit\DtoSerializers\ErrorDtoSerializer\Scenarios\Deserialize\Given_Deserialize_Is_Called;

class When_ErrorMeta_Deserialization_Fails_Test extends Given_Deserialize_Is_Called
{

    private array $errorMetaInput=["invalidData2"=>"data","invalidData3"=>"data2"];

    function setUpClassProperties()
    {
        $this->mockErrorMetaSerializer
            ->shouldReceive("")
            ->andThrows(new ErrorDtoDeserializationError());
    }

    function fixtureProvider(): array
    {
        return [
            [
                "dataIn"=>
                    ["data"=>$this->errorMetaInput]
            ]
        ];
    }

    function initFixture(array $data)
    {
        $this->dataIn = $data["dataIn"];
    }

    /**
     * @test
     */
    function Then_ErrorDtoDeserializationError_Should_Be_Thrown()
    {
        self::assertInstanceOf(ErrorDtoDeserializationError::class,$this->exception);
    }

    /**
     * @doesNotPerformAssertions
     * @test
     */
    function Then_Deserialize_ErrorMetaDto_Is_Called()
    {
        $this->mockErrorMetaSerializer
            ->shouldHaveReceived("deserialize",[$this->errorMetaInput])
            ->once();
    }
}