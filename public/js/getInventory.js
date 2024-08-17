document.addEventListener("DOMContentLoaded", event => {
    let url = "http://127.0.0.1:8000/api/";

    fetch(url + "resources",{
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            Token: "Bearer " + localStorage.getItem("token")
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.total === 0) {
                return;
            }

            let table = document.getElementById("inventoryTable").getElementsByTagName('tbody')[0];

            data.resources.forEach((resource, index) => {
                let row = table.insertRow();
                let cell1 = row.insertCell(0);
                let cell2 = row.insertCell(1);
                let cell3 = row.insertCell(2);
                let cell4 = row.insertCell(3);

                cell1.innerHTML = index + 1;
                cell2.innerHTML = resource.room.name;
                cell3.innerHTML = resource.resource_type.resource_name;
                cell4.innerHTML = resource.status.status;
            });
        });
});
