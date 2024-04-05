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
    // <========== INPUT EVENT FOR STUDENTID FOR DTR DATA DISPLAY ==========>
    // document.getElementById('studentid').addEventListener('input', (e)=>{
    //     const studentid = e.target.value;
    //     getOjtData(studentid);
    // });
    // <========== GET STUDENTID OPTIONS FOR SELECT TAG ==========>
    getStudentidOptions();
    // <========== SHOW ADD SCHEDULE FORM ==========>
    document.getElementById('showAddSchedForm').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const form = document.getElementById('form');
        form.style.display = 'grid';
    });
    // <========== HIDE ADD SCHEDULE FORM ==========>
    document.getElementById('formClose').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const form = document.getElementById('form');
        form.style.display = 'none';
    });
    // <========== STUDENTID PICKER ==========>
    studentPicker();
    // <========== HIDE EDIT SCHEDULE FORM ==========>
    document.getElementById('editFormClose').addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const form = document.getElementById('Editform');
        form.style.display = 'none';
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
const get = async (url)=>{
    try {
        const loader = document.getElementById('loader');
        const response = await fetch(url);
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
const DELETE = async (url)=>{
    try {
        const loader = document.getElementById('loader');
        const response = await fetch(url, {
            method: 'delete'
        });
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
const PUT = async (url, data, dataType)=>{
    try {
        const loader = document.getElementById('loader');
        
        let fetchObject;
        if(dataType == 'JSON'){
            fetchObject = {
                method: 'put',
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
        // <========== IF THE FORM DATA WAS NOT EMPTY ==========>
        post(url, data, dataType)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }else if(res.status){
                Swal.fire({
                    title: "Good job!",
                    text: res.status,
                    icon: "success"
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
        // <========== CATCH ERROR ==========>
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
// <========== GET OJT SCHEDULE ==========>
// const getOjtData = (studentid) => {
//     const encodedStudentid = encodeURIComponent(studentid);
//     const url = `../../process/admin/getOjtSchedule.php?id=${encodedStudentid}`;
//     get(url)
//     .then(res => {
//         if(res.err){
//             throw new Error(res.err);
//         }
//         if(res.data){
//             document.querySelector('.tbody').innerHTML += res.data;
//         }
//     })
//     .catch(error => {
//         console.error(error);
//         Swal.fire({
//             icon: "error",
//             title: "ERROR",
//             text: error,
//             footer: ''
//         });
//     });
// }
// <========== GET STUDENTID FOR SELECT OPTION ==========>
const getStudentidOptions = () => {
    const url = '../../process/admin/getStudentIdOptions.php';
    get(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else if(res.data){
            document.getElementById('studentid').innerHTML = res.data;
            document.getElementById('studentIdPicker').innerHTML = res.data;
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "ERROR",
            text: error,
            footer: ''
        });
    });
}
// <========== SELECTING A STUDENTID FOR DISPLAYING THE DTR ==========>
const studentPicker = ()=>{
    // <========== VARIABLE ==========>
    const studentid = document.getElementById('studentIdPicker');
    
    // <========== ADD CHANGE EVENT LISTENER TO THE SELECT TAG ==========>
    studentid.addEventListener('change', (e)=>{
        const month = document.getElementById('monthSelectorOjtDtr');
        // <========== VARIABLE ==========>
        const studentidNumber = e.target.value;
        getOjtDataTable(studentidNumber, month);
    });
}
// <========== FETCHING TABLE DATA OF THE OJT DTR ==========>
const getOjtDataTable = (studentidNumber, month) => {
    const url = '../../process/admin/getOjtSchedule.php';
    const dataType = 'JSON';
    const data = JSON.stringify({
        studentid: studentidNumber,
        month: month.value
    });
    // <========== POST REQUEST ==========>
    post(url, data, dataType)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }
        if(res.data){
            document.getElementById('tbodyOjtDtr').innerHTML = res.data;
            editSchedule(studentidNumber, month);
            deleteSchedule(studentidNumber, month);
            // console.log(res.data);
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "ERROR",
            text: "FAILED TO FETCH!",
            footer: ''
        });
    });
}
// <========== EDIT SCHEDULE ==========>
const editSchedule = (studentidNumber, month) => {
    const editIcon = document.querySelectorAll('.edit-icon');
    const editForm = document.getElementById('Editform');
    Array.from(editIcon, item => {
        item.addEventListener('click', (e)=>{
            const id = e.target.getAttribute('data-record-id');
            if(id != ''){
                editForm.style.display = 'grid';
                editForm.scrollIntoView({
                    'behavior':'smooth',
                    'block':'center',
                    'inline':'center'
                });
                const url = `../../process/admin/editOjtDtr.php?id=${id}`;
                get(url)
                .then(res => {
                    if(res.error){
                        throw new Error(res.error);
                    }
                    if(res.data){
                        document.getElementById('edit_id').value = id;
                        document.getElementById('edit_date').value = res.data.date;
                        document.getElementById('edit_date').disabled
                        document.getElementById('edit_meridiem').value = res.data.meridiem;
                        document.getElementById('edit_meridiem').disabled;
                        document.getElementById('edit_timeIn').value = res.data.timeIn;
                        document.getElementById('edit_timeOut').value = res.data.timeOut;
                        editBtnClick(id, studentidNumber, month);
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: "error",
                        title: "ERROR",
                        text: "FAILED TO GET DATA!",
                        footer: ''
                    });
                });
            }
        });
    });
}
// <========== DELETE SCHEDULE ==========>
const deleteSchedule = (studentidNumber, month) => {
    const deleteIcon = document.querySelectorAll('.delete-icon');
    Array.from(deleteIcon, item => {
        item.addEventListener('click', (e)=>{
            const id = e.target.getAttribute('data-record-id');
            if(id != ''){
                const url = `../../process/admin/deleteOjtSched.php?id=${id}`;
                DELETE(url)
                .then(res => {
                    if(res.err){
                        throw new Error(res.err);
                    }
                    if(res.status){
                        Swal.fire({
                            title: "Good job!",
                            text: res.status,
                            icon: "success"
                        })
                        .then(()=>{
                            getOjtDataTable(studentidNumber, month);
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: "error",
                        title: "ERROR",
                        text: "FAILED TO DELETE!",
                        footer: ''
                    });
                });
            }
        });
    });
}
// <========== EDIT BUTTON CLICK ==========>
const editBtnClick = (id, studentidNumber, month) => {
    const editBtnClick = document.getElementById('editBtnSubmit');
    editBtnClick.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopImmediatePropagation();
        const timeIn = document.getElementById('edit_timeIn');
        const timeOut = document.getElementById('edit_timeOut');
        const url = '../../process/admin/editOjtSchedule.php';
        const data = JSON.stringify({
            id: id,
            timeIn: timeIn.value,
            timeOut: timeOut.value
        });
        const dataType = 'JSON';
        PUT(url, data, dataType)
        .then(res => {
            if(res.err) {
                throw new Error(res.err);
            }
            if(res.status){
                Swal.fire({
                    title: "Good job!",
                    text: res.status,
                    icon: "success"
                }).then(()=>{
                    getOjtDataTable(studentidNumber, month);
                });
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: "error",
                title: "ERROR",
                text: error,
                footer: ''
            });
        });
    });
}

