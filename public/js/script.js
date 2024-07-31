document.addEventListener('DOMContentLoaded', () => {
    // Open modals
    document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.getAttribute('data-target'));
            target.style.display = 'block';
            
            // Populate edit modal with data
            if (target.id === 'editCarModal') {
                const id = button.getAttribute('data-id');
                const kmStand = button.getAttribute('data-km-stand');
                const name = button.getAttribute('data-name');
                const number = button.getAttribute('data-number');
                const lastUsed = button.getAttribute('data-last-used');
                const isActive = button.getAttribute('data-is-active') === '1';

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_km_stand').value = kmStand;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_number').value = number;
                document.getElementById('edit_last_used').value = lastUsed;
                document.getElementById('edit_is_active').checked = isActive;
            }

            // Load repairs for the selected car
            if (target.id === 'repairModal') {
                const carId = button.getAttribute('data-car-id');
                const carName = button.getAttribute('data-car-name');
                document.getElementById('car_id').value = carId;
                document.getElementById('carName').textContent = carName;

                // Fetch and display repairs
                fetch(`fetch_repairs.php?car_id=${carId}`)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('repairTableBody');
                        tableBody.innerHTML = '';
                        data.forEach(repair => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${repair.repair_description}</td>
                                <td>${repair.repair_date}</td>
                                <td><button type="button" data-repair-id="${repair.id}" class="delete-repair-button">Delete</button></td>
                            `;
                            tableBody.appendChild(row);
                        });

                        // Handle delete repair button click
                        document.querySelectorAll('.delete-repair-button').forEach(button => {
                            button.addEventListener('click', () => {
                                const repairId = button.getAttribute('data-repair-id');
                                fetch(`dashboard.php`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: `delete_repair=1&id=${repairId}`
                                }).then(() => {
                                    button.closest('tr').remove();
                                });
                            });
                        });
                    });
            }
        });
    });

    // Close modals
    document.querySelectorAll('.modal .close').forEach(span => {
        span.addEventListener('click', () => {
            span.parentElement.parentElement.style.display = 'none';
        });
    });

    window.addEventListener('click', event => {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
});
