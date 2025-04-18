<?php
interface Attackable
{
    public function attack();
}

interface Defendable
{
    public function defend();
}

interface Interactable
{
    public function interact();
}

trait MagicTrait
{
    public function castSpell()
    {
        return " используя магию!";
    }
}

class Weapon
{
    protected $name;
    protected $type;

    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }
}

class Sword extends Weapon
{
    public function __construct($name)
    {
        parent::__construct($name, "Sword");
    }
}

class Staff extends Weapon
{
    public function __construct($name)
    {
        parent::__construct($name, "Staff");
    }
}

class EnvironmentObject implements Interactable
{
    protected $name;
    protected $type;
    protected $state;

    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->state = "Закрыт";
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getState()
    {
        return $this->state;
    }

    public function interact()
    {
        $this->state = ($this->state == "Закрыт") ? "Открыт" : "Закрыт";
        return $this->name . " теперь " . $this->state;
    }
}

class Chest extends EnvironmentObject
{
    public function __construct($name)
    {
        parent::__construct($name, "Chest");
    }
}

class Door extends EnvironmentObject
{
    public function __construct($name)
    {
        parent::__construct($name, "Door");
    }
}

class Quest
{
    private $title;
    private $stages;

    public function __construct($title, $stages)
    {
        $this->title = $title;
        $this->stages = explode(", ", $stages);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getStages()
    {
        return $this->stages;
    }
}

class Inventory
{
    private $items = [];

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function getItems()
    {
        return $this->items;
    }
}

class Battle
{
    public static function fight($attacker, $defender)
    {
        return $attacker->attack() . " против " . $defender->defend();
    }
}

class Character implements Attackable, Defendable
{
    protected $name;
    protected $type;
    protected $strength;
    protected $agility;
    protected $intellect;
    protected $weapon;
    protected $inventory;

    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->inventory = new Inventory();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAttributes()
    {
        return "Сила - " . $this->strength . ", Ловкость - " . $this->agility . ", Интеллект - " . $this->intellect;
    }

    public function equipWeapon($weapon)
    {
        $this->weapon = $weapon;
        $this->inventory->addItem($weapon);
    }

    public function getWeapon()
    {
        return $this->weapon;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function attack()
    {
        return $this->name . " атакует с " . $this->weapon->getName();
    }

    public function defend()
    {
        return $this->name . " защищается";
    }
}

class Warrior extends Character
{
    public function __construct($name)
    {
        parent::__construct($name, "Warrior");
        $this->strength = 10;
        $this->agility = 5;
        $this->intellect = 3;
    }

    public function attack()
    {
        return parent::attack() . " как воин!";
    }
}

class Mage extends Character
{
    use MagicTrait;

    public function __construct($name)
    {
        parent::__construct($name, "Mage");
        $this->strength = 3;
        $this->agility = 5;
        $this->intellect = 10;
    }

    public function attack()
    {
        return parent::attack() . $this->castSpell();
    }
}

$characterName = trim(readline());
$characterType = trim(readline());
$weaponName = trim(readline());
$weaponType = trim(readline());
$envObjName = trim(readline());
$envObjType = trim(readline());
$questTitle = trim(readline());
$questStages = trim(readline());

$character = null;
switch ($characterType) {
    case 'Warrior':
        $character = new Warrior($characterName);
        break;
    case 'Mage':
        $character = new Mage($characterName);
        break;
}

$weapon = null;
switch ($weaponType) {
    case 'Sword':
        $weapon = new Sword($weaponName);
        break;
    case 'Staff':
        $weapon = new Staff($weaponName);
        break;
}

$envObj = null;
switch ($envObjType) {
    case 'Chest':
        $envObj = new Chest($envObjName);
        break;
    case 'Door':
        $envObj = new Door($envObjName);
        break;
}

$quest = new Quest($questTitle, $questStages);

$character->equipWeapon($weapon);

echo "Персонаж: " . $character->getName() . " (" . $character->getType() . ")\n";
echo "Атрибуты: " . $character->getAttributes() . "\n";
echo "Оружие: " . $weapon->getName() . " (" . $weapon->getType() . ")\n";

echo "Инвентарь: ";
foreach ($character->getInventory()->getItems() as $item) {
    echo $item->getName() . " (" . $item->getType() . ")";
}
echo "\n";

echo $envObj->getName() . " (" . $envObj->getType() . ") - Состояние: " . $envObj->getState() . "\n";
echo "Квест: " . $quest->getTitle() . "\n";
echo "Этапы квеста:\n";
foreach ($quest->getStages() as $i => $stage) {
    echo ($i + 1) . ". " . $stage . "\n";
}

echo $character->attack() . "\n";
?>