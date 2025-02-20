<?php

namespace App\Tests\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TeamControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $teamRepository;
    private string $path = '/team/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->teamRepository = $this->manager->getRepository(Team::class);

        foreach ($this->teamRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Team index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'team[teamName]' => 'Testing',
            'team[dateCreate]' => 'Testing',
            'team[dateUpdate]' => 'Testing',
            'team[point]' => 'Testing',
            'team[userAdd]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->teamRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Team();
        $fixture->setTeamName('My Title');
        $fixture->setDateCreate('My Title');
        $fixture->setDateUpdate('My Title');
        $fixture->setPoint('My Title');
        $fixture->setUserAdd('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Team');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Team();
        $fixture->setTeamName('Value');
        $fixture->setDateCreate('Value');
        $fixture->setDateUpdate('Value');
        $fixture->setPoint('Value');
        $fixture->setUserAdd('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'team[teamName]' => 'Something New',
            'team[dateCreate]' => 'Something New',
            'team[dateUpdate]' => 'Something New',
            'team[point]' => 'Something New',
            'team[userAdd]' => 'Something New',
        ]);

        self::assertResponseRedirects('/team/');

        $fixture = $this->teamRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTeamName());
        self::assertSame('Something New', $fixture[0]->getDateCreate());
        self::assertSame('Something New', $fixture[0]->getDateUpdate());
        self::assertSame('Something New', $fixture[0]->getPoint());
        self::assertSame('Something New', $fixture[0]->getUserAdd());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Team();
        $fixture->setTeamName('Value');
        $fixture->setDateCreate('Value');
        $fixture->setDateUpdate('Value');
        $fixture->setPoint('Value');
        $fixture->setUserAdd('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/team/');
        self::assertSame(0, $this->teamRepository->count([]));
    }
}
