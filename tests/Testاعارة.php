<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Testاعارة extends TestCase
{
    private $pdo;
    private $request;
    private $response;
    private $stream;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
    }

    public function testGetAllاعارة()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM اعارة')
            ->willReturn($this->pdo);

        $this->pdo->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'test'],
                ['id' => 2, 'name' => 'test2'],
            ]);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode([
                ['id' => 1, 'name' => 'test'],
                ['id' => 2, 'name' => 'test2'],
            ]));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->request->expects($this->once())
            ->method('getUri')
            ->willReturn('/اعارة');

        $controller = new اعارةController($this->pdo);
        $response = $controller->getAllاعارة($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetاعارةById()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM اعارة WHERE id = :id')
            ->willReturn($this->pdo);

        $this->pdo->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdo->expects($this->once())
            ->method('execute')
            ->willReturn($this->pdo);

        $this->pdo->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'test']);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 1, 'name' => 'test']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->request->expects($this->once())
            ->method('getUri')
            ->willReturn('/اعارة/1');

        $controller = new اعارةController($this->pdo);
        $response = $controller->getاعارةById($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateاعارة()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO اعارة (name) VALUES (:name)')
            ->willReturn($this->pdo);

        $this->pdo->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'test');

        $this->pdo->expects($this->once())
            ->method('execute')
            ->willReturn($this->pdo);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 1, 'name' => 'test']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(201);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'test']);

        $this->request->expects($this->once())
            ->method('getUri')
            ->willReturn('/اعارة');

        $controller = new اعارةController($this->pdo);
        $response = $controller->createاعارة($this->request, $this->response);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateاعارة()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE اعارة SET name = :name WHERE id = :id')
            ->willReturn($this->pdo);

        $this->pdo->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdo->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'test');

        $this->pdo->expects($this->once())
            ->method('execute')
            ->willReturn($this->pdo);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 1, 'name' => 'test']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');

        $this->request->expects($this->once())
            ->method('getUri')
            ->willReturn('/اعارة/1');

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'test']);

        $controller = new اعارةController($this->pdo);
        $response = $controller->updateاعارة($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteاعارة()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM اعارة WHERE id = :id')
            ->willReturn($this->pdo);

        $this->pdo->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdo->expects($this->once())
            ->method('execute')
            ->willReturn($this->pdo);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(204);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $this->request->expects($this->once())
            ->method('getUri')
            ->willReturn('/اعارة/1');

        $controller = new اعارةController($this->pdo);
        $response = $controller->deleteاعارة($this->request, $this->response);

        $this->assertEquals(204, $response->getStatusCode());
    }
}