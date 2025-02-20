<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $userRepository;
    private string $path = '/user/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->manager->getRepository(User::class);

        foreach ($this->userRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'user[pseudo]' => 'Testing',
            'user[mail]' => 'Testing',
            'user[password]' => 'Testing',
            'user[PP]' => 'Testing',
            'user[level]' => 'Testing',
            'user[HP]' => 'Testing',
            'user[dateCreate]' => 'Testing',
            'user[lastUpdate]' => 'Testing',
            'user[idteam]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->userRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setPseudo('My Title');
        $fixture->setMail('My Title');
        $fixture->setPassword('My Title');
        $fixture->setPP('My Title');
        $fixture->setLevel('My Title');
        $fixture->setHP('My Title');
        $fixture->setDateCreate('My Title');
        $fixture->setLastUpdate('My Title');
        $fixture->setIdteam('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setPseudo('Value');
        $fixture->setMail('Value');
        $fixture->setPassword('Value');
        $fixture->setPP('Value');
        $fixture->setLevel('Value');
        $fixture->setHP('Value');
        $fixture->setDateCreate('Value');
        $fixture->setLastUpdate('Value');
        $fixture->setIdteam('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user[pseudo]' => 'Something New',
            'user[mail]' => 'Something New',
            'user[password]' => 'Something New',
            'user[PP]' => 'Something New',
            'user[level]' => 'Something New',
            'user[HP]' => 'Something New',
            'user[dateCreate]' => 'Something New',
            'user[lastUpdate]' => 'Something New',
            'user[idteam]' => 'Something New',
        ]);

        self::assertResponseRedirects('/user/');

        $fixture = $this->userRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getPseudo());
        self::assertSame('Something New', $fixture[0]->getMail());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getPP());
        self::assertSame('Something New', $fixture[0]->getLevel());
        self::assertSame('Something New', $fixture[0]->getHP());
        self::assertSame('Something New', $fixture[0]->getDateCreate());
        self::assertSame('Something New', $fixture[0]->getLastUpdate());
        self::assertSame('Something New', $fixture[0]->getIdteam());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setPseudo('Value');
        $fixture->setMail('Value');
        $fixture->setPassword('Value');
        $fixture->setPP('Value');
        $fixture->setLevel('Value');
        $fixture->setHP('Value');
        $fixture->setDateCreate('Value');
        $fixture->setLastUpdate('Value');
        $fixture->setIdteam('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/user/');
        self::assertSame(0, $this->userRepository->count([]));
    }
}
