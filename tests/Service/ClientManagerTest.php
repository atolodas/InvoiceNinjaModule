<?php
declare(strict_types=1);

namespace InvoiceNinjaModuleTest\Service;

use InvoiceNinjaModule\Exception\NotFoundException;
use InvoiceNinjaModule\Model\Interfaces\BaseInterface;
use InvoiceNinjaModule\Model\Interfaces\ClientInterface;
use InvoiceNinjaModule\Service\ClientManager;
use InvoiceNinjaModule\Service\Interfaces\ClientManagerInterface;
use InvoiceNinjaModule\Service\Interfaces\ObjectServiceInterface;
use PHPUnit\Framework\TestCase;

class ClientManagerTest extends TestCase
{
    /** @var  ClientManager */
    private $clientManager;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $objectManagerMock;

    protected function setUp() : void
    {
        parent::setUp();

        $this->objectManagerMock = $this->createMock(ObjectServiceInterface::class);
        $this->clientManager = new ClientManager($this->objectManagerMock);
    }

    public function testCreate() : void
    {
        self::assertInstanceOf(ClientManagerInterface::class, $this->clientManager);
    }

    public function testCreateClient() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('createObject')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients')
            )
            ->willReturn($clientMock);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInstanceOf(ClientInterface::class, $this->clientManager->createClient($clientMock));
    }

    public function testDelete() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('deleteObject')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients')
            )
            ->willReturn($clientMock);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInstanceOf(ClientInterface::class, $this->clientManager->delete($clientMock));
    }


    public function testGetClientById() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('getObjectById')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::isType('integer'),
                self::stringContains('/clients')
            )
            ->willReturn($clientMock);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInstanceOf(ClientInterface::class, $this->clientManager->getClientById(777));
    }

    /**
     * @expectedException  \InvoiceNinjaModule\Exception\NotFoundException
     */
    public function testGetClientByIdException() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('getObjectById')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::isType('integer'),
                self::stringContains('/clients')
            )
            ->willThrowException(new NotFoundException());

        self::assertInstanceOf(ClientInterface::class, $this->clientManager->getClientById(777));
    }

    public function testFindClientsByEmail() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('findObjectBy')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::isType('array'),
                self::stringContains('/clients')
            )
            ->willReturn([$clientMock]);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        $result = $this->clientManager->findClientsByEmail('test@test.com');
        self::assertInternalType('array', $result);
        self::assertNotEmpty($result);
    }

    public function testFindClientsByIdNumber() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('findObjectBy')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::isType('array'),
                self::stringContains('/clients')
            )
            ->willReturn([$clientMock]);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        $result = $this->clientManager->findClientsByIdNumber('12343');
        self::assertInternalType('array', $result);
        self::assertNotEmpty($result);
    }


    public function testUpdate() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('updateObject')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients')
            )
            ->willReturn($clientMock);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInstanceOf(ClientInterface::class, $this->clientManager->update($clientMock));
    }

    public function testRestore() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('restoreObject')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients')
            )
            ->willReturn($clientMock);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInstanceOf(ClientInterface::class, $this->clientManager->restore($clientMock));
    }

    public function testArchive() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('archiveObject')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients')
            )
            ->willReturn($clientMock);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInstanceOf(ClientInterface::class, $this->clientManager->archive($clientMock));
    }

    public function testGetAllClientsEmpty() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('getAllObjects')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients'),
                self::isType('integer'),
                self::isType('integer')
            )
            ->willReturn([]);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInternalType('array', $this->clientManager->getAllClients());
    }

    public function testGetAllClients() : void
    {
        $clientMock = $this->createMock(ClientInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('getAllObjects')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients'),
                self::isType('integer'),
                self::isType('integer')
            )
            ->willReturn([ 'test' => $clientMock ]);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInternalType('array', $this->clientManager->getAllClients());
    }

    /**
     * @expectedException \InvoiceNinjaModule\Exception\InvalidResultException
     */
    public function testGetAllClientsOtherResult() : void
    {
        $clientMock = $this->createMock(BaseInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('getAllObjects')
            ->with(
                self::isInstanceOf(ClientInterface::class),
                self::stringContains('/clients'),
                self::isType('integer'),
                self::isType('integer')
            )
            ->willReturn([ 'test' => $clientMock ]);

        $this->clientManager = new ClientManager($this->objectManagerMock);

        self::assertInternalType('array', $this->clientManager->getAllClients());
    }
}
