<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    const POSTS_COUNT = 10;
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < self::POSTS_COUNT; $i++){
            $post = new Post();
            $post->setTitle(sprintf('PHP tutorial %s', $i));
            $post->setBody('Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
              scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
              remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
              and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');
            $manager->persist($post);
        }
        $manager->flush();
    }
}
