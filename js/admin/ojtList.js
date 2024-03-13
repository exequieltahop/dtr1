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
    const seeDTR = document.querySelectorAll('.see-dtr');
    // <========== ADDING EVENTS ==========>
    Array.from(seeDTR, ojt => {
        ojt.addEventListener('click', (e)=>{
            e.stopImmediatePropagation();
        });
    });
});
// <========== FUNCTIONS ==========>
const displayDtr = (id) => {
    
}
