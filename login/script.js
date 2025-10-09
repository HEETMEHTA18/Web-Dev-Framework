// function validate()
// {
//     alert("clicked me !! hello ");
// }

function validate()
{
    
    let username = document.getElementById('Username');
    let pass = document.getElementById('pass');
    if(username.value.trim()=="" || pass.value.trim()=="")
        {alert("Please enter valid data");}

    else 
        {alert("hello "+username.value);}
}

function validation()
{
    // addEventListener (event);
    // event.preventDefault();
    let username = document.getElementById("Username");
    let pass = document.getElementById("pass");
    // if(username.value.trim()=="" || pass.value.trim()=="")
    // {
    //     alert("Please enter valid data");
    // }
    // else
    // {
    //     alert("login successful");
    // }


        let regexusername = /^[a-zA-Z0-9]+$/;
        let regexpass = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/;
        if(regexusername.test(username.value) && regexpass.test(pass.value))
        {
            alert("login successful");
        }
        else
        {
            alert("Please enter valid data");
        }
}
// const data = [
//     {name:"heet",year:"bca"},
//     {name :"yagna",year:"mbbs"},
//     {name :"yagna",age:"10"}
// ];
// const container = document.getElementsByClassName("student");
// addEventListener("load", () => {
//     data.forEach(stu => {
//     const p = document.createElement("p");
//     p.textContent = `${stu.name} -${stu.age} - ${stu.year}`;
//     if (container[0]) container[0].appendChild(p);
// });
// console.log(container);
// })
