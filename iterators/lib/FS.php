<?php ## Пример определения итератора.
  /**
   * Директория. При итерации возвращает свое содержимое.
   */ 
  class FS_Directory implements IteratorAggregate {
    public $path;
    // Конструктор.
    public function __construct($path) {
      $this->path = $path;
    }
    // Возвращает итератор - "представителя" данного объекта.
    public function getIterator() {
      return new FS_DirectoryIterator($this);
    }
  }

  /**
   * Класс-итератор. Является представителем для объектов FS_Directory
   * при переборе содержимого каталога.
   */ 
  class FS_DirectoryIterator implements Iterator {
    // Ссылка на "объект-начальник".
    private $owner;
    // Дескриптор открытой директории.
    private $d = null;
    // Текущий считанный элемент директории.
    private $cur = false;
    // Конструктор. Инициализирует новый итератор.
    public function __construct($owner) {
      $this->owner = $owner;
      $this->d = opendir($owner->path);
      $this->rewind();
    }
    //*
    //* Далее идут переопределения виртуальных методов интерфейса Iterator.
    //*
    // Устанавливает итератор на первый элемент.
    public function rewind() {
      rewinddir($this->d);
      $this->cur = readdir($this->d);
    }
    // Проверяет, не закончились ли уже элементы.
    public function valid() {
      // readdir() возвращает false, когда элементы каталога закончились.
      return $this->cur !== false;
    }
    // Возвращает текущий ключ.
    public function key() {
      return $this->cur;
    }
    // Возвращает текущее значение.
    public function current() {
      $path = $this->owner->path."/".$this->cur;
      return is_dir($path)? new FS_Directory($path) : new FS_File($path);
    }
    // Передвигает итератор к следующему элементу в списке.
    public function next() {
      $this->cur = readdir($this->d);
    }
  }

  /**
   * Файл.
   */ 
  class FS_File {
    public $path;
    // Конструктор.
    public function __construct($path) {
      $this->path = $path;
    }
    // Возвращает информацию об изображении.
    public function getSize() {
      return filesize($this->path);
    }
    // Здесь могут быть другие методы.
  }
?>