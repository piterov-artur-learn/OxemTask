<?php

error_reporting(E_ALL ^ E_WARNING);

class Farm
{
    /*
     * Вложенный массив всех животных
     * Должен выглядеть вот так:
     * [
     *      Животное,
     *      Животное2,
     *      Животное3
     *      ...
     * ]
     */
    public array $allAnimals = [];
    public array $allProducts = [];

    // Возвращает массив всех животных
    public function getAllAnimals(): array
    {
        return $this->allAnimals;
    }

    // Возвращает массив всех продуктов
    public function getAllProducts(): array
    {
        return $this->allProducts;
    }

    // Добавляет одно или нескольких животных
    public function addAnimals(Animal ...$animals)
    {
        foreach ($animals as $animal) {
            if ($animal instanceof Animal) {
                $this->allAnimals[$animal->id] = $animal;
            }
        }
    }

    // Активирует один день работы на ферме
    public function workOneDay()
    {
        foreach ($this->allAnimals as $animal) {
            $this->allProducts[$animal->productName][] = $animal->workDay();
        }
    }

    // Собирает и проверяет количество всех продуктов
    public function showProductsQuantity()
    {
        echo "Количество продуктов:<br>";
        foreach ($this->allProducts as $key => $products) {
            $sum = 0;
            foreach ($products as $product) {
                $sum += $product;
            }
            echo $key . ": $sum<br>";
        }
    }

    // Проверяет количество каждого типа животных
    public function showAnimalQuantity()
    {
        echo "Количество животных:<br>";
        $sumOfAnimals = [];
        foreach ($this->allAnimals as $animal) {
            $sumOfAnimals[$animal->nameOfAnimal] += 1;
        }
        foreach ($sumOfAnimals as $animal => $sum) {
            echo $animal . ": $sum<br>";
        }
    }

    // Показывает информацию о животном по его уникальному id
    public function showInfoById(string $id)
    {
        $animalToShow = null;
        foreach ($this->allAnimals as $key => $animal) {
            if ($key == $id) {
                $animalToShow = $animal;
            }
        }
        if ($animalToShow instanceof Animal) {
            echo "Информация о животном по ID $animalToShow->id<br>"
                . "Животное: $animalToShow->nameOfAnimal<br>"
                . "Продукт: $animalToShow->productName<br>"
                . "Готовых продуктов за сегодня: $animalToShow->productQuantity<br>";
        }
    }
}

// Абстрактный класс, описывающий общие характеристики животных на ферме
abstract class Animal
{
    public string $id;
    public int $productQuantity = 0;
    public string $nameOfAnimal;
    public string $productName;

    abstract public function workDay(): int;
}

class Cow extends Animal
{
    // Конструктор, который инициализирует животное
    public function __construct()
    {
        $this->nameOfAnimal = "Корова";
        $this->productName = "Молоко";
        $this->id = uniqid("$this->nameOfAnimal-", true); // uniqid задает уникальный id животного
    }

    // Работать один день
    public function workDay(): int
    {
        $productsQuantity = random_int(8, 12);
        $this->productQuantity = $productsQuantity;
        return $productsQuantity;
    }
}

class Hen extends Animal
{
    // Конструктор, который инициализирует животное
    public function __construct()
    {
        $this->nameOfAnimal = "Курица";
        $this->productName = "Яйца";
        $this->id = uniqid("$this->nameOfAnimal-", true); // uniqid задает уникальный id животного
    }

    // Работать один день
    public function workDay(): int
    {
        $productsQuantity = random_int(0, 1);
        $this->productQuantity = $productsQuantity;
        return $productsQuantity;
    }
}

// Создаем ферму
$farm = new Farm();

// Добавляем 10 коров
for ($i = 0; $i < 10; $i++) {
    $farm->addAnimals(new Cow());
}

// Добавляем 20 кур
for ($i = 0; $i < 20; $i++) {
    $farm->addAnimals(new Hen());
}

// Проверяем количество каждого типа животных
$farm->showAnimalQuantity();

// Работаем 7 дней
for ($i = 0; $i <= 7; $i++) {
    $farm->workOneDay();
}

// Получаем количество собранных продуктов
$farm->showProductsQuantity();

// Добавляем ещё 5 кур
for ($i = 0; $i < 5; $i++) {
    $farm->addAnimals(new Hen());
}

// Добавляем одну корову
$farm->addAnimals(new Cow());

// Работаем ещё 7 дней
for ($i = 0; $i <= 7; $i++) {
    $farm->workOneDay();
}

// Получаем количество собранных продуктов
$farm->showProductsQuantity();

// Дополнительно: получаем информацию о животном по его ID
$animalsIds = []; // Массив, который будет содержать ID всех животных
foreach ($farm->allAnimals as $key => $animal) { // Перебираем всех животных и добавляем их ID в массив
    $animalsIds[] = $key;
}

$farm->showInfoById($animalsIds[3]);