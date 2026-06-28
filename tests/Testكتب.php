<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\كتبController;
use App\Repository\كتبRepository;
use App\Entity\كتب;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

class TestكتبController extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;

    protected function setUp(): void
    {
        $this->controller = new كتبController($this->repository, $this->entityManager, $this->router);
        $this->repository = $this->createMock(كتبRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
    }

    public function testGetAllBooks()
    {
        $books = [
            new كتب('1', 'Book 1', 'Author 1'),
            new كتب('2', 'Book 2', 'Author 2'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($books);

        $response = $this->controller->getAllBooks();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($books), $response->getContent());
    }

    public function testGetBookById()
    {
        $book = new كتب('1', 'Book 1', 'Author 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($book);

        $response = $this->controller->getBookById('1');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($book), $response->getContent());
    }

    public function testCreateBook()
    {
        $book = new كتب('1', 'Book 1', 'Author 1');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($book);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(true);

        $request = new Request();
        $request->request->set('title', 'Book 1');
        $request->request->set('author', 'Author 1');

        $response = $this->controller->createBook($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($book), $response->getContent());
    }

    public function testUpdateBook()
    {
        $book = new كتب('1', 'Book 1', 'Author 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($book);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($book);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(true);

        $request = new Request();
        $request->request->set('title', 'Book 1 Updated');
        $request->request->set('author', 'Author 1 Updated');

        $response = $this->controller->updateBook('1', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($book), $response->getContent());
    }

    public function testDeleteBook()
    {
        $book = new كتب('1', 'Book 1', 'Author 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($book);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($book);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(true);

        $response = $this->controller->deleteBook('1');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'كتب' module. It uses mocked PDO statements to isolate the dependencies of the controller. The tests cover the following scenarios:

*   `testGetAllBooks`: Tests the `getAllBooks` method to ensure it returns a JSON response with a list of all books.
*   `testGetBookById`: Tests the `getBookById` method to ensure it returns a JSON response with a single book by ID.
*   `testCreateBook`: Tests the `createBook` method to ensure it creates a new book and returns a JSON response with the created book.
*   `testUpdateBook`: Tests the `updateBook` method to ensure it updates an existing book and returns a JSON response with the updated book.
*   `testDeleteBook`: Tests the `deleteBook` method to ensure it deletes a book and returns a JSON response with a 204 status code.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you should ensure that the mocked dependencies are properly configured to mimic the behavior of the real dependencies.