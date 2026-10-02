<?php
// Simple seeding script: run via browser or CLI once
// Example: http://localhost/Food_ordering_web-main/backend/php/seed.php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Food.php';

$db = (new Database())->getConnection();

function out($m){ echo htmlspecialchars($m) . "<br>\n"; }

try {
    // Users
    $userModel = new User($db);
    $adminEmail = 'admin@example.com';
    $demoEmail  = 'demo@example.com';

    // Create admin if not exists
    $stmt = $db->prepare('SELECT id,is_admin FROM users WHERE email=?');
    $stmt->execute([$adminEmail]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$admin){
        $hash = password_hash('secret123', PASSWORD_BCRYPT);
        $db->prepare('INSERT INTO users (name,email,password_hash,is_admin) VALUES (?,?,?,1)')->execute(['Admin User',$adminEmail,$hash]);
        out('Created admin user admin@example.com / secret123');
    } else if(!$admin['is_admin']) {
        $db->prepare('UPDATE users SET is_admin=1 WHERE id=?')->execute([$admin['id']]);
        out('Promoted existing user to admin: '.$adminEmail);
    } else {
        out('Admin user already present');
    }

    // Demo user
    $stmt->execute([$demoEmail]);
    $demo = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$demo){
        $hash = password_hash('secret123', PASSWORD_BCRYPT);
        $db->prepare('INSERT INTO users (name,email,password_hash,is_admin) VALUES (?,?,?,0)')->execute(['Demo User',$demoEmail,$hash]);
        out('Created demo user demo@example.com / secret123');
    } else {
        out('Demo user already present');
    }

    // Categories (idempotent upsert by slug)
    $categories = [
        ['name'=>'Pizza','slug'=>'pizza'],
        ['name'=>'Burger','slug'=>'burger'],
        ['name'=>'Sandwich','slug'=>'sandwich'],
        ['name'=>'Drinks','slug'=>'drinks'],
        ['name'=>'Dessert','slug'=>'dessert'],
    ];
    foreach($categories as $c){
        $stmt = $db->prepare('SELECT id FROM categories WHERE slug=?');
        $stmt->execute([$c['slug']]);
        if(!$stmt->fetch()){
            $db->prepare('INSERT INTO categories (name,slug) VALUES(?,?)')->execute([$c['name'],$c['slug']]);
            out('Inserted category: '.$c['name']);
        }
    }

    // Build category map
    $catMap = [];
    foreach($db->query('SELECT id,slug FROM categories') as $row){ $catMap[$row['slug']] = $row['id']; }

    // Foods (avoid duplicates by name)
    $foods = [
        ['slug'=>'pizza','name'=>'Cheese Pizza','price'=>950.00,'desc'=>'Classic mozzarella cheese pizza','img'=>'img/food/p1.jpg'],
        ['slug'=>'pizza','name'=>'Spicy Veg Pizza','price'=>1050.00,'desc'=>'Veggies with heat','img'=>'img/food/p1.jpg'],
        ['slug'=>'burger','name'=>'Classic Beef Burger','price'=>600.00,'desc'=>'Juicy grilled beef patty','img'=>'img/food/b1.jpg'],
        ['slug'=>'burger','name'=>'Double Stack Burger','price'=>850.00,'desc'=>'Two patties, double cheese','img'=>'img/food/b1.jpg'],
        ['slug'=>'sandwich','name'=>'Club Sandwich','price'=>320.00,'desc'=>'Triple layer classic','img'=>'img/food/s1.jpg'],
        ['slug'=>'sandwich','name'=>'Veg Sandwich','price'=>220.00,'desc'=>'Fresh veggies & spread','img'=>'img/food/s1.jpg'],
        ['slug'=>'drinks','name'=>'Cola 500ml','price'=>120.00,'desc'=>'Chilled fizzy drink','img'=>'img/food/p1.jpg'],
        ['slug'=>'dessert','name'=>'Chocolate Brownie','price'=>300.00,'desc'=>'Rich and gooey','img'=>'img/food/p1.jpg'],
    ];

    $checkFood = $db->prepare('SELECT id FROM foods WHERE name=?');
    $insFood = $db->prepare('INSERT INTO foods (category_id,name,price,description,image) VALUES (?,?,?,?,?)');
    foreach($foods as $f){
        $checkFood->execute([$f['name']]);
        if(!$checkFood->fetch()){
            $catId = $catMap[$f['slug']] ?? null;
            $insFood->execute([$catId,$f['name'],$f['price'],$f['desc'],$f['img']]);
            out('Inserted food: '.$f['name']);
        }
    }

    out('Seeding complete.');
} catch (Exception $e) {
    out('Error: '.$e->getMessage());
}
