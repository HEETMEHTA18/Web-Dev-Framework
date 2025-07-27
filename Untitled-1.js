console.log("Hello World"); // Output: Hello World
const str="Quokka Testing";
console.log(str); // Output: 5
let array =[ "CE","IT","CSE","ME"];
console.log(array); // Output: [ 'CE', 'IT', 'CS', 'ME' ]
let array1=[...array,"EEE","ECE"];
console.log(array1);


let product =[1,'iphone 13 pro '];
let nameofpurchaser = ['heet mehta '];
let date = [`${Date(Date.now)}`];
let price=['$10000'];
let details = [...product, ...nameofpurchaser,...price,...date];
let status1=[...details, "available"];


console.log(status1); 
console.log(status1); 




function myadd(...rest)
{
    let sum=0;
    for(let i of rest )
     sum+=i;
     return sum;
}    

const sum = (n1,n2)=>n1+n2;
console.log(sum(1,3)); 
console.log(myadd(1,3,1,2,2,2,2,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,1,1234567,123456,1234560));