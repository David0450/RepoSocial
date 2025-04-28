document.addEventListener('DOMContentLoaded', () => {
    const editProfileButton = document.getElementById('editProfileButton');
    const editProfileModal = document.getElementById('editProfileModal');
    const closeModal = document.getElementById('closeModal');
    const editProfileForm = document.getElementById('editProfileForm');

    // Abrir el modal de edición de perfil
    editProfileButton.addEventListener('click', () => {
        editProfileModal.style.display = 'block';
    });

    // Cerrar el modal de edición de perfil
    closeModal.addEventListener('click', () => {
        editProfileModal.style.display = 'none';
    });

    // Cerrar el modal si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (event.target === editProfileModal) {
            editProfileModal.style.display = 'none';
        }
    });

    // Manejar el envío del formulario de edición de perfil
    editProfileForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const newUsername = document.getElementById('newUsername').value;
        const newEmail = document.getElementById('newEmail').value;
        const newBio = document.getElementById('newBio').value;

        try {
            const response = await fetch('/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: newUsername,
                    email: newEmail,
                    bio: newBio,
                }),
            });

            if (response.ok) {
                const result = await response.json();
                alert('Perfil actualizado con éxito.');

                // Actualizar los datos en la página
                document.getElementById('username').textContent = result.username;
                document.getElementById('email').textContent = result.email;
                document.getElementById('bio').textContent = result.bio;

                // Cerrar el modal
                editProfileModal.style.display = 'none';
            } else {
                alert('Error al actualizar el perfil. Inténtalo de nuevo.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error al procesar la solicitud.');
        }
    });
});