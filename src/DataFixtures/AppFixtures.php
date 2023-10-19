<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // un utilisateur fictif
        $user = new User();
        $user->setName('jiji brich');
        $user->setEmail('jiji.brich@gmail.com');

        // Hache le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);

        // un article fictif
        $article = new Article();
        $article->setTitre('L\'intelligence artificielle : Une révolution en marche');
        $article->setContenu('L\'intelligence artificielle (IA) est en train de révolutionner le monde de la technologie. De l\'apprentissage automatique à la vision par ordinateur, l\'IA offre des possibilités infinies pour automatiser des tâches, prendre des décisions basées sur des données et améliorer de nombreux domaines, de la médecine à la conduite autonome.');
        $article->setDatePublication(new \DateTime('now'));

        $article1 = new Article();
        $article1->setTitre('Les dernières tendances en matière de cybersécurité');
        $article1->setContenu('Avec la montée en puissance des cybermenaces, la cybersécurité est au cœur des préoccupations. Cet article explore les dernières tendances en matière de cybersécurité, y compris l\'IA pour la détection des menaces, la protection de la vie privée des données et les défis croissants liés à la sécurité des objets connectés.');
        $article1->setDatePublication(new \DateTime('now'));

        $article2 = new Article();
        $article2->setTitre('Le cloud computing : une révolution pour les entreprises');
        $article2->setContenu('Le cloud computing a transformé la manière dont les entreprises gèrent leurs données et leurs infrastructures informatiques. Cet article examine les avantages du cloud, notamment la réduction des coûts, la flexibilité et l\'évolutivité, ainsi que les considérations en matière de sécurité et de confidentialité.');
        $article2->setDatePublication(new \DateTime('now'));
        $article2->setUser($user);

        $manager->persist($user);
        $manager->persist($article);
        $manager->persist($article1);
        $manager->persist($article2);

        $manager->flush();
    }
}
