<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ موظفينController;
use App\Repository\موظفينRepository;
use App\Entity\موظفين;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\PersistentCollection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testموظفين extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $unitOfWork;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MوظفينRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->unitOfWork = $this->createMock(UnitOfWork::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->entityManager->method('getUnitOfWork')->willReturn($this->unitOfWork);

        $this->controller = new موظفينController($this->entityManager);
    }

    public function testGetAll()
    {
        $this->repository->method('findAll')->willReturn([
            new موظفين(),
            new موظفين(),
        ]);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne()
    {
        $id = 1;
        $this->repository->method('find')->willReturn(new موظفين());

        $response = $this->controller->getOne($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOneNotFound()
    {
        $id = 1;
        $this->repository->method('find')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getOne($id);
    }

    public function testCreate()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->repository->method('save')->willReturn(new موظفين());

        $response = $this->controller->create($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate()
    {
        $id = 1;
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $this->repository->method('find')->willReturn(new موظفين());
        $this->repository->method('save')->willReturn(new موظفين());

        $response = $this->controller->update($id, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateNotFound()
    {
        $id = 1;
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $this->repository->method('find')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->update($id, $data);
    }

    public function testDelete()
    {
        $id = 1;

        $this->repository->method('find')->willReturn(new موظفين());
        $this->repository->method('remove')->willReturn(null);

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $id = 1;

        $this->repository->method('find')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->delete($id);
    }
}