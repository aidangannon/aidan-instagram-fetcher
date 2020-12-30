<?php


namespace InstaFetcherTests\Unit\DataAccess\Dao\FacebookPageDao\Scenarios\GetInstaAccounts\When;


use InstaFetcher\DataAccess\Dtos\FacebookPageDto;
use InstaFetcher\DataAccess\Dtos\FacebookPagesDto;
use InstaFetcher\DataAccess\Dtos\InstaUserDto;
use InstaFetcherTests\Unit\DataAccess\Dao\FacebookPageDao\Scenarios\GetInstaAccounts\Given_User_Tries_To_Fetch_Pages_With_The_Page_Insta_User;

class When_Pages_Are_Returned_Test extends Given_User_Tries_To_Fetch_Pages_With_The_Page_Insta_User
{
    private FacebookPagesDto $pagesDto;

    public function setUpMocks()
    {
        $this->mockResponse
            ->shouldReceive("getStatusCode")
            ->andReturns(200);
        $this->mockPageSerializer
            ->shouldReceive("deserialize")
            ->andReturns($this->pagesDto);
    }

    public function fixtureProvider(): array
    {
        return [
            [
                "token"=>"1111",
                "appSecretProof"=>"11110000",
                "pagesDto"=>new FacebookPagesDto(
                    [
                        new FacebookPageDto(
                            "11111",
                            new InstaUserDto("1234",104,"example_handle")
                        )
                    ]
                )
            ]
        ];
    }

    public function initFixture(array $data)
    {
        $this->token=$data["token"];
        $this->appSecretProof=$data["appSecretProof"];
        $this->pagesDto=$data["pagesDto"];
    }

    /**
     * @doesNotPerformAssertions
     * @test
     */
    public function Then_No_Error_Handled()
    {
        $this->mockErrorValidator
            ->shouldNotHaveReceived("validateCode");
        $this->mockErrorSerializer
            ->shouldNotHaveReceived("deserialize");
    }

    /**
     * @test
     */
    public function Then_Pages_Should_Be_Returned()
    {
        self::assertEquals($this->pagesDto,$this->pages);
    }
}