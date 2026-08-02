<?php

declare(strict_types=1);

/**
 * Seeds the JSON storage with demo kitchens and menu items on first run.
 */
final class Seeder
{
    public function __construct(private Storage $db) {}

    public function seedIfEmpty(): void
    {
        if ($this->db->all('kitchens')) return;

        $kitchens = [
            [
                'name'        => 'Burger Bros Moyale',
                'cuisine'     => 'American',
                'description' => 'Smash burgers, loaded fries, and thick shakes. Cooked to order over an open flame.',
                'image'       => 'https://images.pexels.com/photos/12653336/pexels-photo-12653336.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
                'active'      => true,
            ],
            [
                'name'        => 'Sakura Sushi Nairobi',
                'cuisine'     => 'Japanese',
                'description' => 'Fresh nigiri, maki rolls, and sashimi prepared daily by our itamae.',
                'image'       => 'https://images.pexels.com/photos/11470545/pexels-photo-11470545.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
                'active'      => true,
            ],
            [
                'name'        => 'Forno Vivo Rwanda',
                'cuisine'     => 'Italian',
                'description' => 'Wood-fired Neapolitan pizzas and fresh pasta from our brick oven.',
                'image'       => 'https://images.pexels.com/photos/18126737/pexels-photo-18126737.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
                'active'      => true,
            ],
        ];

        $menu = [
            'Burger Bros' => [
                ['Classic Smash Burger', 'Two beef patties, American cheese, pickles, house sauce.', 9.50, 'https://images.pexels.com/photos/4571510/pexels-photo-4571510.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Burgers'],
                ['Loaded Fries', 'Crispy fries topped with cheese sauce, bacon bits and chives.', 5.00, 'https://images.pexels.com/photos/7786563/pexels-photo-7786563.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Sides'],
                ['Iced Vanilla Latte', 'Double espresso shaken with milk and vanilla over ice.', 4.25, 'https://images.pexels.com/photos/18142624/pexels-photo-18142624.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Drinks'],
            ],
            'Sakura Sushi' => [
                ['Assorted Nigiri Platter', 'Chef selection of 8 nigiri pieces with fresh wasabi.', 18.00, 'https://images.pexels.com/photos/7719911/pexels-photo-7719911.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Sushi'],
                ['Maki Roll Set', 'Six pieces of California and spicy tuna rolls.', 11.50, 'https://images.pexels.com/photos/2098143/pexels-photo-2098143.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Sushi'],
                ['Garden Side Salad', 'Mixed greens, cherry tomato, ginger dressing.', 6.00, 'https://images.pexels.com/photos/4887993/pexels-photo-4887993.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Sides'],
            ],
            'Forno Vivo' => [
                ['Margherita Pizza', 'San Marzano tomato, fresh mozzarella, basil, olive oil.', 13.00, 'https://images.pexels.com/photos/13366357/pexels-photo-13366357.png?auto=compress&cs=tinysrgb&h=650&w=940', 'Pizza'],
                ['Wood-Fired Special', 'Spicy salami, mozzarella, chili honey drizzle.', 15.50, 'https://images.pexels.com/photos/29626982/pexels-photo-29626982.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Pizza'],
                ['Chocolate Lava Cake', 'Warm dark chocolate cake with a molten centre.', 7.00, 'https://images.pexels.com/photos/3081657/pexels-photo-3081657.jpeg?auto=compress&cs=tinysrgb&h=650&w=940', 'Desserts'],
            ],
        ];

        $kitchenMap = [];
        foreach ($kitchens as $k) {
            $row = $this->db->insert('kitchens', $k);
            $kitchenMap[$k['name']] = $row['id'];
        }

        foreach ($menu as $kitchenName => $items) {
            $kId = $kitchenMap[$kitchenName];
            foreach ($items as [$name, $desc, $price, $image, $cat]) {
                $this->db->insert('menu_items', [
                    'kitchen_id'  => $kId,
                    'name'        => $name,
                    'description' => $desc,
                    'price'       => $price,
                    'image'       => $image,
                    'category'    => $cat,
                    'available'   => true,
                ]);
            }
        }
    }
}
