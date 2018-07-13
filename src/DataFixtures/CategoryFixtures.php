<?php

namespace App\DataFixtures;

use App\Manager\Forum\CategoryManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $groups = [
            'EvE Online',
            'Mac & VIPs'
        ];

        $forums = [
            'EvE Online' => [
                'News & Bullshit' => 'Les meilleures news non vérifiées de tout New Eden...',
                'Le Bar du Jovien Jovial' => 'Ici on parle de tout mais surtout de rien !',
                'Corps & Drama' => 'Des corps, des recruteurs mais surtout du drama !',
                'Wars, Politics & Corpses' => 'Des guerres, des morts et des larmes !',
                'La hutte de Jabba' => 'Ici, on vends, on achète de tout !'
            ],
            'Mac & VIPs' => [
                'Général' => 'Le forum des vrais, des durs !',
                'Draft de sujets' => 'Espace de travail pour les futur sujets/annonces/event'
            ],
            'Le Bar du Jovien Jovial' => [
                'Clinic of Battle' => 'Les fits de vos futurs wrecks',
                'Guides' => '',
                'Carebear Land' => ''
            ],
            'Corps & Drama' => [
                'Recrutement' => ''
            ]
        ];

        $categories = [];

        foreach ($groups as $group) {
            $category = CategoryManager::create($group, '');
            $category->setType('group');

            $manager->persist($category);
            $categories[$category->getName()] = $category;

        }

        foreach ($forums as $parent => $forum) {
            $parent = $categories[$parent];

            foreach ($forum as $name => $description) {
                $category = CategoryManager::create($name, $description);
                $category->setParent($parent);

                $manager->persist($category);
                $categories[$name] = $category;
            }
        }

        $manager->flush();
    }
}
