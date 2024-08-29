const url = "http://127.0.0.1:8000/careers";
const selectDepartment = document.getElementById('department');

document.addEventListener('DOMContentLoaded', function () {
    let departmentId = selectDepartment.value;

    fetch(`${url}/${departmentId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);

            // Asignar los datos al select
            let career = document.getElementById('career');
            career.innerHTML = '';

            let option = document.createElement('option');
            option.value = '';
            option.text = 'Seleccione una carrera';
            career.appendChild(option);

            data.forEach(d => {
                let option = document.createElement('option');
                option.value = d.id;
                option.text = d.career_name;
                career.appendChild(option);
            });
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});

selectDepartment.addEventListener('change', function () {
    let departmentId = selectDepartment.value;

    fetch(`${url}/${departmentId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);

            // Asignar los datos al select
            let career = document.getElementById('career');
            career.innerHTML = '';

            let option = document.createElement('option');
            option.value = '';
            option.text = 'Seleccione una carrera';
            career.appendChild(option);

            data.forEach(d => {
                let option = document.createElement('option');
                option.value = d.id;
                option.text = d.career_name;
                career.appendChild(option);
            });
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});
