<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\CookingBlog;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CookingBlogFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test@test.te');
        $password = $this->passwordHasher->hashPassword($user1, '1234');
        $user1->setPassword($password);
        $user1->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('rooy.marie@gmail.com');
        $password = $this->passwordHasher->hashPassword($user2, 'mitchoune');
        $user2->setPassword($password);
        $user2->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $manager->persist($user2);


        $blog = new CookingBlog;
        $blog->setTitle("Gâteau au chocolat");
        $blog->setIngredients("Beurre, oeufs, farine, sucre, chocolat");
        $blog->setSteps("1- Mélanger le beurre fondu avec le chocolat fondu. 2- Mélanger la farine, le sucre et les oeufs. 3- Mélanger le tout");
        $blog->setDescription("Recette de gâteau au chocolat.");
        $blog->setUser($user1);
        $manager->persist($blog);

        $manager->flush();
    }
}
