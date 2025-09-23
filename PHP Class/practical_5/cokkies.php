        <?php
if(!isset($_COOKIE["name"]))
{
    setcookie("name", "Heet", time()+3600);
    setcookie("id", "24ce064", time()+3600);
    setcookie("class", "Ce", time()+3600);
}
echo "Name: " . $_COOKIE["name"] . "<br>";
echo "Id: " . $_COOKIE["id"] . "<br>";
echo "Class: " . $_COOKIE["class"] . "<br>";
?>