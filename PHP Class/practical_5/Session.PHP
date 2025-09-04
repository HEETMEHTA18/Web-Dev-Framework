<?PHP
session_start();
if(!isset($_SESSION["name"]))
{
    $_SESSION["name"] = "Heet";
    $_SESSION["id"] = "24ce064";
    $_SESSION["class"] = "Ce";
}
echo "Name: " . $_SESSION["name"] . "<br>";
echo "Id: " . $_SESSION["id"] . "<br>";
echo "Class: " . $_SESSION["class"] . "<br>";
?>