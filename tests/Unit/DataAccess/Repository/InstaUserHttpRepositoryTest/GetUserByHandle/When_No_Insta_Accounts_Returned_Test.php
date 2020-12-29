<?php
declare(strict_types=1);

namespace InstaFetcherTests\Unit\DataAccess\Repository\InstaUserHttpRepositoryTest\GetUserByHandle;


use Exception;
use InstaFetcher\DataAccess\Dtos\FacebookPageDto;
use InstaFetcher\DataAccess\Dtos\FacebookPagesDto;
use InstaFetcher\DataAccess\Dtos\InstaUserDto;
use InstaFetcher\DataAccess\Http\Exception\InstaUserNotFound;
use InstaFetcherTests\Unit\DataAccess\Repository\InstaUserHttpRepositoryTest\InstaUserRepositoryTestCase;
use PHPUnit\Framework\TestCase;

class When_No_Insta_Accounts_Returned extends InstaUserRepositoryTestCase
{

    private string $token;
    private string $handle;
    private FacebookPagesDto $pages;

    private Exception $exception;

    public function when()
    {
        try {
            $this->sut->getByHandle($this->handle);
        } catch (Exception $e) {
            $this->exception = $e;
        }
    }

    public function setUpMocks()
    {
        $this->mockSession
            ->shouldReceive("getToken")
            ->andReturns($this->token);
        $this->mockPageDao
            ->shouldReceive("getInstaAccounts")
            ->with($this->token)
            ->andReturns($this->pages);
    }

    public function fixtureProvider(): array
    {
        return [
            [
                "token" => "00000",
                "handle" => "example_handle",
                "pages" => new FacebookPagesDto([])
            ],
            [
                "token" => "00000",
                "handle" => "example_handle",
                "pages" => new FacebookPagesDto(
                    [
                        new FacebookPageDto(
                            "11111"
                        )
                    ]
                )
            ],
            [
                "token" => "00000",
                "handle" => "example_handle",
                "pages" => new FacebookPagesDto(
                    [
                        new FacebookPageDto(
                            "11111"
                        ),
                        new FacebookPageDto(
                            "33333"
                        )
                    ]
                )
            ]
        ];
    }

    public function initFixture(array $data)
    {
        $this->token = $data["token"];
        $this->handle = $data["handle"];
        $this->pages = $data["pages"];
    }

    /**
     * @test
     */
    public function Then_InstaUserNotFound_Exception_Should_Be_Thrown()
    {
        self::assertNotNull($this->exception);
        self::assertTrue(self::isInstanceOf(InstaUserNotFound::class));
    }

    /**
     * @test
     */
    public function Then_Token_Should_Be_Received_From_Facebook_Session()
    {
        $this->mockSession
            ->shouldHaveReceived("getToken");
    }

    /**
     * @test
     */
    public function Then_Insta_Accounts_Should_Be_Fetched_From_Session_Token()
    {
        $this->mockPageDao
            ->shouldHaveReceived("getInstaAccounts")
            ->once();
        $this->mockPageDao
            ->shouldHaveReceived("getInstaAccounts", [$this->token]);
    }
}