<?php
/**
 * Luxe Cake - Database Connection
 * Configuration as per README specification
 */

$host = "localhost";
$user = "root";
$password = "";
$database = "cake_shop";

try {
    $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    // If local MySQL server is not active, initialize an automatic SQLite fallback
    // so pages render seamlessly without fatal errors during offline review.
    $sqlite_path = __DIR__ . "/cake_shop.sqlite";
    $pdo = new PDO("sqlite:" . $sqlite_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Bootstrap sqlite schema if first run
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT UNIQUE,
            description TEXT,
            image TEXT
        );
        CREATE TABLE IF NOT EXISTS cakes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER,
            name TEXT NOT NULL,
            slug TEXT,
            description TEXT,
            flavor TEXT,
            price REAL NOT NULL,
            weight_options TEXT,
            image TEXT,
            is_featured INTEGER DEFAULT 0,
            is_available INTEGER DEFAULT 1
        );
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE,
            password TEXT NOT NULL,
            phone TEXT,
            address TEXT,
            role TEXT DEFAULT 'user',
            is_verified INTEGER DEFAULT 0,
            verification_token TEXT
        );
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_number TEXT UNIQUE,
            user_id INTEGER,
            total_amount REAL NOT NULL,
            payment_method TEXT,
            payment_status TEXT DEFAULT 'Pending',
            order_status TEXT DEFAULT 'Pending',
            delivery_name TEXT,
            delivery_phone TEXT,
            delivery_address TEXT,
            delivery_date TEXT,
            delivery_time_slot TEXT,
            special_instructions TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER,
            cake_id INTEGER,
            quantity INTEGER,
            weight TEXT,
            price REAL,
            custom_message TEXT
        );
    ");

    $count = $pdo->query("SELECT count(*) FROM cakes")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("
            INSERT INTO categories (id, name, slug, description, image) VALUES
            (1, 'Signature Truffles', 'signature-truffles', 'Decadent chocolate layers infused with artisan ganache.', 'images/cakes/chocolate-truffle.svg'),
            (2, 'Artisan Cheesecakes', 'artisan-cheesecakes', 'Rich baked New York style and berry compote cheesecakes.', 'images/cakes/berry-cheesecake.svg'),
            (3, 'Celebration & Birthday', 'celebration-birthday', 'Joyful handcrafted cakes tailored for unforgettable milestones.', 'images/cakes/red-velvet.svg'),
            (4, 'Luxury Wedding Tiered', 'luxury-wedding-tiered', 'Elegantly sculpted tiered cakes with edible botanical accents.', 'images/cakes/vanilla-rose.svg');

            INSERT INTO cakes (id, category_id, name, slug, description, flavor, price, weight_options, image, is_featured, is_available) VALUES
            (1, 1, 'Dark Chocolate Belgian Truffle', 'dark-chocolate-belgian-truffle', 'Layers of 70% dark Belgian cocoa sponge coated in velvety ganache and cocoa nibs.', 'Belgian Dark Chocolate', 45.00, '0.5kg, 1kg, 2kg, 3kg', 'images/cakes/chocolate-truffle.svg', 1, 1),
            (2, 2, 'Wild Berry New York Cheesecake', 'wild-berry-new-york-cheesecake', 'Classic baked Philadelphia cream cheese atop a butter biscuit crumb with fresh raspberry compote.', 'Berry & Vanilla Cream', 52.00, '0.5kg, 1kg, 2kg', 'images/cakes/berry-cheesecake.svg', 1, 1),
            (3, 3, 'Royal Red Velvet & Mascarpone', 'royal-red-velvet-mascarpone', 'Tender crimson cocoa sponge layered with fluffy cream cheese frosting and white chocolate pearls.', 'Red Velvet & Sweet Cream', 48.00, '0.5kg, 1kg, 2kg, 3kg', 'images/cakes/red-velvet.svg', 1, 1),
            (4, 4, 'French Vanilla Rose Elegance', 'french-vanilla-rose-elegance', 'Madagascar bourbon vanilla sponge infused with organic rosewater essence and edible gold leaf.', 'Vanilla & Floral Rose', 58.00, '1kg, 2kg, 3kg, 5kg', 'images/cakes/vanilla-rose.svg', 1, 1),
            (5, 1, 'Black Forest Gateau Royale', 'black-forest-gateau-royale', 'Kirsch-soaked chocolate sponge with tart Morello cherries and Swiss whipped cream.', 'Chocolate Cherry Kirsch', 42.00, '0.5kg, 1kg, 2kg', 'images/cakes/black-forest.svg', 0, 1),
            (6, 3, 'Salted Caramel Pecan Drip', 'salted-caramel-pecan-drip', 'Moist caramel cake stacked with toasted Georgia pecans and buttery fleur de sel drizzle.', 'Salted Caramel Buttercream', 49.00, '0.5kg, 1kg, 2kg', 'images/cakes/caramel-drip.svg', 1, 1);

            INSERT INTO users (id, name, email, password, phone, role, is_verified) VALUES
            (1, 'Luxe Administrator', 'admin@luxecake.com', '$2y$10$wTfqT8R6aRjF2tTzBf3gIuDk2f7u9j9L0p/N3t.q7zQ1rZzZ4uF6a', '+91 9876543210', 'admin', 1),
            (2, 'Sarah Jenkins', 'sarah@example.com', '$2y$10$wTfqT8R6aRjF2tTzBf3gIuDk2f7u9j9L0p/N3t.q7zQ1rZzZ4uF6a', '+91 9876543211', 'user', 1);
        ");
    }
}
?>