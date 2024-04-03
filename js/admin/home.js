// <========== DOM CONTENT LOADED ==========>
document.addEventListener('DOMContentLoaded', ()=>{
    // <========== SUBMIT BTN SCHEDULE SUBMIT ==========>
    document.getElementById('submitbtn').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const form = document.getElementById('form');
        const newForm = new FormData(form);
        addOjtSchedule(newForm);
    });
});
// <========== HTTP REQUEST ==========>
const post = async (url, data, dataType)=>{
    try {
        const loader = document.getElementById('loader');
        let fetchObject;
        if(dataType == 'JSON'){
            fetchObject = {
                method: 'post',
                headers: {'Content-Type':'application/json'},
                body: data
            };
        }else if(dataType == 'FORMDATA'){
            fetchObject = {
                method: 'post',
                body: data
            };
        }else{
            throw new Error('Invalid Data');
        }
        const response = await fetch(url,fetchObject);
        loader.style.display = 'flex';
        if(!response.ok){
            throw new Error('Cant Connect To Server!');
        }
        const responseType = response.headers.get('Content-Type');
        if(responseType && responseType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(responseType && responseType.includes('application/octet-stream')){
            const responseBlob = await response.arrayBuffer();
            return new Uint8Array(responseBlob);
        }else{
            const responseText = await response.text();
            throw new Error(responseText);
        }
    } catch (error) {
        throw error;
    } finally {
        loader.style.display = 'none';
    }
}
// <========== ADD OJT SCHEDULE ==========>
const addOjtSchedule = (data)=>{
    // <========== VARIABLES ==========>
    const url = '../../process/admin/addOjtSchedule.php';
    const dataType = 'FORMDATA';
    const studentid = document.getElementById('studentid');
    const date = document.getElementById('date');
    const timeIn = document.getElementById('timeIn');
    const timeOut = document.getElementById('timeOut');
    const meridiem = document.getElementById('meridiem');
    // <========== CHECK IF THE FORM DATA WAS EMPTY ==========>
    if(studentid.value == '' ||
       date.value == '' ||
       timeIn.value == '' ||
       timeOut.value == '' ||
       meridiem.value == ''){
        Swal.fire({
            icon: "error",
            title: "ERROR",
            text: "Don\'t Leave The Important Field Empty!",
            footer: ''
          });
    }else{
        post(url, data, dataType)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }else if(res.status){
                Swal.fire({
                    title: "Good job!",
                    text: res.status,
                    icon: "success"
                })
                .then(()=>{
                    studentid.value = '';
                    date.value = '';
                    timeIn.value = '';
                    timeOut.value = '';
                });
            }else if(res.status1){
                Swal.fire({
                    icon: "error",
                    title: "ERROR",
                    text: res.status1,
                    footer: ''
                  });
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: "error",
                title: "ERROR",
                text: "FAILED TO ADD!",
                footer: ''
              });
        });
    }
}
