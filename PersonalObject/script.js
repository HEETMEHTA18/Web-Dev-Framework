const person = {
  id: "24CE064",
  name: "HEET MEHTA",
  age: 20,
  gender: "Male",
  email: "heet.mehta@example.com",
  address: "Surat, Gujarat, India"
};

// Displaying the data in HTML
document.getElementById("name").textContent = person.name;
document.getElementById("id").textContent = person.id;
document.getElementById("age").textContent = person.age;
document.getElementById("gender").textContent = person.gender;
document.getElementById("email").textContent = person.email;
document.getElementById("address").textContent = person.address;
