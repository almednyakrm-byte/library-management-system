<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\MockObject\MockObject;
use App\Repository\DecisionRepository;
use App\Service\DecisionService;
use App\Entity\Decision;

class Testقرارات_الإدارة extends TestCase
{
    private $decisionRepository;
    private $decisionService;
    private $decision;

    protected function setUp(): void
    {
        $this->decisionRepository = $this->createMock(DecisionRepository::class);
        $this->decisionService = $this->createMock(DecisionService::class);
        $this->decision = new Decision();
    }

    public function testGetDecisions()
    {
        $this->decisionRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->decision]);

        $this->decisionService->expects($this->once())
            ->method('getDecisions')
            ->willReturn($this->decisionRepository);

        $client = static::createClient();
        $client->request('GET', '/api/decisions');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateDecision()
    {
        $this->decisionRepository->expects($this->once())
            ->method('save')
            ->with($this->decision);

        $this->decisionService->expects($this->once())
            ->method('createDecision')
            ->willReturn($this->decisionRepository);

        $client = static::createClient();
        $client->request('POST', '/api/decisions', [], [], [], json_encode(['title' => 'Decision title']));

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testUpdateDecision()
    {
        $this->decisionRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($this->decision);

        $this->decisionRepository->expects($this->once())
            ->method('save')
            ->with($this->decision);

        $this->decisionService->expects($this->once())
            ->method('updateDecision')
            ->willReturn($this->decisionRepository);

        $client = static::createClient();
        $client->request('PUT', '/api/decisions/1', [], [], [], json_encode(['title' => 'Updated decision title']));

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testDeleteDecision()
    {
        $this->decisionRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($this->decision);

        $this->decisionRepository->expects($this->once())
            ->method('remove')
            ->with($this->decision);

        $this->decisionService->expects($this->once())
            ->method('deleteDecision')
            ->willReturn($this->decisionRepository);

        $client = static::createClient();
        $client->request('DELETE', '/api/decisions/1');

        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }
}


This test file covers the CRUD API operations for the 'قرارات الإدارة' module. It uses mocked PDO statements to simulate the database interactions. The tests verify that the correct HTTP status codes are returned for each operation.