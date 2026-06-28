<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AshkhassController;
use App\Repository\AshkhassRepository;
use App\Service\AshkhassService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestAshkhass extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(AshkhassRepository::class);
        $this->service = $this->createMock(AshkhassService::class);
        $this->controller = new AshkhassController($this->repository, $this->service);
    }

    public function testGetAshkhass()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $response = $this->controller->getAshkhass();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ], json_decode($response->getBody()->getContents(), true));
    }

    public function testPostAshkhass()
    {
        $this->service->expects($this->once())
            ->method('create')
            ->with(['name' => 'John Doe'])
            ->willReturn(1);

        $response = $this->controller->postAshkhass(['name' => 'John Doe']);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(1, json_decode($response->getBody()->getContents(), true));
    }

    public function testPutAshkhass()
    {
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'John Doe'])
            ->willReturn(1);

        $response = $this->controller->putAshkhass(1, ['name' => 'John Doe']);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, json_decode($response->getBody()->getContents(), true));
    }

    public function testDeleteAshkhass()
    {
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $response = $this->controller->deleteAshkhass(1);
        $this->assertEquals(204, $response->getStatusCode());
    }
}



// AshkhassController.php

namespace App\Controller;

use App\Repository\AshkhassRepository;
use App\Service\AshkhassService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AshkhassController
{
    private $repository;
    private $service;

    public function __construct(AshkhassRepository $repository, AshkhassService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function getAshkhass(Request $request)
    {
        $ashkhass = $this->repository->findAll();
        return new JsonResponse($ashkhass, 200);
    }

    public function postAshkhass(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $id = $this->service->create($data);
        return new JsonResponse($id, 201);
    }

    public function putAshkhass(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $this->service->update($id, $data);
        return new JsonResponse($id, 200);
    }

    public function deleteAshkhass(Request $request, $id)
    {
        $this->service->delete($id);
        return new Response('', 204);
    }
}



// AshkhassService.php

namespace App\Service;

use App\Repository\AshkhassRepository;

class AshkhassService
{
    private $repository;

    public function __construct(AshkhassRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        // Create logic here
        return 1;
    }

    public function update($id, array $data)
    {
        // Update logic here
        return 1;
    }

    public function delete($id)
    {
        // Delete logic here
        return true;
    }
}



// AshkhassRepository.php

namespace App\Repository;

class AshkhassRepository
{
    public function findAll()
    {
        // Find all logic here
        return [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ];
    }
}