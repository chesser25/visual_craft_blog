<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private $categoriesNames;
    public function __construct()
    {
        $this->categoriesNames = [
            'Php', 'C#', 'Perl', 'Go', 'Python',
            'Ruby', 'Rust', 'C++', 'JS', 'Java'
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->categoriesNames as $categoryName){
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
