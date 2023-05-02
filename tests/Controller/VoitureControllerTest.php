<?php

namespace App\Test\Controller;

use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VoitureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private VoitureRepository $repository;
    private string $path = '/v/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Voiture::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voiture index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'voiture[immatriculation]' => 'Testing',
            'voiture[marque]' => 'Testing',
            'voiture[modele]' => 'Testing',
            'voiture[boiteVitesse]' => 'Testing',
            'voiture[kilometrage]' => 'Testing',
            'voiture[carburant]' => 'Testing',
            'voiture[image_voiture]' => 'Testing',
            'voiture[prixLocation]' => 'Testing',
            'voiture[dateValidationTechnique]' => 'Testing',
            'voiture[description]' => 'Testing',
            'voiture[id_user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/v/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setImmatriculation('My Title');
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setBoiteVitesse('My Title');
        $fixture->setKilometrage('My Title');
        $fixture->setCarburant('My Title');
        $fixture->setImage_voiture('My Title');
        $fixture->setPrixLocation('My Title');
        $fixture->setDateValidationTechnique('My Title');
        $fixture->setDescription('My Title');
        $fixture->setId_user('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voiture');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setImmatriculation('My Title');
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setBoiteVitesse('My Title');
        $fixture->setKilometrage('My Title');
        $fixture->setCarburant('My Title');
        $fixture->setImage_voiture('My Title');
        $fixture->setPrixLocation('My Title');
        $fixture->setDateValidationTechnique('My Title');
        $fixture->setDescription('My Title');
        $fixture->setId_user('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'voiture[immatriculation]' => 'Something New',
            'voiture[marque]' => 'Something New',
            'voiture[modele]' => 'Something New',
            'voiture[boiteVitesse]' => 'Something New',
            'voiture[kilometrage]' => 'Something New',
            'voiture[carburant]' => 'Something New',
            'voiture[image_voiture]' => 'Something New',
            'voiture[prixLocation]' => 'Something New',
            'voiture[dateValidationTechnique]' => 'Something New',
            'voiture[description]' => 'Something New',
            'voiture[id_user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/v/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getImmatriculation());
        self::assertSame('Something New', $fixture[0]->getMarque());
        self::assertSame('Something New', $fixture[0]->getModele());
        self::assertSame('Something New', $fixture[0]->getBoiteVitesse());
        self::assertSame('Something New', $fixture[0]->getKilometrage());
        self::assertSame('Something New', $fixture[0]->getCarburant());
        self::assertSame('Something New', $fixture[0]->getImage_voiture());
        self::assertSame('Something New', $fixture[0]->getPrixLocation());
        self::assertSame('Something New', $fixture[0]->getDateValidationTechnique());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getId_user());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Voiture();
        $fixture->setImmatriculation('My Title');
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setBoiteVitesse('My Title');
        $fixture->setKilometrage('My Title');
        $fixture->setCarburant('My Title');
        $fixture->setImage_voiture('My Title');
        $fixture->setPrixLocation('My Title');
        $fixture->setDateValidationTechnique('My Title');
        $fixture->setDescription('My Title');
        $fixture->setId_user('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/v/');
    }
}
