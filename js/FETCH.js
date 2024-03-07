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