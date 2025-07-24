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


console.log(status1); // Output: [ 1, 'iphone 13 pro ', 'heet mehta ', '10000', 'Thu Oct 05 2023 12:00:00 GMT+0000 (Coordinated Universal Time)', 'available' ]
console.log(status1); // Output: 1




function myadd(...rest)
{
    let sum=0;
    for(let i of rest )
     sum+=i;
     return sum;
}    


console.log(myadd(1,3,1,2,2,2,2,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,1,1234567,123456,1234560));