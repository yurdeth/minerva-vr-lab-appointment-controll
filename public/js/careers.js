const selectDepartment = document.getElementById('department');

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        let departmentId = selectDepartment.value;

        fetch(`/api/careers/${departmentId}`, {
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
    }, 600);
});

selectDepartment.addEventListener('change', function () {
    let departmentId = selectDepartment.value;

    fetch(`/api/careers/${departmentId}`, {
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
