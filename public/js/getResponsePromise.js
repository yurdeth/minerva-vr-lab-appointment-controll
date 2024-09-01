async function getResponse(url){

    try{
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        if(!response.ok){
            throw new Error('Error al obtener los datos');
        }

        return await response.json();
    } catch (error) {
        console.log(error);
    }
}

export {getResponse};
