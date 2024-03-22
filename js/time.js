document.addEventListener('DOMContentLoaded', ()=>{
    // ini menu()
    menu();
    // ini getTime()
    setInterval(getTime, 1000);
    // get status
    addStatusHiddenInputValue();
    // time in
    timeIn();
    // // ini statusChecker()
    // statusChecker();
    // status time in checker
    // timeInChecker();
    // time out
    timeOut();
    // GET TOTAL HOURS IN THE OJT
    totalHours();
});
// post method
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
// show/hide menu
function menu(){
    const nav = document.querySelector('.nav');
    document.querySelector('.burger-icon').addEventListener('click', (e)=>{
        if(nav.style.display == 'flex'){
            nav.style.display = 'none';
        }else{
            nav.style.display = 'flex';
        }
    });
}
// get time
function getTime(){
    const url = 'process/getTime.php';
    getMethod(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            document.querySelector('.time').innerHTML = res.dataTime;
        }
    })
    .catch(error => {
        alert(error);
    });
}
// time in
function timeIn(){
    document.getElementById('btnTime').addEventListener('click', (e)=>{
        e.preventDefault();
        const status = document.getElementById('hiddenStatus');
        const url = 'process/timeIn.php';
        const data = JSON.stringify({
            ini: 1
        });
        const dataType = 'JSON'
        if(status.value == 'TimeOut'){
            alert('It\'s not time yet to in!');
            return
        }else{
            postMethod(url, data, dataType)
            .then(res => {
                if(res.err){
                    throw new Error("are you stupid?", res.err);
                }else{
                    alert("status: " + res.status);
                    document.getElementById('hiddenStatus').value = 'TimeIn';
                    addStatusHiddenInputValue();
                }
            })
            .catch(error => {
                console.error(error);s
                alert('failed To Time in!');
                return
            });
        }
    });
}
// add status value in the hidden input
function addStatusHiddenInputValue(){
    const url = 'process/fetStatusFinal.php';
    const status = document.getElementById('hiddenStatus');
    getMethod(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            if(res.status == 'AM Time in'){
                status.value = 'TimeIn'
                statusChecker();
            }else if(res.status == 'AM Time Out'){
                status.value = 'TimeOut'
                statusChecker();
            }
            else if(res.status == 'PM Time in'){
                status.value = 'TimeIn'
                statusChecker();
            }else if(res.status == 'PM Time out'){
                status.value = 'TimeOut'
                statusChecker();
            }
            else{
                status.value = 'okay'
                statusChecker();
            }
        }
    })
    .catch(err => {
        console.error(err);
    });
}
// check status if login then make button time out, else if okay then nuetral
function statusChecker() {
    const status = document.getElementById('hiddenStatus');
    const btn = document.getElementById('btnTime');
    const btnOut = document.getElementById('btnTimeOut');
    if(status.value == 'TimeIn'){
        btnOut.style.display = 'flex';
        btn.style.display = 'none';
        btnOut.disable = false;
        btn.disable = true;
    }else if(status.value == 'TimeOut'){
        btn.style.display = 'none';
        btnOut.style.display = 'flex';
        btn.disable = false;
        btnOut.disable = true;
    }
    else if(status.value == 'okay'){
        btn.style.display = 'flex';
        btnOut.style.display = 'none';
        btn.disable = false;
        btnOut.disable = true;
    }
}
// time out
function timeOut() {
    document.getElementById('btnTimeOut').addEventListener('click', (e)=>{
        e.preventDefault();
        const url = 'process/timeOutProcess.php';
        getMethod(url)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }else{
                alert(res.status);
                document.getElementById('hiddenStatus').value = 'TimeOut';
                addStatusHiddenInputValue();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed To Time Out!')
        });
    });
}
// <========== GET TOTAL HOURS ==========>
const totalHours = () => {
    const totalhours = document.querySelector('.h1-total-hours');
    const url = 'process/getTotalHoursJob.php';
    getMethod(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.data){
            totalhours.innerHTML = res.data;        
        }
    })
    .catch(error => {
        console.log(error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: error,
            footer: ''
          });
    });
}   
