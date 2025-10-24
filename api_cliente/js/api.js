let debounceTimer;

async function buscarHoteles() {
    const search = document.getElementById('search').value;
    const token = document.getElementById('token').value;
    const resultadosDiv = document.getElementById('resultados');

    // Si el campo de búsqueda está vacío, limpiar resultados
    if (!search.trim()) {
        resultadosDiv.innerHTML = '';
        return;
    }

    // Usar debounce para evitar múltiples llamadas mientras se escribe
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
        resultadosDiv.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Buscando hoteles...</div>';

        try {
            const formData = new FormData();
            formData.append('token', token);
            formData.append('search', search);

            const response = await fetch('api_handler.php?action=buscarHoteles', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.status) {
                resultadosDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-triangle"></i> ${data.msg}</div>`;
                return;
            }

            if (data.data.length === 0) {
                resultadosDiv.innerHTML = '<div class="no-results"><i class="fas fa-info-circle"></i> No se encontraron hoteles.</div>';
                return;
            }

            let html = '';
            data.data.forEach(hotel => {
                html += `
                    <div class="hotel-card">
                        <div class="hotel-header">
                            ${hotel.imagen ? `<img src="${hotel.imagen}" alt="${hotel.nombre}" class="hotel-image">` : '<div class="hotel-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;"><i class="fas fa-hotel" style="font-size: 2rem; color: #ccc;"></i></div>'}
                            <div class="hotel-info">
                                <h3 class="hotel-nombre">${hotel.nombre}</h3>
                                <p class="hotel-direccion">${hotel.direccion}</p>
                            </div>
                        </div>
                        <div class="hotel-details">
                            <p><i class="fas fa-phone"></i> ${hotel.telefono || 'No especificado'}</p>
                            <p><i class="fas fa-envelope"></i> ${hotel.email || 'No especificado'}</p>
                            <p><i class="fas fa-dollar-sign"></i> Precio promedio: ${hotel.precio_promedio || 'No especificado'}</p>
                            <p><i class="fas fa-concierge-bell"></i> Servicios: ${hotel.servicios || 'No especificado'}</p>
                        </div>
                    </div>
                `;
            });

            resultadosDiv.innerHTML = html;

        } catch (error) {
            console.error('Error:', error);
            resultadosDiv.innerHTML = '<div class="error"><i class="fas fa-exclamation-triangle"></i> Ocurrió un error al buscar los hoteles.</div>';
        }
    }, 500); // Esperar 500ms después de que el usuario deje de escribir
}
