<?php

// Из-за key который отображается как Deprecated (я не знаю как по другому проверить верность массива :) )
error_reporting(E_ALL ^ E_DEPRECATED);

class Farm
{
    /*
     * Вложенный массив всех животных
     * Должен выглядеть вот так:
     * [
     *      "ИМЯ_ЖИВОТНОГО" => [
     *          Животное,
     *          Животное
     *          ...
     *      ],
     *       "ИМЯ_ЖИВОТНОГО_2" => [
     *          Животное_2,
     *          Животное_2
     *          ...
     *      ],
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

    // Заменяет массив животных на указаный в аргументе
    /*
     * Пример работы:
     *   $farm->setAllAnimals([
     *       "Корова" => [
     *           new Cow(),
     *           new Cow(),
     *       ],
     *       "Курица" => [
     *           new Hen(),
     *       ],
     *   ]);
     *
     */
    public function setAllAnimals(array $allAnimals): void
    {
        // Проверка, верно ли указан массив
        foreach ($allAnimals as $animals) {
            if (!empty(key($animals))) {
                foreach ($animals as $animal) {
                    try {
                        echo key($animal);
                    } catch (TypeError) {
                        echo "Неверно передан массив.";
                        exit();
                    }
                }
            }
        }

        // Проверка, являются ли элементы в массиве животными
        $flag = false;
        foreach ($allAnimals as $animals) {
            foreach ($animals as $animal) {
                if ($animal instanceof Animal) $flag = true;
                else $flag = false;
            }
        }

        // Замена массива, если массив прошел проверки
        if ($flag) $this->allAnimals = $allAnimals;
        else echo "Объекты в массиве не являются животными.";
    }

    // Добавляет одно животное
    public function addAnimal(Animal $animal)
    {
        $this->allAnimals[$animal->nameOfAnimal][] = $animal;
    }

    // Активирует один день работы на ферме
    public function workOneDay()
    {
        foreach ($this->allAnimals as $animals) {
            foreach ($animals as $animal) {
                if ($animal instanceof Animal) {
                    $this->allProducts[$animal->productName][] = $animal->workDay();
                }
            }
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
        foreach ($this->allAnimals as $animals) {
            $sum = 0;
            foreach ($animals as $ignored) {
                $sum++;
            }
            echo $animals[0]->nameOfAnimal . ": $sum<br>";
        }
    }

    // Показывает информацию о животном по его уникальному id
    public function showInfoById(string $id)
    {
        $animalToShow = null;
        foreach ($this->allAnimals as $animals) {
            foreach ($animals as $animal) {
                if ($animal->id == $id) {
                    $animalToShow = $animal;
                }
            }
        }
        if ($animalToShow instanceof Animal) {
            echo "Информация о животном по ID $animalToShow->id<br>"
                . "Животное: $animalToShow->nameOfAnimal<br>"
                . "Продукт: $animalToShow->productName<br>"
                . "Готовых продуктов: $animalToShow->productQuantity<br>";
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
        $this->productQuantity += $productsQuantity;
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
        $this->productQuantity += $productsQuantity;
        return $productsQuantity;
    }
}

// Создаем ферму
$farm = new Farm();

// Добавляем 10 коров
for ($i = 0; $i < 10; $i++) {
    $farm->addAnimal(new Cow());
}

// Добавляем 20 кур
for ($i = 0; $i < 20; $i++) {
    $farm->addAnimal(new Hen());
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
    $farm->addAnimal(new Hen());
}

// Добавляем одну корову
$farm->addAnimal(new Cow());

// Работаем ещё 7 дней
for ($i = 0; $i <= 7; $i++) {
    $farm->workOneDay();
}

// Получаем количество собранных продуктов
$farm->showProductsQuantity();

// Дополнительно: получаем информацию о животном по его ID
$farm->showInfoById($farm->allAnimals["Корова"][0]->id);