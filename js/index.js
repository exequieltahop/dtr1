document.addEventListener('DOMContentLoaded', ()=>{
    // ini labelToTopAnimate()
    labelToTopAnimate();
    // show hide password
    document.querySelector('.show-hide-icon').addEventListener('click', (e)=>{
        const password = document.getElementById('password');
        if(password.type == 'password'){
            password.type = 'text';
            e.target.src = 'assets/show.png';
        }else{
            password.type = 'password';
            e.target.src = 'assets/hide.png';
        }
    });
    // login
    document.getElementById('submitBtn')
    .addEventListener('click', (e)=>{
        e.preventDefault();
        const username = document.getElementById('student');
        const pass = document.getElementById('password');
        if(username.vaue == '' || pass.value == ''){
            alert('Please Enter Your Username and Password');
        }else{
            const data = JSON.stringify({
                username: username.value,
                password : pass.value
            });
            const dataType = 'JSON';
            const url = 'process/loginProcess.php';
            POST(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error(res.err);
                }else{
                    if(res.status == 'ok'){
                        window.location.href = 'time.php';
                    }else{
                        alert('Wrong username or password!');
                    }
                }
            })
            .catch(error => {
                console.error(error);
                alert('Can\'t Login Please Try Again!');
            });
        }
    });
});
// to top label of the input function
function labelToTopAnimate(){
    const inputs = document.querySelectorAll('.input');
    Array.from(inputs, input => {
        const label = input.previousElementSibling;
        // console.log(label);
        input.addEventListener('focus', (e)=>{
            label.style.top = '-5px';
            label.style.transform = 'none';
            label.style.fontSize = '0.7rem';
            label.style.fontWeight = 'bold';
        });
        input.addEventListener('blur', (e)=>{
            if(e.target.value == ''){
                label.style.top = '50%';
                label.style.transform = 'translateY(-50%)';
                label.style.fontSize = '0.8rem';
                label.style.fontWeight = 'lighter';
            }else{
                label.style.top = '-5px';
                label.style.transform = 'none';
            }
        });
    });
}
// post method
async function POST(url , data, dataType){
    try {
        let fetchObject;
        if(dataType == 'JSON'){
            fetchObject = {
                method: 'post',
                headers: {'Content-Type':'application/json'},
                body: data
            };
        }else{
            fetchObject = {
                method: 'post',
                body: data
            };
        }
        const response = await fetch(url, fetchObject);
        if(!response.ok){
            throw new Error('Can\'t Connect To Server!');
        }
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(contentType && contentType.includes('application/octet-stream')){
            const responseBlob = await response.arrayBuffer();
            return new Uint8Array(responseBlob);
        }else{
            const responseText = await response.text();
            throw new Error(responseText);
        }
    } catch (error) {
        throw error;  
    } 
}