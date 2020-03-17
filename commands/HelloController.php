<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Category;
use app\models\Menu;
use Faker\Factory;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionInitSeeder()
    {
        $categories = [
            Category::CATEGORY_OTHER,
            Category::CATEGORY_FOOD,
            Category::CATEGORY_PACKAGE,
            Category::CATEGORY_BEVERAGE
        ];

        foreach ($categories as $category) {
            $menu = new Category();
            $menu->name = $category;
            $menu->save();
        }

        echo 'init seeder success';

        return ExitCode::OK;
    }

    public function actionMenuSeeder($category, $quantity)
    {
        $quantity = intval($quantity);

        $faker = Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));

        for ($i = 0; $i < $quantity; $i++) {
            $data = [];

            switch ($category) {
                case Category::CATEGORY_BEVERAGE:
                    $data['name'] = $faker->beverageName();
                    break;
                case Category::CATEGORY_FOOD:
                    $data['name'] = $faker->foodName();
                    break;
                case Category::CATEGORY_OTHER:
                    $data['name'] = $faker->dairyName();
                    break;
            }

            $data = array_merge($data, [
                'category' => $category,
                'price' => $faker->numberBetween(15000, 25000)
            ]);

            $menu = new Menu;

            $menu->attributes = $data;
            $menu->save();
        }

        echo "success generate menu for category: $category with qty: $quantity";
        return ExitCode::OK;
    }
}
