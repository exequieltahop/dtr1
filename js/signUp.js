document.addEventListener('DOMContentLoaded', ()=>{
    // label in input animation
    labelToTop();
    // sign up 
    document.getElementById('btnSignUp')
    .addEventListener('click', (e)=>{
        e.preventDefault();
        signUp();
    });
    // show hide password
    document.getElementById('showHidePassword')
    .addEventListener('change', (e)=>{
        if(e.target.checked){
            document.getElementById('password').type = 'text';
        }else{
            document.getElementById('password').type = 'password';
        }
    });
});
// ASYNC POST
async function postMethod(url, data, dataType){
    try {
        let fetchObject;
        if(dataType == 'JSON'){
            fetchObject = {
                method: 'post',
                headers: {
                    'Content-Type':'application/json'
                },
                body: data
            };
        }else if(dataType == 'Fetching'){
            fetchObject = {
                headers: {
                    'Content-Type':'application/json'
                },
                body: data
            };
        } else{
            fetchObject = {
                body: data
            };
        }
        const response = await fetch(url,fetchObject);
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(contentType && contentType.includes('application/octet-stream')){
            const responseblob = await response.arrayBuffer();
            return new Uint8Array(responseblob);
        }else{
            const responseText = await response.text();
            throw new Error(responseText);
        }
    } catch (error) {
        throw error;
    }
}
// get method
async function getMethod(url){
    try {
        const response = await fetch(url);
        if(!response.ok){   
            throw new Error('Can\'t Connect to server!');
        }
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(contentType && contentType.includes('application/octet-stream')){
            const responseblob = await response.arrayBuffer();
            return new Uint8Array(responseblob);
        }else{
            const responseText = await response.text();
            throw new Error(responseText);
        }
    } catch (error) {
        throw error;
    }
}
// LABEL TO TOP
function labelToTop(){
    const inputs = document.querySelectorAll('.input');
    Array.from(inputs, item =>{
        const label = item.previousElementSibling;
        item.addEventListener('focus', (e)=>{
            label.style.top = '-5px';
            label.style.transform = 'none';
        });
        item.addEventListener('blur', (e)=>{
            if(e.target.value == ''){
                label.style.top = '50%';
                label.style.transform = 'translateY(-50%)';
            }else{
                label.style.top = '-5px';
                label.style.transform = 'none';
            }
        });
    });
}
// SIGN PROCESS
function signUp(){
    const fullname = document.getElementById('fullName');
        const studentId = document.getElementById('studentId');
        const hte = document.getElementById('hte');
        const password = document.getElementById('password');
        const hteAdviser = document.getElementById('hteAdviser');
        if(fullname.value == '' ||
           studentId.value == '' ||
           hte.value == '' ||
           password.value == '' ||
           hteAdviser.value == ''){
            alert('Please Fill up the required Field Input! Thank You!');
        }else{
            const url = '../process/signUpProcess.php';
            const data = JSON.stringify({
                fullname: fullname.value,
                studentId: studentId.value,
                hte: hte.value,
                password: password.value,
                hteAdviser: hteAdviser.value
            });
            const dataType = 'JSON';
            postMethod(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    alert(res.status);
                    window.location.href = '../index.php';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Can\'t Sign Up! Please Try Again! Thank You! (Username Already Taken!)');
            });
        }
}
