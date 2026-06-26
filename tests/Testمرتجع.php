<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مرتجعController;
use App\Repository\مرتجعRepository;
use App\Entity\مرتجع;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testمرتجع extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(مرتجعRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new مرتجعController($this->repository, $this->entityManager);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOneNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getOne(1);
    }

    public function testCreate()
    {
        $expectedResponse = ['data' => []];
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(مرتجع::class));
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn($expectedResponse['data']);

        $request = new Request();
        $request->request->set('name', 'مرتجع');
        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse['data']);
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn($expectedResponse['data']);

        $request = new Request();
        $request->request->set('name', 'مرتجع');
        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'مرتجع');
        $this->controller->update(1, $request);
    }

    public function testDelete()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse['data']);
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf(مرتجع::class));
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->delete(1);
    }
}