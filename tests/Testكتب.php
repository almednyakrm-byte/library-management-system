<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Testكتب extends TestCase
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

    public function testGetكتب()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM كتب')
            ->willReturn($this->createMock(\PDOStatement::class));

        $كتبController = new كتبController($this->pdo);
        $response = $كتبController->getكتب($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPostكتب()
    {
        $data = ['title' => 'new book', 'author' => 'new author'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO كتب (title, author) VALUES (:title, :author)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $كتبController = new كتبController($this->pdo);
        $response = $كتبController->postكتب($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPutكتب()
    {
        $data = ['id' => 1, 'title' => 'updated book', 'author' => 'updated author'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE كتب SET title = :title, author = :author WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $كتبController = new كتبController($this->pdo);
        $response = $كتبController->putكتب($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDeleteكتب()
    {
        $data = ['id' => 1];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM كتب WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $كتبController = new كتبController($this->pdo);
        $response = $كتبController->deleteكتب($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}