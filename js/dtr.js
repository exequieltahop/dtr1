document.addEventListener('DOMContentLoaded', ()=>{
    // ini menu()
    menu();
    // display dtr data
    displayDtrData();
    // dynamic changes in dtr table base in select tag of month
    changeMonth();
    setMonthSelectTag();
});
// get method
async function getMethod(url) {
    try {
        const response = await fetch(url);
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(contentType && contentType.includes('application/octet-stream')){
            const responseBlob = await response.arrayBuffer();
            return Uint8Array(responseBlob);
        }else{
            const responseText = await response.text();
            throw new Error(responseText);
        }
    } catch (error) {
        throw error;
    }
}
// post method
async function postMethod(url, data, dataType) {
    try {
        let fetchObject;
        if(dataType == 'JSON'){
            fetchObject = {
                method: 'post',
                headers: {'Content-Type': 'application/json'},
                body: data
            };
        }else if(dataType == 'FormData'){
            fetchObject = {
                method: 'post',
                body: data
            };
        }
        const response = await fetch(url,fetchObject);
        if(!response.ok){
            throw new Error('Can\'t Connect Server');
        }
        const contentType = response.headers.get('Content-Type');
        if(contentType && contentType.includes('application/json')){
            const responseJson = await response.json();
            return responseJson;
        }else if(contentType && contentType.includes('application/octet-stream')){
            const responseBlob = await response.arrayBuffer();
            return Uint8Array(responseBlob);
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
// display dtr data
function displayDtrData() {
    const url = '../process/getSched.php';
    getMethod(url)
    .then(res => {
        if(res.err){
            throw new Error(res.err);
        }else{
            document.querySelector('.tbody').innerHTML += res.data;
            document.getElementById('inputTotalHours').value = res.totalHours;
            document.getElementById('hteAdviser').value = res.hteAdviser;       
        }
    }) 
    .catch(err => {
        console.error(err)  
    });
}
// change month 
function changeMonth(){
    document.getElementById('month').addEventListener('change', (e)=>{
        const url = '../process/getDaysDataDynamically.php';
        const dataType = 'JSON';
        const data = JSON.stringify({
            data: e.target.value
        });
        // post method
        postMethod(url, data, dataType)
        .then(res => {
            if(res.err){
                throw new Error(res.err);
            }else{
                document.querySelector('.tbody').innerHTML = '<tr class="tr-bold"><td class="td"  rowspan="2">Day</td><td class="td" colspan="2">A.M</td><td class="td" colspan="2">P.M</td><td class="td" colspan="2">Undertime</td></tr><tr class="tr-bold"><td class="td">Arrival</td><td class="td">Departure</td><td class="td">Arrival</td><td class="td">Departure</td><td class="td">Hours</td><td class="td">Minutes</td></tr>';
                document.querySelector('.tbody').innerHTML += res.data;
                document.getElementById('inputTotalHours').value = res.totalHours;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to change month! Please Try Again! Thank You!');
        });
    });
}
// SET SELECT TAG OPT 
function setMonthSelectTag(){
    const months = ["January", 
                    "February", 
                    "March", 
                    "April", 
                    "May", 
                    "June", 
                    "July", 
                    "August", 
                    "September", 
                    "October", 
                    "November", 
                    "December"];
    const month = document.getElementById('month');
    const date = new Date();
    const monthNow = date.getMonth();
    const options = month.children;
    Array.from(options, item => {
        if(item.value == months[monthNow]){
            item.selected = true;
        }
    });
}
