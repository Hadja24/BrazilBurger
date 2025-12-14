-- =========================
-- ENUM TYPES
-- =========================

CREATE TYPE role_enum AS ENUM (
    'CUSTOMER',
    'MANAGER',
    'DELIVERY_GUY'
);

CREATE TYPE order_state_enum AS ENUM (
    'PENDING',
    'FINISHED',
    'CANCELLED'
);

CREATE TYPE payment_mode_enum AS ENUM (
    'WAVE',
    'ORANGE_MONEY'
);

CREATE TYPE reception_type_enum AS ENUM (
    'DELIVERY',
    'AT_THE_RESTAURANT',
    'PICK_UP'
);

CREATE TYPE neighbourhood_enum AS ENUM (
    'A', 'B', 'C', 'D', 'E', 'F'
);

-- =========================
-- ACCOUNT
-- =========================

CREATE TABLE account
(
    id       SERIAL PRIMARY KEY,
    name     VARCHAR(100)        NOT NULL,
    surname  VARCHAR(100)        NOT NULL,
    phone    VARCHAR(30) UNIQUE  NOT NULL,
    email    VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255)        NOT NULL,
    role     role_enum           NOT NULL
);

-- =========================
-- CUSTOMER / MANAGER / DELIVERY GUY
-- =========================

CREATE TABLE customer
(
    id         SERIAL PRIMARY KEY,
    account_id INTEGER UNIQUE
        REFERENCES account (id)
            ON DELETE CASCADE
);

CREATE TABLE manager
(
    id         SERIAL PRIMARY KEY,
    account_id INTEGER UNIQUE
        REFERENCES account (id)
            ON DELETE CASCADE
);

CREATE TABLE delivery_guy
(
    id         SERIAL PRIMARY KEY,
    account_id INTEGER UNIQUE
        REFERENCES account (id)
            ON DELETE CASCADE
);

-- =========================
-- BURGER (STOCK)
-- =========================

CREATE TABLE burger
(
    id        SERIAL PRIMARY KEY,
    name      VARCHAR(100)     NOT NULL,
    price     DOUBLE PRECISION NOT NULL,
    image_url TEXT,
    quantity  INTEGER          NOT NULL CHECK (quantity >= 0),
    archived  BOOLEAN DEFAULT FALSE
);

-- =========================
-- EXTRA
-- =========================

CREATE TABLE extra
(
    id        SERIAL PRIMARY KEY,
    name      VARCHAR(100)     NOT NULL,
    price     DOUBLE PRECISION NOT NULL,
    image_url TEXT,
    archived  BOOLEAN DEFAULT FALSE
);

-- =========================
-- MENU (STOCK)
-- =========================

CREATE TABLE menu
(
    id        SERIAL PRIMARY KEY,
    name      VARCHAR(100)     NOT NULL,
    price     DOUBLE PRECISION NOT NULL,
    image_url TEXT,
    quantity  INTEGER          NOT NULL CHECK (quantity >= 0),
    archived  BOOLEAN DEFAULT FALSE
);

-- =========================
-- MENU ↔ BURGER (COMPOSITION)
-- =========================

CREATE TABLE menu_burger
(
    menu_id   INTEGER REFERENCES menu (id) ON DELETE CASCADE,
    burger_id INTEGER REFERENCES burger (id) ON DELETE CASCADE,
    PRIMARY KEY (menu_id, burger_id)
);

-- =========================
-- ZONE
-- =========================

CREATE TABLE zone
(
    id             SERIAL PRIMARY KEY,
    delivery_price DOUBLE PRECISION NOT NULL
);

-- =========================
-- ZONE ↔ NEIGHBOURHOOD
-- =========================

CREATE TABLE zone_neighbourhood
(
    zone_id       INTEGER REFERENCES zone (id) ON DELETE CASCADE,
    neighbourhood neighbourhood_enum NOT NULL,
    PRIMARY KEY (zone_id, neighbourhood)
);

-- =========================
-- ORDER
-- =========================

CREATE TABLE orders
(
    id             SERIAL PRIMARY KEY,
    total_price    DOUBLE PRECISION    NOT NULL,
    order_date     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    customer_id    INTEGER REFERENCES customer (id),
    extra_id       INTEGER REFERENCES extra (id),
    order_state    order_state_enum    NOT NULL,
    reception_type reception_type_enum NOT NULL,
    zone_id        INTEGER REFERENCES zone (id)
);

-- =========================
-- ORDER ↔ BURGER (QUANTITY ORDERED)
-- =========================

CREATE TABLE order_burger
(
    order_id  INTEGER REFERENCES orders (id) ON DELETE CASCADE,
    burger_id INTEGER REFERENCES burger (id),
    quantity  INTEGER NOT NULL CHECK (quantity > 0),
    PRIMARY KEY (order_id, burger_id)
);

-- =========================
-- ORDER ↔ MENU (QUANTITY ORDERED)
-- =========================

CREATE TABLE order_menu
(
    order_id INTEGER REFERENCES orders (id) ON DELETE CASCADE,
    menu_id  INTEGER REFERENCES menu (id),
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    PRIMARY KEY (order_id, menu_id)
);

-- =========================
-- PAYMENT (1 PAYMENT = 1 ORDER)
-- =========================

CREATE TABLE payment
(
    id           SERIAL PRIMARY KEY,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount       DOUBLE PRECISION  NOT NULL,
    mode         payment_mode_enum NOT NULL,
    order_id     INTEGER UNIQUE
        REFERENCES orders (id)
            ON DELETE CASCADE
);