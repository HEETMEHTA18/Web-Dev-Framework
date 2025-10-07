
// function validate()
// {
//     alert("clicked me !! hello ");
// }

function validate()
{
    
    let username = document.getElementById('Username');
let pass = document.getElementById('pass');
    if(username.value.trim()=="" || pass.value.trim()=="")
        alert("tari masi no piko");

    else 
        {alert("hello "+username.value);}
}

function validation()
{

    event.preventDefault();
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