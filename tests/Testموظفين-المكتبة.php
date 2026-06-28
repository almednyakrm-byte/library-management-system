<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;

class Testموظفينالمكتبة extends TestCase
{
    private $app;
    private $request;
    private $response;
    private $streamFactory;

    protected function setUp(): void
    {
        $this->app = new App();
        $this->streamFactory = new StreamFactory();
        $this->request = new Request();
        $this->response = new Response();
    }

    public function testGetAllموظفينالمكتبة(): void
    {
        // Arrange
        $request = $this->request->withMethod('GET')
            ->withUri(new Uri('/موظفين-المكتبة'));
        $response = new Response();

        // Mock PDO statement
        $pdoStatement = $this->createMock(\PDOStatement::class);
        $pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        // Act
        $response = $this->app->handle($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertJson($response->getBody()->__toString());
    }

    public function testGetموظفينالمكتبةById(): void
    {
        // Arrange
        $request = $this->request->withMethod('GET')
            ->withUri(new Uri('/موظفين-المكتبة/1'));
        $response = new Response();

        // Mock PDO statement
        $pdoStatement = $this->createMock(\PDOStatement::class);
        $pdoStatement->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        // Act
        $response = $this->app->handle($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertJson($response->getBody()->__toString());
    }

    public function testCreateموظفينالمكتبة(): void
    {
        // Arrange
        $request = $this->request->withMethod('POST')
            ->withUri(new Uri('/موظفين-المكتبة'))
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream('{"name": "John Doe"}'));
        $response = new Response();

        // Mock PDO statement
        $pdoStatement = $this->createMock(\PDOStatement::class);
        $pdoStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Act
        $response = $this->app->handle($request);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertJson($response->getBody()->__toString());
    }

    public function testUpdateموظفينالمكتبة(): void
    {
        // Arrange
        $request = $this->request->withMethod('PUT')
            ->withUri(new Uri('/موظفين-المكتبة/1'))
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream('{"name": "Jane Doe"}'));
        $response = new Response();

        // Mock PDO statement
        $pdoStatement = $this->createMock(\PDOStatement::class);
        $pdoStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Act
        $response = $this->app->handle($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertJson($response->getBody()->__toString());
    }

    public function testDeleteموظفينالمكتبة(): void
    {
        // Arrange
        $request = $this->request->withMethod('DELETE')
            ->withUri(new Uri('/موظفين-المكتبة/1'));
        $response = new Response();

        // Mock PDO statement
        $pdoStatement = $this->createMock(\PDOStatement::class);
        $pdoStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Act
        $response = $this->app->handle($request);

        // Assert
        $this->assertEquals(204, $response->getStatusCode());
    }
}