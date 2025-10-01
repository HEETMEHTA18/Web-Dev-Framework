<?php
class Name{
    
        public $name = "om";
        public $age = "10";

        public function __construct($name = "om", $age = null) {
            $this->name = $name;
            $this->age = $age;
        }

        public function display() {
            return "this is the displayed info {$this->name} {$this->age}";
        }

        // Magic method to allow setDetails($name) or setDetails($name, $age)
        public function __call($method, $args) {
            if ($method == "setDetails") {
                if (count($args) == 1) {
                    $this->name = $args[0];
                    $this->age=23;
                } elseif (count($args) == 2) {
                    $this->name = $args[0];
                    $this->age = $args[1];
                }
            }
        }
    }

    // Usage examples:
    $data = new Name("raj", 20);
    echo $data->display();
    echo "<br>";

    $data2 = new Name("krish", 100);
    echo $data2->display();
    echo "<br>";

    $data3 = new Name();
    $data3->setDetails("amit");
    echo $data3->display();
    echo "<br>";

    $data3->setDetails("sita");
    echo $data3->display();
    echo "<br>";