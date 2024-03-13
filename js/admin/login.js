// <==========<========== ASYNC FUNCTION FOR HTTP REQUEST ==========>==========>
const POST = async (url, data, dataType) => {
    const loadindAni = document.getElementById('blurLoading');
    try {
        loadindAni.style.display = 'flex';
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
        if(!response.ok){
            throw new Error('Can\'t Connect To Server!');
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
    } finally {
        loadindAni.style.display = 'none';
    }
}
// <========== DOMCONTENTLOADED ==========>
document.addEventListener('DOMContentLoaded', ()=>{
    // <========== VARIABLES ==========>
    const checkboxShowPassword = document.getElementById('showPassword');
    const btnLogin = document.getElementById('loginBtn');
    // <==========<========== ADDING EVENTS IN THE ELEMENTS ==========>==========>
    // <========== SHOW HIDE PASSWORD ==========>
    checkboxShowPassword.addEventListener('change', (e) => {
        const password = document.getElementById('password');
        const checkBoxInput = e.target;
        showHidePassword(checkBoxInput,password);
    });
    // <========== LOGIN BTN CLICKED ==========>
    btnLogin.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        processLogin();
    });
});
// <========== SHOW PASSWORD ==========>
const showHidePassword = (checkBox, txtBoxPassword) => {
    if(checkBox.checked){
        txtBoxPassword.type = 'text';
    }else{
        txtBoxPassword.type = 'password';
    }
}
// <========== PROCESS LOGIN ==========>
const processLogin = () => {
    const username = document.getElementById('username');
    const password = document.getElementById('password');

    if (username.value == '' || password.value == '') {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Username Or Password Empty!",
            footer: ''
        });
    } else {
        const url = '../../process/admin/processAdminLogin.php';
        const data = JSON.stringify({
            uname: username.value,
            pass: password.value
        });
        const dataType = 'JSON';
        POST(url, data, dataType)
            .then(res => {
                if (res.err) {
                    throw new Error(res.err);
                }
                if (res.status == 'Wrong Password!') {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: res.status,
                        footer: ''
                    });
                    
                }
                if(res.status == 'Invalid Account!'){
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: res.status,
                        footer: ''
                    });
                }
                if(res.status == 'Successfully Login!'){
                    Swal.fire({
                        title: "Good job!",
                        text: res.status,
                        icon: "success"
                    }).then(()=>{
                        window.location.href = 'home.php';
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something Went Wrong! Try Again!",
                    footer: ''
                });
            });
    }
}

